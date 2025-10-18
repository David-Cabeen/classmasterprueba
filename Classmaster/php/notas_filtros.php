<?php
    // Endpoint que devuelve las opciones usadas por la UI de notas:
    // - Para profesores: devuelve las materias y grados asignados en su perfil (campos combinados en DB)
    // - Para estudiantes: devuelve las materias (cursos) en los que está inscrito el estudiante
    // - Para acudientes: devuelve la lista de estudiantes (hijos) y, opcionalmente, las materias
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';


    // Autorización mínima: requerimos user_id y rol en sesión
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol'])) {
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $materias = [];
    $grados = [];

    // Rama: profesor -> leer campos 'grados' y 'materias' desde la tabla profesores
    if($_SESSION['rol'] == "profesor"){
        // Los campos en la tabla 'profesores' están guardados como cadenas separadas por comas.
        // Se separan en arrays para enviarlos al frontend.
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
        // Rama: estudiante o acudiente
        // Si es acudiente, retornamos también la lista de estudiantes vinculados (hijos)
        if ($_SESSION['rol'] == 'acudiente') {
            $children = [];
            // Buscar hijos por id_padre en la tabla users
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

                $materias = [];
                // Si hay hijos, determinamos para cuál consultar materias. Por defecto usamos el primero.
                if (!empty($children)) {
                    $requested = isset($_GET['estudiante_id']) ? intval($_GET['estudiante_id']) : null;
                    $firstId = $children[0]['id'];
                    $targetId = $firstId;
                    // Si el frontend envió estudiante_id, comprobamos que pertenezca a los hijos
                    if ($requested) {
                        $found = false;
                        foreach ($children as $c) {
                            if ($c['id'] === $requested) { $found = true; break; }
                        }
                        if ($found) $targetId = $requested;
                    }
                    // Obtener las materias (cursos) en las que está inscrito el estudiante target
                    $stmt = $conn->prepare('
                        SELECT c.nombre AS curso_nombre
                        FROM curso_estudiante ce
                        JOIN cursos c ON ce.curso_id = c.id
                        WHERE ce.estudiante_id = ?
                    ');
                    if ($stmt) {
                        $stmt->bind_param('i', $targetId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $materias[] = $row['curso_nombre'];
                        }
                        $stmt->close();
                    }
                }

            // Devolver hijos y materias (si las determinamos)
            echo json_encode([
                'success' => true,
                'estudiantes' => $children,
                'materias' => $materias
            ]);
            $conn->close();
            exit;
        }

        // Rama: estudiante -> devolver las materias (cursos) para el estudiante logueado
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