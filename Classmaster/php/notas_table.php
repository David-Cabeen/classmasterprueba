<?php
    session_start();
    require_once 'connection.php';
    header('Content-Type: application/json');

    $grado = isset($_GET['grado']) ? $_GET['grado'] : '';
    $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : '';
    $materia = isset($_GET['materia']) ? $_GET['materia'] : '';
    $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : '';
    $estudiante_id = null;

    if (isset($_GET['estudiante_id'])) {
        $estudiante_id = intval($_GET['estudiante_id']);
    };

    $curso = "$materia $grado$seccion";


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

    $tareas = [];

    if ($curso_id && $periodo) {
        $stmt = $conn->prepare('SELECT titulo, porcentaje, descripcion, id FROM tareas WHERE curso_id = ? AND periodo = ? ORDER BY fecha_creacion ASC');
        if ($stmt) {
            $stmt->bind_param('is', $curso_id, $periodo);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $tareas[] = $row;
            }
            echo json_encode(['success' => true, 'tareas' => $tareas]);
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error preparando consulta de tareas: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Curso o periodo no encontrado', 'curso_id' => $curso_id, 'periodo' => $periodo]);
    }

    $estudiantes = [];

    $stmt = $conn->prepare('
        SELECT u.id, u.nombre, u.apellido
        FROM users u
        JOIN curso_estudiante ce ON u.id = ce.estudiante_id
        WHERE ce.curso_id = ?
        ORDER BY u.apellido, u.nombre
    ');

    $conn->close();
?>