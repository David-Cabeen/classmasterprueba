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

    // Agregar indicador de evento en calendario
    function checkForEvents() {
        const events = JSON.parse(localStorage.getItem('events')) || {};
        const currentMonth = currentDate.getMonth();
        const currentYear = currentDate.getFullYear();

        for (let i = 0; i < daysContainer.childElementCount; i++) {
            const dayDiv = daysContainer.children[i];
            const dayNumber = parseInt(dayDiv.textContent.trim());

            // Remove previous event markers
            const oldDotContainer = dayDiv.querySelector('.event-dot-container');
            if (oldDotContainer) {
                dayDiv.removeChild(oldDotContainer);
            }

            if (dayDiv.classList.contains('fade')) {
                dayDiv.classList.remove('has-event');
                continue;
            }

            const dateKey = `${dayNumber} / ${months[currentMonth]} / ${currentYear}`;
            if (events[dateKey]) {
                dayDiv.classList.add('has-event');
                // Parse the event HTML to get priorities
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = events[dateKey];
                let hasUrgent = false, hasImportant = false, hasNormal = false;
                Array.from(tempDiv.children).forEach(evDiv => {
                    if (evDiv.classList.contains('urgent')) hasUrgent = true;
                    if (evDiv.classList.contains('important')) hasImportant = true;
                    if (evDiv.classList.contains('normal')) hasNormal = true;
                });
                // Create dot container
                const dotContainer = document.createElement('div');
                dotContainer.className = 'event-dot-container';
                dotContainer.style.position = 'absolute';
                dotContainer.style.left = '50%';
                dotContainer.style.bottom = '4px';
                dotContainer.style.transform = 'translateX(-50%)';
                dotContainer.style.display = 'flex';
                dotContainer.style.gap = '2px';
                // Add dots for each priority
                if (hasUrgent) {
                    const dot = document.createElement('div');
                    dot.style.width = '10px';
                    dot.style.height = '10px';
                    dot.style.borderRadius = '50%';
                    dot.style.backgroundColor = 'hsl(11, 80%, 65%)';
                    dotContainer.appendChild(dot);
                }
                if (hasImportant) {
                    const dot = document.createElement('div');
                    dot.style.width = '10px';
                    dot.style.height = '10px';
                    dot.style.borderRadius = '50%';
                    dot.style.backgroundColor = 'hsl(175, 40%, 45%)';
                    dotContainer.appendChild(dot);
                }
                if (hasNormal) {
                    const dot = document.createElement('div');
                    dot.style.width = '10px';
                    dot.style.height = '10px';
                    dot.style.borderRadius = '50%';
                    dot.style.backgroundColor = 'hsl(255, 75%, 70%)';
                    dotContainer.appendChild(dot);
                }
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

    // Crear evento
    eventForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const eventInput = document.getElementById('event-input');
        let priorityInput = document.getElementsByName('priority');
        const eventDescription = document.getElementById('event-description');
        for (let i = 0; i < priorityInput.length; i++) {
            if (priorityInput[i].checked) {
                priorityInput[i].nextElementSibling.querySelector('ion-icon').setAttribute('name', 'ellipse-outline');
                priorityInput[i].checked = false;
                priorityInput = priorityInput[i].value;
            }
        }
        let div = document.createElement('div');
        let title = document.createElement('h3');
        title.innerHTML = eventInput.value;
        div.classList.add(priorityInput)

        // Crear botón de eliminar
        let deleteButton = document.createElement('ion-icon');
        deleteButton.setAttribute('name', 'trash-outline');
        deleteButton.addEventListener('click', (e) => {
            const liElement = e.target.parentElement;
            liElement.style.transition = 'translate 0.3s ease';
            liElement.style.translate = '-100%';
            setTimeout(() => {
                liElement.remove();
                saveEvent();
            }, 300);
        });
        div.appendChild(deleteButton);
        div.appendChild(title);

        if(eventDescription.value !== '') {
            let description = document.createElement('p');
            description.innerHTML = eventDescription.value;
            eventDescription.value = '';
            div.appendChild(description);
        }

        eventList.appendChild(div);
        saveEvent();
        eventInput.value = '';
    });

    // Guardar eventos en localStorage
    function saveEvent() {
        const events = JSON.parse(localStorage.getItem('events')) || {};
        events[selectedDay.textContent] = eventList.innerHTML;
        localStorage.setItem('events', JSON.stringify(events));
        checkForEvents();
    }

    // Cargar eventos desde localStorage
    function loadEvent() {
        const events = JSON.parse(localStorage.getItem('events')) || {};
        eventList.innerHTML = events[selectedDay.textContent] || '';

        const deleteButtons = eventList.querySelectorAll('ion-icon[name="trash-outline"]');
        deleteButtons.forEach((button) => {
            button.addEventListener('click', (e) => {
                const liElement = e.target.parentElement;
                liElement.style.transition = 'translate 0.3s ease';
                liElement.style.translate = '-100%';
                setTimeout(() => {
                    liElement.remove();
                    saveEvent();
                }, 300);
            });
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

    // Calcular distancia desde hoy
    function checkDistanceFromToday(today, selectedDay) {
        selectedDay = selectedDay.split(' / ');
        let distance = 0;
        switch (selectedDay[1]) {
            case 'Enero':
                selectedDay[1] = 1;
                break;
            case 'Febrero':
                selectedDay[1] = 2;
                break;
            case 'Marzo':
                selectedDay[1] = 3;
                break;
            case 'Abril':
                selectedDay[1] = 4;
                break;
            case 'Mayo':
                selectedDay[1] = 5;
                break;
            case 'Junio':
                selectedDay[1] = 6;
                break;
            case 'Julio':
                selectedDay[1] = 7;
                break;
            case 'Agosto':
                selectedDay[1] = 8;
                break;
            case 'Septiembre':
                selectedDay[1] = 9;
                break;
            case 'Octubre':
                selectedDay[1] = 10;
                break;
            case 'Noviembre':
                selectedDay[1] = 11;
                break;
            case 'Diciembre':
                selectedDay[1] = 12;
                break;
        }
        //console.log(selectedDay[1], currentDate.getMonth() + 1);
        //console.log(currentDate)
        if (selectedDay[1] == currentDate.getMonth() + 1) {
            distance = parseInt(selectedDay[0]) - parseInt(today);
            return distanceCalc(distance);
        } else {
            let monthDiff = parseInt(selectedDay[1]) - (currentDate.getMonth() + 1);
            let yearDiff = parseInt(selectedDay[2]) - currentDate.getFullYear();
            distance = (monthDiff * 30) + (yearDiff * 365) + (parseInt(selectedDay[0]) - parseInt(today));
            return distanceCalc(distance);
        }
        
        function distanceCalc(distance) {
            if (distance == 0) {
                return 'Hoy';
            } else if (distance == 1) {
                return 'Mañana';
            } else if (distance > 1) {
                return 'Dentro de ' + distance + ' días';
            } else if (distance == -1) {
                return 'Ayer';
            } else {
                return 'Hace ' + (distance * -1) + ' días';
            }
        };
    };
});