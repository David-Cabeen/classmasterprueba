<?php
    session_start();
    require_once 'connection.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ob_start();

    $id = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? '';

    switch($type) {
        case 'estudiante': $db = 'users'; $auth = 'id'; $bind = 'i'; break;
        case 'acudiente': $db = 'acudientes'; $auth = 'email'; $bind = 's'; break;
    };

    // Verifica que ambos campos estén presentes
    if (!$id || !$password) {
        echo json_encode(['success' => false, 'error' => 'Ambos campos son requeridos.']);
        exit;
    }

    // Consulta para buscar el usuario por ID y obtener nombre y apellido
    $stmt = $conn->prepare("SELECT password, nombre, apellido, id FROM $db WHERE $auth = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Error en la consulta: ' . $conn->error]);
        exit;
    };
    $stmt->bind_param($bind, $id);
    $stmt->execute();
    $stmt->store_result();

    // Si no se encuentra el usuario, retorna error
    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Obtiene el hash de la contraseña almacenada y el nombre
    $stmt->bind_result($hashed_password, $nombre, $apellido, $db_id);
    $stmt->fetch();

    // Verifica si la contraseña ingresada es correcta
    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $db_id; // Guarda el ID del usuario en la sesión
        $_SESSION['nombre'] = $nombre; // Guarda el nombre del usuario en la sesión
        $_SESSION['rol'] = $type; // Guarda el rol del usuario en la sesión
        $_SESSION['apellido'] = $apellido; // Guarda el apellido del usuario en la sesión
        echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso. Bienvenido ' . $nombre]);
        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Contraseña incorrecta.']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $out = ob_get_clean();
    if (strlen(trim($out)) > 0) {
        echo json_encode(['success' => false, 'error' => 'Unexpected output: ' . $out]);
    }
?>