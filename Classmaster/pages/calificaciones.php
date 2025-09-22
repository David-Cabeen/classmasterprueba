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
  <title>Página de Calificaciones</title>
  <link rel="stylesheet" href="calificaciones.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  
 
</head>
<body>
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Calificaciones de Estudiantes</h1>

    <!-- Filtro de materias -->
    <div class="mb-4">
      <label for="materia" class="block mb-2 font-semibold">Selecciona una materia:</label>
      <select id="materia" class="w-full p-2 rounded border" onchange="cambiarMateria()">
        <option value="espanol">Español</option>
        <option value="matematicas">Matemáticas</option>
        <option value="ciencias">Ciencias</option>
      </select>
    </div>

    <!-- Tabla de actividades -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white rounded-xl shadow">
        <thead>
          <tr>
            <th>Actividad</th>
            <th>Tipo</th>
            <th>Nota</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tabla-cuerpo">
          <!-- Filas generadas dinámicamente -->
        </tbody>
      </table>
    </div>

    <!-- Botón para añadir actividad -->
    <div class="mt-4 text-right">
      <button onclick="mostrarFilaNueva()" class="btn-primario px-4 py-2 rounded">Añadir Actividad</button>
    </div>
  </div>

  <script>
    // Datos base por materia
    const datos = {
      espanol: [
        { actividad: "Ensayo", tipo: "Tarea", nota: 4.5 },
        { actividad: "Prueba de lectura", tipo: "Examen", nota: 4.0 }
      ],
      matematicas: [
        { actividad: "Guía de ejercicios", tipo: "Tarea", nota: 3.8 },
        { actividad: "Examen parcial", tipo: "Examen", nota: 4.2 }
      ],
      ciencias: [
        { actividad: "Laboratorio", tipo: "Actividad", nota: 4.0 }
      ]
    };

    let materiaActual = "espanol";
    let filaNuevaMostrada = false;

    function cargarTabla() {
      const cuerpo = document.getElementById("tabla-cuerpo");
      cuerpo.innerHTML = "";
      datos[materiaActual].forEach((fila, index) => {
        const tr = document.createElement("tr");
        tr.classList.add("border-b");
        tr.innerHTML = `
          <td>${fila.actividad}</td>
          <td>${fila.tipo}</td>
          <td><span class="calificacion">${fila.nota.toFixed(1)}</span></td>
          <td class="space-x-2">
            <button class="btn-editar text-sm" onclick="editarNota(${index})">Editar</button>
            <button class="btn-borrar" onclick="borrarFila(${index})">
              <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
            </button>
          </td>
        `;
        cuerpo.appendChild(tr);
      });
      lucide.createIcons();
    }

    function cambiarMateria() {
      materiaActual = document.getElementById("materia").value;
      filaNuevaMostrada = false;
      cargarTabla();
    }

    function editarNota(index) {
      const nuevaNota = prompt("Editar nota:", datos[materiaActual][index].nota);
      if (nuevaNota !== null && !isNaN(nuevaNota)) {
        datos[materiaActual][index].nota = parseFloat(nuevaNota);
        cargarTabla();
      }
    }

    function borrarFila(index) {
      if (confirm("¿Estás seguro de borrar esta actividad?")) {
        datos[materiaActual].splice(index, 1);
        cargarTabla();
      }
    }

    function mostrarFilaNueva() {
      if (filaNuevaMostrada) return;
      filaNuevaMostrada = true;
      const cuerpo = document.getElementById("tabla-cuerpo");
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td><input type="text" class="w-full p-1 border rounded" placeholder="Actividad" id="nueva-actividad"></td>
        <td><input type="text" class="w-full p-1 border rounded" placeholder="Tipo" id="nuevo-tipo"></td>
        <td><input type="number" step="0.1" min="0" max="5" class="w-full p-1 border rounded" placeholder="Nota" id="nueva-nota"></td>
        <td>
          <button onclick="guardarFilaNueva()" class="btn-guardar px-3 py-1 rounded">Guardar</button>
        </td>
      `;
      cuerpo.appendChild(tr);
    }

    function guardarFilaNueva() {
      const actividad = document.getElementById("nueva-actividad").value;
      const tipo = document.getElementById("nuevo-tipo").value;
      const nota = document.getElementById("nueva-nota").value;
      if (actividad && tipo && nota && !isNaN(nota)) {
        datos[materiaActual].push({ actividad, tipo, nota: parseFloat(nota) });
        filaNuevaMostrada = false;
        cargarTabla();
      } else {
        alert("Por favor completa todos los campos correctamente.");
      }
    }

    cargarTabla();
  </script>
</body>
</html>