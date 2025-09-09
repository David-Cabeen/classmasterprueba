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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hogar</title>
  <link rel="stylesheet" href="../styles/home.css" />
</head>
<body>

  <header class="home-header">
    <h1>Bienvenido a ClassMaster, <span id="nombre-usuario">
      <?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario'; ?>
    </span></h1>

    <div class="logo-menu-container">
      <img
        id="logo"
        src="../assets/logo.svg"
        class="logo-derecha"
      />
      <div class="menu-desplegable" id="menu">
        <button onclick="cerrarSesion()">Cerrar sesión</button>
      </div>
    </div>
  </header>

  <main>
    <h2>¿Qué deseas hacer hoy?</h2>

    <section class="cards-container">
      <a href="notas.html" class="card">📚 Notas</a>
      <a href="calendario.html" class="card">🗓️ Calendario</a>
      <a href="tareas.html" class="card">📝 Tareas</a>
      <a href="pomodoro.html" class="card">🍅 Pomodoro</a>
      <a href="metodos.html" class="card">📒 Métodos</a>
      <a href="ayudas.html" class="card">❓ Ayudas</a>
    </section>

    <section>
      <p class="frase-motivacional" id="frase-semanal"></p>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 - ClassMaster, todos los derechos reservados</p>
  </footer>

  <script src="home.js"></script>
</body>
</html>

