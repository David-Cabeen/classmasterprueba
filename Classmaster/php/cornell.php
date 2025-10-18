<?php
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    $method = $_SERVER['REQUEST_METHOD'];

    if($method == 'POST'){
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        if ($action == 'create') {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $cues = isset($_POST['cues']) ? trim($_POST['cues']) : '';
            $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
            $summary = isset($_POST['summary']) ? trim($_POST['summary']) : '';
            $stmt = $conn->prepare("INSERT INTO cornell (user_id, titulo, claves, principal, resumen) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $_SESSION['user_id'], $title, $cues, $notes, $summary);

            if($stmt->execute()){
                $newId = $conn->insert_id;
                $stmt->close();
                // fetch inserted row
                $stmt2 = $conn->prepare("SELECT id, titulo, claves, principal, resumen, creada FROM cornell WHERE id = ? AND user_id = ?");
                $stmt2->bind_param('ii', $newId, $_SESSION['user_id']);
                $stmt2->execute();
                $res = $stmt2->get_result();
                $row = $res->fetch_assoc();
                $stmt2->close();
                echo json_encode(['success' => true, 'note' => $row]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al guardar la nota: ' . $stmt->error]);
                $stmt->close();
            }
        } else if ($action == 'update') {
            $id = intval($_POST['id'] ?? 0);
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $cues = isset($_POST['cues']) ? trim($_POST['cues']) : '';
            $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
            $summary = isset($_POST['summary']) ? trim($_POST['summary']) : '';

            $stmt = $conn->prepare("UPDATE cornell SET titulo = ?, claves = ?, principal = ?, resumen = ?, modificada = NOW() WHERE id = ? AND user_id = ?");
            $stmt->bind_param('sssiii', $title, $cues, $notes, $summary, $id, $_SESSION['user_id']);
            if($stmt->execute()){
                $stmt->close();
                $stmt2 = $conn->prepare("SELECT id, titulo, claves, principal, resumen, creada FROM cornell WHERE id = ? AND user_id = ?");
                $stmt2->bind_param('ii', $id, $_SESSION['user_id']);
                $stmt2->execute();
                $res = $stmt2->get_result();
                $row = $res->fetch_assoc();
                $stmt2->close();
                echo json_encode(['success' => true, 'note' => $row]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al actualizar la nota: ' . $stmt->error]);
                $stmt->close();
            }
        } else if ($action == 'delete'){
            // DELETE action
            $id = intval($_POST['id'] ?? 0);
            $stmt = $conn->prepare("DELETE FROM cornell WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $id, $_SESSION['user_id']);
            if($stmt->execute()){
                echo json_encode(['success' => true, 'message' => 'Nota eliminada correctamente']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar la nota: ' . $stmt->error]);
            }
            $stmt->close();
        }
    } else if($method == 'GET'){
        // Obtener todas las notas del usuario
        $stmt = $conn->prepare("SELECT id, titulo, claves, principal, resumen, creada FROM cornell WHERE user_id = ? ORDER BY creada DESC");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $notes = [];
        while($row = $result->fetch_assoc()){
            $notes[] = $row;
        }
        echo json_encode(['success' => true, 'notes' => $notes]);
        $stmt->close();
    }