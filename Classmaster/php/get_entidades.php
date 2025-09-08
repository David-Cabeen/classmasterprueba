<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = "localhost";
$usuario = "root";
$contrasena_bd = "";
$base_datos = "classmaster";

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena_bd, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}

// Obtener usuarios (estudiantes)
$sql_users = "SELECT id, nombre, apellido, email, grado, seccion, fecha_registro, activo FROM users ORDER BY id";
$result_users = $conn->query($sql_users);
$users = [];

if ($result_users && $result_users->num_rows > 0) {
    while($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }
}

// Obtener padres (acudientes)
$sql_padres = "SELECT id, email, nombre, apellido, telefono, fecha_registro, activo FROM padres ORDER BY id";
$result_padres = $conn->query($sql_padres);
$padres = [];

if ($result_padres && $result_padres->num_rows > 0) {
    while($row = $result_padres->fetch_assoc()) {
        $padres[] = $row;
    }
}

// Cerrar conexión
$conn->close();

// Devolver datos en formato JSON
echo json_encode([
    'success' => true,
    'users' => $users,
    'padres' => $padres
]);
?>