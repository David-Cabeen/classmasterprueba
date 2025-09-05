const search = document.getElementById('search');
search.addEventListener('input', function() {
    const filter = search.value.toLowerCase();
    const rows = document.querySelectorAll('tr');

    // Revisa cada fila de la tabla
    rows.forEach(row => {
        const studentName = row.cells[0].textContent.toLowerCase();
        if (studentName.includes(filter)) { //Si incluye el texto buscado, mostrar dicha fila
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});