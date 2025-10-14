import { confirmModal, toast } from './components.js';

// Elementos del DOM
const loading = document.getElementById('loading');
const contentGrid = document.getElementById('contentGrid');
const errorMessage = document.getElementById('errorMessage');
const errorText = document.getElementById('errorText');
const usersTable = document.getElementById('usersTable');
const acudientesTable = document.getElementById('acudientesTable');
const teachersTable = document.getElementById('teachersTable');
const adminsTable = document.getElementById('adminsTable');
const usersCount = document.getElementById('usersCount');
const acudientesCount = document.getElementById('acudientesCount');
const teachersCount = document.getElementById('teachersCount');
const adminsCount = document.getElementById('adminsCount');

// Materias master list (used for create and edit selects)
const MATERIAS_OPTIONS = [
    'Matemáticas', 'Física', 'Química', 'Inglés', 'Español', 'Educación Física', 'Sociales',
    'Filosofía', 'Religión', 'Ética', 'Ciencias Políticas', 'Artística', 'Science', 'Programación', 'Informática', 'Robótica'
];

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
// Función para cargar los datos (restores missing function)
async function loadEntities() {
    try {
        loading.style.display = 'flex';
        contentGrid.style.display = 'none';
        errorMessage.style.display = 'none';

        const res = await fetch('../php/get_entidades.php');
        const txt = await res.text();
        let data;
        try { data = JSON.parse(txt); } catch (e) { throw new Error('Invalid JSON response:\n' + txt); }

        if (data.success) {
            renderUsers(data.users || []);
            usersCount.textContent = (data.users || []).length;

            renderAcudientes(data.acudientes || []);
            acudientesCount.textContent = (data.acudientes || []).length;

            renderTeachers(data.teachers || []);
            if (typeof teachersCount !== 'undefined') teachersCount.textContent = (data.teachers || []).length;

            renderAdmins(data.admins || []);
            adminsCount.textContent = (data.admins || []).length;

            loading.style.display = 'none';
            contentGrid.style.display = 'block';
        } else {
            throw new Error(data.error || 'Error desconocido');
        }
    } catch (err) {
        console.error('Error:', err);
        loading.style.display = 'none';
        contentGrid.style.display = 'none';
        errorMessage.style.display = 'flex';
        errorText.textContent = err.message;
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
    }
    users.forEach(user => {
        const row = document.createElement('tr');
        row.dataset.id = user.id;
        row.innerHTML = `
            <td>${user.id}</td>
            <td class="editable" data-field="nombre">${user.nombre}</td>
            <td class="editable" data-field="apellido">${user.apellido}</td>
            <td class="editable" data-field="email">${user.email}</td>
            <td class="editable" data-field="grado">${user.grado || 'N/A'}</td>
            <td class="editable" data-field="seccion">${user.seccion || 'N/A'}</td>
            <td>${formatDate(user.fecha_registro)}</td>
            <td class="actions-cell">
                <button class="btn-edit" data-type="user" title="Editar"><ion-icon name="pencil-outline"></ion-icon></button>
                <button class="btn-save" data-type="user" style="display:none" title="Guardar"><ion-icon name="checkmark-outline"></ion-icon></button>
                <button class="btn-delete" data-type="user" title="Eliminar"><ion-icon name="trash-outline"></ion-icon></button>
            </td>
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
        <td></td>
        <td><button style='white-space: nowrap;' id="createUserBtn">Crear usuario</button></td>
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
        row.dataset.id = acudiente.id;
        row.innerHTML = `
            <td>${acudiente.id}</td>
            <td class="editable" data-field="email">${acudiente.email}</td>
            <td class="editable" data-field="nombre">${acudiente.nombre}</td>
            <td class="editable" data-field="apellido">${acudiente.apellido}</td>
            <td class="editable" data-field="telefono">${acudiente.telefono || 'N/A'}</td>
            <td>${formatDate(acudiente.fecha_registro)}</td>
            <td class="actions-cell">
                <button class="btn-edit" data-type="acudiente" title="Editar"><ion-icon name="pencil-outline"></ion-icon></button>
                <button class="btn-save" data-type="acudiente" style="display:none" title="Guardar"><ion-icon name="checkmark-outline"></ion-icon></button>
                <button class="btn-delete" data-type="acudiente" title="Eliminar"><ion-icon name="trash-outline"></ion-icon></button>
            </td>
        `;
        acudientesTable.appendChild(row);
    });
}

function renderTeachers(teachers) {
    teachersTable.innerHTML = '';
    if (teachers.length === 0) {
        teachersTable.innerHTML = `
            <tr>
                <td colspan="8" class="no-data">No hay maestros registrados</td>
            </tr>
        `;
    } else {
        teachers.forEach(teacher => {
            const row = document.createElement('tr');
            row.dataset.id = teacher.id;
            row.innerHTML = `
                <td>${teacher.id}</td>
                <td class="editable" data-field="nombre">${teacher.nombre}</td>
                <td class="editable" data-field="apellido">${teacher.apellido}</td>
                <td class="editable" data-field="email">${teacher.email}</td>
                <td class="editable" data-field="grados">${teacher.grados || 'N/A'}</td>
                <td class="editable" data-field="materias">${teacher.materias || 'N/A'}</td>
                <td>${formatDate(teacher.fecha_registro)}</td>
                <td class="actions-cell">
                    <button class="btn-edit" data-type="teacher" title="Editar"><ion-icon name="pencil-outline"></ion-icon></button>
                    <button class="btn-save" data-type="teacher" style="display:none" title="Guardar"><ion-icon name="checkmark-outline"></ion-icon></button>
                    <button class="btn-delete" data-type="teacher" title="Eliminar"><ion-icon name="trash-outline"></ion-icon></button>
                </td>
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
    // Remove any existing floating dropdowns left from previous renders
    const existingMaterias = document.getElementById('materiasDropdown');
    if (existingMaterias) existingMaterias.remove();
    const existingGrados = document.getElementById('gradosDropdown');
    if (existingGrados) existingGrados.remove();

    // Always add the new teacher row
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td style="font-weight:600">+</td>
        <td><input type="text" id="newTeacherNombre" placeholder="Nombre"></td>
        <td><input type="text" id="newTeacherApellido" placeholder="Apellido"></td>
        <td><input type="email" id="newTeacherEmail" placeholder="Email"></td>
        <td>
            <div class="relative" id="gradosDropdownWrap">
                <button type="button" id="gradosDropdownBtn" class="w-full min-w-[80px] max-w-xs px-3 py-2 bg-white/10 border border-white/20 text-white rounded focus:outline-none focus:ring-2 focus:ring-accent flex justify-between items-center">
                    <span id="gradosDropdownLabel">Grados</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="gradosDropdown" class="absolute z-10 left-0 mt-1 w-full max-h-48 overflow-y-auto bg-panel border border-white/20 rounded shadow-lg hidden">
                    ${Array.from({length:11}).map((_,i)=>{
                        const val = i+1;
                        return '<label class="relative block px-3 py-2 hover:bg-white/10 cursor-pointer">' +
                               '<input type="checkbox" class="grado-checkbox absolute left-3 top-1/2 -translate-y-1/2" value="' + val + '" id="gradoOpt' + val + '">' +
                               '<span class="ml-9 pr-3 block">' + val + '</span>' +
                               '</label>';
                    }).join('')}
                </div>
            </div>
        </td>
        <td>
            <div class="relative" id="materiasDropdownWrap">
                <button type="button" id="materiasDropdownBtn" class="w-full min-w-[180px] max-w-xs px-3 py-2 bg-white/10 border border-white/20 text-white rounded focus:outline-none focus:ring-2 focus:ring-accent flex justify-between items-center">
                    <span id="materiasDropdownLabel">Seleccionar materias</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="materiasDropdown" class="absolute z-10 left-0 mt-1 w-full max-h-48 overflow-y-auto bg-panel border border-white/20 rounded shadow-lg hidden">
                    ${materiasOptions.map((m, i) => `
                        <label class="relative block px-3 py-2 hover:bg-white/10 cursor-pointer">
                            <input type="checkbox" class="materia-checkbox absolute left-3 top-1/2 -translate-y-1/2" value="${m}" id="materiaOpt${i}">
                            <span class="ml-9 pr-3 block">${m}</span>
                        </label>
                    `).join('')}
                </div>
            </div>
        </td>
        <td><button id="createTeacherBtn">Crear maestro</button></td>
        <td></td>
    `;
    teachersTable.appendChild(newRow);

    // Dropdown logic for materias and grados
    const dropdownBtn = document.getElementById('materiasDropdownBtn');
    const dropdown = document.getElementById('materiasDropdown');
    const label = document.getElementById('materiasDropdownLabel');
    let checkboxes = dropdown.querySelectorAll('.materia-checkbox');
    let open = false;

    function positionFor(btn, dd) {
        const rect = btn.getBoundingClientRect();
        dd.style.position = 'absolute';
        dd.style.left = `${rect.left + window.scrollX}px`;
        dd.style.top = `${rect.bottom + window.scrollY}px`;
        dd.style.width = `${rect.width}px`;
        dd.style.zIndex = 9999;
        dd.style.backgroundColor = 'var(--bg)';
    }

    dropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        open = !open;
        if (open) {
            document.body.appendChild(dropdown);
            positionFor(dropdownBtn, dropdown);
            dropdown.classList.remove('hidden');
            dropdownBtn.classList.add('ring-2');
            checkboxes = dropdown.querySelectorAll('.materia-checkbox');
        } else {
            dropdown.classList.add('hidden');
            dropdownBtn.classList.remove('ring-2');
        }
    });

    const onDocClick = (e) => {
        if (open && !dropdown.contains(e.target) && e.target !== dropdownBtn) {
            open = false;
            dropdown.classList.add('hidden');
            dropdownBtn.classList.remove('ring-2');
        }
    };
    document.addEventListener('click', onDocClick);
    window.addEventListener('resize', () => { if (open) positionFor(dropdownBtn, dropdown); });
    window.addEventListener('scroll', () => { if (open) positionFor(dropdownBtn, dropdown); }, true);
    // Update label on selection for materias
    function updateLabel() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        label.textContent = selected.length ? selected.join(', ') : 'Seleccionar materias';
    }
    checkboxes.forEach(cb => cb.addEventListener('change', updateLabel));

    // Grados dropdown
    const gradosBtn = document.getElementById('gradosDropdownBtn');
    const gradosDd = document.getElementById('gradosDropdown');
    const gradosLabel = document.getElementById('gradosDropdownLabel');
    let gradoCheckboxes = gradosDd.querySelectorAll('.grado-checkbox');
    let gradosOpen = false;

    gradosBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        gradosOpen = !gradosOpen;
        if (gradosOpen) {
            document.body.appendChild(gradosDd);
            positionFor(gradosBtn, gradosDd);
            gradosDd.classList.remove('hidden');
            gradosBtn.classList.add('ring-2');
            gradoCheckboxes = gradosDd.querySelectorAll('.grado-checkbox');
        } else {
            gradosDd.classList.add('hidden');
            gradosBtn.classList.remove('ring-2');
        }
    });

    const onDocClickGrados = (e) => {
        if (gradosOpen && !gradosDd.contains(e.target) && e.target !== gradosBtn) {
            gradosOpen = false;
            gradosDd.classList.add('hidden');
            gradosBtn.classList.remove('ring-2');
        }
    };
    document.addEventListener('click', onDocClickGrados);
    window.addEventListener('resize', () => { if (gradosOpen) positionFor(gradosBtn, gradosDd); });
    window.addEventListener('scroll', () => { if (gradosOpen) positionFor(gradosBtn, gradosDd); }, true);
    function updateGradosLabel() {
        const selected = Array.from(gradoCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        gradosLabel.textContent = selected.length ? selected.join(', ') : 'Grados';
    }
    gradoCheckboxes.forEach(cb => cb.addEventListener('change', updateGradosLabel));
    // On create, collect checked materias

    document.getElementById('createTeacherBtn').onclick = async function() {
        const nombre = document.getElementById('newTeacherNombre').value.charAt(0).toUpperCase() + document.getElementById('newTeacherNombre').value.slice(1).toLowerCase().trim();
        const apellido = document.getElementById('newTeacherApellido').value.charAt(0).toUpperCase() + document.getElementById('newTeacherApellido').value.slice(1).toLowerCase().trim();
        const email = document.getElementById('newTeacherEmail').value.trim();
        const materias = Array.from(document.querySelectorAll('.materia-checkbox:checked')).map(cb => cb.value).join(',');
        const gradosSelected = Array.from(document.querySelectorAll('.grado-checkbox:checked')).map(cb => cb.value).join(',');
        const password = 'Teacher_2025';
        if (!nombre || !apellido || !email || !materias || !gradosSelected || !password) {
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
    data.append('grados', gradosSelected);
        data.append('password', password);
        const res = await fetch('../php/manage_entidad.php', { method: 'POST', body: data });
        const json = await res.json();
        if (json.success) {
            // Clear inputs before reloading
            document.getElementById('newTeacherNombre').value = '';
            document.getElementById('newTeacherApellido').value = '';
            document.getElementById('newTeacherEmail').value = '';
            checkboxes.forEach(cb => cb.checked = false);
            updateLabel();
            // clear grados
            document.querySelectorAll('.grado-checkbox').forEach(cb => cb.checked = false);
            updateGradosLabel();
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

// Función para renderizar administradores (moved here)
function renderAdmins(admins) {
    adminsTable.innerHTML = '';
    if (!admins || admins.length === 0) {
        adminsTable.innerHTML = `
            <tr>
                <td colspan="6" class="no-data">No hay administradores registrados</td>
            </tr>
        `;
    } else {
        admins.forEach(admin => {
            const row = document.createElement('tr');
            row.dataset.id = admin.id;
            row.innerHTML = `
                <td>${admin.id}</td>
                <td class="editable" data-field="nombre">${admin.nombre}</td>
                <td class="editable" data-field="apellido">${admin.apellido}</td>
                <td class="editable" data-field="email">${admin.email}</td>
                <td>${formatDate(admin.fecha_registro)}</td>
                <td class="actions-cell">
                    <button class="btn-edit" data-type="admin" title="Editar"><ion-icon name="pencil-outline"></ion-icon></button>
                    <button class="btn-save" data-type="admin" style="display:none" title="Guardar"><ion-icon name="checkmark-outline"></ion-icon></button>
                    <button class="btn-delete" data-type="admin" title="Eliminar"><ion-icon name="trash-outline"></ion-icon></button>
                </td>
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
        <td></td>
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

// Generic delegation for edit/save/delete buttons
document.addEventListener('click', async (e) => {
    const editBtn = e.target.closest('.btn-edit');
    const saveBtn = e.target.closest('.btn-save');
    const delBtn = e.target.closest('.btn-delete');
    if (editBtn) {
        const tr = editBtn.closest('tr');
        const type = editBtn.dataset.type;
        tr.querySelectorAll('.editable').forEach(td => {
            const field = td.dataset.field;
            const raw = td.textContent === 'N/A' ? '' : td.textContent.trim();
            td.innerHTML = '';
            if (type === 'teacher' && field === 'materias') {
                // render materia checkboxes inline for editing
                const selected = raw ? raw.split(',').map(s => s.trim()) : [];
                const container = document.createElement('div');
                container.className = 'edit-materias';
                container.style.display = 'none'; // hidden until toggled
                // create toggle button
                const toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.className = 'edit-select-toggle';
                toggle.style.whiteSpace = 'nowrap';
                const toggleLabel = document.createElement('span');
                toggleLabel.textContent = selected.length ? selected.join(', ') : 'Seleccionar materias';
                toggle.appendChild(toggleLabel);
                toggle.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    const isOpen = container.style.display !== 'none';
                    container.style.display = isOpen ? 'none' : 'block';
                    toggle.classList.toggle('ring-2', !isOpen);
                });
                td.appendChild(toggle);
                MATERIAS_OPTIONS.forEach((m, i) => {
                    const id = `editMateria${i}`;
                    const label = document.createElement('label');
                    label.className = 'flex items-center gap-2 py-1';
                    const cb = document.createElement('input');
                    cb.type = 'checkbox';
                    cb.className = 'edit-materia-checkbox';
                    cb.value = m;
                    cb.id = id;
                    if (selected.includes(m)) cb.checked = true;
                    const span = document.createElement('span');
                    span.textContent = m;
                    label.appendChild(cb);
                    label.appendChild(span);
                    container.appendChild(label);
                });
                // when selection changes update toggle label
                container.addEventListener('change', () => {
                    const sel = Array.from(container.querySelectorAll('.edit-materia-checkbox:checked')).map(x => x.value);
                    toggleLabel.textContent = sel.length ? sel.join(', ') : 'Seleccionar materias';
                });
                td.appendChild(container);
            } else if (type === 'teacher' && field === 'grados') {
                // render grados checkboxes 1..11
                const selected = raw ? raw.split(',').map(s => s.trim()) : [];
                const container = document.createElement('div');
                container.className = 'edit-grados flex flex-wrap gap-2';
                container.style.display = 'none';
                const toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.className = 'edit-select-toggle';
                const toggleLabel = document.createElement('span');
                toggleLabel.textContent = selected.length ? selected.join(', ') : 'Seleccionar grados';
                toggle.appendChild(toggleLabel);
                toggle.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    const isOpen = container.style.display !== 'none';
                    container.style.display = isOpen ? 'none' : 'flex';
                    toggle.classList.toggle('ring-2', !isOpen);
                });
                td.appendChild(toggle);
                for (let g = 1; g <= 11; g++) {
                    const id = `editGrado${g}`;
                    const label = document.createElement('label');
                    label.className = 'flex items-center gap-2 py-1';
                    const cb = document.createElement('input');
                    cb.type = 'checkbox';
                    cb.className = 'edit-grado-checkbox';
                    cb.value = String(g);
                    cb.id = id;
                    if (selected.includes(String(g))) cb.checked = true;
                    const span = document.createElement('span');
                    span.textContent = String(g);
                    label.appendChild(cb);
                    label.appendChild(span);
                    container.appendChild(label);
                }
                container.addEventListener('change', () => {
                    const sel = Array.from(container.querySelectorAll('.edit-grado-checkbox:checked')).map(x => x.value);
                    toggleLabel.textContent = sel.length ? sel.join(', ') : 'Seleccionar grados';
                });
                td.appendChild(container);
            } else {
                const input = document.createElement('input');
                input.value = raw;
                input.className = 'inline-edit-input';
                input.style.width = '100%';
                td.appendChild(input);
            }
        });
        editBtn.style.display = 'none';
        tr.querySelector('.btn-save').style.display = '';
    }
    if (saveBtn) {
        const tr = saveBtn.closest('tr');
        const id = tr.dataset.id;
        const type = saveBtn.dataset.type;
        const payload = new FormData();
        payload.append('action', 'update');
        payload.append('type', type);
        payload.append('id', id);
        tr.querySelectorAll('.editable').forEach(td => {
            const field = td.dataset.field;
            let value = '';
            // handle multi-select fields for teachers
            if (field === 'materias') {
                const checked = Array.from(td.querySelectorAll('.edit-materia-checkbox:checked')).map(cb => cb.value);
                if (checked.length) value = checked.join(',');
            } else if (field === 'grados') {
                const checked = Array.from(td.querySelectorAll('.edit-grado-checkbox:checked')).map(cb => cb.value);
                if (checked.length) value = checked.join(',');
            } else {
                const input = td.querySelector('input');
                value = input ? input.value.trim() : td.textContent.trim();
            }
            payload.append(field, value);
        });
        const res = await fetch('../php/manage_entidad.php', { method: 'POST', body: payload });
        let json;
        try {
            const txt = await res.text();
            try { json = JSON.parse(txt); } catch (e) { throw new Error('Invalid JSON response:\n' + txt); }
        } catch (err) {
            console.error('Fetch/parse error', err);
            alert('Error al comunicarse con el servidor: ' + err.message);
            return;
        }
        if (json.success) {
            // Show a success toast mentioning the edited entity type, then refresh
            const typeLabels = { user: 'Usuario', acudiente: 'Acudiente', teacher: 'Profesor', admin: 'Administrador' };
            const label = typeLabels[type] || 'Registro';
            toast(`${label} editado correctamente`, 'success');
            // Refresh the entities
            loadEntities();
        } else {
            alert(json.error || 'Error al actualizar');
        }
    }
    if (delBtn) {
        const tr = delBtn.closest('tr');
        const id = tr.dataset.id;
        const type = delBtn.dataset.type;
        // Build a friendly label from the row: prefer nombre+apellido, fallback to email or id
        const displayNameCell = tr.querySelector('.editable[data-field="nombre"]');
        const displayEmailCell = tr.querySelector('.editable[data-field="email"]');
        let displayLabel = tr.dataset.id;
        if (displayNameCell && displayNameCell.textContent.trim()) displayLabel = displayNameCell.textContent.trim();
        else if (displayEmailCell && displayEmailCell.textContent.trim()) displayLabel = displayEmailCell.textContent.trim();

        confirmModal({
            titulo: 'Eliminar registro',
            descripcion: `¿Eliminar a "${displayLabel}"? Esta acción no se puede deshacer.`,
            confirmarTxt: 'Eliminar',
            cancelarTxt: 'Cancelar',
            onConfirm: async function() {
                const payload = new FormData();
                payload.append('action', 'delete');
                payload.append('type', type);
                payload.append('id', id);
                const res = await fetch('../php/manage_entidad.php', { method: 'POST', body: payload });
                let delJson;
                try {
                    const txt = await res.text();
                    try { delJson = JSON.parse(txt); } catch (e) { throw new Error('Invalid JSON response:\n' + txt); }
                } catch (err) {
                    console.error('Fetch/parse error', err);
                    toast('Error al comunicarse con el servidor', 'error');
                    return;
                }
                if (delJson.success) {
                    toast('Usuario eliminado exitosamente', 'success');
                    loadEntities();
                } else {
                    toast(delJson.error || 'Error al eliminar', 'error');
                }
            }
        });
    }
});

// Close any open edit dropdowns when clicking outside
document.addEventListener('click', (e) => {
    document.querySelectorAll('.edit-materias, .edit-grados').forEach(container => {
        if (!container.contains(e.target)) {
            container.style.display = 'none';
        }
    });
    document.querySelectorAll('.edit-select-toggle').forEach(btn => btn.classList.remove('ring-2'));
});