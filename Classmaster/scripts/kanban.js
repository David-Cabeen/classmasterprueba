window.addEventListener('DOMContentLoaded', function () {
    // Elementos principales
    const overlay = document.getElementById('overlay'),
    popup = document.getElementById('task-popup'),
    closePopupBtn = document.getElementById('close-task-popup'),
    addTaskBtns = document.querySelectorAll('.add-task-btn'),
    taskForm = document.getElementById('task-form'),
    columns = {
        'por-hacer': document.querySelector('#por-hacer .kanban-tasks'),
        'en-proceso': document.querySelector('#en-proceso .kanban-tasks'),
        'terminado': document.querySelector('#terminado .kanban-tasks')
    },
    priorityRadios = document.querySelectorAll('#task-form input[type="radio"]');
    let currentColumn = 'por-hacer',
    draggedTask = null;

    // Mostrar popup para nueva tarea
    addTaskBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            currentColumn = btn.dataset.column;
            overlay.style.display = 'block';
            setTimeout(() => { overlay.style.opacity = 1; }, 1);
            popup.style.display = 'block';
            setTimeout(() => { popup.style.opacity = 1; popup.style.top = '50%'; }, 1);
        });
    });

    // Cerrar popup
    closePopupBtn.addEventListener('click', cerrarPopup);
    overlay.addEventListener('click', cerrarPopup);
    function cerrarPopup() {
        popup.style.opacity = 0;
        overlay.style.opacity = 0;
        popup.style.top = '55%';
        setTimeout(() => {
            popup.style.display = 'none';
            overlay.style.display = 'none';
            taskForm.reset();
            document.querySelectorAll('#task-form input[type="radio"]').forEach(r => r.checked = false);
        }, 300);
    }

    // Animación e icono de prioridad
    priorityRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('#task-form fieldset label ion-icon').forEach(icon => icon.setAttribute('name', 'ellipse-outline'));
            if (radio.checked) {
                radio.nextElementSibling.querySelector('ion-icon').setAttribute('name', 'checkmark-circle');
            }
        });
    });

    // Crear tarea
    taskForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const title = document.getElementById('task-title').value,
        desc = document.getElementById('task-desc').value,
        date = document.getElementById('task-date').value;
        let priority = 'normal';
        priorityRadios.forEach(r => { if (r.checked) priority = r.value; });
        const taskDiv = document.createElement('div');
        taskDiv.className = `kanban-task ${priority}`;
        taskDiv.innerHTML = `
            <div class="task-title">${title}</div>
            ${desc ? `<div class="task-desc">${desc}</div>` : ''}
            <div class="task-date">${date}</div>
            <div class="task-actions">
                <ion-icon name="arrow-forward-outline" title="Mover"></ion-icon>
                <ion-icon name="trash-outline" title="Eliminar"></ion-icon>
            </div>
        `;
        // Mover tarea
        taskDiv.querySelector('ion-icon[name="arrow-forward-outline"]').addEventListener('click', function () {
            moverTarea(taskDiv);
        });
        // Eliminar tarea
        taskDiv.querySelector('ion-icon[name="trash-outline"]').addEventListener('click', function () {
            taskDiv.style.transition = 'transform 0.3s, opacity 0.3s';
            taskDiv.style.transform = 'translateX(100%)';
            taskDiv.style.opacity = '0';
            setTimeout(() => { taskDiv.remove(); }, 300);
        });
        columns[currentColumn].appendChild(taskDiv);
        cerrarPopup();
    });

    // Función para mover tarea a la siguiente columna
    function moverTarea(taskDiv) {
        const parentId = taskDiv.parentElement.parentElement.id;
        if (parentId === 'por-hacer') {
            columns['en-proceso'].appendChild(taskDiv);
        } else if (parentId === 'en-proceso') {
            columns['terminado'].appendChild(taskDiv);
        }
    }
});
