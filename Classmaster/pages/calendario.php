<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
      header('Location: inicio.html');
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
</head>
<body>
    <div id="overlay"></div>
    <div class="container">
        <div class="navigation">
            <ion-icon id="prev" name="arrow-back"></ion-icon>
            <h1 id="month-year"></h1>
            <ion-icon id="next" name="arrow-forward"></ion-icon>
        </div>
        <div id="calendar">
            <div class="weekdays">
                <div>Dom</div>
                <div>Lun</div>
                <div>Mar</div>
                <div>Mie</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sab</div>
            </div>
            <div id="days"></div>
        </div>
        <div id="events">
            <div class="day-header">
                <h1 id="day-number"></h1>
                <ion-icon id="close" name="close"></ion-icon>
                <p id="distance"></p>
            </div>
            <hr>
            <h2>Eventos</h2>
            <ul id="event-list"></ul>
            <button id="event-adder" tabindex="-1">Añadir evento</button>
        </div>
    </div>
    <div id="event-window">
        <h1>Añadir evento</h1>
        <ion-icon id="close-event" name="close"></ion-icon> <hr>
        <form id="event-form">
            <input type="text" id="event-input" placeholder="Título" required/> <br>
            <textarea name="event-description" placeholder="Descripción" id="event-description"></textarea>
            <fieldset>
                <input type="radio" name="priority" id="normal" value="normal">
                <label for="normal">Normal <ion-icon name="ellipse-outline"/></label>
                <input type="radio" name="priority" id="important" value="important">
                <label for="important">Importante <ion-icon name="ellipse-outline"/></label>
                <input type="radio" name="priority" id="urgent" value="urgent">
                <label for="urgent">Urgente <ion-icon name="ellipse-outline"/></label>
            </fieldset>
            <button type="submit">Añadir</button>
        </form>
    </div>
    <script src="../scripts/calendario.js"></script>
</body>
</html>