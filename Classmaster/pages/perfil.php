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
    <title>Mi Perfil</title>
    <link href="../styles/perfil.css" rel="stylesheet">
</head>
<body>
    </head>
<body>
    <div class="container">
        <header class="header">
            <button class="theme-toggle" onclick="toggleTheme()">
                 🌙Cambiar Tema
            </button>
            <h1>Mi Perfil</h1>
        </header>

        <main>
            <section class="profile-card">
                <div class="profile-photo-section">
                    <img src="https://via.placeholder.com/150x150/007bff/ffffff?text=Foto" 
                         alt="Foto de perfil" 
                         class="profile-photo" 
                         id="profilePhoto">
                    <br>
                    <button class="photo-upload-btn" onclick="changePhoto()">
                         Cambiar Foto
                    </button>
                    <input type="file" 
                           id="photoInput" 
                           class="hidden-input" 
                           accept="image/*" 
                           onchange="handlePhotoChange(event)">
                </div>

                <div class="success-message" id="successMessage">
                    ✅ Cambios guardados exitosamente
                </div>

                <section class="student-info">
                    <h2 class="section-title">Información del Estudiante</h2>
                    
                    <form id="profileForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">Nombre</label>
                                <input type="text" 
                                       id="firstName" 
                                       name="firstName" 
                                       value="Ximena" 
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Apellido</label>
                                <input type="text" 
                                       id="lastName" 
                                       name="lastName" 
                                       value="Moreno Londoño" 
                                       required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="grade">Grado</label>
                                <select id="grade" name="grade" required>
                                    <option value="">Seleccionar grado</option>
                                    <option value="6">6° Grado</option>
                                    <option value="7">7° Grado</option>
                                    <option value="8">8° Grado</option>
                                    <option value="9" selected>9° Grado</option>
                                    <option value="10">10° Grado</option>
                                    <option value="11">11° Grado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="document">Número de Documento</label>
                                <input type="text" 
                                       id="document" 
                                       name="document" 
                                       value="1034993675" 
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="ximenamorenolondono10@gmail.com" 
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Guardar Cambios
                        </button>
                    </form>
                </section>

                <section class="password-section">
                    <h2 class="section-title">Cambiar Contraseña</h2>
                    
                    <form id="passwordForm">
                        <div class="form-group">
                            <label for="currentPassword">Contraseña Actual</label>
                            <input type="password" 
                                   id="currentPassword" 
                                   name="currentPassword" 
                                   required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="newPassword">Nueva Contraseña</label>
                                <input type="password" 
                                       id="newPassword" 
                                       name="newPassword" 
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirmar Contraseña</label>
                                <input type="password" 
                                       id="confirmPassword" 
                                       name="confirmPassword" 
                                       required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger">
                             Cambiar Contraseña
                        </button>
                    </form>
                </section>
            </section>
        </main>
    </div>

    <script src="../scripts/perfil.js"> </script>
</body>
</html>