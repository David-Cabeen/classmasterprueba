<?php
require_once 'connection.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $where = '';
    if ($year && $month) {
        $where = "WHERE YEAR(fecha) = $year AND MONTH(fecha) = $month";
    }
    $sql = "SELECT id, titulo, descripcion, prioridad, fecha FROM eventos $where ORDER BY fecha ASC";
    $result = $conn->query($sql);
    $eventos = [];
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }
    echo json_encode(['success' => true, 'eventos' => $eventos]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $prioridad = $_POST['prioridad'] ?? 'normal';
        $fecha = $_POST['fecha'] ?? '';
        if (!$titulo || !$fecha) {
            echo json_encode(['success' => false, 'error' => 'Título y fecha requeridos.']);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO eventos (titulo, descripcion, prioridad, fecha) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $titulo, $descripcion, $prioridad, $fecha);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID requerido.']);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM eventos WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Método o acción no soportada.']);
$conn->close();
