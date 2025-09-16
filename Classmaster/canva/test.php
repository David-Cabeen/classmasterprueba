<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Administrador · Reportes de Calificaciones</title>
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            bg: "#0e0e10",
            panel: "#16161a",
            muted: "#9ca3af",
            text: "#f3f4f6",
            line: "#26262b",
            accent: "#ffffff",
          },
          boxShadow: {
            glow: "0 0 0 2px rgba(255,255,255,0.05), 0 10px 30px rgba(0,0,0,0.5)",
          },
          borderRadius: {
            xl2: "1rem",
          },
          fontFamily: {
            inter: ['Inter','ui-sans-serif','system-ui','Segoe UI','Roboto','Helvetica Neue','Arial'],
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg: #0e0e10;
      --panel: #16161a;
      --muted: #9ca3af;
      --text: #f3f4f6;
      --line: #26262b;
      --accent: #ffffff;
    }
    .smooth{ transition: all .2s ease; }
    .smooth-300{ transition: all .3s ease; }
    .glass {
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0));
      backdrop-filter: blur(6px);
    }
    ::-webkit-scrollbar { height: 10px; width: 10px; }
    ::-webkit-scrollbar-track { background: var(--bg); }
    ::-webkit-scrollbar-thumb { background: #2b2b31; border-radius: 999px; }
    ::-webkit-scrollbar-thumb:hover { background: #3a3a40; }
    tr.data-row:hover td { background-color: rgba(255,255,255,0.02); }
    .chip { background: #202027; color: var(--text); }
    .ring-soft:focus { outline: none; box-shadow: 0 0 0 3px rgba(255,255,255,0.08); }
    .top-accent {
      background: linear-gradient(90deg, rgba(255,255,255,.25) 0%, rgba(255,255,255,.1) 35%, rgba(255,255,255,.25) 100%);
      height: 2px;
    }
    .pulse-dot{ position: relative; }
    .pulse-dot::after{
      content:""; position:absolute; inset:-6px; border-radius:999px; border:2px solid rgba(255,255,255,0.08);
      animation: ping 1.6s cubic-bezier(0,0,0.2,1) infinite;
    }
    @keyframes ping { 0%{ transform:scale(.9); opacity:.6 } 80%{ transform:scale(1.4); opacity:0 } 100%{ opacity:0 } }
  </style>
</head>
<body class="bg-[var(--bg)] text-[var(--text)] font-inter min-h-screen">
  <!-- Admin Header -->
  <header class="sticky top-0 z-30 border-b border-[var(--line)] bg-[var(--bg)]/80 glass">
    <div class="top-accent"></div>
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="pulse-dot w-3 h-3 rounded-full bg-[var(--accent)]"></div>
        <div>
          <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Administrador · Reportes de Calificaciones</h1>
          <div class="text-[var(--muted)] text-xs sm:text-sm">Vista general de estudiantes, materias y periodos</div>
        </div>
      </div>
      <div class="hidden md:flex items-center gap-2">
        <button id="resetAll" class="smooth px-3.5 py-2 rounded-lg bg-panel/60 hover:bg-panel border border-[var(--line)] text-[var(--text)] text-sm">Limpiar filtros</button>
        <button id="exportCSV" class="smooth px-3.5 py-2 rounded-lg bg-[var(--text)] text-[var(--bg)] hover:opacity-90 text-sm font-semibold">Exportar CSV</button>
      </div>
      <div class="md:hidden">
        <button id="mobileMenuBtn" class="smooth p-2 rounded-lg bg-panel border border-[var(--line)] hover:bg-panel/80" aria-label="Abrir filtros">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="max-w-7xl mx-auto px-6 py-8">
    <!-- Top layout: compact summary + filters -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <!-- Compact summary card (smaller) -->
      <div class="lg:col-span-2 bg-[var(--panel)] border border-[var(--line)] rounded-xl2 shadow-glow p-0 overflow-hidden">
        <div class="flex items-center justify-between px-4 sm:px-5 py-3 border-b border-[var(--line)]">
          <div class="font-semibold">Resumen general</div>
          <div class="flex items-center gap-1 text-xs text-[var(--muted)]">Datos de muestra</div>
        </div>
        <div class="px-4 sm:px-5 py-4">
          <!-- Period tabs (only period filter) -->
          <div class="flex flex-wrap gap-2 mb-3">
            <button data-period-tab="" class="period-tab smooth px-3 py-1.5 rounded-md border border-[var(--line)] bg-[#1b1b20] text-sm">Todos</button>
            <button data-period-tab="Periodo 1" class="period-tab smooth px-3 py-1.5 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm">Periodo 1</button>
            <button data-period-tab="Periodo 2" class="period-tab smooth px-3 py-1.5 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm">Periodo 2</button>
            <button data-period-tab="Periodo 3" class="period-tab smooth px-3 py-1.5 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm">Periodo 3</button>
            <button data-period-tab="Periodo 4" class="period-tab smooth px-3 py-1.5 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm">Periodo 4</button>
          </div>
          <!-- KPIs compact -->
          <div class="grid grid-cols-3 gap-3">
            <div class="bg-[#121216] border border-[var(--line)] rounded-xl p-3">
              <div class="text-xs text-[var(--muted)]">Estudiantes</div>
              <div id="statEstudiantes" class="text-xl sm:text-2xl font-bold mt-1">0</div>
            </div>
            <div class="bg-[#121216] border border-[var(--line)] rounded-xl p-3">
              <div class="text-xs text-[var(--muted)]">Promedio</div>
              <div id="statPromedio" class="text-xl sm:text-2xl font-bold mt-1">0.00</div>
            </div>
            <div class="bg-[#121216] border border-[var(--line)] rounded-xl p-3">
              <div class="text-xs text-[var(--muted)]">Materias</div>
              <div id="statMaterias" class="text-xl sm:text-2xl font-bold mt-1">Todas</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Side filters (clean, no period select now) -->
      <div class="bg-[var(--panel)] border border-[var(--line)] rounded-xl2 shadow-glow p-5">
        <div class="text-sm text-[var(--muted)] mb-1.5">Buscar estudiante</div>
        <div class="relative mb-4">
          <input id="searchInput" type="text" placeholder="Escribe un nombre..." class="w-full ring-soft smooth bg-[#121216] border border-[var(--line)] rounded-lg pl-10 pr-3.5 py-2.5"/>
          <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--muted)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-width="1.5" d="M21 21l-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
            </svg>
          </div>
        </div>

        <div class="text-sm text-[var(--muted)] mb-1.5">Grado</div>
        <div class="relative mb-4">
          <select id="gradoSelect" class="w-full ring-soft smooth bg-[#121216] border border-[var(--line)] rounded-lg px-3.5 py-2.5 pr-9 appearance-none">
            <option value="">Todos</option>
            <option>6°</option>
            <option>7°</option>
            <option>8°</option>
            <option>9°</option>
            <option>10°</option>
            <option>11°</option>
          </select>
          <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[var(--muted)]">▼</div>
        </div>

        <div class="text-sm text-[var(--muted)] mb-1.5">Materias</div>
        <div id="materiasMultiselect" class="relative">
          <button id="materiasBtn" class="w-full ring-soft smooth bg-[#121216] border border-[var(--line)] rounded-lg px-3.5 py-2.5 flex items-center justify-between gap-3">
            <span id="materiasLabel" class="truncate text-left">Todas</span>
            <span class="text-[var(--muted)]">▼</span>
          </button>
          <div id="materiasMenu" class="hidden absolute z-20 mt-2 w-full bg-[var(--panel)] border border-[var(--line)] rounded-xl shadow-glow p-2">
            <div class="max-h-56 overflow-auto pr-1"></div>
            <div class="flex justify-between items-center pt-2 border-t border-[var(--line)]">
              <button id="materiasClear" class="text-sm text-[var(--muted)] hover:text-[var(--text)] smooth">Limpiar</button>
              <button id="materiasApply" class="text-sm font-semibold px-3 py-1.5 rounded-md bg-[var(--text)] text-[var(--bg)] hover:opacity-90">Aplicar</button>
            </div>
          </div>
        </div>

        <div class="mt-4 flex items-center gap-2">
          <button id="resetAll_m" class="smooth px-3.5 py-2 rounded-lg bg-panel/60 hover:bg-panel border border-[var(--line)] text-[var(--text)] text-sm flex-1">Limpiar</button>
          <button id="exportCSV_m" class="smooth px-3.5 py-2 rounded-lg bg-[var(--text)] text-[var(--bg)] hover:opacity-90 text-sm font-semibold flex-1">CSV</button>
        </div>
      </div>
    </section>

    <!-- Table Panel -->
    <section class="mt-6 bg-[var(--panel)] border border-[var(--line)] rounded-xl2 shadow-glow overflow-hidden">
      <div class="px-5 py-4 flex items-center justify-between border-b border-[var(--line)]">
        <div class="flex items-center gap-3">
          <h2 class="text-lg font-semibold">Notas por estudiante</h2>
          <span id="tableCount" class="text-[var(--muted)] text-sm"></span>
        </div>
        <div class="hidden sm:flex items-center gap-2">
          <button id="sortByName" class="smooth px-3 py-1.5 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm">Ordenar por nombre</button>
          <button id="sortByAvg" class="smooth px-3 py-1.5 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm">Ordenar por promedio</button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full min-w-[920px] text-left">
          <thead class="bg-[#121216] sticky top-[var(--top-offset,0px)] z-10">
            <tr class="text-[var(--muted)] text-sm border-b border-[var(--line)]">
              <th class="px-5 py-3 font-medium">Estudiante</th>
              <th class="px-5 py-3 font-medium">Grado</th>
              <th class="px-5 py-3 font-medium">Periodo</th>
              <th class="px-5 py-3 font-medium">Materias</th>
              <th class="px-5 py-3 font-medium">Tareas</th>
              <th class="px-5 py-3 font-medium">Promedio</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody id="tableBody" class="divide-y divide-[var(--line)]">
            <!-- Rows injected -->
          </tbody>
        </table>
      </div>

      <div class="px-5 py-4 border-t border-[var(--line)] flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-sm text-[var(--muted)]" id="paginationInfo">Mostrando 0 de 0</div>
        <div class="flex items-center gap-2">
          <button id="prevPage" class="smooth px-3 py-1.5 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm">Anterior</button>
          <div id="pageIndicators" class="flex items-center gap-1"></div>
          <button id="nextPage" class="smooth px-3 py-1.5 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm">Siguiente</button>
        </div>
      </div>
    </section>
  </main>

  <!-- Modal detalles -->
  <div id="detailsModal" class="hidden fixed inset-0 z-40 items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="relative max-w-3xl w-full bg-[var(--panel)] border border-[var(--line)] rounded-xl2 shadow-glow p-5">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h3 id="modalTitle" class="text-xl font-bold">Detalle de estudiante</h3>
          <p id="modalSubtitle" class="text-[var(--muted)] text-sm mt-1">—</p>
        </div>
        <button id="modalClose" class="smooth p-2 rounded-lg border border-[var(--line)] hover:bg-[#1b1b20]" aria-label="Cerrar">✕</button>
      </div>
      <div id="modalBody" class="mt-4 space-y-4">
        <!-- Content injected -->
      </div>
      <div class="mt-5 flex justify-end">
        <button id="modalClose2" class="smooth px-4 py-2 rounded-lg bg-[var(--text)] text-[var(--bg)] hover:opacity-90 font-semibold">Cerrar</button>
      </div>
    </div>
  </div>

  <script>
    // Catálogos
    const MATERIAS = ["Matemáticas", "Lengua", "Ciencias", "Historia", "Inglés", "Arte"];
    const PERIODOS = ["Periodo 1","Periodo 2","Periodo 3","Periodo 4"];
    const GRADOS = ["6°","7°","8°","9°","10°","11°"];

    // Utilidades
    const avg = arr => arr.length ? arr.reduce((a,b)=>a+b,0)/arr.length : 0;
    const format2 = n => (isNaN(n)?0:n).toFixed(2);

    // Genera tareas distintas por materia y periodo
    function generarTareas(materia, periodo){
      const seed = materia.charCodeAt(0) + periodo.charCodeAt(8);
      const temas = {
        "Matemáticas": ["Álgebra","Funciones","Geometría","Cálculo"],
        "Lengua": ["Ensayo","Lectura","Análisis","Gramática"],
        "Ciencias": ["Biología","Química","Física","Laboratorio"],
        "Historia": ["Línea de tiempo","Mapa","Ensayo","Debate"],
        "Inglés": ["Essay","Listening","Speaking","Quiz"],
        "Arte": ["Acuarela","Collage","Escultura","Dibujo"]
      };
      const lista = [];
      const count = 2 + (seed % 2);
      for (let i=0;i<count;i++){
        const titulo = `${temas[materia][(i + seed) % temas[materia].length]} ${periodo.split(' ')[1]}.${i+1}`;
        const nota = Math.max(2.5, Math.min(5, 3 + ((seed + i) % 20)/10));
        lista.push({ materia, titulo, nota: parseFloat(nota.toFixed(1)) });
      }
      return lista;
    }

    // Periodos con mismas materias, tareas distintas
    function crearPeriodosPara(est){
      const periodos = {};
      PERIODOS.forEach(p=>{
        let tareas = [];
        est.materias.forEach(m => { tareas = tareas.concat(generarTareas(m, p)); });
        periodos[p] = { materias: [...est.materias], tareas };
      });
      return periodos;
    }

    // Dataset
    const baseAlumnos = [
      { id: 1, nombre: "Ana María López", grado: "11°", materias: ["Matemáticas","Inglés"] },
      { id: 2, nombre: "Carlos Pérez", grado: "10°", materias: ["Ciencias","Historia","Arte"] },
      { id: 3, nombre: "Daniela Gómez", grado: "11°", materias: ["Matemáticas","Ciencias"] },
      { id: 4, nombre: "Esteban Ruiz", grado: "9°", materias: ["Historia","Lengua"] },
      { id: 5, nombre: "Fernanda Castro", grado: "8°", materias: ["Lengua","Inglés","Arte"] },
      { id: 6, nombre: "Gabriel Díaz", grado: "7°", materias: ["Ciencias","Matemáticas"] },
      { id: 7, nombre: "Helena Naranjo", grado: "6°", materias: ["Arte","Historia"] },
      { id: 8, nombre: "Iván Torres", grado: "10°", materias: ["Inglés","Ciencias"] },
    ];
    const alumnos = baseAlumnos.map(stu => ({ ...stu, periodos: crearPeriodosPara(stu) }));

    // Estado
    const state = {
      periodo: "",          // controlado por tabs
      grado: "",
      materias: new Set(),
      search: "",
      sort: { by: "", dir: "asc" },
      page: 1,
      perPage: 6,
      materiasTemp: new Set(),
    };

    // Elementos
    const gradoSelect = document.getElementById("gradoSelect");
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.getElementById("tableBody");
    const tableCount = document.getElementById("tableCount");
    const statEstudiantes = document.getElementById("statEstudiantes");
    const statPromedio = document.getElementById("statPromedio");
    const statMaterias = document.getElementById("statMaterias");
    const paginationInfo = document.getElementById("paginationInfo");
    const pageIndicators = document.getElementById("pageIndicators");
    const prevPage = document.getElementById("prevPage");
    const nextPage = document.getElementById("nextPage");
    const sortByName = document.getElementById("sortByName");
    const sortByAvg = document.getElementById("sortByAvg");
    const exportCSV = document.getElementById("exportCSV");
    const exportCSV_m = document.getElementById("exportCSV_m");
    const resetAll = document.getElementById("resetAll");
    const resetAll_m = document.getElementById("resetAll_m");
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");

    // Multiselect
    const materiasBtn = document.getElementById("materiasBtn");
    const materiasMenu = document.getElementById("materiasMenu");
    const materiasLabel = document.getElementById("materiasLabel");
    const materiasClear = document.getElementById("materiasClear");
    const materiasApply = document.getElementById("materiasApply");

    // Modal
    const modal = document.getElementById("detailsModal");
    const modalClose = document.getElementById("modalClose");
    const modalClose2 = document.getElementById("modalClose2");
    const modalTitle = document.getElementById("modalTitle");
    const modalSubtitle = document.getElementById("modalSubtitle");
    const modalBody = document.getElementById("modalBody");

    // Tabs periodo
    document.querySelectorAll(".period-tab").forEach(btn=>{
      btn.addEventListener("click", ()=>{
        document.querySelectorAll(".period-tab").forEach(b=> b.classList.remove("bg-[#1b1b20]"));
        btn.classList.add("bg-[#1b1b20]");
        state.periodo = btn.getAttribute("data-period-tab") || "";
        state.page = 1;
        render();
      });
    });

    // Helpers
    function getStudentMaterias(stu){ return stu.periodos[PERIODOS[0]].materias; }
    function computeStudentAverage(stu) {
      const materiasSet = state.materias;
      const periodosKeys = state.periodo ? [state.periodo] : PERIODOS;
      const notas = [];
      periodosKeys.forEach(p=>{
        const tareas = stu.periodos[p].tareas.filter(t => materiasSet.size ? materiasSet.has(t.materia) : true);
        tareas.forEach(t=> notas.push(t.nota));
      });
      return avg(notas);
    }

    // Filtros
    function applyFilters(){
      let rows = alumnos.filter(a =>
        (state.grado ? a.grado === state.grado : true) &&
        (state.search ? a.nombre.toLowerCase().includes(state.search.toLowerCase()) : true) &&
        (state.materias.size ? getStudentMaterias(a).some(m => state.materias.has(m)) : true)
      );

      if (state.sort.by === "name") {
        rows.sort((a,b)=>{
          const r = a.nombre.localeCompare(b.nombre, 'es', { sensitivity: 'base' });
          return state.sort.dir === "asc" ? r : -r;
        });
      } else if (state.sort.by === "avg") {
        rows.sort((a,b)=>{
          const aa = computeStudentAverage(a);
          const bb = computeStudentAverage(b);
          const r = aa - bb;
          return state.sort.dir === "asc" ? r : -r;
        });
      }
      return rows;
    }

    function render(){
      const rows = applyFilters();

      // Paginación
      const total = rows.length;
      const totalPages = Math.max(1, Math.ceil(total / state.perPage));
      if (state.page > totalPages) state.page = totalPages;
      const start = (state.page - 1) * state.perPage;
      const current = rows.slice(start, start + state.perPage);

      // KPIs
      statEstudiantes.textContent = total.toString();
      const globalAvg = avg(rows.map(s => computeStudentAverage(s)));
      statPromedio.textContent = format2(globalAvg);
      statMaterias.textContent = state.materias.size ? `${state.materias.size} sel.` : "Todas";

      // Tabla
      tableCount.textContent = total ? `${total} resultados` : "Sin resultados";
      renderTable(current);

      // Paginación UI
      paginationInfo.textContent = `Mostrando ${current.length} de ${total}`;
      renderPagination(state.page, totalPages);
    }

    function summarizeTasksForRow(stu){
      const periodKey = state.periodo || "Periodo 1";
      const tareas = stu.periodos[periodKey].tareas.filter(t => state.materias.size ? state.materias.has(t.materia) : true);
      const max = 3;
      const chips = tareas.slice(0, max).map(t=>{
        const color = t.nota >= 4.5 ? "text-emerald-300" : t.nota >= 4.0 ? "text-teal-200" : t.nota >= 3.0 ? "text-yellow-200" : "text-rose-300";
        return `<span class="px-2 py-1 text-xs rounded-md bg-[#1b1b21] border border-[var(--line)]">${t.titulo} <b class="${color}">${t.nota.toFixed(1)}</b></span>`;
      }).join("");
      if (tareas.length > max){
        const extra = tareas.length - max;
        return chips + `<span class="px-2 py-1 text-xs rounded-md bg-[#1b1b21] border border-[var(--line)] text-[var(--muted)]">+${extra} más</span>`;
      }
      return chips || `<span class="text-xs text-[var(--muted)]">Sin tareas</span>`;
    }

    function renderTable(current){
      tableBody.innerHTML = "";
      current.forEach(stu=>{
        const materias = getStudentMaterias(stu);
        const prom = computeStudentAverage(stu);
        const promColor = prom >= 4.5 ? "text-emerald-400" : prom >= 4.0 ? "text-teal-300" : prom >= 3.0 ? "text-yellow-300" : "text-rose-400";
        const materiasBadges = materias.map(m => `<span class="px-2 py-1 text-xs rounded-md bg-[#1b1b21] border border-[var(--line)]">${m}</span>`).join(" ");

        const tr = document.createElement("tr");
        tr.className = "data-row";
        tr.innerHTML = `
          <td class="px-5 py-4 align-top">
            <div class="font-semibold">${stu.nombre}</div>
            <div class="text-xs text-[var(--muted)] mt-0.5">ID ${stu.id}</div>
          </td>
          <td class="px-5 py-4 align-top"><span class="text-sm">${stu.grado}</span></td>
          <td class="px-5 py-4 align-top"><span class="text-sm">${state.periodo || "Todos"}</span></td>
          <td class="px-5 py-4 align-top">
            <div class="flex flex-wrap gap-1.5">${materiasBadges}</div>
          </td>
          <td class="px-5 py-4 align-top">
            <div class="flex flex-wrap gap-1.5">${summarizeTasksForRow(stu)}</div>
          </td>
          <td class="px-5 py-4 align-top">
            <div class="flex items-baseline gap-2">
              <span class="text-lg font-bold ${promColor}">${format2(prom)}</span>
              <span class="text-xs text-[var(--muted)]">/ 5.00</span>
            </div>
          </td>
          <td class="px-5 py-4 align-top">
            <button data-id="${stu.id}" class="smooth px-3 py-1.5 rounded-md bg-[#1b1b20] hover:bg-[#222229] border border-[var(--line)] text-sm">
              Ver detalle
            </button>
          </td>
        `;
        tr.querySelector("button").addEventListener("click", ()=> openDetails(stu));
        tableBody.appendChild(tr);
      });
    }

    function renderPagination(page, totalPages){
      pageIndicators.innerHTML = "";
      const maxDots = 5;
      let start = Math.max(1, page - 2);
      let end = Math.min(totalPages, start + maxDots - 1);
      start = Math.max(1, Math.min(start, end - maxDots + 1));
      for (let i=start;i<=end;i++){
        const b = document.createElement("button");
        b.className = "smooth w-8 h-8 rounded-md border border-[var(--line)] hover:bg-[#1b1b20] text-sm " + (i===page?"bg-[#1b1b20]":"");
        b.textContent = i;
        b.addEventListener("click", ()=>{ state.page = i; render(); });
        pageIndicators.appendChild(b);
      }
      prevPage.disabled = page <= 1;
      nextPage.disabled = page >= totalPages;
      prevPage.classList.toggle("opacity-50", prevPage.disabled);
      nextPage.classList.toggle("opacity-50", nextPage.disabled);
    }

    // Modal detalles
    function openDetails(stu){
      modalTitle.textContent = stu.nombre;
      modalSubtitle.textContent = `${stu.grado} · ${state.periodo || "Todos los periodos"}`;
      modalBody.innerHTML = "";

      const periodos = state.periodo ? [state.periodo] : PERIODOS;
      periodos.forEach(p=>{
        const tareas = stu.periodos[p].tareas.filter(t => state.materias.size ? state.materias.has(t.materia) : true);
        const byMateria = {};
        tareas.forEach(t=>{
          if (!byMateria[t.materia]) byMateria[t.materia] = [];
          byMateria[t.materia].push(t);
        });
        const section = document.createElement("div");
        const pAvg = avg(tareas.map(t=>t.nota));
        section.innerHTML = `
          <div class="flex items-center justify-between mb-2">
            <div class="font-semibold">${p}</div>
            <div class="text-sm text-[var(--muted)]">Promedio: <b class="text-[var(--text)]">${format2(pAvg)}</b></div>
          </div>
          <div class="space-y-3">
            ${Object.keys(byMateria).length ? Object.keys(byMateria).map(m=>{
              const mAvg = avg(byMateria[m].map(x=>x.nota));
              return `
                <div class="p-3 rounded-lg bg-[#1b1b21] border border-[var(--line)]">
                  <div class="flex items-center justify-between">
                    <div class="text-sm font-medium">${m}</div>
                    <div class="text-xs text-[var(--muted)]">Prom: <b class="text-[var(--text)]">${format2(mAvg)}</b></div>
                  </div>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                    ${byMateria[m].map(t=>`
                      <div class="p-2 rounded-md bg-[#19191f] border border-[var(--line)]">
                        <div class="text-sm">${t.titulo}</div>
                        <div class="text-xs text-[var(--muted)] mt-0.5">Nota: <b class="text-[var(--text)]">${t.nota.toFixed(1)}</b></div>
                      </div>
                    `).join("")}
                  </div>
                </div>
              `;
            }).join("") : `<div class="text-sm text-[var(--muted)]">Sin tareas en filtros actuales.</div>`}
          </div>
        `;
        modalBody.appendChild(section);
      });

      modal.classList.remove("hidden");
      modal.classList.add("flex");
    }
    function closeModal(){ modal.classList.add("hidden"); modal.classList.remove("flex"); }

    // Multiselect materias
    function buildMateriasMenu(){
      const container = materiasMenu.querySelector("div.max-h-56");
      container.innerHTML = "";
      MATERIAS.forEach(m=>{
        const id = "m_" + m.toLowerCase().replace(/\s+/g,'_');
        const row = document.createElement("label");
        row.className = "flex items-center justify-between gap-3 px-2 py-2 rounded-md hover:bg-[#1b1b20] cursor-pointer";
        row.innerHTML = `
          <span class="text-sm">${m}</span>
          <input id="${id}" type="checkbox" class="w-4 h-4 accent-[var(--text)]" ${state.materiasTemp.has(m) ? "checked": ""}/>
        `;
        row.querySelector("input").addEventListener("change", (e)=>{
          if (e.target.checked) state.materiasTemp.add(m);
          else state.materiasTemp.delete(m);
        });
        container.appendChild(row);
      });
    }
    function updateMateriasLabel(){
      materiasLabel.textContent = state.materias.size ? `${state.materias.size} seleccionadas` : "Todas";
    }

    // CSV
    function toCSV(rows){
      const header = ["Estudiante","Grado","Periodo","Materias","Tareas","Promedio"];
      const lines = [header.join(",")];
      rows.forEach(stu=>{
        const materias = getStudentMaterias(stu).join(" / ");
        if (state.periodo){
          const tareas = stu.periodos[state.periodo].tareas
            .filter(t => state.materias.size ? state.materias.has(t.materia) : true)
            .map(t=> `${t.titulo} (${t.materia}: ${t.nota.toFixed(1)})`).join(" | ");
          const prom = computeStudentAverage(stu);
          lines.push([`"${stu.nombre}"`, `"${stu.grado}"`, `"${state.periodo}"`, `"${materias}"`, `"${tareas}"`, `"${format2(prom)}"`].join(","));
        } else {
          const prom = computeStudentAverage(stu);
          const count = PERIODOS.reduce((acc,p)=>acc + stu.periodos[p].tareas.filter(t => state.materias.size ? state.materias.has(t.materia) : true).length, 0);
          lines.push([`"${stu.nombre}"`, `"${stu.grado}"`, `"Todos"`, `"${materias}"`, `"${count} tareas"`, `"${format2(prom)}"`].join(","));
        }
      });
      return lines.join("\n");
    }
    function download(filename, text){
      const blob = new Blob([text], {type: "text/csv;charset=utf-8;"});
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url; a.download = filename; document.body.appendChild(a); a.click();
      URL.revokeObjectURL(url); a.remove();
    }

    // Eventos
    gradoSelect.addEventListener("change", ()=>{ state.grado = gradoSelect.value; state.page = 1; render(); });
    searchInput.addEventListener("input", ()=>{ state.search = searchInput.value.trim(); state.page = 1; render(); });

    // Materias multiselect
    materiasBtn.addEventListener("click", ()=>{
      state.materiasTemp = new Set([...state.materias]);
      materiasMenu.classList.toggle("hidden");
      buildMateriasMenu();
    });
    materiasApply.addEventListener("click", ()=>{
      state.materias = new Set([...state.materiasTemp]);
      materiasMenu.classList.add("hidden");
      updateMateriasLabel();
      state.page = 1;
      render();
    });
    materiasClear.addEventListener("click", ()=>{ state.materiasTemp.clear(); buildMateriasMenu(); });
    document.addEventListener("click", (e)=>{
      if (!materiasMenu.classList.contains("hidden")) {
        const within = e.target.closest("#materiasMultiselect");
        if (!within) materiasMenu.classList.add("hidden");
      }
    });

    // Orden
    sortByName.addEventListener("click", ()=>{
      if (state.sort.by === "name") state.sort.dir = state.sort.dir === "asc" ? "desc" : "asc";
      else { state.sort.by = "name"; state.sort.dir = "asc"; }
      render();
    });
    sortByAvg.addEventListener("click", ()=>{
      if (state.sort.by === "avg") state.sort.dir = state.sort.dir === "asc" ? "desc" : "asc";
      else { state.sort.by = "avg"; state.sort.dir = "desc"; }
      render();
    });

    // Paginación
    prevPage.addEventListener("click", ()=>{ state.page = Math.max(1, state.page - 1); render(); });
    nextPage.addEventListener("click", ()=>{ state.page = state.page + 1; render(); });

    // Export
    ;[exportCSV, exportCSV_m].forEach(btn=>{
      btn.addEventListener("click", ()=>{
        const rows = applyFilters();
        const csv = toCSV(rows);
        download("reporte_notas.csv", csv);
      });
    });

    // Reset
    function resetAllFilters(){
      state.periodo = "";
      state.grado = "";
      state.materias.clear();
      state.search = "";
      state.sort = { by: "", dir: "asc" };
      state.page = 1;
      searchInput.value = "";
      gradoSelect.value = "";
      updateMateriasLabel();
      document.querySelectorAll(".period-tab").forEach(b=> b.classList.remove("bg-[#1b1b20]"));
      document.querySelector('.period-tab[data-period-tab=""]')?.classList.add("bg-[#1b1b20]");
      render();
    }
    ;[resetAll, resetAll_m].forEach(b=> b.addEventListener("click", resetAllFilters));

    // Mobile accent
    const topGrid = document.querySelector("section.grid");
    mobileMenuBtn.addEventListener("click", ()=>{
      topGrid.classList.toggle("ring-2");
      topGrid.classList.toggle("ring-white/10");
    });

    // Modal events
    modalClose.addEventListener("click", closeModal);
    modalClose2.addEventListener("click", closeModal);
    document.getElementById("detailsModal").addEventListener("click", (e)=>{ if (e.target === document.getElementById("detailsModal")) closeModal(); });

    // Init
    updateMateriasLabel();
    document.querySelector('.period-tab[data-period-tab=""]')?.classList.add("bg-[#1b1b20]");
    render();
    document.documentElement.style.setProperty('--top-offset','0px');
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9803a025a5a53ee6',t:'MTc1ODA1OTkxMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
