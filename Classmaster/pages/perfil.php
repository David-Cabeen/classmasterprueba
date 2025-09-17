<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
      header('Location: inicio.html');
      exit();
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="../styles/perfil.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../scripts/perfil.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body class="min-h-screen flex flex-col selection:bg-white/10 selection:text-white bg-gradient-to-br from-[#202024] via-[#121214] to-[#0d0d0f] text-gray-100">
    <!-- Header -->
    <header class="w-full">
        <div class="max-w-4xl mx-auto px-6 pt-8 pb-6">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="size-3 rounded-full bg-white/80 shadow-[0_0_24px_4px_rgba(255,255,255,0.25)]"></div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold tracking-tight">
                        Perfil de Usuario
                    </h1>
                </div>
                <a href="home.php" class="btn-home flex items-center gap-2 px-4 py-2 rounded-lg glass ring-soft hover-glow transition text-white/80 hover:text-white">
                    <svg aria-hidden="true" viewBox="0 0 24 24" class="w-5 h-5"><path fill="currentColor" d="M10.707 2.293a1 1 0 0 1 1.414 0l8 8a1 1 0 0 1-1.414 1.414L20 11V20a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-4H10v4a1 1 0 0 1-1 1H6a2 2 0 0 1-2-2v-9l1.293 1.293A1 1 0 0 1 3.88 10.88l8-8z"/></svg>
                    Inicio
                </a>
            </div>
        </div>
        <div class="divider"></div>
    </header>

    <!-- Main -->
    <main class="flex-1 w-full">
        <section class="max-w-2xl mx-auto px-6 py-10 animate-in">
            <div class="glass rounded-2xl p-8 ring-soft shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-white/90 flex items-center gap-2">
                    <ion-icon name="person"></ion-icon>
                    Información Personal
                </h2>
                <div class="flex flex-col sm:flex-row gap-8 items-start">
                    <div class="flex flex-col items-center gap-3 w-full sm:w-auto">
                        <form id="formCambiarFoto" enctype="multipart/form-data" class="flex flex-col items-center gap-2">
                            <label for="profilePicInput" class="cursor-pointer group">
                                <span class="sr-only">Cambiar foto de perfil</span>
                                <img id="profilePicPreview" src="<?php echo isset($_SESSION['foto_perfil']) ? htmlspecialchars($_SESSION['foto_perfil']) : '../assets/logo.svg'; ?>" alt="Foto de perfil" class="w-24 h-24 rounded-full object-cover border-2 border-white/20 group-hover:opacity-80 transition" />
                                <div class="text-xs text-white/60 mt-1 group-hover:text-white/80 transition">Editar foto</div>
                            </label>
                            <input type="file" id="profilePicInput" name="profilePic" accept="image/*" class="hidden" />
                            <button type="submit" class="px-4 py-1.5 rounded-lg bg-blue-500/80 hover:bg-blue-600 text-white font-semibold text-sm transition focus-outline mt-2">Guardar</button>
                            <span id="profilePicMsg" class="text-xs mt-1"></span>
                        </form>
                    </div>
                    <div class="space-y-5 flex-1">
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                            <label class="w-32 text-white/60 font-medium">Nombre:</label>
                            <span id="nombre-usuario" class="text-lg font-semibold text-white/90"><?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario'; ?></span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                            <label class="w-32 text-white/60 font-medium">Email:</label>
                            <span id="email-usuario" class="text-lg font-semibold text-white/90">
                                <?php
                                
                                switch($_SESSION['rol']) {
                                    case 'estudiante': $db = 'users'; break;
                                    case 'padre': $db = 'padres'; break;
                                    case 'profesor': $db = 'profesores'; break;
                                    case 'administrador': $db = 'administradores'; break;
                                };
                                require_once '../php/connection.php';

                                $user_id = $_SESSION['user_id'];
                                $email = 'No disponible';

                                $stmt = $conn->prepare("SELECT email FROM $db WHERE id = ?");
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $stmt->bind_result($email_db);
                                if ($stmt->fetch()) {
                                    $email = htmlspecialchars($email_db);
                                }
                                $stmt->close();

                                echo $email;
                                 ?>
                             </span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                            <label class="w-32 text-white/60 font-medium">Rol:</label>
                            <span id="rol-usuario" class="text-lg font-semibold text-white/90"><?php echo isset($_SESSION['rol']) ? ucfirst(strtolower(htmlspecialchars($_SESSION['rol']))) : ''; ?></span>
                        </div>
                    </div>
                </div>
                <div class="divider my-4"></div>
                <h3 class="text-xl font-semibold mb-4 text-white/80 flex items-center gap-2">
                    <ion-icon name="key"></ion-icon>
                    Cambiar Contraseña
                </h3>
                <form action="../php/pass_change.php" method="POST" id="formCambiarPassword" class="space-y-5">
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center relative">
                        <label for="currentPassword" class="w-32 text-white/60 font-medium">Contraseña actual:</label>
                        <div class="flex-1 relative">
                            <input type="password" id="currentPassword" name="currentPassword" class="w-full px-4 py-2 rounded-lg bg-white/10 text-white focus:outline-none focus:ring-2 focus:ring-white/30 password-input" required>
                            <ion-icon class="eye-icon absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-xl text-white/60" name="eye-off-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center relative">
                        <label for="newPassword" class="w-32 text-white/60 font-medium">Nueva contraseña:</label>
                        <div class="flex-1 relative">
                            <input type="password" id="newPassword" name="newPassword" class="w-full px-4 py-2 rounded-lg bg-white/10 text-white focus:outline-none focus:ring-2 focus:ring-white/30 password-input" required autocomplete="new-password">
                            <ion-icon class="eye-icon absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-xl text-white/60" name="eye-off-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center relative">
                        <label for="confirmPassword" class="w-32 text-white/60 font-medium">Confirmar nueva:</label>
                        <div class="flex-1 relative">
                            <input type="password" id="confirmPassword" name="confirmPassword" class="w-full px-4 py-2 rounded-lg bg-white/10 text-white focus:outline-none focus:ring-2 focus:ring-white/30 password-input" required autocomplete="new-password">
                            <ion-icon class="eye-icon absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-xl text-white/60" name="eye-off-outline"></ion-icon>
                        </div>
                    </div>
                    <div>
                        <button id='passBtn' type="submit" class="px-6 py-2 rounded-lg bg-blue-500/80 hover:bg-blue-600 text-white font-semibold transition focus-outline">Guardar Contraseña</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="mt-auto">
        <div class="divider"></div>
        <div class="max-w-4xl mx-auto px-6 py-6 text-sm text-white/50">
            ©️ 2025 · ClassMaster — todos los derechos reservados
        </div>
    </footer>
</body>
</html>