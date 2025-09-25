<?php
session_start();
require_once 'connection.php';
require_once 'PHPMailerAutoload.php';
require_once 'PHPMailerConfig.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$email = $_POST['email'] ?? '';
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'El correo es obligatorio.']);
    exit;
}

$tables = [
    'users',
    'acudientes',
    'profesores',
    'administradores'
];
$found = false;
$id = $nombre = $apellido = '';
foreach ($tables as $table) {
    $stmt = $conn->prepare("SELECT id, nombre, apellido FROM $table WHERE email = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $nombre, $apellido);
            $stmt->fetch();
            $found = true;
            break;
        }
        $stmt->close();
    }
}
if (!$found) {
    echo json_encode(['success' => false, 'message' => 'No se encontró una cuenta con ese correo.']);
    $conn->close();
    exit;
}


function generatePasswordResetToken($user_id, $conn) {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry
    // Remove any previous tokens for this user
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->close();
    // Insert new token
    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user_id, $token, $expires);
    $stmt->execute();
    $stmt->close();
    return $token;
}

function generatePasswordResetLink($token) {
    // Use your real domain here
    return 'https://classmasterprueba.local/reset_password.php?token=' . urlencode($token);
}

// Generate and store token
$token = generatePasswordResetToken($id, $conn);
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;
    $mail->setFrom(SMTP_FROM, SMTP_FROMNAME);
    $mail->addAddress($email, $nombre . ' ' . $apellido);
    $mail->Subject = 'Reestablecimiento de contraseña en ClassMaster';
    $mail->Body = "Hola $nombre,\n\nPara reestablecer tu contraseña, por favor utiliza el siguiente enlace:\n\n" . generatePasswordResetLink($token) . "\n\nEste enlace expirará en 1 hora. Si no solicitaste este cambio, por favor ignora este correo.";
    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Se ha enviado un enlace de reestablecimiento a tu correo.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
}
$conn->close();
?>