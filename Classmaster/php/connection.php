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
?>