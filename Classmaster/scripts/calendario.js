window.addEventListener('DOMContentLoaded', function () {
    const closeButton = document.getElementById('close'),
    distance = document.getElementById('distance'),
    events = document.getElementById('events'),
    calendar = document.getElementById('calendar'),
    daysContainer = document.getElementById('days'),
    prevButton = document.getElementById('prev'),
    nextButton = document.getElementById('next'),
    eventAdder = document.getElementById('event-adder'),
    eventList = document.getElementById('event-list');
    let selectedDay = document.getElementById('day-number');
    const months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    let currentDate = new Date();
    let today = new Date();
    let selectedCursoId = null;

    // Renderizar el calendario
    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const lastDay = new Date(year, month + 1, 0).getDate();
        const monthYear = document.getElementById('month-year');
        monthYear.textContent = `${months[month]} ${year}`;
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

    // Removed unnecessary click handler for 'held' id


    // Fetch events from DB and mark days
    async function checkForEvents() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        let eventos = [];
        try {
            const res = await fetch(`../php/eventos.php?year=${year}&month=${month}`);
            const data = await res.json();
            if (data.success) eventos = data.eventos;
        } catch (e) { eventos = []; }
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
            // Find events for this day
            const dateStr = `${year}-${String(month).padStart(2,'0')}-${String(dayNumber).padStart(2,'0')}`;
            const dayEvents = eventos.filter(ev => ev.fecha === dateStr);
            if (dayEvents.length > 0) {
                dayDiv.classList.add('has-event');
                // Priority dots
                let hasUrgent = false, hasImportant = false, hasNormal = false;
                dayEvents.forEach(ev => {
                    if (ev.prioridad === 'urgente') hasUrgent = true;
                    if (ev.prioridad === 'importante') hasImportant = true;
                    if (ev.prioridad === 'normal') hasNormal = true;
                });
                dayDiv.style.position = 'relative';
                const dotContainer = document.createElement('div');
                dotContainer.className = 'event-dot-container';
                dotContainer.style.position = 'absolute';
                dotContainer.style.display = 'flex';
                dotContainer.style.alignContent = 'flex-end'
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
    closeButton.addEventListener('click', function () {
        events.style.translate = '150%';
        setTimeout(() => {
            events.style.boxShadow = 'none';
        }, 300);
        daysContainer.querySelectorAll('div').forEach(div => {
            div.classList.remove('selected');
        }
        );
    });

    function eventModal({ onConfirm = () => { } } = {}) {
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

        const createEvent = async () => {
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
            // AJAX create
            let action = 'create';
            console.log(window.rol)
            if (window.rol === 'profesor') action = 'create_profesor'; 
            const formData = new FormData();
            formData.append('action', action);
            formData.append('titulo', titulo);
            formData.append('descripcion', descripcion);
            formData.append('prioridad', prioridad);
            formData.append('fecha', fecha);
            if (window.rol === 'profesor') formData.append('curso_id', selectedCursoId);
            await fetch('../php/eventos.php', { method: 'POST', body: formData });
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
        try {
            const res = await fetch(`../php/eventos.php?year=${year}&month=${month}`);
            const data = await res.json();
            if (data.success) eventos = data.eventos.filter(ev => ev.fecha === fecha);
        } catch (e) { eventos = []; }
        eventos.forEach(ev => {
            let div = document.createElement('div');
            div.classList.add(ev.prioridad);
            let title = document.createElement('h3');
            title.innerHTML = ev.titulo;
            // Delete button
            let deleteButton = document.createElement('ion-icon');
            deleteButton.setAttribute('name', 'trash-outline');
            deleteButton.style.cursor = 'pointer';
            deleteButton.onclick = async function() {
                // AJAX delete
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', ev.id);
                await fetch('../php/eventos.php', { method: 'POST', body: formData });
                loadEvent();
                checkForEvents();
            };
            div.appendChild(deleteButton);
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

    // Calcular distancia desde hoy usando Date objects
    function checkDistanceFromToday(selectedDayStr) {
        const [day, monthName, year] = selectedDayStr.split(' / ');
        const month = months.indexOf(monthName);
        const selectedDate = new Date(parseInt(year), month, parseInt(day));
        const now = new Date();
        // Zero out time for both
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