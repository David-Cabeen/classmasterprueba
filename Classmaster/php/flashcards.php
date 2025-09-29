<?php
    require_once '../php/connection.php';
    session_start();
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'POST') {
        $action = $_POST['action'];
        if ($action === 'create') {
            $question = $_POST['question'];
            $answer = $_POST['answer'];
            $stmt = $conn->prepare("INSERT INTO flashcards (user_id, pregunta, respuesta, creada) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iss", $_SESSION['user_id'], $question, $answer);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'id' => $stmt->insert_id, 'message' => 'Flashcard creada exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al crear la flashcard.']);
            }
            $stmt->close();
        } else if ($action === 'delete') {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM flashcards WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $id, $_SESSION['user_id']);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Flashcard eliminada exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar la flashcard.']);
            }
            $stmt->close();
        }
    } else if ($method === 'GET') {
        $stmt = $conn->prepare("SELECT id, pregunta, respuesta, creada FROM flashcards WHERE user_id = ? ORDER BY creada DESC");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $flashcards = [];
        while ($row = $result->fetch_assoc()) {
            $flashcards[] = [
                'id' => $row['id'],
                'pregunta' => $row['pregunta'],
                'respuesta' => $row['respuesta'],
                'creada' => date('Y-m-d', strtotime($row['creada']))
            ];
        }
        echo json_encode($flashcards);
        $stmt->close();
    }