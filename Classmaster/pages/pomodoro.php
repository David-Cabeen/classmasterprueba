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
    <title>Classmaster | Método Pomodoro</title>
    <link rel="stylesheet" href="../styles/pomodoro.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
    <script src="../scripts/pomodoro.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../styles/sidebar.css">
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <div class="circle"><p></p></div>
    <div class="timer">
        <h1 class="text-6xl font-bold mb-2" id="time">25:00</h1>
        <div class="button-dock">
            <ion-icon id="start" name="play-circle"></ion-icon>
            <ion-icon id="pause" name="pause-circle"></ion-icon>
            <ion-icon style="transform: rotateY(180deg);" id="reset" name="refresh-circle"></ion-icon>
        </div>
    </div>
    <div class="controls">
        <ion-icon id="controls-toggle" name="settings-outline"></ion-icon>
        <div class="hidden-controls text-sm">
            <input class="hidden" type="number" id="work-time" value="25">
            <input class="hidden" type="number" id="break-time" value="5">
            <input class="hidden" type="number" id="long-break-time" value="10">
            <input class="hidden" type="number" id="cycles" value="4">
        </div>
        <p></p>
    </div>
</body>
</html>