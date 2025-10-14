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

    // Obtener acudientes
    $sql_acudientes = "SELECT id, email, nombre, apellido, telefono, fecha_registro FROM acudientes ORDER BY id";
    $result_acudientes = $conn->query($sql_acudientes);
    $acudientes = [];

    if ($result_acudientes && $result_acudientes->num_rows > 0) {
        while($row = $result_acudientes->fetch_assoc()) {
            $acudientes[] = $row;
        }
    }

    $sql_teachers = "SELECT id, nombre, apellido, email, grados, materias, fecha_registro FROM profesores ORDER BY id";
    $result_teachers = $conn->query($sql_teachers);
    $teachers = [];

    if ($result_teachers && $result_teachers->num_rows > 0) {
        while($row = $result_teachers->fetch_assoc()) {
            $teachers[] = $row;
        }
    }

    // Obtener administradores
    $sql_admins = "SELECT id, nombre, apellido, email, fecha_registro FROM administradores ORDER BY id";
    $result_admins = $conn->query($sql_admins);
    $admins = [];
    if ($result_admins && $result_admins->num_rows > 0) {
        while($row = $result_admins->fetch_assoc()) {
            $admins[] = $row;
        }
    }

    // Cerrar conexión
    $conn->close();

    // Devolver datos en formato JSON
    echo json_encode([
        'success' => true,
        'users' => $users,
        'acudientes' => $acudientes,
        'teachers' => $teachers,
        'admins' => $admins
    ]);
?>