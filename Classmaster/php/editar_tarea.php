<?php
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    if ($_SESSION['rol'] !== 'profesor') {
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'editar') {
        $tarea_id = $_POST['id'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $porcentaje = $_POST['porcentaje'] ?? '';

        if ($tarea_id && $titulo !== '' && $porcentaje !== '') {
            $stmt = $conn->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, porcentaje = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("ssdi", $titulo, $descripcion, $porcentaje, $tarea_id);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Tarea actualizada correctamente']);
                } else {
                    echo json_encode(['success' => false, 'error' => $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'error' => 'Error preparando la actualización: ' . $conn->error]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Faltan datos']);
        };
    } else if($action === 'eliminar') {
        $tarea_id = $_POST['id'] ?? '';

        if ($tarea_id) {
            $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $tarea_id);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Tarea eliminada correctamente']);
                } else {
                    echo json_encode(['success' => false, 'error' => $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'error' => 'Error preparando la eliminación: ' . $conn->error]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Faltan datos']);
        };
    } else {
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    };

    $conn->close();

?>