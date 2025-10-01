// Elementos del DOM
const loading = document.getElementById('loading');
const contentGrid = document.getElementById('contentGrid');
const errorMessage = document.getElementById('errorMessage');
const errorText = document.getElementById('errorText');
const usersTable = document.getElementById('usersTable');
const acudientesTable = document.getElementById('acudientesTable');
const teachersTable = document.getElementById('teachersTable');
const usersCount = document.getElementById('usersCount');
const acudientesCount = document.getElementById('acudientesCount');
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

            // Cargar acudientes
            renderAcudientes(data.acudientes);
            acudientesCount.textContent = data.acudientes.length;

            // Cargar maestros
            renderTeachers(data.teachers || []);
            if (typeof teachersCount !== 'undefined') {
                teachersCount.textContent = (data.teachers || []).length;
            }

            // Cargar administradores
            renderAdmins(data.admins || []);
            if (typeof adminsCount !== 'undefined') {
                adminsCount.textContent = (data.admins || []).length;
            }

            // Mostrar contenido
            loading.style.display = 'none';
            contentGrid.style.display = 'block';
        } else {
            throw new Error(data.error || 'Error desconocido');
        }
// Función para renderizar administradores
function renderAdmins(admins) {
    adminsTable.innerHTML = '';
    if (admins.length === 0) {
        adminsTable.innerHTML = `
            <tr>
                <td colspan="5" class="no-data">No hay administradores registrados</td>
            </tr>
        `;
    } else {
        admins.forEach(admin => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${admin.id}</td>
                <td>${admin.nombre}</td>
                <td>${admin.apellido}</td>
                <td>${admin.email}</td>
                <td>${formatDate(admin.fecha_registro)}</td>
            `;
            adminsTable.appendChild(row);
        });
    }
    // Add new admin row
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td style="font-weight:600">+</td>
        <td><input type="text" id="newAdminNombre" placeholder="Nombre"></td>
        <td><input type="text" id="newAdminApellido" placeholder="Apellido"></td>
        <td><input type="email" id="newAdminEmail" placeholder="Email"></td>
        <td><button id="createAdminBtn">Crear admin</button></td>
    `;
    adminsTable.appendChild(newRow);
    document.getElementById('createAdminBtn').onclick = async function() {
        const nombre = document.getElementById('newAdminNombre').value.charAt(0).toUpperCase() + document.getElementById('newAdminNombre').value.slice(1).toLowerCase().trim();
        const apellido = document.getElementById('newAdminApellido').value.charAt(0).toUpperCase() + document.getElementById('newAdminApellido').value.slice(1).toLowerCase().trim();
        const email = document.getElementById('newAdminEmail').value.trim();
        // Generate random ID (11 chars, uppercase letters and numbers)
        function randomId() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; 
            let id = '';
            for (let i = 0; i < 11; i++) id += chars.charAt(Math.floor(Math.random() * chars.length));
            return id;
        }
        // Generate random password (11 chars, mixed case letters and numbers)
        function randomPassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let pw = '';
            for (let i = 0; i < 11; i++) pw += chars.charAt(Math.floor(Math.random() * chars.length));
            return pw;
        }
        const id = randomId();
        const password = randomPassword();
        if (!nombre || !apellido || !email || !password) {
            alert('Todos los campos obligatorios deben estar completos.');
            return;
        }
        const data = new FormData();
        data.append('action', 'create');
        data.append('type', 'admin');
        data.append('id', id);
        data.append('nombre', nombre);
        data.append('apellido', apellido);
        data.append('email', email);
        data.append('password', password);
        const res = await fetch('../php/manage_entidad.php', { method: 'POST', body: data });
        const json = await res.json();
        if (json.success) {
            document.getElementById('newAdminNombre').value = '';
            document.getElementById('newAdminApellido').value = '';
            document.getElementById('newAdminEmail').value = '';
            alert(`Administrador creado.\nID: ${id}\nContraseña: ${password}`);
            loadEntities();
        } else {
            alert(json.error);
        }
    };
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
        window.location.reload();
    };
}

// Función para renderizar acudientes
function renderAcudientes(acudientes) {
    acudientesTable.innerHTML = '';
    if (acudientes.length === 0) {
        acudientesTable.innerHTML = `
            <tr>
                <td colspan="7" class="no-data">No hay acudientes registrados</td>
            </tr>
        `;
        return;
    }
    acudientes.forEach(acudiente => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${acudiente.id}</td>
            <td>${acudiente.email}</td>
            <td>${acudiente.nombre}</td>
            <td>${acudiente.apellido}</td>
            <td>${acudiente.telefono || 'N/A'}</td>
            <td>${formatDate(acudiente.fecha_registro)}</td>
        `;
        acudientesTable.appendChild(row);
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
    } else {
        teachers.forEach(teacher => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${teacher.id}</td>
                <td>${teacher.nombre}</td>
                <td>${teacher.apellido}</td>
                <td>${teacher.email}</td>
                <td>${teacher.materias || 'N/A'}</td>
                <td>${formatDate(teacher.fecha_registro)}</td>
            `;
            teachersTable.appendChild(row);
        });
    }
    // Opciones posibles del set 'materias' en la tabla profesores
    const materiasOptions = [
        'Matemáticas',
        'Física',
        'Química',
        'Inglés',
        'Español',
        'Educación Física',
        'Sociales',
        'Filosofía',
        'Religión',
        'Ética',
        'Ciencias Políticas',
        'Artística',
        'Science',
        'Programación',
        'Informática',
        'Robótica'
    ];
    // Always add the new teacher row
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td style="font-weight:600">+</td>
        <td><input type="text" id="newTeacherNombre" placeholder="Nombre"></td>
        <td><input type="text" id="newTeacherApellido" placeholder="Apellido"></td>
        <td><input type="email" id="newTeacherEmail" placeholder="Email"></td>
        <td>
            <select id="newTeacherMaterias" multiple>
                ${materiasOptions.map(m => `<option value="${m}">${m}</option>`).join('')}
            </select>
        </td>
        <td><button id="createTeacherBtn">Crear maestro</button></td>
    `;
    teachersTable.appendChild(newRow);
    document.getElementById('createTeacherBtn').onclick = async function() {
        const nombre = document.getElementById('newTeacherNombre').value.charAt(0).toUpperCase() + document.getElementById('newTeacherNombre').value.slice(1).toLowerCase().trim();
        const apellido = document.getElementById('newTeacherApellido').value.charAt(0).toUpperCase() + document.getElementById('newTeacherApellido').value.slice(1).toLowerCase().trim();
        const email = document.getElementById('newTeacherEmail').value.trim();
        const materiasSelect = document.getElementById('newTeacherMaterias');
        const materias = Array.from(materiasSelect.selectedOptions).map(opt => opt.value).join(',');
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
            // Clear inputs before reloading
            document.getElementById('newTeacherNombre').value = '';
            document.getElementById('newTeacherApellido').value = '';
            document.getElementById('newTeacherEmail').value = '';
            document.getElementById('newTeacherMaterias').selectedIndex = -1;
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