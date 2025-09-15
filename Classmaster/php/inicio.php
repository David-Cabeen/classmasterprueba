<?php
    session_start();
    require_once 'connection.php';

    $id = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verifica que ambos campos estén presentes
    if (!$id || !$password) {
        echo json_encode(['success' => false, 'error' => 'ID y contraseña requeridos.']);
        exit;
    }

    // Consulta para buscar el usuario por ID y obtener nombre
    $stmt = $conn->prepare("SELECT password, nombre FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    // Si no se encuentra el usuario, retorna error
    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'ID de usuario no encontrado.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Obtiene el hash de la contraseña almacenada y el nombre
    $stmt->bind_result($hashed_password, $nombre);
    $stmt->fetch();

    // Verifica si la contraseña ingresada es correcta
    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id; // Guarda el ID del usuario en la sesión
        $_SESSION['nombre'] = $nombre; // Guarda el nombre del usuario en la sesión
        $_SESSION['rol'] = 'estudiante'; // Guarda el rol del usuario en la sesión
        echo json_encode(['success' => true]);
        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Contraseña incorrecta.']);
        $stmt->close();
        $conn->close();
        exit;
    }
?>