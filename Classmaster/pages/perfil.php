<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
      exit();
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classmaster | Perfil</title>
    <link rel="stylesheet" href="../styles/perfil.css">
    <link rel="stylesheet" href="../styles/sidebar.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="../scripts/perfil.js" defer></script>
    <script type="module" src="../scripts/modalVinculo.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body class="min-h-screen flex flex-col selection:bg-white/10 selection:text-white text-gray-100">
    <?php include '../components/sidebar.php'; ?>
    <!-- Header -->
    <div id="mainContent" class="transition-all duration-300 ease-in-out ml-16">
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
                        <div class="space-y-5 flex-1">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                                <label class="w-32 text-white/60 font-medium">Nombre:</label>
                                <span id="nombre-usuario" class="text-lg font-semibold text-white/90"><?php echo htmlspecialchars($_SESSION['nombre']) . ' ' . htmlspecialchars($_SESSION['apellido']); ?></span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                                <label class="w-32 text-white/60 font-medium">Rol:</label>
                                <span id="rol-usuario" class="text-lg font-semibold text-white/90"><?php echo isset($_SESSION['rol']) ? ucfirst(strtolower(htmlspecialchars($_SESSION['rol']))) : ''; ?></span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                                <label class="w-32 text-white/60 font-medium">Email:</label>
                                <span id="email-usuario" class="text-lg font-semibold text-white/90">
                                    <?php
                                    
                                    switch($_SESSION['rol']) {
                                        case 'estudiante': $db = 'users'; break;
                                        case 'acudiente': $db = 'acudientes'; break;
                                        case 'profesor': $db = 'profesores'; break;
                                        case 'administrador': $db = 'administradores'; break;
                                    };
                                    $bind = ($_SESSION['rol'] === 'administrador') ? 's' : 'i';
                                    require_once '../php/connection.php';

                                    $user_id = $_SESSION['user_id'];
                                    $email = 'No disponible';

                                    $stmt = $conn->prepare("SELECT email FROM $db WHERE id = ?");
                                    $stmt->bind_param("$bind", $user_id);
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
                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'estudiante'): 
                                require_once '../php/connection.php';
                                $grado = 'No asignado';
                                $id_padre = null;
                                $stmt = $conn->prepare("SELECT grado, seccion, id_padre FROM users WHERE id = ?");
                                $stmt->bind_param("i", $_SESSION['user_id']);
                                $stmt->execute();
                                $stmt->bind_result($grado_db, $seccion_db, $id_padre);
                                if ($stmt->fetch()) {
                                    $grado = htmlspecialchars($grado_db) . ' - ' . htmlspecialchars($seccion_db);
                                }
                                $stmt->close();

                                $acudiente = 'No asignado';
                                if (!empty($id_padre)) {
                                    $stmt = $conn->prepare("SELECT nombre, apellido FROM acudientes WHERE id = ?");
                                    $stmt->bind_param("i", $id_padre);
                                    $stmt->execute();
                                    $stmt->bind_result($nombre_padre, $apellido_padre);
                                    if ($stmt->fetch()) {
                                        $acudiente = htmlspecialchars($nombre_padre) . ' ' . htmlspecialchars($apellido_padre);
                                    }
                                    $stmt->close();
                                }
                            ?>
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                                    <label class="w-32 text-white/60 font-medium">Grado:</label>
                                    <span id="grado-usuario" class="text-lg font-semibold text-white/90"><?php echo $grado; ?></span>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                                    <label class="w-32 text-white/60 font-medium">Acudiente:</label>
                                    <span id="acudiente-usuario" class="text-lg font-semibold text-white/90"><?php echo $acudiente; ?></span>
                                    <?php if (empty($id_padre) || $id_padre == 0): ?>
                                        <button id="btnAgregarAcudiente" class="ml-4 px-3 py-1 rounded-lg bg-green-500 text-white hover:bg-green-600">Agregar acudiente</button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'acudiente'){

                                require_once '../php/connection.php';
                                $telefono = 'Sin teléfono';
                                $stmt = $conn->prepare("SELECT telefono FROM acudientes WHERE id = ?");
                                $stmt->bind_param("i", $_SESSION['user_id']);
                                $stmt->execute();
                                $stmt->bind_result($telefono_db);
                                if ($stmt->fetch()) {
                                    $telefono = htmlspecialchars($telefono_db);
                                }
                                $stmt->close();
                                
                                echo '<div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                                        <label class="w-32 text-white/60 font-medium">Teléfono:</label>
                                        <span id="telefono-usuario" class="text-lg font-semibold text-white/90">' . $telefono . '</span>
                                        <ion-icon class="edit-icon cursor-pointer hover:scale-125 transition-transform" name="create"></ion-icon>
                                    </div>';
                                
                                // Obtener todos los estudiantes vinculados a este acudiente
                                $stmt = $conn->prepare("SELECT nombre, apellido FROM users WHERE id_padre = ?");
                                $stmt->bind_param("i", $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $estudiantes = array();
                                while ($row = $result->fetch_assoc()) {
                                    $estudiantes[] = htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellido']);
                                }
                                $stmt->close();

                                echo '<div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">';
                                echo '<label class="w-32 text-white/60 font-medium">Acudidos:</label>';
                                echo '<span id="hijo-usuario" class="text-lg font-semibold text-white/90">';
                                if (count($estudiantes) > 0) {
                                    echo implode('<br>', $estudiantes);
                                } else {
                                    echo 'Sin acudidos vinculados';
                                }
                                echo '</span>';
                                echo '</div>';
                            }?>
                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'profesor'){
                                require_once '../php/connection.php';
                                $materias = [];
                                $stmt = $conn->prepare("SELECT materias FROM profesores WHERE id = ?");
                                $stmt->bind_param("s", $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                    $materias[] = htmlspecialchars($row['materias']);
                                }
                                $stmt->close();
                                echo '<div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-start sm:items-center">
                                        <label class="w-32 text-white/60 font-medium">Materias:</label>
                                        <span id="materias-usuario" class="text-lg font-semibold text-white/90">' . (count($materias) > 0 ? implode($materias) : 'No asignadas') . '</span>
                                    </div>';
                            }
                            ?>
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
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const btn = document.getElementById('btnAgregarAcudiente');
        if (!btn) return;
        btn.addEventListener('click', function(e){
            e.preventDefault();
            if (typeof window.openModalVinculo === 'function') {
                window.openModalVinculo();
            } else {
                // fallback: load the module dynamically
                import('../scripts/modalVinculo.js').then(m => { if (m.openModalVinculo) m.openModalVinculo(); }).catch(err => console.error(err));
            }
        });
    });
</script>
</html>