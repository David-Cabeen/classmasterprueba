<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once 'connection.php';

    // Verificar si la solicitud es POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        // Obtener datos del formulario
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $nombre_completo = explode(' ', trim($_POST['nombre'])) ?? '';
        $nombre = $nombre_completo[0] ?? '';
        $apellido = $nombre_completo[1] ?? '';

        // Validar campos requeridos
        if (empty($email) || empty($password) || empty($nombre_completo)) {
            echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
            exit;
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'error' => 'Formato de correo electrónico inválido.']);
            exit;
        }

        // Verificar si el email ya existe
        $stmt_check_email = $conn->prepare("SELECT email FROM padres WHERE email = ?");
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result_email = $stmt_check_email->get_result();
        
        if ($result_email->num_rows > 0) {
            echo json_encode(['success' => false, 'error' => 'El correo electrónico ya está registrado.']);
            $stmt_check_email->close();
            $conn->close();
            exit;
        }
        $stmt_check_email->close();

        // Encriptar la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Preparar consulta de inserción
        $stmt_insert = $conn->prepare("INSERT INTO padres (email, password, nombre, apellido, fecha_registro) VALUES (?, ?, ?, ?, NOW())");

        if ($stmt_insert) {
            $stmt_insert->bind_param("ssss", $email, $password_hash, $nombre, $apellido);
            if ($stmt_insert->execute()) {
                $id = $conn->insert_id; // Obtener el ID insertado
                $_SESSION['user_id'] = $id; // Guarda el ID del usuario en la sesión
                $_SESSION['nombre'] = $nombre; // Guarda el nombre del usuario en la sesión
                $_SESSION['rol'] = 'padre'; // Guarda el rol del usuario en la sesión
                echo json_encode([
                    'success' => true, 
                    'message' => 'Registro exitoso. Bienvenido a ClassMaster.',
                    'user_id' => $id
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al registrar usuario: ' . $stmt_insert->error]);
            }
            $stmt_insert->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta: ' . $conn->error]);
        }

    } else {
        echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    }

    // Cerrar conexión
    $conn->close();
?>