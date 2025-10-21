<?php
    // Página: Calendario académico
    // Muestra eventos personales y de curso; permite crear/editar eventos según permisos
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php');
            exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.png">
    <title>Classmaster | Calendario</title>
    <script type="module" src="../scripts/calendario.js"></script>
    <link rel="stylesheet" href="../styles/calendario.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../styles/sidebar.css"/>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <?php $is_parent = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'acudiente'); ?>
    <div class="container glass rounded-2xl border border-white/10 animate-in<?php echo $is_parent ? ' has-studentselector' : ''; ?>">
        <div id="calendar" class="rounded-xl border border-white/10 glass my-4">
            <div class="weekdays grid grid-cols-7 text-center gap-2 text-white/70 font-semibold text-base">
                <div>Dom</div>
                <div>Lun</div>
                <div>Mar</div>
                <div>Mie</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sab</div>
            </div>
            <div id="days" class="mt-2"></div>
        </div>
        <div class="navigation flex items-center justify-between border border-white/10">
            <ion-icon id="prev" class="hover:text-blue-500" name="arrow-back"></ion-icon>
            <h1 id="month-year" class="tracking-tight"></h1>
            <ion-icon id="next" class="hover:text-blue-500" name="arrow-forward"></ion-icon>
        </div>
        <div id="events" class="border border-white/10">
            <div class="day-header gap-3 px-6 pt-6 pb-2">
                <h1 id="day-number" class="text-lg font-semibold"></h1>
                <ion-icon id="close" name="close" class="ml-auto text-2xl text-white/60 hover:text-red-500 cursor-pointer focus:outline-none"></ion-icon>
                <p id="distance" class="text-sm text-white/40 ml-2"></p>
            </div>
            <div class="divider my-1"></div>
            <h2 class="px-6 text-base font-bold text-white/80 mb-2 tracking-wide">Eventos</h2>
            <ul id="event-list" class="px-4"></ul>
            <button id="event-adder" tabindex="-1" class="absolute bottom-4 right-4">Añadir evento</button>
        </div>
        <?php if ($is_parent): ?>
        <div id="student-selector" class="border border-white/10 flex items-center px-6">
            <label for="student-select" class="text-sm text-white/70 mr-3 font-semibold">Ver calendario de:</label>
            <select id="student-select" class="bg-white/10 border border-white/10 rounded px-3 py-2 text-white">
            </select>
        </div>
        <?php endif; ?>
    </div>
    <script>
        window.rol = "<?php echo $_SESSION['rol']; ?>";
    </script>
</body>
</html>