import { confirmModal, toast } from './components.js'

document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('table');
    let btn = null,
    overlay,
    tareaId;
    // keep a reference to the current escape key handler so we can remove it when overlay closes
    let escHandlerRef = null;

    function closeOverlay() {
        if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
        if (escHandlerRef) {
            document.removeEventListener('keydown', escHandlerRef);
            escHandlerRef = null;
        }
        overlay = null;
    }

    // Opciones de los filtros
    if (window.rol === 'profesor') {
        fetch('../php/notas_filtros.php')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const materiaSelect = document.getElementById('materia');
                    materiaSelect.innerHTML = '';
                    data.materias.forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m;
                        opt.textContent = m;
                        materiaSelect.appendChild(opt);
                    });
                    const gradoSelect = document.getElementById('grado');
                    if (gradoSelect) {
                        gradoSelect.innerHTML = '';
                        data.grados.forEach(g => {
                            const opt = document.createElement('option');
                            opt.value = g;
                            opt.textContent = g;
                            gradoSelect.appendChild(opt);
                        });
                    };
                };
            });

        // Busqueda por nombre
        const search = document.getElementById('search');
        search.addEventListener('input', function() {
            const filter = search.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const studentName = row.cells[0]?.textContent.toLowerCase() || '';
                if (studentName.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
    } else if(window.rol === "acudiente") {
        // Get linked students
        fetch('../php/padre_estudiante.php')
            .then(res => res.json())
            .then(estudiantes => {
                const estudianteSelect = document.getElementById('estudiante');
                estudianteSelect.innerHTML = '';
                if(estudiantes.length === 0) {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'Sin estudiantes vinculados';
                    estudianteSelect.appendChild(opt);
                    document.getElementById('materia').innerHTML = '';
                    return;
                }
                estudiantes.forEach(e => {
                    const opt = document.createElement('option');
                    opt.value = e.id;
                    opt.textContent = e.nombre + ' ' + e.apellido;
                    estudianteSelect.appendChild(opt);
                });
                // Load materias for first student
                cargarMaterias(estudiantes[0].id);
                estudianteSelect.addEventListener('change', function() {
                    cargarMaterias(this.value);
                });
            });
        function cargarMaterias(estudianteId) {
            fetch('../php/notas_filtros.php?estudiante_id=' + estudianteId)
                .then(res => res.json())
                .then(data => {
                    const materiaSelect = document.getElementById('materia');
                    materiaSelect.innerHTML = '';
                    if(data.success && data.materias.length > 0) {
                        data.materias.forEach(m => {
                            const opt = document.createElement('option');
                            opt.value = m;
                            opt.textContent = m;
                            materiaSelect.appendChild(opt);
                        });
                    } else {
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = 'Sin materias';
                        materiaSelect.appendChild(opt);
                    }
                });
        }
    } else if(window.rol === "estudiante") {
        fetch('../php/notas_filtros.php')
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    const materiaSelect = document.getElementById('materia');
                    materiaSelect.innerHTML = '';
                    data.materias.forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m;
                        opt.textContent = m;
                        materiaSelect.appendChild(opt);
                    });
                };
            });
    };

    async function crearTarea(){
        const materia = document.getElementById('materia').value;
        const grado = document.getElementById('grado').value;
        const seccion = document.getElementById('seccion').value;
        const curso = `${materia} ${grado}${seccion}`;
        const data = new FormData();
        data.append('curso_id', curso);
        data.append('periodo', document.getElementById('periodo').value);
        data.append('titulo', overlay.querySelector('#tarea-input').value);
        data.append('descripcion', overlay.querySelector('#event-description').value);
        data.append('porcentaje', overlay.querySelector('#porcentaje').value);
        try {
            const res = await fetch('../php/crear_tarea.php', { method: 'POST', body: data });
            const result = await res.json();
            toast(result.message || result.error, result.success ? 'success' : 'error');
            if(result.success){
                closeOverlay();
                updateTable();
            }
        } catch (err) {
            console.log(err);
            toast('Error de red al crear la tarea: ' + err, 'error');
        }
    }

    async function editarTarea(){
        const data = new FormData();
        data.append('action', 'editar');
        data.append('id', tareaId);
        data.append('titulo', overlay.querySelector('#tarea-input').value);
        data.append('descripcion', overlay.querySelector('#event-description').value);
        data.append('porcentaje', overlay.querySelector('#porcentaje').value);
        try {
            const res = await fetch('../php/editar_tarea.php', { method: 'POST', body: data });
            const result = await res.json();
            toast(result.message || result.error, result.success ? 'success' : 'error');
            if(result.success){
                closeOverlay();
                updateTable();
            }
        } catch (err) {
            console.log(err);
            toast('Error de red al editar la tarea: ' + err, 'error');
        }
    }

    async function eliminarTarea(id){
        const data = new FormData();
        data.append('action', 'eliminar');
        data.append('id', id);
        const res = await fetch('../php/editar_tarea.php', { method: 'POST', body: data });
        const result = await res.json();
        toast(result.message || result.error, result.success ? 'success' : 'error');
        if(result.success){
            updateTable();
        }
    }

    function updateTable() {
        const thead = table.querySelector('thead tr');
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
        let grado = document.getElementById('grado').value,
        seccion = document.getElementById('seccion').value,
        materia = document.getElementById('materia').value,
        periodo = document.getElementById('periodo').value;

        fetch(`../php/notas_table.php?grado=${encodeURIComponent(grado)}&seccion=${encodeURIComponent(seccion)}&materia=${encodeURIComponent(materia)}&periodo=${encodeURIComponent(periodo)}${window.rol === 'acudiente' ? '&estudiante_id=' + document.getElementById('estudiante').value : ''}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Header (build headers as elements and use a single delegated click handler)
                    const tr = document.querySelector('#notas-header');
                    // Reset header
                    tr.innerHTML = '';
                    const nameTh = document.createElement('th');
                    nameTh.className = 'px-6 py-4 font-semibold align-middle';
                    nameTh.textContent = 'Nombre';
                    tr.appendChild(nameTh);

                    // Append a TH per tarea with a data attribute
                    data.tareas.forEach(tarea => {
                        const th = document.createElement('th');
                        th.className = 'px-6 py-4 cursor-pointer font-semibold align-middle';
                        th.textContent = `${tarea.titulo} (${tarea.porcentaje}%)`;
                        th.dataset.tareaId = tarea.id;
                        tr.appendChild(th);
                    });

                    // Remove any previous click handler and attach a single delegated handler
                    tr.onclick = function(e) {
                        const th = e.target.closest('th');
                        if (!th) return;
                        const tareaIdClicked = th.dataset.tareaId;
                        if (!tareaIdClicked) return; // clicked the "Nombre" header or other non-tarea th

                        // Find the tarea data for this column
                        const tarea = data.tareas.find(t => String(t.id) === String(tareaIdClicked));
                        if (!tarea) return;

                        // If an overlay already exists, close it first to avoid stacking and remove its listeners
                        const existing = document.querySelector('.cm-overlay');
                        if (existing) closeOverlay();

                        overlay = document.createElement('div');
                        overlay.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-4 z-50 cm-overlay';
                        overlay.innerHTML = `
                        <div class="editar-tarea w-full sm:max-w-md rounded-2xl border border-white/10 p-6 animate-in" role="dialog" aria-modal="true">
                            <h1 class="text-xl font-semibold text-white mb-4">Editar tarea</h1>
                            <form id="event-form" class="flex flex-col gap-4">
                                <input type="text" id="tarea-input" placeholder="Título" value="${tarea.titulo}" required class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                                <textarea name="tarea-description" placeholder="Descripción" id="event-description" class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-base placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition resize-none min-h-[60px]">${tarea.descripcion}</textarea>
                                <input type="number" id="porcentaje" placeholder="Porcentaje" value="${tarea.porcentaje}" required class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                                <div class="flex gap-3 justify-end mt-4">
                                    <button id="cm-delete" type="button" class="mr-auto px-4 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600 transition-colors duration-200">Eliminar</button>
                                    <button id="cm-cancel" type="button" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition focus:outline-none">Cancelar</button>
                                    <button id="cm-ok" class="px-4 py-2 rounded-lg bg-blue-500 font-semibold hover:bg-blue-600 transition focus:outline-none">Guardar</button>
                                </div>
                            </form>
                        </div>`;
                        tareaId = tarea.id;
                        document.body.appendChild(overlay);

                        const ok = overlay.querySelector('#cm-ok'),
                        cancel = overlay.querySelector('#cm-cancel'),
                        del = overlay.querySelector('#cm-delete');

                        del.addEventListener('click', function() {
                            confirmModal({
                                titulo: 'Eliminar tarea',
                                descripcion: '¿Estás seguro de que deseas eliminar esta tarea? Esta acción no se puede deshacer.',
                                onConfirm: function() {
                                    eliminarTarea(tareaId);
                                    closeOverlay();
                                }
                            });
                        });

                        function escHandler(e) {
                            if (e.key === 'Escape') closeOverlay();
                        }

                        ok.addEventListener('click', function(e) {
                            e.preventDefault();
                            editarTarea();
                        });
                        cancel.addEventListener('click', closeOverlay);
                        overlay.addEventListener('click', (e) => {
                            if (e.target === overlay) closeOverlay();
                        });
                        // keep a reference so closeOverlay can remove it
                        escHandlerRef = escHandler;
                        document.addEventListener('keydown', escHandlerRef);
                    };
                    btn = document.createElement('button');
                    btn.className = 'px-4 py-2 rounded-lg bg-green-500 text-white font-semibold hover:bg-green-600 transition-colors duration-200';
                    btn.textContent = 'Crear nueva tarea';
                    tr.append(btn);

                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        // Remove any existing overlay before creating a new one
                        const existing = document.querySelector('.cm-overlay');
                        if (existing) closeOverlay();

                        overlay = document.createElement('div');
                        overlay.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-4 z-50 cm-overlay';
                        overlay.innerHTML = `
                            <div class="crear-tarea w-full sm:max-w-md rounded-2xl border border-white/10 p-6 animate-in" role="dialog" aria-modal="true">
                                <h1 class="text-xl font-semibold text-white mb-4">Agregar tarea</h1>
                                <form id="event-form" class="flex flex-col gap-4">
                                    <input type="text" id="tarea-input" placeholder="Título" required class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                                    <textarea name="tarea-description" placeholder="Descripción" id="event-description" class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-base placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition resize-none min-h-[60px]"></textarea>
                                    <input type="number" id="porcentaje" placeholder="Porcentaje" required class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                                    <div class="flex gap-3 justify-end mt-4">
                                        <button id="cm-cancel" type="button" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition focus:outline-none">Cancelar</button>
                                        <button id="cm-ok" class="px-4 py-2 rounded-lg bg-blue-500 font-semibold hover:bg-blue-600 transition focus:outline-none">Agregar</button>
                                    </div>
                                </form>
                            </div>
                        `;
                        document.body.appendChild(overlay);
                        const ok = overlay.querySelector('#cm-ok');
                        const cancel = overlay.querySelector('#cm-cancel');

                        function escHandler(e) {
                            if (e.key === 'Escape') closeOverlay();
                        }

                        ok.addEventListener('click', function(e) {
                            e.preventDefault();
                            crearTarea();
                        });
                        cancel.addEventListener('click', closeOverlay);
                        overlay.addEventListener('click', (e) => {
                            if (e.target === overlay) closeOverlay();
                        });
                        escHandlerRef = escHandler;
                        document.addEventListener('keydown', escHandlerRef);
                    });

                    // Body
                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';
                    data.estudiantes.forEach(est => {
                        const tr = document.createElement('tr');
                        tr.className = 'border-b border-white/10';
                        let innerHTML = `<td class="px-6 py-4 font-semibold align-middle">${est.nombre} ${est.apellido}</td>`;
                        tr.innerHTML = innerHTML;
                        tbody.appendChild(tr);
                    });
                };
            });
    };

    const filtros = document.querySelectorAll('#grado, #seccion, #materia, #periodo');
    filtros.forEach(filtro => {
        filtro.addEventListener('change', updateTable);
    });

    setTimeout(() => {
        updateTable();
    }, 100);
});

