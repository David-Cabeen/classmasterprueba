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

// Accept POST with tarea_id, estudiante_id, valor
if ($_SERVER['REQUEST_METHOD'] !== 'POST') send_json(['success' => false, 'error' => 'Método no permitido']);

$tarea_id = isset($_POST['tarea_id']) ? intval($_POST['tarea_id']) : 0;
$estudiante_id = isset($_POST['estudiante_id']) ? intval($_POST['estudiante_id']) : 0;
$valor = isset($_POST['valor']) ? floatval($_POST['valor']) : null;

if (!$tarea_id || !$estudiante_id || $valor === null) send_json(['success' => false, 'error' => 'Faltan datos']);

$stmt = $conn->prepare('UPDATE notas SET valor = ? WHERE tarea_id = ? AND estudiante_id = ?');
if (!$stmt) send_json(['success' => false, 'error' => $conn->error]);

$stmt->bind_param('dii', $valor, $tarea_id, $estudiante_id);
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    send_json(['success' => true, 'message' => 'Nota actualizada correctamente']);
} else {
    $err = $stmt->error;
    $stmt->close();
    $conn->close();
    send_json(['success' => false, 'error' => $err]);
}
