<?php
    // Endpoint: cambiar contraseña del usuario logueado
    // - Verifica la contraseña actual
    // - Hashea y actualiza la nueva contraseña
    session_start();
    require_once '../php/connection.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];

        switch($_SESSION['rol']) {
            case 'estudiante': $db = 'users'; break;
            case 'acudiente': $db = 'acudientes'; break;
            case 'profesor': $db = 'profesores'; break;
            case 'administrador': $db = 'administradores'; break;
        };

        $stmt = $conn->prepare("SELECT password FROM $db WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        // Verifica que la contraseña actual sea correcta
        if (!password_verify($currentPassword, $hashedPassword)) {
            echo json_encode(['success' => false, 'error' => 'La contraseña actual es incorrecta.']);
            exit;
        };

        // Hashea la nueva contraseña
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Actualiza la contraseña en la base de datos
        $stmt = $conn->prepare("UPDATE $db SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newHashedPassword, $_SESSION['user_id']);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar la contraseña.']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Método de solicitud no válido.']);
}