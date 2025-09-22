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
    <title>Tablero Kanban</title>
    <link rel="stylesheet" href="../styles/kanban.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body>
    <header>
        <h1>Tablero Kanban</h1>
    </header>
    <main>
        <div class="kanban-board">
            <section class="kanban-column" id="por-hacer">
                <h2>Por hacer</h2>
                <div class="kanban-tasks"></div>
                <button class="add-task-btn" data-column="por-hacer">Añadir tarea</button>
            </section>
            <section class="kanban-column" id="en-proceso">
                <h2>En proceso</h2>
                <div class="kanban-tasks"></div>
            </section>
            <section class="kanban-column" id="terminado">
                <h2>Terminado</h2>
                <div class="kanban-tasks"></div>
            </section>
        </div>
    </main>
    <div id="overlay"></div>
    <div id="task-popup">
        <h1>Añadir tarea</h1>
        <ion-icon id="close-task-popup" name="close"></ion-icon>
        <hr>
        <form id="task-form">
            <input type="text" id="task-title" placeholder="Nombre de la tarea" required><br>
            <textarea id="task-desc" placeholder="Descripción (opcional)"></textarea>
            <input type="date" id="task-date" required><br>
            <fieldset>
                <input type="radio" name="priority" id="normal" value="normal">
                <label for="normal">Normal <ion-icon name="ellipse-outline"></ion-icon></label>
                <input type="radio" name="priority" id="important" value="important">
                <label for="important">Importante <ion-icon name="ellipse-outline"></ion-icon></label>
                <input type="radio" name="priority" id="urgent" value="urgent">
                <label for="urgent">Urgente <ion-icon name="ellipse-outline"></ion-icon></label>
            </fieldset>
            <button type="submit">Añadir</button>
        </form>
    </div>
    <script src="../scripts/kanban.js"></script>
</body>
</html>
