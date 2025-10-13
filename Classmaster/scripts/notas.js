import { confirmModal, toast } from './components.js'

// notas.js - handles rendering the notas table, creating/editing/deleting tareas,
// and updating individual nota values inline.

document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('table');
    let overlay = null;
    let tareaId = null;
    let escHandlerRef = null;

    function closeOverlay() {
        if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
        if (escHandlerRef) {
            document.removeEventListener('keydown', escHandlerRef);
            escHandlerRef = null;
        }
        overlay = null;
    }

    async function crearTarea() {
        const materia = document.getElementById('materia')?.value || '';
        const grado = document.getElementById('grado')?.value || '';
        const seccion = document.getElementById('seccion')?.value || '';
        const curso = `${materia} ${grado}${seccion}`.trim();

        const data = new FormData();
        data.append('curso_id', curso);
        data.append('periodo', document.getElementById('periodo')?.value || '');
        data.append('titulo', overlay.querySelector('#tarea-input').value);
        data.append('descripcion', overlay.querySelector('#event-description').value);
        data.append('porcentaje', overlay.querySelector('#porcentaje').value);

        try {
            const res = await fetch('../php/crear_tarea.php', { method: 'POST', body: data });
            const result = await res.json();
            toast(result.message || result.error, result.success ? 'success' : 'error');
            if (result.success) {
                closeOverlay();
                updateTable();
            }
        } catch (err) {
            console.error(err);
            toast('Error de red al crear la tarea: ' + err, 'error');
        }
    }

    async function editarTarea() {
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
            if (result.success) {
                closeOverlay();
                updateTable();
            }
        } catch (err) {
            console.error(err);
            toast('Error de red al editar la tarea: ' + err, 'error');
        }
    }

    async function eliminarTarea(id) {
        const data = new FormData();
        data.append('action', 'eliminar');
        data.append('id', id);
        try {
            const res = await fetch('../php/editar_tarea.php', { method: 'POST', body: data });
            const result = await res.json();
            toast(result.message || result.error, result.success ? 'success' : 'error');
            if (result.success) updateTable();
        } catch (err) {
            console.error(err);
            toast('Error de red al eliminar la tarea: ' + err, 'error');
        }
    }

    // Save a single nota (valor) for tarea_id and estudiante_id
    async function saveNota(tarea_id, estudiante_id, valor, inputEl) {
        const data = new FormData();
        data.append('tarea_id', tarea_id);
        data.append('estudiante_id', estudiante_id);
        data.append('valor', valor);

        // UI: disable while saving
        inputEl.disabled = true;
        try {
            const res = await fetch('../php/update_nota.php', { method: 'POST', body: data });
            const result = await res.json();
            if (result.success) {
                toast(result.message || 'Valor guardado', 'success');
            } else {
                toast(result.message || result.error || 'Error al guardar', 'error');
            }
        } catch (err) {
            console.error(err);
            toast('Error de red al guardar: ' + err, 'error');
        } finally {
            inputEl.disabled = false;
        }
    }

    function attachInputHandlers(input, tarea_id, estudiante_id) {
        let original = input.value;
        // Save on blur
        input.addEventListener('blur', function() {
            const val = input.value.trim();
            if (val === original) return;
            // Basic numeric validation
            const num = parseFloat(val.replace(',', '.'));
            if (isNaN(num)) {
                toast('Valor inválido', 'error');
                input.value = original;
                return;
            }
            original = input.value = num.toFixed(2);
            saveNota(tarea_id, estudiante_id, input.value, input);
        });
        // Save on Enter
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                input.blur();
            }
        });
    }

    function buildHeader(tareas) {
        const tr = document.querySelector('#notas-header');
        tr.innerHTML = '';
        const nameTh = document.createElement('th');
        nameTh.className = 'px-6 py-4 font-semibold align-middle';
        nameTh.textContent = 'Nombre';
        tr.appendChild(nameTh);

        tareas.forEach(t => {
            const th = document.createElement('th');
            th.className = 'px-6 py-4 cursor-pointer font-semibold align-middle';
            th.textContent = `${t.titulo} (${t.porcentaje}%)`;
            th.dataset.tareaId = t.id;
            tr.appendChild(th);
        });

        // Add "Crear nueva tarea" button as a TH at the end
        const btnTh = document.createElement('th');
        btnTh.className = 'px-6 py-4 align-middle';
        const btn = document.createElement('button');
        btn.className = 'px-4 py-2 rounded-lg bg-green-500 text-white font-semibold hover:bg-green-600 transition-colors duration-200';
        btn.textContent = 'Crear nueva tarea';
        btn.addEventListener('click', (e) => {
            e.preventDefault();
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

            function escHandler(e) { if (e.key === 'Escape') closeOverlay(); }
            escHandlerRef = escHandler;
            document.addEventListener('keydown', escHandlerRef);

            ok.addEventListener('click', function(e) { e.preventDefault(); crearTarea(); });
            cancel.addEventListener('click', closeOverlay);
            overlay.addEventListener('click', (e) => { if (e.target === overlay) closeOverlay(); });
        });

        btnTh.appendChild(btn);
        tr.appendChild(btnTh);

        // Single delegated click handler to edit a tarea
        tr.onclick = function(e) {
            const th = e.target.closest('th');
            if (!th) return;
            const tareaIdClicked = th.dataset.tareaId;
            if (!tareaIdClicked) return;

            const tarea = (window._notasData && window._notasData.tareas) ? window._notasData.tareas.find(t => String(t.id) === String(tareaIdClicked)) : null;
            if (!tarea) return;

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
                </div>
            `;
            tareaId = tarea.id;
            document.body.appendChild(overlay);

            const ok = overlay.querySelector('#cm-ok');
            const cancel = overlay.querySelector('#cm-cancel');
            const del = overlay.querySelector('#cm-delete');

            function escHandler(e) { if (e.key === 'Escape') closeOverlay(); }
            escHandlerRef = escHandler;
            document.addEventListener('keydown', escHandlerRef);

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

            ok.addEventListener('click', function(e) { e.preventDefault(); editarTarea(); });
            cancel.addEventListener('click', closeOverlay);
            overlay.addEventListener('click', (e) => { if (e.target === overlay) closeOverlay(); });
        };
    }

    function buildBody(estudiantes, tareas) {
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';
        estudiantes.forEach(est => {
            const tr = document.createElement('tr');
            tr.className = 'border-b border-white/10';
            // First cell: student name
            const nameTd = document.createElement('td');
            nameTd.className = 'px-6 py-4 font-semibold align-middle';
            nameTd.textContent = `${est.nombre} ${est.apellido}`;
            tr.appendChild(nameTd);

            // For each tarea, create a TD with numeric input
            tareas.forEach(t => {
                const td = document.createElement('td');
                td.className = 'px-6 py-4 align-middle';
                const input = document.createElement('input');
                input.type = 'number';
                input.step = '0.01';
                input.className = 'w-full bg-transparent border border-white/10 rounded px-2 py-1 text-right';
                const notaVal = (est.notas && typeof est.notas[t.id] !== 'undefined') ? est.notas[t.id] : 3.2;
                // Ensure two decimals
                input.value = (typeof notaVal === 'number') ? Number(notaVal).toFixed(2) : parseFloat(notaVal || 3.2).toFixed(2);
                td.appendChild(input);
                tr.appendChild(td);

                attachInputHandlers(input, t.id, est.id);
            });

            tbody.appendChild(tr);
        });
    }

    function updateTable() {
        const filtros = {
            grado: document.getElementById('grado')?.value || '',
            seccion: document.getElementById('seccion')?.value || '',
            materia: document.getElementById('materia')?.value || '',
            periodo: document.getElementById('periodo')?.value || ''
        };
        const params = new URLSearchParams(filtros);
        if (window.rol === 'acudiente') {
            const estSelect = document.getElementById('estudiante');
            if (estSelect) params.append('estudiante_id', estSelect.value);
        }

        fetch(`../php/notas_table.php?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    toast(data.error || 'Error al cargar notas', 'error');
                    return;
                }
                // store for delegated handlers
                window._notasData = data;
                buildHeader(data.tareas || []);
                buildBody(data.estudiantes || [], data.tareas || []);
            })
            .catch(err => {
                console.error(err);
                toast('Error de red al cargar la tabla: ' + err, 'error');
            });
    }

    // attach filtros
    const filtros = document.querySelectorAll('#grado, #seccion, #materia, #periodo');
    filtros.forEach(f => f.addEventListener('change', updateTable));

    // If acogiente, grado/select change also affects
    const estSelect = document.getElementById('estudiante');
    if (estSelect) estSelect.addEventListener('change', updateTable);

    // initial load
    setTimeout(updateTable, 100);
});

