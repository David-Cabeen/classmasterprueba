import { toast } from './components.js';

window.addEventListener('DOMContentLoaded', function () {
    // Módulo de calendario académico
    // Gestiona eventos personales y de curso, con funcionalidades específicas
    // para estudiantes, profesores y padres
    // Referencias a elementos del DOM
    const closeButton = document.getElementById('close'),
    // Elementos de la interfaz del calendario
    distance = document.getElementById('distance'),         // Muestra la distancia temporal desde hoy
    events = document.getElementById('events'),            // Panel lateral de eventos
    calendar = document.getElementById('calendar'),        // Contenedor principal del calendario
    daysContainer = document.getElementById('days'),       // Grid de días del mes
    prevButton = document.getElementById('prev'),          // Botón mes anterior
    nextButton = document.getElementById('next'),          // Botón mes siguiente
    eventAdder = document.getElementById('event-adder'),   // Botón para agregar eventos
    eventList = document.getElementById('event-list');     // Lista de eventos del día
    let selectedDay = document.getElementById('day-number');

    // Arreglo con nombres de meses en español
    const months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    // Variables de estado
    let currentDate = new Date();                  // Fecha actual mostrada en el calendario
    let today = new Date();                        // Fecha actual real
    let selectedCursoId = null;                    // ID del curso seleccionado (para profesores)
    let viewingEstudianteId = null;               // ID del estudiante siendo visualizado por un padre

    // Función principal para renderizar el calendario
    // Genera el grid de días incluyendo:
    // - Días del mes anterior para completar la primera semana
    // - Días del mes actual con marcador de "hoy"
    // - Días del siguiente mes para completar la última semana
    // - Ajuste automático del tamaño según el número de filas
    function renderCalendar(date) {
        // Cálculo de fechas importantes para el mes
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1).getDay();      // Primer día del mes (0-6)
        const lastDay = new Date(year, month + 1, 0).getDate();  // Último día del mes
        
        // Actualizar encabezado del calendario
        const monthYear = document.getElementById('month-year');
        monthYear.textContent = `${months[month]} ${year}`;
        
        // Limpiar el contenedor antes de renderizar
        daysContainer.innerHTML = '';

        // Días del mes anterior
        const prevMonthLastDay = new Date(year, month, 0).getDate();
        for (let i = firstDay; i > 0; i--) {
            const dayDiv = document.createElement('div');
            dayDiv.textContent = prevMonthLastDay - i + 1;
            dayDiv.classList.add('fade');
            daysContainer.appendChild(dayDiv);
        };

        // Días del mes actual
        for (let i = 1; i <= lastDay; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.textContent = i;
            if (i === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                dayDiv.classList.add('today');
            }
            daysContainer.appendChild(dayDiv);
        }

        // Días del siguiente mes
        const nextMonthStartDay = 7 - new Date(year, month + 1, 0).getDay() - 1;
        for (let i = 1; i <= nextMonthStartDay; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.textContent = i;
            dayDiv.classList.add('fade');
            daysContainer.appendChild(dayDiv);
        }

        // Ajustar el ancho de la grilla de días según el número de filas
        // Cada fila tiene 7 días, así que filas = Math.ceil(childElementCount / 7)
        const numRows = Math.ceil(daysContainer.childElementCount / 7);
        const root = document.documentElement;
        if (numRows > 5) {
            root.style.setProperty('--width', '90%');
        } else {
            root.style.setProperty('--width', '100%');
        }
    };

    // Botones para cambiar de mes
    prevButton.addEventListener('click', function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
        checkForEvents();
    });

    nextButton.addEventListener('click', function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
        checkForEvents();
    });

    renderCalendar(currentDate);
    checkForEvents();

    // Consulta y marca eventos en el calendario
    // Tareas principales:
    // - Obtener eventos personales y de curso desde el servidor
    // - Marcar días con indicadores de color según prioridad
    // - Manejar vista especial para padres que visualizan calendarios de estudiantes
    // - Actualizar los marcadores visuales en la UI
    async function checkForEvents() {
        // Obtener mes y año actual para la consulta
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        let eventos = [];           // Eventos personales
        let eventosCurso = [];      // Eventos de curso (para profesores/estudiantes)
        
        try {
            // Preparar parámetros de consulta
            const params = new URLSearchParams({ year, month });
            if (viewingEstudianteId) params.set('estudiante_id', viewingEstudianteId);
            const res = await fetch(`../php/eventos.php?${params.toString()}`);
            const data = await res.json();
            if (data.success) {
                eventos = data.eventos;
                eventosCurso = data.eventos_curso;
            }
    } catch (e) { eventos = []; eventosCurso = []; }
    // Depuración: resumen de la consulta (quitar en producción si no es necesario)
    try { console.debug('checkForEvents', { year: currentDate.getFullYear(), month: currentDate.getMonth()+1, viewingEstudianteId, eventosCount: eventos.length, eventosCursoCount: eventosCurso.length }); } catch(e){}
        for (let i = 0; i < daysContainer.childElementCount; i++) {
            const dayDiv = daysContainer.children[i];
            const dayNumber = parseInt(dayDiv.textContent.trim());
            // Remove previous event markers
            const oldDotContainer = dayDiv.querySelector('.event-dot-container');
            if (oldDotContainer) dayDiv.removeChild(oldDotContainer);
            if (dayDiv.classList.contains('fade')) {
                dayDiv.classList.remove('has-event');
                continue;
            }
            // Buscar eventos para este día (personales y de curso)
            const dateStr = `${year}-${String(month).padStart(2,'0')}-${String(dayNumber).padStart(2,'0')}`;
            const dayEvents = eventos.filter(ev => ev.fecha === dateStr);
            const dayCursoEvents = eventosCurso.filter(ev => ev.fecha === dateStr);
            if (dayEvents.length > 0 || dayCursoEvents.length > 0) {
                dayDiv.classList.add('has-event');
                // Priority dots (combine both)
                let hasUrgent = false, hasImportant = false, hasNormal = false;
                [...dayEvents, ...dayCursoEvents].forEach(ev => {
                    if (ev.prioridad === 'urgente') hasUrgent = true;
                    if (ev.prioridad === 'importante') hasImportant = true;
                    if (ev.prioridad === 'normal') hasNormal = true;
                });
                dayDiv.style.position = 'relative';
                const dotContainer = document.createElement('div');
                dotContainer.className = 'event-dot-container';
                dotContainer.style.position = 'absolute';
                dotContainer.style.display = 'flex';
                dotContainer.style.alignContent = 'flex-end';
                dotContainer.style.gap = '3px';
                // Default: bottom left
                dotContainer.style.left = '5%';
                dotContainer.style.bottom = '5%';
                dotContainer.style.right = '';
                // If today, move to bottom right
                if (dayDiv.classList.contains('today')) {
                    dotContainer.style.left = '';
                    dotContainer.style.right = '5%';
                    dotContainer.style.justifyContent = 'flex-end';
                }
                if (hasUrgent) { let dot = document.createElement('div'); dot.style.width = '10px'; dot.style.height = '10px'; dot.style.borderRadius = '50%'; dot.style.backgroundColor = 'var(--urgent-color)'; dotContainer.appendChild(dot); }
                if (hasImportant) { let dot = document.createElement('div'); dot.style.width = '10px'; dot.style.height = '10px'; dot.style.borderRadius = '50%'; dot.style.backgroundColor = 'var(--important-color)'; dotContainer.appendChild(dot); }
                if (hasNormal) { let dot = document.createElement('div'); dot.style.width = '10px'; dot.style.height = '10px'; dot.style.borderRadius = '50%'; dot.style.backgroundColor = 'var(--normal-color)'; dotContainer.appendChild(dot); }
                dayDiv.appendChild(dotContainer);
            } else {
                dayDiv.classList.remove('has-event');
            }
        }
    }

    // Cerrar ventana de eventos
    // Helper reutilizable: cierra el panel de eventos con la animación "fade-right"
    function closeEventsPanel() {
        events.style.translate = '150%';
        // Mantener la sombra mientras anima, eliminarla al terminar
        setTimeout(() => {
            events.style.boxShadow = 'none';
        }, 300);
        // Quitar la clase 'selected' de cualquier día seleccionado
        daysContainer.querySelectorAll('div').forEach(div => {
            div.classList.remove('selected');
        });
    }

    // Vincular el botón de cierre a la función reutilizable
    closeButton.addEventListener('click', function () {
        closeEventsPanel();
    });

    // Modal para crear/editar eventos
    // Genera un modal interactivo con:
    // - Campos para título y descripción del evento
    // - Selector de prioridad con animaciones
    // - Búsqueda de cursos (solo para profesores)
    // - Manejo de cerrado con ESC y click fuera
    function eventModal({ onConfirm = () => { } } = {}) {
        // Crear overlay semi-transparente con efecto de blur
        const overlay = document.createElement("div");
        overlay.className = "fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-4 z-50";
        let searchbarHtml = '';
        if (window.rol === 'profesor') {
            searchbarHtml = `
                <div class="mb-4">
                    <label for="curso-search" class="block mb-2 font-semibold text-white">Buscar curso:</label>
                    <input type="text" id="curso-search" placeholder="Nombre del curso" class="w-full p-2 rounded border bg-white/10 text-white placeholder-white/60" autocomplete="off" />
                    <ul id="curso-results" class="bg-white/10 rounded mt-2 max-h-32 overflow-y-auto"></ul>
                </div>
            `;
        }
        overlay.innerHTML = `
            <div class="event-window w-full sm:max-w-md rounded-2xl border border-white/10 p-6 animate-in" role="dialog" aria-modal="true">
                <h1 class="text-xl font-semibold text-white mb-4">Agregar evento</h1>
                <form id="event-form" class="flex flex-col gap-4">
                    ${searchbarHtml}
                    <input type="text" id="event-input" placeholder="Título" required class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    <textarea name="event-description" placeholder="Descripción" id="event-description" class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-base placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition resize-none min-h-[60px]"></textarea>
                    <fieldset class="flex gap-2 justify-between mt-2">
                        <input type="radio" name="priority" id="normal" value="normal">
                        <label for="normal" class="flex-1 flex items-center justify-center gap-2 bg-green-500/80 hover:bg-green-400/90 rounded-lg py-2 cursor-pointer font-medium transition">Normal <ion-icon name="ellipse-outline"/></label>
                        <input type="radio" name="priority" id="important" value="importante">
                        <label for="important" class="flex-1 flex items-center justify-center gap-2 bg-yellow-400/80 hover:bg-yellow-300/90 rounded-lg py-2 cursor-pointer font-medium transition">Importante <ion-icon name="ellipse-outline"/></label>
                        <input type="radio" name="priority" id="urgent" value="urgente">
                        <label for="urgent" class="flex-1 flex items-center justify-center gap-2 bg-red-500/80 hover:bg-red-400/90 rounded-lg py-2 cursor-pointer font-medium transition">Urgente <ion-icon name="ellipse-outline"/></label>
                    </fieldset>
                    <div class="flex gap-3 justify-end mt-4">
                        <button id="cm-cancel" type="button" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition focus:outline-none">Cancelar</button>
                        <button id="cm-ok" type="submit" class="px-4 py-2 rounded-lg bg-blue-500 font-semibold hover:bg-blue-600 transition focus:outline-none">Agregar</button>
                    </div>
                </form>
            </div>
            `;
        if (window.rol === 'profesor'){
            overlay.innerHTML = `
            <div class="event-window w-full sm:max-w-md rounded-2xl border border-white/10 p-6 animate-in" role="dialog" aria-modal="true">
                <h1 class="text-xl font-semibold text-white mb-4">Agregar evento</h1>
                <form id="event-form" class="flex flex-col gap-4">
                    <input type="text" id="event-input" placeholder="Título" required class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    <textarea name="event-description" placeholder="Descripción" id="event-description" class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-base placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition resize-none min-h-[60px]"></textarea>
                    <input type="search" id="curso-search" placeholder="Buscar curso" class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" autocomplete="off"/>
                    <ul id="curso-results" class="bg-white/10 border border-white/10 rounded-lg mt-2 max-h-32 overflow-y-auto"></ul>
                    <fieldset class="flex gap-2 justify-between mt-2">
                        <input type="radio" name="priority" id="normal" value="normal">
                        <label for="normal" class="flex-1 flex items-center justify-center gap-2 bg-green-500/80 hover:bg-green-400/90 rounded-lg py-2 cursor-pointer font-medium transition">Normal <ion-icon name="ellipse-outline"/></label>
                        <input type="radio" name="priority" id="important" value="importante">
                        <label for="important" class="flex-1 flex items-center justify-center gap-2 bg-yellow-400/80 hover:bg-yellow-300/90 rounded-lg py-2 cursor-pointer font-medium transition">Importante <ion-icon name="ellipse-outline"/></label>
                        <input type="radio" name="priority" id="urgent" value="urgente">
                        <label for="urgent" class="flex-1 flex items-center justify-center gap-2 bg-red-500/80 hover:bg-red-400/90 rounded-lg py-2 cursor-pointer font-medium transition">Urgente <ion-icon name="ellipse-outline"/></label>
                    </fieldset>
                    <div class="flex gap-3 justify-end mt-4">
                        <button id="cm-cancel" type="button" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition focus:outline-none">Cancelar</button>
                        <button id="cm-ok" type="submit" class="px-4 py-2 rounded-lg bg-blue-500 font-semibold hover:bg-blue-600 transition focus:outline-none">Agregar</button>
                    </div>
                </form>
            </div>
            `;
            const searchInput = overlay.querySelector('#curso-search');
            const resultsList = overlay.querySelector('#curso-results');
            searchInput.addEventListener('input', async function() {
                const term = searchInput.value.trim();
                resultsList.innerHTML = '';
                if (term.length < 2) return;
                const res = await fetch(`../php/search_cursos.php?term=${encodeURIComponent(term)}`);
                const data = await res.json();
                if (data.success && data.cursos.length > 0) {
                    data.cursos.forEach(curso => {
                        const li = document.createElement('li');
                        li.textContent = curso.nombre;
                        li.className = 'cursor-pointer p-2 hover:bg-blue-500/30';
                        li.onclick = () => {
                            selectedCursoId = curso.id;
                            searchInput.value = curso.nombre;
                            resultsList.innerHTML = '';
                        };
                        resultsList.appendChild(li);
                    });
                }
            });
        }
    document.body.appendChild(overlay);
        const priorityLabel = overlay.querySelectorAll('label'),
        priorityRadio = overlay.querySelectorAll('input[type="radio"]'),
        ok = overlay.querySelector("#cm-ok"),
        cancel = overlay.querySelector("#cm-cancel");

        const close = () => overlay.remove();
        ok.addEventListener("click", () => { onConfirm(); close(); });
        cancel.addEventListener("click", close);
        overlay.addEventListener("click", (e) => {
            if (e.target === overlay) close();
        });
        document.addEventListener("keydown", function esc(e) {
            if (e.key === "Escape") {
                close();
                document.removeEventListener("keydown", esc);
            }
        });

        // Animación de selectores de prioridad
        priorityLabel.forEach((label) => {
            let xAngle = getComputedStyle(
            document.querySelector(":root")
            ).getPropertyValue("--rotateX");
            let yAngle = getComputedStyle(
            document.querySelector(":root")
            ).getPropertyValue("--rotateY");
            label.addEventListener("click", function (e) {
            const rect = label.getBoundingClientRect();
            const xDecimal = (e.clientX - rect.left) / label.offsetWidth;
            const yDecimal = (e.clientY - rect.top) / label.offsetHeight;
            const xMapped = xDecimal * 20 - 10;
            const yMapped = yDecimal * 4 - 2;
            xAngle = Math.max(-10, Math.min(10, xMapped));
            yAngle = Math.max(-4, Math.min(4, yMapped));
            label.style.setProperty("--rotateY", xAngle + "deg");
            label.style.setProperty("--rotateX", yAngle * -1 + "deg");
            });
        });

            // Cambiar icono de prioridad
        priorityRadio.forEach((radio) => {
            radio.addEventListener('change', function () {
                const icon = radio.nextElementSibling.querySelector('ion-icon');
                if (radio.checked) {
                    icon.setAttribute('name', 'checkmark-circle');
                    for(let i = 0; i < priorityRadio.length; i++) {
                        if (priorityRadio[i] !== radio) {
                            priorityRadio[i].nextElementSibling.querySelector('ion-icon').setAttribute('name', 'ellipse-outline');
                        }
                    }
                }
            });
        });
};

        // Función para crear un nuevo evento
        // Maneja:
        // - Recopilación de datos del formulario
        // - Validación de permisos
        // - Comunicación con el servidor
        // - Actualización de la interfaz post-creación
        const createEvent = async () => {
            // Obtener referencias a los campos del formulario
            const eventInput = document.getElementById('event-input');
            let priorityInput = document.getElementsByName('priority');
            const eventDescription = document.getElementById('event-description');
            let prioridad = 'normal';
            for (let i = 0; i < priorityInput.length; i++) {
                if (priorityInput[i].checked) {
                    priorityInput[i].nextElementSibling.querySelector('ion-icon').setAttribute('name', 'ellipse-outline');
                    priorityInput[i].checked = false;
                    prioridad = priorityInput[i].value;
                }
            }
            const [day, monthName, year] = selectedDay.textContent.split(' / ');
            const month = months.indexOf(monthName) + 1;
            const fecha = `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            const titulo = eventInput.value.trim();
            const descripcion = eventDescription.value.trim();
            if (!titulo) return;
            // Crear vía AJAX (envío de formulario al backend)
            let action = 'create';
            if (window.rol === 'profesor') action = 'create_profesor'; 
            const formData = new FormData();
            formData.append('action', action);
            formData.append('titulo', titulo);
            formData.append('descripcion', descripcion);
            formData.append('prioridad', prioridad);
            formData.append('fecha', fecha);
            if (window.rol === 'profesor') formData.append('curso_id', selectedCursoId);
            // If a parent is viewing a child calendar, prevent creating events
            if (window.rol === 'acudiente' && viewingEstudianteId) {
                toast('No tienes permisos para crear eventos en el calendario del estudiante', 'error');
                return;
            }
            try {
                const res = await fetch('../php/eventos.php', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.success) {
                    toast('Evento creado correctamente', 'success');
                } else {
                    toast(data.error || 'Error al crear el evento', 'error');
                }
            } catch (e) {
                toast('Error de red al crear el evento', 'error');
            }
            eventInput.value = '';
            eventDescription.value = '';
            loadEvent();
            checkForEvents();
        };


    // Abrir ventana de eventos
    daysContainer.addEventListener('click', function (e) {
        if (e.target && e.target.parentElement === daysContainer) {
            if (e.target.classList.contains('fade')) {
                return;
            }
            events.style.translate = '0';
            events.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.3)';
            selectedDay.textContent = e.target.textContent + ' / ' + months[currentDate.getMonth()] + ' / ' + currentDate.getFullYear();
            distance.textContent = checkDistanceFromToday(selectedDay.textContent);
            daysContainer.querySelectorAll('div').forEach(div => {
                div.classList.remove('selected');
            }
            );
            e.target.classList.add('selected');
            loadEvent(); // Cargar eventos para el día seleccionado
        }
    });

    // Cargar eventos desde DB
    async function loadEvent() {
        eventList.innerHTML = '';
        const [day, monthName, year] = selectedDay.textContent.split(' / ');
        const month = months.indexOf(monthName) + 1;
        const fecha = `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        let eventos = [];
        let eventosCurso = [];
        try {
            const params = new URLSearchParams({ year, month });
            if (viewingEstudianteId) params.set('estudiante_id', viewingEstudianteId);
            const res = await fetch(`../php/eventos.php?${params.toString()}`);
            const data = await res.json();
            console.debug('loadEvent fetched', { fecha, viewingEstudianteId, data });
            if (data.success) {
                eventos = data.eventos.filter(ev => ev.fecha === fecha);
                eventosCurso = data.eventos_curso.filter(ev => ev.fecha === fecha);
            }
        } catch (e) { eventos = []; eventosCurso = []; }

        if (eventos.length === 0 && eventosCurso.length === 0) {
            const noMsg = document.createElement('div');
            noMsg.className = 'text-white/60 px-4 py-6';
            noMsg.textContent = 'No hay eventos para este día.';
            eventList.appendChild(noMsg);
            return;
        }
    // Renderizar eventos personales
        eventos.forEach(ev => {
            let div = document.createElement('div');
            div.classList.add(ev.prioridad);
            let title = document.createElement('h3');
            title.innerHTML = ev.titulo;
            // Botón de eliminar
            let deleteButton = document.createElement('ion-icon');
            deleteButton.setAttribute('name', 'trash-outline');
            deleteButton.style.cursor = 'pointer';
            // Los padres que ven el calendario de su hijo no pueden eliminar eventos
            if (!(window.rol === 'acudiente' && viewingEstudianteId)) {
                deleteButton.onclick = async function() {
                // Eliminación vía AJAX (petición al backend)
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', ev.id);
                try {
                    await fetch('../php/eventos.php', { method: 'POST', body: formData });
                } catch (e) {}
                loadEvent();
                checkForEvents();
                };
            } else {
                // ocultar visualmente el botón de eliminar cuando es vista de padre
                deleteButton.style.display = 'none';
            }
            div.appendChild(deleteButton);
            div.appendChild(title);
            if (ev.descripcion) {
                let description = document.createElement('p');
                description.innerHTML = ev.descripcion;
                div.appendChild(description);
            }
            eventList.appendChild(div);
        });

    // Renderizar eventos de curso
        eventosCurso.forEach(ev => {
            let div = document.createElement('div');
            div.classList.add(ev.prioridad);
            div.classList.add('curso-event');
            let title = document.createElement('h3');
            let courseName = ev.curso_nombre ? ev.curso_nombre : 'Curso';
            title.innerHTML = `${ev.titulo} <span style="font-size:0.8em;color:#6cf;">[${courseName}]</span>`;
            // Si el usuario actual es el profesor que creó este evento de curso, mostrar icono de eliminar
            if (window.rol === 'profesor') {
                const delIcon = document.createElement('ion-icon');
                delIcon.setAttribute('name', 'trash-outline');
                delIcon.style.cursor = 'pointer';
                delIcon.style.marginRight = '8px';
                delIcon.onclick = async function() {
                    const formData = new FormData();
                    formData.append('action', 'delete_profesor');
                    formData.append('id', ev.id);
                    try { await fetch('../php/eventos.php', { method: 'POST', body: formData }); } catch (e) {}
                    loadEvent(); checkForEvents();
                };
                div.appendChild(delIcon);
            }
            div.appendChild(title);
            if (ev.descripcion) {
                let description = document.createElement('p');
                description.innerHTML = ev.descripcion;
                div.appendChild(description);
            }
            eventList.appendChild(div);
        });
    };

    // Abrir ventana de añadir evento
    eventAdder.addEventListener('click', () => eventModal({ onConfirm: createEvent }));

    // Funcionalidad específica para padres/acudientes
    // Permite a los padres:
    // - Ver y cambiar entre calendarios de sus hijos
    // - Visualizar eventos pero sin poder modificarlos
    // - Mantener la sincronización de la interfaz al cambiar de estudiante
    // Función para cargar y gestionar la lista de estudiantes para padres
    // Realiza:
    // - Carga de estudiantes asociados al padre
    // - Configuración del selector de estudiantes
    // - Manejo de cambios entre calendarios de estudiantes
    // - Sincronización de la UI al cambiar de estudiante
    async function loadChildrenForParent() {
        // Inicializar selector de estudiantes
        const select = document.getElementById('student-select');
        if (!select) return;  // No continuar si no es vista de padre
        
        try {
            // Obtener lista de estudiantes vinculados al padre
            const res = await fetch('../php/padre_estudiante.php');
            const data = await res.json();
            // La API devuelve un arreglo de estudiantes
            if (Array.isArray(data)) {
                data.forEach((st, idx) => {
                    const opt = document.createElement('option');
                    opt.value = st.id;
                    opt.textContent = st.nombre + ' ' + st.apellido;
                    select.appendChild(opt);
                    // Auto-select first child
                    if (idx === 0) {
                        select.value = st.id;
                        viewingEstudianteId = st.id;
                        const cur = document.getElementById('student-current');
                        if (cur) cur.textContent = st.nombre + ' ' + st.apellido;
                    }
                });
            }
        } catch (e) {}
        // Manejar cambios en la selección de estudiante
        select.addEventListener('change', function() {
            // Actualizar ID del estudiante seleccionado
            viewingEstudianteId = select.value || null;
            
            // Gestionar visibilidad del botón de agregar eventos
            // Los padres no pueden agregar eventos en calendarios de estudiantes
            const adder = document.getElementById('event-adder');
            if (viewingEstudianteId) {
                adder.style.display = 'none';  // Ocultar botón al ver calendario de estudiante
            } else {
                adder.style.display = '';      // Mostrar botón en calendario propio
            }
            const cur = document.getElementById('student-current');
            if (cur) {
                const opt = select.querySelector(`option[value="${select.value}"]`);
                cur.textContent = opt ? opt.textContent : '';
            }
            // Refresh events
            renderCalendar(currentDate);
            checkForEvents();
            // If events panel open, close it with animation (like clicking the close button),
            // then reload events if the panel was open and should remain open for the newly selected student.
            const panelOpen = (events.style.translate === '0px' || events.style.translate === '0');
            if (panelOpen) {
                // Close with same animation
                closeEventsPanel();
                // After animation completes, reload events for the potentially different student
                setTimeout(() => {
                    // If a day is selected, refresh its events for the new student view
                    const selected = daysContainer.querySelector('div.selected');
                    if (selected) {
                        // ensure selectedDay text matches the selected element
                        selectedDay.textContent = selected.textContent + ' / ' + months[currentDate.getMonth()] + ' / ' + currentDate.getFullYear();
                        loadEvent();
                    }
                }, 320);
            }
        });

        if (viewingEstudianteId) {
            const adder = document.getElementById('event-adder');
            if (adder) adder.style.display = 'none';
            renderCalendar(currentDate);
            checkForEvents();
        }
    }

    // Initialize children selector if present
    if (window.rol === 'acudiente') {
        loadChildrenForParent();
    }

    // Utilidad para calcular la distancia temporal desde hoy
    function checkDistanceFromToday(selectedDayStr) {
        // Extraer componentes de la fecha seleccionada
        const [day, monthName, year] = selectedDayStr.split(' / ');
        const month = months.indexOf(monthName);
        
        // Crear objetos Date para comparación
        const selectedDate = new Date(parseInt(year), month, parseInt(day));
        const now = new Date();
        selectedDate.setHours(0,0,0,0);
        now.setHours(0,0,0,0);
        const diff = Math.round((selectedDate - now) / (1000*60*60*24));
        if (diff === 0) return 'Hoy';
        if (diff === 1) return 'Mañana';
        if (diff > 1) return 'Dentro de ' + diff + ' días';
        if (diff === -1) return 'Ayer';
        return 'Hace ' + (diff * -1) + ' días';
    }
});