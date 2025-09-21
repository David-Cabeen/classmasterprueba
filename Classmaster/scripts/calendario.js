window.addEventListener('DOMContentLoaded', function () {
    const closeButton = document.getElementById('close'),
    distance = document.getElementById('distance'),
    events = document.getElementById('events'),
    calendar = document.getElementById('calendar'),
    daysContainer = document.getElementById('days'),
    prevButton = document.getElementById('prev'),
    nextButton = document.getElementById('next'),
    eventForm = document.getElementById('event-form'),
    eventAdder = document.getElementById('event-adder'),
    eventWindow = document.getElementById('event-window'),
    eventList = document.getElementById('event-list'),
    closeWindowButton = document.getElementById('close-event'),
    priorityLabel = eventWindow.querySelectorAll('label'),
    priorityRadio = eventWindow.querySelectorAll('input[type="radio"]');
    let selectedDay = document.getElementById('day-number'),
    eventWindowIsOpen = false;
    const months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    let currentDate = new Date();
    let today = new Date();

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
            } if (localStorage.getItem('events')) {
                const events = JSON.parse(localStorage.getItem('events')) || {};
                if (events[`${i} / ${months[month]} / ${year}`]) {
                    dayDiv.classList.add('has-event');
                } ;
            };
            daysContainer.appendChild(dayDiv); // Lo embaraza
        };

        // Días del siguiente mes
        const nextMonthStartDay = 7 - new Date(year, month + 1, 0).getDay() - 1;
        for (let i = 1; i <= nextMonthStartDay; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.textContent = i;
            dayDiv.classList.add('fade');
            daysContainer.appendChild(dayDiv);
        };
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

    daysContainer.addEventListener('click', function (e) {
        for (let i = 0; i < daysContainer.childElementCount; i++) {  
            if (e.target.parentElement !== daysContainer) {
                if (e.target.parentElement.textContent === daysContainer.children[i].textContent) {
                    const marker = e.target
                    if (marker) {
                        marker.addEventListener('mousedown', function () {
                            marker.setAttribute('id','held');
                        });
                        marker.addEventListener('mouseup', function () {
                            marker.setAttribute('id','');
                        });
                    };
                };           
            }  
        };
    });


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
                const dotContainer = document.createElement('div');
                dotContainer.className = 'event-dot-container';
                dotContainer.style.position = 'absolute';
                dotContainer.style.left = '50%';
                dotContainer.style.bottom = '4px';
                dotContainer.style.transform = 'translateX(-50%)';
                dotContainer.style.display = 'flex';
                dotContainer.style.gap = '2px';
                if (hasUrgent) { let dot = document.createElement('div'); dot.style.width = '10px'; dot.style.height = '10px'; dot.style.borderRadius = '50%'; dot.style.backgroundColor = 'hsl(11, 80%, 65%)'; dotContainer.appendChild(dot); }
                if (hasImportant) { let dot = document.createElement('div'); dot.style.width = '10px'; dot.style.height = '10px'; dot.style.borderRadius = '50%'; dot.style.backgroundColor = 'hsl(175, 40%, 45%)'; dotContainer.appendChild(dot); }
                if (hasNormal) { let dot = document.createElement('div'); dot.style.width = '10px'; dot.style.height = '10px'; dot.style.borderRadius = '50%'; dot.style.backgroundColor = 'hsl(255, 75%, 70%)'; dotContainer.appendChild(dot); }
                dayDiv.appendChild(dotContainer);
            } else {
                dayDiv.classList.remove('has-event');
            }
        }
    }



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

    // Cerrar ventana de eventos
    closeButton.addEventListener('click', function () {
        events.style.translate = '100%';
        setTimeout(() => {
            events.style.boxShadow = 'none';
        }, 300);
        daysContainer.querySelectorAll('div').forEach(div => {
            div.classList.remove('selected');
            eventWindowIsOpen = false;
        }
        );
    });

    // Abrir ventana de eventos
    daysContainer.addEventListener('click', function (e) {
        if (e.target && e.target.parentElement === daysContainer) {
            if (e.target.classList.contains('fade')) {
                return;
            }
            events.style.translate = '0';
            events.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.3)';
            selectedDay.textContent = e.target.textContent + ' / ' + months[currentDate.getMonth()] + ' / ' + currentDate.getFullYear();
            distance.textContent = checkDistanceFromToday(currentDate.getDate(), selectedDay.textContent);
            daysContainer.querySelectorAll('div').forEach(div => {
                div.classList.remove('selected');
            }
            );
            e.target.classList.add('selected');
            loadEvent(); // Cargar eventos para el día seleccionado
            eventWindowIsOpen = true;
        }
    });


    // Crear evento en DB
    eventForm.addEventListener('submit', async function (e) {
        e.preventDefault();
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
        const formData = new FormData();
        formData.append('action', 'create');
        formData.append('titulo', titulo);
        formData.append('descripcion', descripcion);
        formData.append('prioridad', prioridad);
        formData.append('fecha', fecha);
        await fetch('../php/eventos.php', { method: 'POST', body: formData });
        eventInput.value = '';
        eventDescription.value = '';
        loadEvent();
        checkForEvents();
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
    }

    // Abrir ventana de añadir evento
    eventAdder.addEventListener('click', function () {
        eventWindow.style.display = "block";
        overlay.style.display = "block";
        setTimeout(() => {
            overlay.style.opacity = 1;
            eventWindow.style.opacity = 1;
            eventWindow.style.top = '50%';
        }, 1);
    });

    // Cerrar ventana de añadir evento
    closeWindowButton.addEventListener('click', function () {
        const priorityInput = document.getElementsByName('priority');
        const eventInput = document.getElementById('event-input');
        const eventDescription = document.getElementById('event-description');
        eventWindow.style.opacity = 0;
        overlay.style.opacity = 0;
        eventWindow.style.top = '55%';
        setTimeout(() => {
            eventWindow.style.display = "none";
            overlay.style.display = "none";
            eventInput.value = '';
            eventDescription.value = '';
            for (let i = 0; i < priorityInput.length; i++) {
                priorityInput[i].nextElementSibling.querySelector('ion-icon').setAttribute('name', 'ellipse-outline');
                priorityInput[i].checked = false;
            }
        }, 300);
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

    // Calcular distancia desde hoy usando Date objects
    function checkDistanceFromToday(todayDay, selectedDayStr) {
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