<?php
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST') {
        $phone = $_POST['phone'] ?? '';
        $user_id = $_SESSION['user_id'] ?? '';

        if ($phone && $user_id) {
            $stmt = $conn->prepare("UPDATE acudientes SET telefono = ? WHERE id = ?");
            $stmt->bind_param("si", $phone, $user_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Teléfono actualizado correctamente']);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Faltan datos']);
        }
    } else if($method === 'GET') {
        $user_id = $_SESSION['user_id'] ?? '';
        $stmt = $conn->prepare("SELECT telefono FROM acudientes WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $telefono = $row['telefono'];
                $hasPhone = !empty($telefono);
                echo json_encode(['success' => true, 'telefono' => $telefono, 'hasPhone' => $hasPhone]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['hasPhone' => false]);
    }
?>