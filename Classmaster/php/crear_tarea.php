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

// Validación del porcentaje: asegurarnos que el porcentaje enviado sea numérico y entre 0 y 100
$porcentaje_num = floatval($porcentaje);
if ($porcentaje_num < 0 || $porcentaje_num > 100) {
    send_json(['success' => false, 'error' => 'Porcentaje inválido']);
}

// Verificar la suma de porcentajes ya existentes para este curso y periodo
$stmt_check = $conn->prepare('SELECT IFNULL(SUM(porcentaje),0) AS total FROM tareas WHERE curso_id = ? AND periodo = ?');
if ($stmt_check) {
    $stmt_check->bind_param('is', $curso_id, $periodo);
    $stmt_check->execute();
    $stmt_check->bind_result($existing_total);
    $stmt_check->fetch();
    $stmt_check->close();
    // Si la suma existente + el nuevo porcentaje excede 100, rechazamos la creación
    if (floatval($existing_total) + $porcentaje_num > 100.0001) {
        send_json(['success' => false, 'error' => 'La suma de porcentajes excede 100% (actual: ' . $existing_total . '%)']);
    }
}

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

// Insert default notas for each student in the course (valor 3.2)
$s2 = $conn->prepare('SELECT estudiante_id FROM curso_estudiante WHERE curso_id = ?');
if ($s2) {
    $s2->bind_param('i', $curso_id);
    $s2->execute();
    $res = $s2->get_result();
    $insert = $conn->prepare('INSERT INTO notas (tarea_id, estudiante_id, valor) VALUES (?, ?, ?)');
    if ($insert) {
        while ($r = $res->fetch_assoc()) {
            $est_id = intval($r['estudiante_id']);
            $v = 3.20;
            $insert->bind_param('iid', $new_tarea_id, $est_id, $v);
            $insert->execute();
        }
        $insert->close();
    }
    $s2->close();
}

$conn->close();
send_json(['success' => true, 'message' => 'Tarea creada correctamente']);

?>
