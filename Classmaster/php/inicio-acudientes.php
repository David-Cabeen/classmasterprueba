<?php
    session_start();
    require_once 'connection.php';

    // Verificar si el formulario fue enviado por método POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Recibir los datos del formulario
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];

        // Encriptar la contraseña antes de guardar
        $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar datos
    $sql = "INSERT INTO acudientes (email, contrasena) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        // Verificar si la consulta fue preparada correctamente
        if ($stmt) {
            // Enlazar parámetros a la consulta (dos strings)
            $stmt->bind_param("ss", $email, $contrasena_encriptada);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                echo "<script>alert('Registro exitoso'); window.location.href='login.html';</script>";
            } else {
                echo "Error al registrar: " . $stmt->error;
            }
            
            // Cerrar la consulta
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    }

    // Cerrar conexión
    $conn->close();
?>
