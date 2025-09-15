<?php
    require_once 'connection.php';

    // Obtener usuarios (estudiantes)
    $sql_users = "SELECT id, nombre, apellido, email, grado, seccion, fecha_registro FROM users ORDER BY id";
    $result_users = $conn->query($sql_users);
    $users = [];

    if ($result_users && $result_users->num_rows > 0) {
        while($row = $result_users->fetch_assoc()) {
            $users[] = $row;
        }
    }

    // Obtener padres (acudientes)
    $sql_padres = "SELECT id, email, nombre, apellido, telefono, fecha_registro FROM padres ORDER BY id";
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