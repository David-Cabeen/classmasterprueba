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
            <span>Grado: </span>
            <select name="grado" id="grado">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
            </select>
            <span>Materia: </span>
            <select name="materia" id="materia">
                <option value="espanol">Español</option>
                <option value="matematicas">Matemáticas</option>
                <option value="ciencias">Ciencias</option>
                <option value="historia">Historia</option>
                <option value="geografia">Geografía</option>
            </select>
            <input type="search" name="" id="search" placeholder="Buscar estudiante...">
        </section>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tarea 1</th> <!--Reemplazar estas tareas por las notas agregadas en la BBDD-->
                    <th>Tarea 2</th>
                    <th>Promedio</th>
                </tr>
            </thead>
            <tbody>
                <!--Reemplazar estos estudiantes por los que cumplan los requistos de 
                los filtros de grado, periodo y materia. Son tomados de la BBDD-->
                <tr>
                    <td>Juan Pérez</td>
                    <td>8.5</td>
                    <td>9.0</td>
                    <td>8.75</td>
                </tr>
                <tr>
                    <td>María López</td>
                    <td>7.0</td>
                    <td>8.5</td>
                    <td>7.75</td>
                </tr>
                <tr>
                    <td>Carlos García</td>
                    <td>9.0</td>
                    <td>9.5</td>
                    <td>9.25</td>
                </tr>
                <tr>
                    <td>Laura Martínez</td>
                    <td>6.5</td>
                    <td>7.0</td>
                    <td>6.75</td>
                </tr>
                <tr>
                    <td>Pedro Sánchez</td>
                    <td>8.0</td>
                    <td>8.5</td>
                    <td>8.25</td>
                </tr>
                <tr>
                    <td>Lucía Fernández</td>
                    <td>9.5</td>
                    <td>10.0</td>
                    <td>9.75</td>
                </tr>
                <tr>
                    <td>Jorge Ramírez</td>
                    <td>7.5</td>
                    <td>8.0</td>
                    <td>7.75</td>
                </tr>
                <tr>
                    <td>Ana Torres</td>
                    <td>8.0</td>
                    <td>9.0</td>
                    <td>8.50</td>
                </tr>
                <tr>
                    <td>Diego Flores</td>
                    <td>6.0</td>
                    <td>7.5</td>
                    <td>6.75</td>
                </tr>
                <tr>
                    <td>Sofía Gómez</td>
                    <td>9.0</td>
                    <td>9.5</td>
                    <td>9.25</td>
                </tr>
            </tbody>
        </table>
    </main>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>