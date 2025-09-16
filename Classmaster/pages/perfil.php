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
                    <svg aria-hidden="true" viewBox="0 0 24 24" class="w-7 h-7 text-white/80"><circle cx="12" cy="8" r="4" fill="currentColor" opacity=".8"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7" fill="currentColor" opacity=".2"/></svg>
                    Información Personal
                </h2>
                <div class="flex flex-col sm:flex-row gap-8 items-start mb-8">
                    <div class="flex flex-col items-center gap-3 w-full sm:w-auto">
                        <form id="formCambiarFoto" enctype="multipart/form-data" class="flex flex-col items-center gap-2">
                            <label for="profilePicInput" class="cursor-pointer group">
                                <span class="sr-only">Cambiar foto de perfil</span>
                                <img id="profilePicPreview" src="<?php echo isset($_SESSION['foto_perfil']) ? htmlspecialchars($_SESSION['foto_perfil']) : '../assets/logo.svg'; ?>" alt="Foto de perfil" class="w-24 h-24 rounded-full object-cover border-2 border-white/20 group-hover:opacity-80 transition" />
                                <div class="text-xs text-white/60 mt-1 group-hover:text-white/80 transition">Editar foto</div>
                            </label>
                            <input type="file" id="profilePicInput" name="profilePic" accept="image/*" class="hidden" />
                            <button type="submit" class="px-4 py-1.5 rounded-lg bg-blue-500/80 hover:bg-blue-600 text-white font-semibold text-sm transition focus-outline mt-2">Guardar Foto</button>
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
                            <span id="email-usuario" class="text-lg font-semibold text-white/90"><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Sin email'; ?></span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                            <label class="w-32 text-white/60 font-medium">Rol:</label>
                            <span id="rol-usuario" class="text-lg font-semibold text-white/90"><?php echo isset($_SESSION['rol']) ? htmlspecialchars($_SESSION['rol']) : 'Sin rol'; ?></span>
                        </div>
                    </div>
                </div>
                <div class="divider my-8"></div>
                <h3 class="text-xl font-semibold mb-4 text-white/80 flex items-center gap-2">
                    <svg aria-hidden="true" viewBox="0 0 24 24" class="w-6 h-6 text-white/70"><path fill="currentColor" d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm6-2a6 6 0 1 1-12 0 6 6 0 0 1 12 0zm-6-9a1 1 0 0 1 1 1v1.07A7.001 7.001 0 0 1 19 15a1 1 0 1 1-2 0 5 5 0 1 0-10 0 1 1 0 1 1-2 0 7.001 7.001 0 0 1 6-6.93V7a1 1 0 0 1 2 0v1.07A7.001 7.001 0 0 1 19 15a1 1 0 1 1-2 0 5 5 0 1 0-10 0 1 1 0 1 1-2 0 7.001 7.001 0 0 1 6-6.93V7a1 1 0 0 1 1-1z"/></svg>
                    Cambiar Contraseña
                </h3>
                <form id="formCambiarPassword" class="space-y-5">
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                        <label for="currentPassword" class="w-32 text-white/60 font-medium">Contraseña actual:</label>
                        <input type="password" id="currentPassword" name="currentPassword" class="flex-1 px-4 py-2 rounded-lg bg-white/10 text-white focus:outline-none focus:ring-2 focus:ring-white/30" required autocomplete="current-password">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                        <label for="newPassword" class="w-32 text-white/60 font-medium">Nueva contraseña:</label>
                        <input type="password" id="newPassword" name="newPassword" class="flex-1 px-4 py-2 rounded-lg bg-white/10 text-white focus:outline-none focus:ring-2 focus:ring-white/30" required autocomplete="new-password">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                        <label for="confirmPassword" class="w-32 text-white/60 font-medium">Confirmar nueva:</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="flex-1 px-4 py-2 rounded-lg bg-white/10 text-white focus:outline-none focus:ring-2 focus:ring-white/30" required autocomplete="new-password">
                    </div>
                    <div>
                        <button type="submit" class="px-6 py-2 rounded-lg bg-blue-500/80 hover:bg-blue-600 text-white font-semibold transition focus-outline">Guardar Contraseña</button>
                        <span id="passwordChangeMsg" class="ml-4 text-sm"></span>
                        <button id="cerrarSesionBtn" class="px-6 py-2 rounded-lg bg-red-500/80 hover:bg-red-600 text-white font-semibold transition focus-outline mt-3">Cerrar Sesión</button><br>
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

    <style>
    .glass {
        background: rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .ring-soft {
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
    }
    .hover-glow:hover {
        box-shadow: 0 10px 30px rgba(255, 255, 255, 0.07), inset 0 0 0 1px rgba(255, 255, 255, 0.08);
        transform: translateY(-2px);
    }
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
    }
    .focus-outline:focus-visible {
        outline: 2px solid #ffffff;
        outline-offset: 2px;
    }
    .animate-in {
        animation: fadeSlideUp .45s ease both;
    }
    @keyframes fadeSlideUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</body>
</html>