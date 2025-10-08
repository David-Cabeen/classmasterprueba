<?php
  session_start();
  if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
      header('Location: ../index.php');
      exit();
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classmaster | Entidades</title>
    <link rel="stylesheet" href="../styles/sidebar.css" />
    <link rel="stylesheet" href="../styles/entidades.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../scripts/entidades.js" defer></script>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <div id="mainContent" class="transition-all duration-300 ease-in-out ml-16">
        <header class="w-full">
            <div class="max-w-6xl mx-auto px-6 pt-8 pb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="size-3 rounded-full bg-white/80 shadow-[0_0_24px_4px_rgba(255,255,255,0.25)]"></div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold tracking-tight text-accent flex items-center gap-2">
                        <ion-icon name="school-outline"></ion-icon>
                        Entidades
                    </h1>
                </div>
                <p class="mt-2 text-sm text-white/60">Administra los usuarios, acudientes, profesores y administradores registrados en la plataforma</p>
            </div>
            <div class="divider"></div>
        </header>

        <main class="main-container">
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

                <!-- Sección de Acudientes -->
                <section class="entity-section">
                    <div class="section-header">
                        <h3><ion-icon name="person-add-outline"></ion-icon>Acudientes</h3>
                        <span class="count-badge" id="acudientesCount">0</span>
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
                            <tbody id="acudientesTable">
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

            <!-- Sección de Administradores -->
            <section class="entity-section">
                    <div class="section-header">
                        <h3><ion-icon name="people-outline"></ion-icon>Administradores</h3>
                        <span class="count-badge" id="adminsCount">0</span>
                    </div>
                    <div class="table-container">
                        <table class="entity-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Email</th>
                                    <th>Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody id="adminsTable">
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
    </div>
</body>
</html>