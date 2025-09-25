<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password recovery</title>
	<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../styles/password.css">
	<script type="module" src="../scripts/password.js" defer></script>
	<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body class="min-h-screen flex flex-col selection:bg-white/10 selection:text-white">
    <div class="container">
        <form id='pass'>
            <h2 class="text-3xl font-bold mb-6 text-center">Recuperar Contraseña</h2>
            <p class="mb-4 text-center text-white/70">Ingrese su correo electrónico para recibir instrucciones de recuperación de contraseña.</p>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-1">Correo Electrónico</label>
                <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-white/10 rounded-md bg-transparent text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">Enviar Instrucciones</button>
            <p class="mt-4 text-sm text-center text-white/70">Recibirá un correo con un enlace para restablecer su contraseña.</p>
        </form>
    </div>
</body>
</html>