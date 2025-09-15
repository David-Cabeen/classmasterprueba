<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once 'connection.php';

    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';

    if ($action === 'create' && $type === 'user') {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $grado = $_POST['grado'] ?? '';
        $seccion = $_POST['seccion'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$nombre || !$apellido || !$email || !$password) response(false, 'Faltan campos obligatorios');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nombre, apellido, email, password, grado, seccion, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $nombre, $apellido, $email, $hash, $grado, $seccion);
        if ($stmt->execute()) response(true, '', ['id' => $conn->insert_id]);
        response(false, $stmt->error);
    }
    if ($action === 'create' && $type === 'padre') {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$nombre || !$apellido || !$email || !$password) response(false, 'Faltan campos obligatorios');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO padres (nombre, apellido, email, telefono, password, fecha_registro) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $hash);
        if ($stmt->execute()) response(true, '', ['id' => $conn->insert_id]);
        response(false, $stmt->error);
    }
    if ($action === 'update' && $type && isset($_POST['id'])) {
        $id = $_POST['id'];
        $fields = $_POST;
        unset($fields['action'], $fields['type'], $fields['id']);
        $set = [];
        $params = [];
        $types = '';
        foreach ($fields as $k => $v) {
            $set[] = "$k = ?";
            $params[] = $v;
            $types .= 's';
        }
        if (!$set) response(false, 'Nada que actualizar');
        $table = $type === 'user' ? 'users' : 'padres';
        $sql = "UPDATE $table SET ".implode(',', $set)." WHERE id = ?";
        $params[] = $id;
        $types .= 'i';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) response(true);
        response(false, $stmt->error);
    }
    if ($action === 'delete' && $type && isset($_POST['id'])) {
        $id = $_POST['id'];
        $table = $type === 'user' ? 'users' : 'padres';
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) response(true);
        response(false, $stmt->error);
    }
    response(false, 'Acción no válida');
?>
