// Guardar y recuperar tareas del almacenamiento local
document.addEventListener('DOMContentLoaded', () => {
    //Carga las tareas guardadas previamente
    loadTasks();
    //funcionalidad de arrastrar y soltar
    setupDragAndDrop();
});

//Que cada columna tenga su propio "añadir tarea"
function loadTasks() {
    const columns = ['pending', 'inprogress', 'done'];
    columns.forEach(column => {
        const tasks = JSON.parse(localStorage.getItem(column)) || [];
        const container = document.getElementById(column);
        
        tasks.forEach(taskText => {
            const taskElement = createTaskElement(taskText);
            container.appendChild(taskElement);
        });
    });
}
// Guarda las tareas
function saveTasks() {
    const columns = ['pending', 'inprogress', 'done'];
    columns.forEach(column => {
        const container = document.getElementById(column);
        const tasks = Array.from(container.querySelectorAll('.task')).map(task => task.textContent.trim());
        localStorage.setItem(column, JSON.stringify(tasks));
    });
}
//Muestra el formulario para añadir una nueva tarea
function showForm(formId) {
    document.getElementById(formId).style.display = 'block';
    document.getElementById(formId).querySelector('textarea').focus();
}
//Oculta el formulario
function hideForm(formId) {
    document.getElementById(formId).style.display = 'none';
    document.getElementById(formId).querySelector('textarea').value = '';
}

function addTask(columnId) {
    const form = document.getElementById(`${columnId}-form`);
    const textarea = form.querySelector('textarea');
    const taskText = textarea.value.trim();
    
    if (taskText) {
        const taskElement = createTaskElement(taskText);
        document.getElementById(columnId).appendChild(taskElement);
        saveTasks();
        hideForm(`${columnId}-form`);
    }
}

function createTaskElement(text) {
    const task = document.createElement('article');
    task.className = 'task';
    //'draggable' para permitir arrastrar
    task.draggable = true;
    task.textContent = text;
    //Manejo del arrastre
    task.addEventListener('dragstart', dragStart);
    task.addEventListener('dragend', dragEnd);
    
    return task;
}

function setupDragAndDrop() {
    const containers = document.querySelectorAll('.tasks-container');
    
    containers.forEach(container => {
        container.addEventListener('dragover', dragOver);
        container.addEventListener('drop', drop);
    });
}

function dragStart(e) {
    e.target.classList.add('dragging');
}

function dragEnd(e) {
    e.target.classList.remove('dragging');
}

function dragOver(e) {
    e.preventDefault();
}

function drop(e) {
    e.preventDefault();
    const draggable = document.querySelector('.dragging');
    if (draggable && e.target.classList.contains('tasks-container')) {
        e.target.appendChild(draggable);
        saveTasks();
    }
}