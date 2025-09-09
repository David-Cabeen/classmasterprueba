<?php
    session_start();
    header('Content-Type: application/json');

    // Configuración de la base de datos
    $host = "localhost";
    $usuario = "root";
    $contrasena_bd = "";
    $base_datos = "classmaster";

    // Crear conexión
    $conn = new mysqli($host, $usuario, $contrasena_bd, $base_datos);

    // Verificar conexión
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
        exit;
    }

    // Verificar si la solicitud es POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        // Obtener datos del formulario
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $id_estudiante = $_POST['id_estudiante'] ?? '';

        // Validar campos requeridos
        if (empty($email) || empty($password) || empty($id_estudiante)) {
            echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
            exit;
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'error' => 'Formato de correo electrónico inválido.']);
            exit;
        }

        // Validar que el ID sea numérico
        if (!is_numeric($id_estudiante)) {
            echo json_encode(['success' => false, 'error' => 'El ID de estudiante debe ser numérico.']);
            exit;
        }

        // Verificar si el ID ya existe
        $stmt_check_id = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $stmt_check_id->bind_param("i", $id_estudiante);
        $stmt_check_id->execute();
        $result_id = $stmt_check_id->get_result();
        
        if ($result_id->num_rows > 0) {
            echo json_encode(['success' => false, 'error' => 'El ID de estudiante ya está registrado.']);
            $stmt_check_id->close();
            $conn->close();
            exit;
        }
        $stmt_check_id->close();

        // Verificar si el email ya existe
        $stmt_check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
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

        // Extraer nombre del email (antes del @)
        $nombre_from_email = explode('@', $email)[0];
        $nombre = ucfirst($nombre_from_email);
        $apellido = "Estudiante"; // Valor por defecto

        // Preparar consulta de inserción
        $stmt_insert = $conn->prepare("INSERT INTO users (id, nombre, apellido, email, password, grado, seccion, fecha_registro, activo) VALUES (?, ?, ?, ?, ?, NULL, NULL, NOW(), 1)");
        
        if ($stmt_insert) {
            $stmt_insert->bind_param("issss", $id_estudiante, $nombre, $apellido, $email, $password_hash);
            
            if ($stmt_insert->execute()) {
                $_SESSION['user_id'] = $id_estudiante; // Guarda el ID del usuario en la sesión
                $_SESSION['nombre'] = $nombre; // Guarda el nombre del usuario en la sesión
                echo json_encode([
                    'success' => true, 
                    'message' => 'Registro exitoso. Bienvenido a ClassMaster.',
                    'user_id' => $id_estudiante
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