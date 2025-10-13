<?php
session_start();
ini_set('display_errors', '0');
error_reporting(0);
ob_start();
require_once 'connection.php';
header('Content-Type: application/json');

function send_json($payload) {
    if (ob_get_length() !== false) {
        while (ob_get_level() > 0) ob_end_clean();
    }
    echo json_encode($payload);
    exit;
}

$curso = $_POST['curso_id'] ?? '';
$periodo = $_POST['periodo'] ?? '';
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$porcentaje = $_POST['porcentaje'] ?? '';

if (empty($curso) || empty($periodo) || empty($titulo) || $porcentaje === '') {
    send_json(['success' => false, 'error' => 'Faltan datos']);
}

$curso_id = null;
$stmt = $conn->prepare("SELECT id FROM cursos WHERE nombre = ?");
if ($stmt) {
    $stmt->bind_param('s', $curso);
    $stmt->execute();
    $stmt->bind_result($found_id);
    if ($stmt->fetch()) $curso_id = $found_id;
    $stmt->close();
}

if (!$curso_id) send_json(['success' => false, 'error' => 'Curso no encontrado']);

$stmt = $conn->prepare("INSERT INTO tareas (curso_id, periodo, titulo, descripcion, porcentaje, profesor_id) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) send_json(['success' => false, 'error' => $conn->error]);

$stmt->bind_param('isssis', $curso_id, $periodo, $titulo, $descripcion, $porcentaje, $_SESSION['user_id']);
if (!$stmt->execute()) {
    $err = $stmt->error;
    $stmt->close();
    send_json(['success' => false, 'error' => $err]);
}

$new_tarea_id = $conn->insert_id;
$stmt->close();

// Insert default notas for each student in the course
$s2 = $conn->prepare('SELECT estudiante_id FROM curso_estudiante WHERE curso_id = ?');
if ($s2) {
    $s2->bind_param('i', $curso_id);
    $s2->execute();
    $res = $s2->get_result();
    $insert = $conn->prepare('INSERT INTO notas (tarea_id, estudiante_id, valor) VALUES (?, ?, ?)');
    if ($insert) {
        while ($r = $res->fetch_assoc()) {
            $est_id = intval($r['estudiante_id']);
            $v = 3.2;
            $insert->bind_param('iid', $new_tarea_id, $est_id, $v);
            $insert->execute();
        }
        $insert->close();
    }
    $s2->close();
}

$conn->close();
send_json(['success' => true, 'message' => 'Tarea creada correctamente']);
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

        if ($curso_id && $periodo && $titulo && $porcentaje !== '') {
            $stmt = $conn->prepare("INSERT INTO tareas (curso_id, periodo, titulo, descripcion, porcentaje, profesor_id) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssssss", $curso_id, $periodo, $titulo, $descripcion, $porcentaje, $_SESSION['user_id']);
                if ($stmt->execute()) {
                    $new_tarea_id = $conn->insert_id;

                    // insert default notas (3.2) for each estudiante in that curso
                    $stmt->close();
                    $s2 = $conn->prepare('SELECT estudiante_id FROM curso_estudiante WHERE curso_id = ?');
                    if ($s2) {
                        $s2->bind_param('i', $curso_id);
                        $s2->execute();
                        $res = $s2->get_result();
                        $insert = $conn->prepare('INSERT INTO notas (tarea_id, estudiante_id, valor) VALUES (?, ?, ?)');
                        if ($insert) {
                            while ($r = $res->fetch_assoc()) {
                                $est_id = $r['estudiante_id'];
                                $v = 3.2;
                                $insert->bind_param('iid', $new_tarea_id, $est_id, $v);
                                $insert->execute();
                            }
                            $insert->close();
                        }
                        $s2->close();
                    }

                    echo json_encode(['success' => true, 'message' => 'Tarea creada correctamente']);
                } else {
                    echo json_encode(['success' => false, 'error' => $stmt->error]);
                }
    $conn->close();

?>
