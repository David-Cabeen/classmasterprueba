<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once 'connection.php';

$curso_id = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : '';

if ($curso_id && $periodo) {
    $stmt = $conn->prepare('SELECT titulo, porcentaje FROM tareas WHERE curso_id = ? AND periodo = ? ORDER BY fecha_creacion ASC');
    $stmt->bind_param('is', $curso_id, $periodo);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        echo '<tr class="border-b border-white/10 tarea-row">';
        echo '<th class="px-6 py-4 font-semibold align-middle">' . htmlspecialchars($row['titulo']) . '</th>';
        echo '<th class="px-6 py-4 font-semibold align-middle">' . htmlspecialchars($row['porcentaje']) . '%</th>';
        echo '</tr>';
    }
    $stmt->close();
}
$conn->close();