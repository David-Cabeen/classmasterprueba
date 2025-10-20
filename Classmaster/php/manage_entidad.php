<?php
    // Función auxiliar para generar respuestas JSON estandarizadas
    // Parámetros:
    // - success: boolean indicando éxito/fallo de la operación
    // - error: mensaje de error (opcional)
    // - data: datos adicionales a retornar (opcional)
    function response($success, $error = '', $data = []) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'error' => $error, 'data' => $data]);
        exit;
    }
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once 'connection.php';

    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';

    // Creación de nuevo estudiante
    // - Valida campos requeridos
    // - Hashea la contraseña
    // - Crea el registro del estudiante
    // - Vincula automáticamente con los cursos correspondientes
    if ($action === 'create' && $type === 'user') {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $grado = $_POST['grado'] ?? '';
        $seccion = $_POST['seccion'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$nombre || !$apellido || !$email || !$password) response(false, 'Faltan campos obligatorios');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nombre, apellido, email, password, grado, seccion, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $nombre, $apellido, $email, $hash, $grado, $seccion);
        if (!$stmt->execute()) {
            response(false, $stmt->error);
        }
        $user_id = $conn->insert_id;
        $stmt->close();
    // Vincular el nuevo estudiante a cada curso con el mismo grado y sección
        $stmt = $conn->prepare("SELECT id FROM cursos WHERE grado = ? AND seccion = ?");
        $stmt->bind_param("ss", $grado, $seccion);
        $stmt->execute();
        $result = $stmt->get_result();
        $curso_ids = [];
        while ($row = $result->fetch_assoc()) {
            $curso_ids[] = $row['id'];
        }
        $stmt->close();
        if (!empty($curso_ids)) {
            $stmt = $conn->prepare("INSERT INTO curso_estudiante (estudiante_id, curso_id) VALUES (?, ?)");
            foreach ($curso_ids as $curso_id) {
                $stmt->bind_param("ii", $user_id, $curso_id);
                $stmt->execute();
            }
            $stmt->close();
        }
        response(true, '', ['id' => $user_id]);
    }
    if ($action === 'create' && $type === 'acudiente') {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$nombre || !$apellido || !$email || !$password) response(false, 'Faltan campos obligatorios');
        $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO acudientes (nombre, apellido, email, telefono, password, fecha_registro) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $hash);
        if ($stmt->execute()) response(true, '', ['id' => $conn->insert_id]);
        response(false, $stmt->error);
    }
    if ($action === 'create' && $type === 'teacher') {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $materias = $_POST['materias'] ?? '';
        $grados = $_POST['grados'] ?? '';
        $password = $_POST['password'] ?? '';
        $id = 'P' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        if (!$nombre || !$apellido || !$email || !$password) response(false, 'Faltan campos obligatorios');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO profesores (id, nombre, apellido, email, grados, materias, password, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssss", $id, $nombre, $apellido, $email, $grados, $materias, $hash);
        if ($stmt->execute()) response(true, '', ['id' => $id]);
        response(false, $stmt->error);
    }
    if ($action === 'create' && $type === 'admin') {
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!$id || !$nombre || !$apellido || !$email || !$password) response(false, 'Faltan campos obligatorios');
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO administradores (id, nombre, apellido, email, password, fecha_registro) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $id, $nombre, $apellido, $email, $hash);
    if ($stmt->execute()) {
        // Send email with ID and password using PHPMailer
        require_once __DIR__ . '/PHPMailerConfig.php';
        require_once __DIR__ . '/PHPMailerAutoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->setFrom(SMTP_FROM, SMTP_FROMNAME);
            $mail->addAddress($email, $nombre . ' ' . $apellido);
            $mail->Subject = 'Tu cuenta de administrador en ClassMaster';
            $mail->Body = "Hola $nombre,\n\nTu cuenta de administrador ha sido creada.\nID: $id\nContraseña: $password\n\nPor favor guarda esta información de forma segura.";
            $mail->send();
        } catch (Exception $e) {
            // Optionally log or handle email error
        }
        response(true, '', ['id' => $id, 'password' => $password]);
    }
    response(false, $stmt->error);
    }

    if ($action === 'update' && $type && isset($_POST['id'])) {
        $id = $_POST['id'];
        $fields = $_POST;
        unset($fields['action'], $fields['type'], $fields['id']);
        $set = [];
        $params = [];
        $types = '';
        foreach ($fields as $k => $v) {
            if ($k === 'password' && $v !== '') {
                $v = password_hash($v, PASSWORD_DEFAULT);
            }
            if ($k === 'password' && $v === '') continue;
            $set[] = "$k = ?";
            $params[] = $v;
            $types .= 's';
        }
        if (!$set) response(false, 'Nada que actualizar');
            if (!$set) response(false, 'Nada que actualizar');
            if ($type === 'user') {
                $table = 'users';
            } elseif ($type === 'acudiente') {
                $table = 'acudientes';
            } elseif ($type === 'teacher') {
                $table = 'profesores';
            } elseif ($type === 'admin') {
                $table = 'administradores';
            } else {
                response(false, 'Tipo no soportado');
            }
        $sql = "UPDATE $table SET ".implode(',', $set)." WHERE id = ?";
    $params[] = (string)$id;
    // Bind id as string for compatibility with varchar PKs (admins/profesores). Using 's' works for numeric ids too.
    $types .= 's';
        $stmt = $conn->prepare($sql);
        if ($stmt === false) response(false, 'Error en la preparación de la consulta: ' . $conn->error);
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) response(true);
        response(false, $stmt->error);
    }
    if ($action === 'delete' && $type && isset($_POST['id'])) {
        $id = $_POST['id'];
        if ($type === 'user') {
            $table = 'users';
            $bindType = 'i';
        } elseif ($type === 'acudiente') {
            $table = 'acudientes';
            $bindType = 'i';
        } elseif ($type === 'teacher') {
            $table = 'profesores';
            $bindType = 's';
        } elseif ($type === 'admin') {
            $table = 'administradores';
            $bindType = 's';
        } else {
            response(false, 'Tipo no soportado');
        }
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        if ($stmt === false) response(false, 'Error al preparar DELETE: ' . $conn->error);
        if ($bindType === 'i') {
            $stmt->bind_param('i', $id);
        } else {
            $strId = (string)$id;
            $stmt->bind_param('s', $strId);
        }
        if ($stmt->execute()) response(true);
        response(false, $stmt->error);
    }
    response(false, 'Acción no válida');
?>
