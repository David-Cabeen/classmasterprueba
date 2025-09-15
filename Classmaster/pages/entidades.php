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
    <title>Listado de Entidades - ClassMaster</title>
    <link rel="stylesheet" href="../styles/entidades.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../scripts/entidades.js" defer></script>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1><ion-icon name="school-outline"></ion-icon> ClassMaster</h1>
            <a href="landing.html" class="btn-home">
                <ion-icon name="home-outline"></ion-icon> Inicio
            </a>
        </div>
    </header>

    <main class="main-container">
        <div class="page-header">
            <h2>Listado de Entidades del Sistema</h2>
            <p>Vista general de usuarios y padres registrados en la plataforma</p>
        </div>

        <div class="loading" id="loading">
            <ion-icon name="refresh-outline" class="spinner"></ion-icon>
            <span>Cargando datos...</span>
        </div>

        <div class="content-grid" id="contentGrid" style="display: none;">
            <!-- Sección de Usuarios (Estudiantes) -->
            <section class="entity-section">
                <div class="section-header">
                    <h3><ion-icon name="people-outline"></ion-icon>Estudiantes</h3>
                    <span class="count-badge" id="usersCount">0</span>
                </div>
                <div class="table-container">
                    <table class="entity-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Grado</th>
                                <th>Sección</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody id="usersTable">
                            <!-- Los datos se cargarán de la BBDD -->
                        </tbody>

                    </table>
                </div>
            </section>

            <!-- Sección de Padres (Acudientes) -->
            <section class="entity-section">
                <div class="section-header">
                    <h3><ion-icon name="person-add-outline"></ion-icon>Acudientes</h3>
                    <span class="count-badge" id="padresCount">0</span>
                </div>
                <div class="table-container">
                    <table class="entity-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Teléfono</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody id="padresTable">
                            <!-- Los datos se cargarán de la BBDD -->
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Sección de Maestros -->
        <section class="entity-section">
                <div class="section-header">
                    <h3><ion-icon name="people-outline"></ion-icon>Maestros</h3>
                    <span class="count-badge" id="teachersCount">0</span>
                </div>
                <div class="table-container">
                    <table class="entity-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Materias</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody id="teachersTable">
                            <!-- Los datos se cargarán de la BBDD -->
                        </tbody>

                    </table>
                </div>
            </section>

        <div class="error-message" id="errorMessage" style="display: none;">
            <ion-icon name="alert-circle-outline"></ion-icon>
            <span id="errorText">Error al cargar los datos</span>
        </div>

    </main>

    <style>
    .modal { position: fixed; z-index: 1000; left: 0; top: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; }
    .modal-content { background: #fff; padding: 2rem; border-radius: 8px; min-width: 300px; position: relative; }
    .close { position: absolute; right: 1rem; top: 1rem; cursor: pointer; font-size: 1.5rem; }
    </style>
</body>
</html>