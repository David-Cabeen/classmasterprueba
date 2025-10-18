<?php 
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ./pages/home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro e inicio</title>
    <link rel="stylesheet" href="./styles/inicio.css">
    <script type="module" src="./scripts/inicio.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body>
    <section class="container">
        <section class="container-form">
            <form class="sign-in">
                <h2>Iniciar Sesión</h2>
                <span>Use su ID de estudiante o correo de acudiente</span>
                <section class="container-input">
                    <ion-icon name="id-card-outline"></ion-icon>
                    <input type="text" id="id" placeholder="ID o Email">
                </section>
                <section class="container-input">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input id="password" type="password" class="password" placeholder="Password">
                    <ion-icon class="eye-icon" name="eye-off-outline"></ion-icon>
                </section>
                <button class="button">Iniciar Sesión</button>
            </form>
        </section>

        <section class="container-form">
            <form class="sign-up">
                <h2>Registrarse</h2>
                <span>Ingrese su información para registrarse</span>
                <section class="container-input">
                    <ion-icon name="person-outline"></ion-icon>
                    <input id="nombre" type="text" placeholder="Nombre y Apellido">
                </section>
                <section class="container-input">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input id="email" type="text" placeholder="Email">
                    
                </section>
                <section class="container-input">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" class="password" placeholder="Password">
                    <ion-icon class="eye-icon" name="eye-off-outline"></ion-icon>
                </section>
                <p>Solo registrese si es acudiente</p>
                <button class="button">Registrarse</button>
            </form>
        </section>

        <section class="container-welcome">
            <section class="welcome-sign-up welcome">
                <h3>¡Bienvenido!</h3>
                <p>Ingrese los datos provistos por su institución. En caso de ser acudiente registrese abajo.</p>
                <button class="button" id="btn-sign-up">Registrarse</button>
            </section>
            <section class="welcome-sign-in welcome">
                <h3>¡Saludos!</h3>
                <p>Registrese con su correo, contraseña e ID de su hijo para acceder al sitio.</p>
                <button  class="button" id="btn-sign-in">Iniciar sesión</button>
            </section>
        </section>
    </section>
</body>
</html>