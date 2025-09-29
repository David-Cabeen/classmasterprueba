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
    <title>Notas</title>
    <link rel="stylesheet" href="../styles/notas.css">
    <script src="../scripts/notas.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body>
    <h1>Calificaciones</h1>
    <main>
        <section class="selector">
            <span>Periodo: </span>
            <select name="periodo" id="periodo">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'profesor') {
                echo '<span>Grado: </span>
                    <select name="grado" id="grado">
                        <!--Cargar grados del maestro desde la bbdd-->
                    </select>';
            } ?>
            <span>Materia: </span>
            <select name="materia" id="materia">
                <!--Cargar materias del maestro desde la bbdd-->
            </select>
            <input type="search" name="" id="search" placeholder="Buscar estudiante...">
        </section>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                        <!--Reemplazar estas tareas por las notas agregadas en la BBDD-->
                </tr>
            </thead>
            <tbody>
                <!--Reemplazar estos estudiantes por los que cumplan los requistos de 
                los filtros de grado, periodo y materia. Son tomados de la BBDD-->
            </tbody>
        </table>
    </main>
    <script>
        window.rol = "<?php echo $_SESSION['rol']; ?>";
        document.addEventListener('DOMContentLoaded', function() {
            if (window.rol === 'profesor') {
                fetch('../php/get_profesor_materias_grados.php')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const materiaSelect = document.getElementById('materia');
                            materiaSelect.innerHTML = '';
                            data.materias.forEach(m => {
                                const opt = document.createElement('option');
                                opt.value = m;
                                opt.textContent = m;
                                materiaSelect.appendChild(opt);
                            });
                            const gradoSelect = document.getElementById('grado');
                            if (gradoSelect) {
                                gradoSelect.innerHTML = '';
                                data.grados.forEach(g => {
                                    const opt = document.createElement('option');
                                    opt.value = g;
                                    opt.textContent = g;
                                    gradoSelect.appendChild(opt);
                                });
                            }
                        }
                    });
            }
        });
    </script>
</body>
</html>