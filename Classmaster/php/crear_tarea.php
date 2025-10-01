<?php
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    $curso_id = isset($_POST['curso_id']) ? intval($_POST['curso_id']) : 0;
    $periodo = $_POST['periodo'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $porcentaje = $_POST['porcentaje'] ?? '';

    // Combine curso_id and seccion to form the search string
    $curso_str = $curso_id . $seccion;

    // Search for matching curso in cursos table

    if ($curso_id && $periodo && $titulo && $porcentaje !== '') {
        $stmt = $conn->prepare("INSERT INTO tareas (curso_id, periodo, titulo, descripcion, porcentaje, creado_por_profesor_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $curso_id, $periodo, $titulo, $descripcion, $porcentaje, $_SESSION['user_id']);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tarea creada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Faltan datos']);
    }
    $conn->close();

