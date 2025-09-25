<?php
require_once '../php/connection.php';
$token = $_GET['token'] ?? '';
$error = '';
$success = '';
if (!$token) {
    $error = 'Token inválido.';
} else {
    $stmt = $conn->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $error = 'Token inválido o expirado.';
    } else {
        $stmt->bind_result($user_id, $expires_at);
        $stmt->fetch();
        if (strtotime($expires_at) < time()) {
            $error = 'El enlace ha expirado.';
        }
    }
    $stmt->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $new_password = $_POST['new_password'] ?? '';
    if (strlen($new_password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } else {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        // Try all user tables
        $tables = ['users', 'acudientes', 'profesores', 'administradores'];
        $updated = false;
        foreach ($tables as $table) {
            $update = $conn->prepare("UPDATE $table SET password = ? WHERE id = ?");
            $update->bind_param("ss", $hash, $user_id);
            $update->execute();
            if ($update->affected_rows > 0) {
                $updated = true;
            }
            $update->close();
        }
        if ($updated) {
            $success = 'Contraseña actualizada correctamente.';
            // Delete token
            $del = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $del->bind_param("s", $token);
            $del->execute();
            $del->close();
        } else {
            $error = 'No se pudo actualizar la contraseña.';
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="../styles/inicio.css">
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php else: ?>
            <form method="post">
                <label for="new_password">Nueva Contraseña:</label>
                <input type="password" name="new_password" id="new_password" required minlength="8">
                <button type="submit">Actualizar</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
