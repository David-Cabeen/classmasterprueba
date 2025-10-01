import { toast } from './components.js'

document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('table');
    let btn = null,
    overlay;

    // Opciones de los filtros
    if (window.rol === 'profesor') {
        btn = document.createElement('button');
        btn.className = 'px-4 py-2 rounded-lg bg-green-500 text-white font-semibold hover:bg-green-600 transition-colors duration-200';
        btn.textContent = 'Crear nueva tarea';
        table.querySelector('tr').append(btn)
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

    function crearTarea(){
        const data = new FormData();
        data.append('curso_id', document.getElementById('curso'));
        data.append('periodo', document.getElementById('periodo').value);
        data.append('titulo', overlay.querySelector('#tarea-input').value);
        data.append('descripcion', overlay.querySelector('#tarea-descripcion').value);
        data.append('porcentaje', overlay.querySelector('#porcentaje').value);
        const res = fetch('../php/crear_tarea.php', { method: 'POST', body: formData });
        toast(res.message)
    };

    if(btn !== null){
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            overlay = document.createElement("div");
            overlay.className = "fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-4 z-50";
            overlay.innerHTML = `
                <div class="crear-tarea w-full sm:max-w-md rounded-2xl border border-white/10 p-6 animate-in" role="dialog" aria-modal="true">
                    <h1 class="text-xl font-semibold text-white mb-4">Agregar tarea</h1>
                    <form id="event-form" class="flex flex-col gap-4">
                        <input type="text" id="tarea-input" placeholder="Título" required class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                        <textarea name="tarea-description" placeholder="Descripción" id="event-description" class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-base placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition resize-none min-h-[60px]"></textarea>
                        <input type="number" id="porcentaje" placeholder="Porcentaje" required class="bg-white/10 border border-white/10 rounded-lg px-4 py-2 text-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                        <div class="flex gap-3 justify-end mt-4">
                            <button id="cm-cancel" type="button" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition focus:outline-none">Cancelar</button>
                            <button id="cm-ok" type="submit" class="px-4 py-2 rounded-lg bg-blue-500 font-semibold hover:bg-blue-600 transition focus:outline-none">Agregar</button>
                        </div>
                    </form>
                </div>
                `;
            document.body.appendChild(overlay)
            const ok = overlay.querySelector("#cm-ok");
            const cancel = overlay.querySelector("#cm-cancel");

            const close = () => overlay.remove();
            ok.addEventListener("click", crearTarea);
            cancel.addEventListener("click", close);
            overlay.addEventListener("click", (e) => {
                if (e.target === overlay) close();
            });
            document.addEventListener("keydown", function esc(e) {
                if (e.key === "Escape") {
                    close();
                    document.removeEventListener("keydown", esc);
                }
            });
        });
    }
}); 