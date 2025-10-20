<?php
    // Endpoint: actualiza el teléfono de un acudiente (usuaro logueado)
    // Entrada: POST { telefono }
    // Salida: JSON success/error
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    $telefono = $_POST['telefono'] ?? '';
    $user_id = $_SESSION['user_id'] ?? '';

    $stmt = $conn->prepare("UPDATE acudientes SET telefono = ? WHERE id = ?");
    $stmt->bind_param("si", $telefono, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Teléfono actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
?>