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
    <link rel="icon" type="image/x-icon" href="../assets/logo.svg">
    <title>Classmaster | Calificaciones</title>
    <link rel="stylesheet" href="../styles/sidebar.css" />
    <link rel="stylesheet" href="../styles/notas.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="../scripts/notas.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body class="min-h-screen flex flex-col selection:bg-white/10 selection:text-white">
    <?php include '../components/sidebar.php'; ?>
    <div id="mainContent" class="transition-all duration-300 ease-in-out ml-16">
        <header class="w-full animate-fadeInDown">
            <div class="max-w-6xl mx-auto px-6 pt-8 pb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="size-3 rounded-full bg-white/80 shadow-[0_0_24px_4px_rgba(255,255,255,0.25)]"></div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold tracking-tight text-accent">Calificaciones</h1>
                </div>
                <p class="mt-2 text-sm text-white/60">Consulta y gestiona las calificaciones de tus materias y estudiantes</p>
            </div>
            <div class="divider"></div>
        </header>
        <main class="max-w-6xl mx-auto w-full px-4 pb-10">
            <section class="container rounded-xl p-4 flex flex-wrap gap-4 items-center mb-6 animate-fadeInUp">
                <label for="periodo" class="text-white/80 font-medium">Periodo:</label>
                <select name="periodo" id="periodo" class="bg-[#181824] text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                <?php if($_SESSION['rol'] === 'profesor') {
                    echo '<label for="grado" class="text-white/80 font-medium">Grado:</label>
                        <select name="grado" id="grado" class="bg-[#181824] text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30"></select>
                        <label for="seccion" class="text-white/80 font-medium ml-4">Sección:</label>
                        <select name="seccion" id="seccion" class="bg-[#181824] text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30">
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    ';
                } ?>
                <?php if($_SESSION['rol'] === 'acudiente') {
                    echo '<label for="estudiante" class="text-white/80 font-medium">Estudiante:</label>';
                    echo '<select name="estudiante" id="estudiante" class="bg-[#181824] text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30"></select>';
                } ?>
                <label for="materia" class="text-white/80 font-medium">Materia:</label>
                <select name="materia" id="materia" class="bg-[#181824] text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30"></select>
                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'profesor') {
                    echo '<input type="search" id="search" placeholder="Buscar estudiante..." class="ml-auto bg-[#181824] text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30 w-56" />';
                } ?>
            </section>
            <div class="container rounded-xl overflow-x-auto animate-fadeInUp">
                <table class="min-w-full text-left text-white/90 notas-table">
                    <thead class="notas-thead">
                        <tr id="notas-header" class="border-b border-white/10">
                            <th class="px-6 py-4 font-semibold align-middle">Nombre</th>
                        </tr>
                        <!-- Aquí van las tareas creadas filtradas-->
                    </thead>
                    <tbody>
                        <!-- Aquí van las filas de estudiantes y sus notas -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script>
        window.rol = "<?php echo $_SESSION['rol']; ?>";
        window.user_id = "<?php echo $_SESSION['user_id']; ?>";
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica de profesor manejada en scripts/notas.js
        });
    </script>
</body>
</html>