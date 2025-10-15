import { confirmModal, toast } from './components.js'

// Rebuilt notas.js: consistent, renders inputs per (student,tarea) and saves via update_nota.php
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
        // include active filters so the server can resolve the curso robustly
        const materia = document.getElementById('materia')?.value || '';
        const grado = document.getElementById('grado')?.value || '';
        const seccion = document.getElementById('seccion')?.value || '';
        if (!materia || !grado || !seccion) {
            toast('Seleccione materia, grado y sección antes de crear una tarea', 'error');
            return;
        }
        const curso = `${materia} ${grado}${seccion}`.trim();

        const data = new FormData();
        // send both composed curso_id and explicit fields
        data.append('curso_id', curso);
        data.append('materia', materia);
        data.append('grado', grado);
        data.append('seccion', seccion);
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

    async function saveNota(tarea_id, estudiante_id, valor, inputEl) {
        const data = new FormData();
        data.append('tarea_id', tarea_id);
        data.append('estudiante_id', estudiante_id);
        data.append('valor', valor);

        inputEl.disabled = true;
        try {
            const res = await fetch('../php/update_nota.php', { method: 'POST', body: data });
            const result = await res.json();
            if (result.success) {
                // Actualizamos el modelo en memoria y el promedio en pantalla
                toast(result.message || 'Nota actualizada correctamente', 'success');
                if (window._notasData && Array.isArray(window._notasData.estudiantes)) {
                    const est = window._notasData.estudiantes.find(e => String(e.id) === String(estudiante_id));
                    if (est) {
                        if (!est.notas) est.notas = {};
                        est.notas[tarea_id] = parseFloat(valor);
                    }
                }
                // Recalcular promedio para este estudiante
                recomputePromedio(estudiante_id);
            } else {
                toast(result.message || result.error || 'Error al guardar la nota', 'error');
            }
        } catch (err) {
            console.error(err);
            toast('Error de red al guardar la nota: ' + err, 'error');
        } finally {
            inputEl.disabled = false;
        }
    }

    // Recalcula el promedio ponderado de un estudiante y actualiza la celda correspondiente
    function recomputePromedio(estudiante_id) {
        if (!window._notasData) return;
        const tareas = window._notasData.tareas || [];
        const estudiantes = window._notasData.estudiantes || [];
        const est = estudiantes.find(e => String(e.id) === String(estudiante_id));
        if (!est) return;

        let weightedSum = 0.0;
        let weightTotal = 0.0;
        tareas.forEach(t => {
            const nota = (est.notas && typeof est.notas[t.id] !== 'undefined' && est.notas[t.id] !== null) ? parseFloat(est.notas[t.id]) : null;
            const peso = parseFloat(t.porcentaje) || 0;
            if (nota !== null && !isNaN(nota)) {
                weightedSum += nota * (peso / 100.0);
                weightTotal += peso;
            }
        });
        let promedio = 0.0;
        if (weightTotal > 0) {
            promedio = (weightedSum / (weightTotal / 100.0));
        } else {
            promedio = 0.0;
        }
        const tbody = table.querySelector('tbody');
        const row = tbody.querySelector(`tr[data-estudiante-id="${estudiante_id}"]`);
        if (row) {
            const promTd = row.querySelector('td[data-promedio-for]');
            if (promTd) promTd.textContent = (isNaN(promedio) ? '0.00' : Number(promedio).toFixed(2));
        }
    }

    function attachInputHandlers(input, tarea_id, estudiante_id) {
        let original = input.value;
        input.addEventListener('blur', function() {
            const val = input.value.trim();
            if (val === original) return;
            const num = parseFloat(val.replace(',', '.'));
            if (isNaN(num)) { toast('Valor inválido', 'error'); input.value = original; return; }
            original = input.value = num.toFixed(2);
            saveNota(tarea_id, estudiante_id, input.value, input);
        });
        input.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); input.blur(); } });
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

        // Añadir columna de promedio (no editable) antes del botón de crear tarea
        const promTh = document.createElement('th');
        promTh.className = 'px-6 py-4 font-semibold align-middle';
        promTh.textContent = 'Promedio';
        promTh.dataset.promedio = 'true';
        tr.appendChild(promTh);

        // Only show the crear-tarea button for professors
        if (window.rol === 'profesor') {
            const btnTh = document.createElement('th');
            btnTh.className = 'px-6 py-4 align-middle';
            const btn = document.createElement('button');
            btn.className = 'px-4 py-2 rounded-lg bg-green-500 text-white font-semibold hover:bg-green-600 transition-colors duration-200';
            btn.textContent = '+';
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const existing = document.querySelector('.cm-overlay'); if (existing) closeOverlay();
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
                const ok = overlay.querySelector('#cm-ok'); const cancel = overlay.querySelector('#cm-cancel');
                function escHandler(e){ if(e.key === 'Escape') closeOverlay(); }
                escHandlerRef = escHandler; document.addEventListener('keydown', escHandlerRef);
                ok.addEventListener('click', function(e){ e.preventDefault(); crearTarea(); });
                cancel.addEventListener('click', closeOverlay);
                overlay.addEventListener('click', (e) => { if (e.target === overlay) closeOverlay(); });
            });
            btnTh.appendChild(btn); tr.appendChild(btnTh);
        }

        tr.onclick = function(e){
            // only professors can open the editar overlay
            if (window.rol !== 'profesor') return;
            const th = e.target.closest('th'); if(!th) return; const tareaIdClicked = th.dataset.tareaId; if(!tareaIdClicked) return;
            const tarea = (window._notasData && window._notasData.tareas) ? window._notasData.tareas.find(t => String(t.id) === String(tareaIdClicked)) : null; if (!tarea) return;
            const existing = document.querySelector('.cm-overlay'); if(existing) closeOverlay();
            overlay = document.createElement('div'); overlay.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-4 z-50 cm-overlay';
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
            tareaId = tarea.id; document.body.appendChild(overlay);
            const ok = overlay.querySelector('#cm-ok'); const cancel = overlay.querySelector('#cm-cancel'); const del = overlay.querySelector('#cm-delete');
            function escHandler(e){ if(e.key === 'Escape') closeOverlay(); }
            escHandlerRef = escHandler; document.addEventListener('keydown', escHandlerRef);
            del.addEventListener('click', function(){ confirmModal({ titulo: 'Eliminar tarea', descripcion: '¿Estás seguro de que deseas eliminar esta tarea? Esta acción no se puede deshacer.', onConfirm: function(){ eliminarTarea(tareaId); closeOverlay(); } }); });
            ok.addEventListener('click', function(e){ e.preventDefault(); editarTarea(); });
            cancel.addEventListener('click', closeOverlay);
            overlay.addEventListener('click', (e)=>{ if (e.target === overlay) closeOverlay(); });
        };
    }

    function buildBody(estudiantes, tareas){
        const tbody = table.querySelector('tbody'); tbody.innerHTML = '';
        estudiantes.forEach(est => {
            const tr = document.createElement('tr'); tr.className = 'border-b border-white/10';
            // tag row with estudiante id so recomputePromedio can update the Promedio cell without reloading whole table
            tr.setAttribute('data-estudiante-id', String(est.id));
            const nameTd = document.createElement('td'); nameTd.className = 'px-6 py-4 font-semibold align-middle'; nameTd.textContent = `${est.nombre} ${est.apellido}`; tr.appendChild(nameTd);
            // Por cada tarea añadimos una celda; si el rol es profesor, es un input editable, sino es texto sólo lectura
            tareas.forEach(t => {
                const td = document.createElement('td'); td.className = 'px-6 py-4 align-middle';
                const notaVal = (est.notas && typeof est.notas[t.id] !== 'undefined' && est.notas[t.id] !== null) ? est.notas[t.id] : 3.20;
                const displayVal = (typeof notaVal === 'number') ? Number(notaVal).toFixed(2) : parseFloat(notaVal || 3.2).toFixed(2);
                if (window.rol === 'profesor') {
                    const input = document.createElement('input'); input.type = 'number'; input.step = '0.01'; input.className = 'w-full bg-transparent border border-white/10 rounded px-2 py-1 text-right';
                    input.value = displayVal;
                    td.appendChild(input);
                    attachInputHandlers(input, t.id, est.id);
                } else {
                    const span = document.createElement('div'); span.className = 'text-right'; span.textContent = displayVal; td.appendChild(span);
                }
                tr.appendChild(td);
            });

            // Calcular el promedio ponderado para este estudiante
            // Lógica: para cada tarea considérese (nota * (porcentaje / 100)).
            // Si la suma de porcentajes es menor a 100, se normaliza dividiendo por la suma de porcentajes (para evitar penalizar cuando no hay 100%).
            const promTd = document.createElement('td'); promTd.className = 'px-6 py-4 align-middle font-medium text-right';
            // calcular suma ponderada y suma de pesos
            let weightedSum = 0.0;
            let weightTotal = 0.0;
            tareas.forEach(t => {
                const nota = (est.notas && typeof est.notas[t.id] !== 'undefined' && est.notas[t.id] !== null) ? parseFloat(est.notas[t.id]) : null;
                const peso = parseFloat(t.porcentaje) || 0;
                if (nota !== null && !isNaN(nota)) {
                    weightedSum += nota * (peso / 100.0);
                    weightTotal += peso;
                }
            });
            let promedio = 0.0;
            if (weightTotal > 0) {
                // normalizar si la suma de pesos no es 100
                promedio = (weightedSum / (weightTotal / 100.0));
            } else {
                promedio = 0.0;
            }
            promTd.textContent = (isNaN(promedio) ? '0.00' : Number(promedio).toFixed(2));
            // mark the promedio cell so it can be found by recomputePromedio
            promTd.setAttribute('data-promedio-for', String(est.id));
            tr.appendChild(promTd);
            tbody.appendChild(tr);
        });
    }

    let _updateRetries = 0;
    const _updateMaxRetries = 30; // try for ~6 seconds (30 * 200ms)

    function ensureDefaultFilters() {
        ['grado','materia','seccion','periodo'].forEach(id => {
            const sel = document.getElementById(id);
            if (!sel) return;
            if (!sel.value) {
                const opt = Array.from(sel.options).find(o => o.value && o.value.trim() !== '');
                if (opt) sel.value = opt.value;
            }
        });
    }

    // Fetch filter options from server and populate selects
    async function loadFilterOptions() {
        try {
            // If the logged role is 'acudiente' include estudiante_id so server returns only relevant materias
            let url = '../php/notas_filtros.php';
            if (window.rol === 'acudiente') {
                const estSelect = document.getElementById('estudiante');
                const estId = estSelect?.value || '';
                if (estId) url += '?estudiante_id=' + encodeURIComponent(estId);
            }

            const res = await fetch(url);
            const data = await res.json();
            if (!data.success) {
                // no filters available; return
                console.warn('no filter data', data);
                return { hasGrados: false, hasMaterias: false };
            }

            // populate grados if provided
            let hasGrados = false;
            if (Array.isArray(data.grados) && data.grados.length > 0) {
                const gradoSel = document.getElementById('grado');
                if (gradoSel) {
                    gradoSel.innerHTML = ''; // clear
                    data.grados.forEach(g => {
                        const o = document.createElement('option'); o.value = g; o.textContent = g; gradoSel.appendChild(o);
                    });
                    hasGrados = true;
                }
            }

            // populate materias if provided
            let hasMaterias = false;
            if (Array.isArray(data.materias) && data.materias.length > 0) {
                const matSel = document.getElementById('materia');
                if (matSel) {
                    matSel.innerHTML = '';
                    data.materias.forEach(m => {
                        const o = document.createElement('option'); o.value = m; o.textContent = m; matSel.appendChild(o);
                    });
                    hasMaterias = true;
                }
            }
            // if the endpoint returned estudiantes (for acudiente), populate the selector
                    if (Array.isArray(data.estudiantes) && data.estudiantes.length > 0) {
                        const estSel = document.getElementById('estudiante');
                        if (estSel) {
                            // preserve current value so we can restore selection after rebuilding options
                            const curVal = estSel.value;
                            estSel.innerHTML = '';
                            data.estudiantes.forEach(s => {
                                const o = document.createElement('option'); o.value = s.id; o.textContent = s.nombre; estSel.appendChild(o);
                            });
                            // Restore previous selection if still present, otherwise default to first
                            if (curVal) {
                                const exists = Array.from(estSel.options).some(o => o.value === curVal);
                                if (exists) estSel.value = curVal; else estSel.selectedIndex = 0;
                            } else {
                                estSel.selectedIndex = 0;
                            }
                            // debug: list options and current selection
                            try { console.debug('estudiantes populated', Array.from(estSel.options).map(o => ({value:o.value, text:o.text})), 'selected', estSel.value); } catch(e){}
                        }
                    }
            return { hasGrados, hasMaterias };
        } catch (err) {
            console.error('Error loading filter options', err);
            return { hasGrados: false, hasMaterias: false };
        }
    }

    function updateTable(){
        // If filter selects are provided by other scripts asynchronously, wait until they have options
        const gradoSel = document.getElementById('grado');
        const materiaSel = document.getElementById('materia');
        const seccionSel = document.getElementById('seccion');
        // If any select exists but has no options yet, retry shortly
        const selectsToCheck = [gradoSel, materiaSel, seccionSel].filter(Boolean);
        const needsRetry = selectsToCheck.some(s => s.options.length === 0);
        if (needsRetry && _updateRetries < _updateMaxRetries) {
            _updateRetries++;
            setTimeout(updateTable, 200);
            return;
        }
        // reset retry counter when proceeding
        _updateRetries = 0;

        // Ensure sensible defaults if selects are present but have no value
        ensureDefaultFilters();

        const filtros = { grado: gradoSel?.value || '', seccion: seccionSel?.value || '', materia: materiaSel?.value || '', periodo: document.getElementById('periodo')?.value || '' };
        const params = new URLSearchParams(filtros);
        // if role is estudiante, always request only their own id
        if (window.rol === 'estudiante') {
            params.append('estudiante_id', String(window.user_id || ''));
        } else if (window.rol === 'acudiente') {
            const estSelect = document.getElementById('estudiante'); if (estSelect && estSelect.value) params.append('estudiante_id', estSelect.value);
        }
        fetch(`../php/notas_table.php?${params.toString()}`).then(res => res.json()).then(data => { if(!data.success){ toast(data.error || 'Error al cargar notas','error'); return; } window._notasData = data; buildHeader(data.tareas || []); buildBody(data.estudiantes || [], data.tareas || []); }).catch(err => { console.error(err); toast('Error de red al cargar la tabla: ' + err, 'error'); });
    }

    // attach filtros
    const filtros = document.querySelectorAll('#grado, #seccion, #materia, #periodo'); filtros.forEach(f => f.addEventListener('change', updateTable));
    const estSelect = document.getElementById('estudiante');
    if (estSelect) {
        estSelect.addEventListener('change', function() {
            try { console.debug('estudiante changed to', estSelect.value); } catch(e){}
            // When parent selects a different student, reload filter options (materias/grados)
            loadFilterOptions().then(() => {
                // ensure sensible defaults then reload table
                ensureDefaultFilters();
                updateTable();
            });
        });
    }
    // load filter options (async) then pick defaults and load the table
    loadFilterOptions().then((res) => {
        // If profesor and filters empty, tell user to set materias/grados in profile instead of requesting notas
        if (window.rol === 'profesor') {
            const hasGrados = res && res.hasGrados;
            const hasMaterias = res && res.hasMaterias;
            if (!hasGrados || !hasMaterias) {
                toast('No se encontraron grados o materias. Verifique su perfil en la sección de perfil y asigne materias/grados.', 'error');
                return;
            }
        }
        ensureDefaultFilters();
        setTimeout(updateTable, 100);
    }).catch(() => {
        // if loading filters fails, still attempt to load the table with whatever is present
        ensureDefaultFilters();
        setTimeout(updateTable, 100);
    });
});

