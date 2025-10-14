<?php
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';


    if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol'])) {
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $materias = [];
    $grados = [];

    if($_SESSION['rol'] == "profesor"){
        $stmt = $conn->prepare('SELECT grados FROM profesores WHERE id = ?');
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $stmt->bind_result($grados_str);
        if ($stmt->fetch()) {
            $grados = array_map('trim', explode(',', $grados_str));
        }
        $stmt->close();

        $stmt = $conn->prepare('SELECT materias FROM profesores WHERE id = ?');
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $stmt->bind_result($materias_str);
        if ($stmt->fetch()) {
            $materias = array_map('trim', explode(',', $materias_str));
        }
        $stmt->close();

        $conn->close();
        echo json_encode([
            'success' => true,
            'materias' => $materias,
            'grados' => $grados
        ]);
    } else if($_SESSION['rol'] == "estudiante" || $_SESSION['rol'] == "acudiente"){
        // If acudiente, we should return the list of students (children) to populate the selector
        if ($_SESSION['rol'] == 'acudiente') {
            $children = [];
            $stmt = $conn->prepare('SELECT id, nombre, apellido FROM users WHERE id_padre = ?');
            if ($stmt) {
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($r = $res->fetch_assoc()) {
                    $children[] = ['id' => intval($r['id']), 'nombre' => $r['nombre'] . ' ' . $r['apellido']];
                }
                $stmt->close();
            }
            // Optionally, also include materias for the first child (if any) so the client can prefill
            $materias = [];
            if (!empty($children)) {
                $firstId = $children[0]['id'];
                $stmt = $conn->prepare('
                    SELECT c.nombre AS curso_nombre
                    FROM curso_estudiante ce
                    JOIN cursos c ON ce.curso_id = c.id
                    WHERE ce.estudiante_id = ?
                ');
                if ($stmt) {
                    $stmt->bind_param('i', $firstId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $materias[] = $row['curso_nombre'];
                    }
                    $stmt->close();
                }
            }

            echo json_encode([
                'success' => true,
                'estudiantes' => $children,
                'materias' => $materias
            ]);
            $conn->close();
            exit;
        }

        // If role is estudiante, return the materias for the logged-in student
        $estudiante_id = $user_id;
        $stmt = $conn->prepare('
            SELECT c.nombre AS curso_nombre
            FROM curso_estudiante ce
            JOIN cursos c ON ce.curso_id = c.id
            WHERE ce.estudiante_id = ?
        ');
        $stmt->bind_param('i', $estudiante_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row['curso_nombre'];
        }
        $stmt->close();

        echo json_encode([
            'success' => true,
            'materias' => $materias
        ]);
        $conn->close();
        exit;
    }
?>