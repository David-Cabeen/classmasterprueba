
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

// Fetch materias and grados for teachers
document.addEventListener('DOMContentLoaded', function() {
    if (window.rol === 'profesor') {
        console.log('hello')
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
                    }
                }
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
                }
            })
    }
});