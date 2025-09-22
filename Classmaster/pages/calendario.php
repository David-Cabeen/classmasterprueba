<?php
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
    <title>Calendario</title>
    <link rel="stylesheet" href="../styles/calendario.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div id="overlay"></div>
    <div class="container glass rounded-2xl border border-white/10 animate-in">
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
    </div>
    <script src="../scripts/calendario.js"></script>
</body>
</html>