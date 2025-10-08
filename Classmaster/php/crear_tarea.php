<?php
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    $curso = $_POST['curso_id'] ?? '';
    $periodo = $_POST['periodo'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $porcentaje = $_POST['porcentaje'] ?? '';


    $curso_id = null;
    $stmt = $conn->prepare("SELECT id FROM cursos WHERE nombre = ?");
    if ($stmt) {
        $stmt->bind_param("s", $curso);
        $stmt->execute();
        $stmt->bind_result($found_id);
        if ($stmt->fetch()) {
            $curso_id = $found_id;
        }
        $stmt->close();
    }


    if ($curso_id && $periodo && $titulo && $porcentaje !== '') {
        $stmt = $conn->prepare("INSERT INTO tareas (curso_id, periodo, titulo, descripcion, porcentaje, profesor_id) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssss", $curso_id, $periodo, $titulo, $descripcion, $porcentaje, $_SESSION['user_id']);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Tarea creada correctamente']);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error preparando la inserción: ' . $conn->error]);
        }
    } else {
        $errorMsg = 'Faltan datos o curso no encontrado';
        if (!$curso_id) $errorMsg .= ' (curso_id vacío)';
        echo json_encode(['success' => false, 'error' => $errorMsg]);
    }
    $conn->close();

?>
