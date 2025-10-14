<?php
    session_start();
    // prevent PHP notices from being sent to output and corrupting JSON
    ini_set('display_errors', '0');
    error_reporting(0);
    ob_start();
    require_once 'connection.php';
    header('Content-Type: application/json');

    function send_json($payload) {
        // remove any accidental output and send a single JSON response
        if (ob_get_length() !== false) {
            while (ob_get_level() > 0) ob_end_clean();
        }
        echo json_encode($payload);
        exit;
    }

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

    // If we couldn't find the curso id, return a single error JSON and exit
    if (!$curso_id) {
        $conn->close();
        send_json(['success' => false, 'error' => 'Curso no encontrado', 'curso' => $curso]);
    }

    $tareas = [];
    // fetch tareas only if periodo provided
    if ($periodo) {
        $stmt = $conn->prepare('SELECT titulo, porcentaje, descripcion, id FROM tareas WHERE curso_id = ? AND periodo = ? ORDER BY fecha_creacion ASC');
        if ($stmt) {
            $stmt->bind_param('is', $curso_id, $periodo);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $tareas[] = $row;
            }
            $stmt->close();
        }
    }

    $estudiantes = [];
    // If an estudiante_id was provided, only return that student (used by student role and acudiente selection)
    if ($estudiante_id) {
        $stmt = $conn->prepare('SELECT u.id, u.nombre, u.apellido FROM users u WHERE u.id = ?');
        if ($stmt) {
            $stmt->bind_param('i', $estudiante_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $row['notas'] = [];
                $estudiantes[] = $row;
            }
            $stmt->close();
        }
    } else {
        $stmt = $conn->prepare(
            'SELECT u.id, u.nombre, u.apellido
            FROM users u
            JOIN curso_estudiante ce ON u.id = ce.estudiante_id
            WHERE ce.curso_id = ?
            ORDER BY u.apellido, u.nombre'
        );
        if ($stmt) {
            $stmt->bind_param('i', $curso_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                // initialize notas map empty; will populate below
                $row['notas'] = [];
                $estudiantes[] = $row;
            }
            $stmt->close();
        }
    }

    // If there are tareas and estudiantes, fetch notas for these tareas
    if (!empty($tareas) && !empty($estudiantes)) {
        $tarea_ids = array_map(function($t){ return $t['id']; }, $tareas);
        $placeholders = implode(',', array_fill(0, count($tarea_ids), '?'));
        $types = str_repeat('i', count($tarea_ids));
        $sql = "SELECT tarea_id, estudiante_id, valor FROM notas WHERE tarea_id IN ($placeholders)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // bind params dynamically
            $refs = array_merge([$types], $tarea_ids);
            $tmp = [];
            foreach ($refs as $k => $v) $tmp[$k] = &$refs[$k];
            call_user_func_array(array($stmt, 'bind_param'), $tmp);
            $stmt->execute();
            $result = $stmt->get_result();
            $notas_map = [];
            while ($row = $result->fetch_assoc()) {
                $notas_map[intval($row['tarea_id'])][intval($row['estudiante_id'])] = $row['valor'];
            }
            $stmt->close();

            // populate estudiantes' notas
            foreach ($estudiantes as &$est) {
                foreach ($tarea_ids as $tid) {
                    if (isset($notas_map[$tid][$est['id']])) {
                        $est['notas'][$tid] = $notas_map[$tid][$est['id']];
                    } else {
                        // absent -> leave empty so front-end can show default
                        $est['notas'][$tid] = null;
                    }
                }
            }
            unset($est);
        }
    }

    // Single JSON response containing both tareas and estudiantes
    $conn->close();
    send_json(['success' => true, 'tareas' => $tareas, 'estudiantes' => $estudiantes]);
