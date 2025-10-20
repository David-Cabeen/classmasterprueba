<?php
// Endpoint: búsqueda de cursos por término (autocomplete)
// GET: ?term=texto  -> devuelve hasta 10 coincidencias {id, nombre}
session_start();
require_once 'connection.php';
header('Content-Type: application/json');

$term = isset($_GET['term']) ? $_GET['term'] : '';
$cursos = [];
if ($term !== '') {
    $stmt = $conn->prepare("SELECT id, nombre FROM cursos WHERE nombre LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param('s', $term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cursos[] = $row;
    }
    $stmt->close();
}
$conn->close();
echo json_encode(['success' => true, 'cursos' => $cursos]);
