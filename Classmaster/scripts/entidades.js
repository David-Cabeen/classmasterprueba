// Elementos del DOM
const loading = document.getElementById('loading');
const contentGrid = document.getElementById('contentGrid');
const errorMessage = document.getElementById('errorMessage');
const errorText = document.getElementById('errorText');
const usersTable = document.getElementById('usersTable');
const padresTable = document.getElementById('padresTable');
const teachersTable = document.getElementById('teachersTable');
const usersCount = document.getElementById('usersCount');
const padresCount = document.getElementById('padresCount');
const teachersCount = document.getElementById('teachersCount');

// Función para formatear fecha
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Función para cargar los datos
async function loadEntities() {
    try {
        loading.style.display = 'flex';
        contentGrid.style.display = 'none';
        errorMessage.style.display = 'none';

        const response = await fetch('../php/get_entidades.php');
        const data = await response.json();

        if (data.success) {
            // Cargar usuarios
            renderUsers(data.users);
            usersCount.textContent = data.users.length;

            // Cargar padres
            renderPadres(data.padres);
            padresCount.textContent = data.padres.length;

            // Mostrar contenido
            loading.style.display = 'none';
            contentGrid.style.display = 'block';
        } else {
            throw new Error(data.error || 'Error desconocido');
        }
    } catch (error) {
        console.error('Error:', error);
        loading.style.display = 'none';
        errorMessage.style.display = 'flex';
        errorText.textContent = error.message;
    }
}

// Función para renderizar usuarios
function renderUsers(users) {
    usersTable.innerHTML = '';
    if (users.length === 0) {
        usersTable.innerHTML = `
            <tr>
                <td colspan="8" class="no-data">No hay usuarios registrados</td>
            </tr>
        `;
        return;
    }
    users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.nombre}</td>
            <td>${user.apellido}</td>
            <td>${user.email}</td>
            <td>${user.grado || 'N/A'}</td>
            <td>${user.seccion || 'N/A'}</td>
            <td>${formatDate(user.fecha_registro)}</td>
        `;
        usersTable.appendChild(row);
    });
    // Add new user row
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td style="font-weight:600">+</td>
        <td><input type="text" id="newUserNombre" placeholder="Nombre"></td>
        <td><input type="text" id="newUserApellido" placeholder="Apellido"></td>
        <td><input type="email" id="newUserEmail" placeholder="Email"></td>
        <td><input type="number" id="newUserGrado" placeholder="Grado"></td>
        <td><input type="text" id="newUserSeccion" placeholder="Sección"></td>
        <td><button id="createUserBtn">Crear usuario</button></td>
    `;
    usersTable.appendChild(newRow);
    // Add event listener for create
    document.getElementById('createUserBtn').onclick = async function() {
        const nombre = document.getElementById('newUserNombre').value.charAt(0).toUpperCase() + document.getElementById('newUserNombre').value.slice(1).toLowerCase().trim();
        const apellido = document.getElementById('newUserApellido').value.charAt(0).toUpperCase() + document.getElementById('newUserApellido').value.slice(1).toLowerCase().trim();
        const email = document.getElementById('newUserEmail').value.trim();
        const grado =  document.getElementById('newUserGrado').value.trim();
        const seccion = document.getElementById('newUserSeccion').value.trim().toUpperCase();
        const password = 'Student_2025';
        if (!nombre || !apellido || !email || !password) {
            alert('Todos los campos obligatorios deben estar completos.');
            return;
        }
        const data = new FormData();
        data.append('action', 'create');
        data.append('type', 'user');
        data.append('nombre', nombre);
        data.append('apellido', apellido);
        data.append('email', email);
        data.append('grado', grado);
        data.append('seccion', seccion);
        data.append('password', password);
        const res = await fetch('../php/manage_entidad.php', { method: 'POST', body: data });
        const json = await res.json();
        if (json.success) {
            loadEntities();
        } else {
            alert(json.error);
        }
    };
}

// Función para renderizar padres
function renderPadres(padres) {
    padresTable.innerHTML = '';
    if (padres.length === 0) {
        padresTable.innerHTML = `
            <tr>
                <td colspan="7" class="no-data">No hay padres registrados</td>
            </tr>
        `;
        return;
    }
    padres.forEach(padre => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${padre.id}</td>
            <td>${padre.email}</td>
            <td>${padre.nombre}</td>
            <td>${padre.apellido}</td>
            <td>${padre.telefono || 'N/A'}</td>
            <td>${formatDate(padre.fecha_registro)}</td>
        `;
        padresTable.appendChild(row);
    });
}

function renderTeachers(teachers) {
    teachersTable.innerHTML = '';
    if (teachers.length === 0) {
        teachersTable.innerHTML = `
            <tr>
                <td colspan="6" class="no-data">No hay maestros registrados</td>
            </tr>
        `;
        return;
    }
    teachers.forEach(teacher => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${teacher.id}</td>
            <td>${teacher.email}</td>
            <td>${teacher.nombre}</td>
            <td>${teacher.apellido}</td>
            <td>${teacher.telefono || 'N/A'}</td>
            <td>${formatDate(teacher.fecha_registro)}</td>
        `;
        teachersTable.appendChild(row);
    });

    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td style="font-weight:600">+</td>
        <td><input type="text" id="newTeacherNombre" placeholder="Nombre"></td>
        <td><input type="text" id="newTeacherApellido" placeholder="Apellido"></td>
        <td><input type="email" id="newTeacherEmail" placeholder="Email"></td>
        <td>
            <select id="newTeacherMaterias" multiple>
                <option value="Matemáticas">Matemáticas</option>
                <option value="Ciencias">Ciencias</option>
                <option value="Lengua">Lengua</option>
                <option value="Historia">Historia</option>
                <option value="Inglés">Inglés</option>
                <option value="Arte">Arte</option>
                <option value="Educación Física">Educación Física</option>
            </select>
        </td>
        <td><button id="createUserBtn">Crear maestro</button></td>
    `;

    teachersTable.appendChild(newRow);

    document.getElementById('createUserBtn').onclick = async function() {
        const nombre = document.getElementById('newTeacherNombre').value.charAt(0).toUpperCase() + document.getElementById('newTeacherNombre').value.slice(1).toLowerCase().trim();
        const apellido = document.getElementById('newTeacherApellido').value.charAt(0).toUpperCase() + document.getElementById('newTeacherApellido').value.slice(1).toLowerCase().trim();
        const email = document.getElementById('newTeacherEmail').value.trim();
        const materiasSelect = document.getElementById('newTeacherMaterias');
        const materias = Array.from(materiasSelect.selectedOptions).map(opt => opt.value).join(',');

        // Prompt for password later on
        const password = 'Teacher_2025';
        if (!nombre || !apellido || !email || !materias || !password) {
            alert('Todos los campos obligatorios deben estar completos.');
            return;
        }
        const data = new FormData();
        data.append('action', 'create');
        data.append('type', 'teacher');
        data.append('nombre', nombre);
        data.append('apellido', apellido);
        data.append('email', email);
        data.append('materias', materias);
        data.append('password', password);
        const res = await fetch('../php/manage_entidad.php', { method: 'POST', body: data });
        const json = await res.json();
        if (json.success) {
            loadEntities();
        } else {
            alert(json.error);
        }
    };
}

// Cargar datos al iniciar la página

document.addEventListener('DOMContentLoaded', loadEntities);

// Botón de recarga (si se agrega en el futuro)
function refreshData() {
    loadEntities();
}