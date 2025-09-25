let notes = JSON.parse(localStorage.getItem('cornell-notes')) || [];
let isEditing = false;
let editingId = null;

// Mostrar sección de crear nota
function showCreateNote() {
    document.getElementById('create-note-section').style.display = 'block';
    document.getElementById('notes-list-section').style.display = 'none';
    if (!isEditing) clearForm();
}

// Mostrar todas las notas
function showAllNotes() {
    document.getElementById('create-note-section').style.display = 'none';
    document.getElementById('notes-list-section').style.display = 'block';
    updateNotesList();
    isEditing = false;
    editingId = null;
}

// Limpiar formulario
function clearForm() {
    document.getElementById('note-title').value = '';
    document.getElementById('cues-area').value = '';
    document.getElementById('notes-area').value = '';
    document.getElementById('summary-area').value = '';
}

// Guardar nota (crear o editar)
function saveNote() {
    const title = document.getElementById('note-title').value.trim();
    const cues = document.getElementById('cues-area').value.trim();
    const notesContent = document.getElementById('notes-area').value.trim();
    const summary = document.getElementById('summary-area').value.trim();

    if (!title) {
        showMessage('Por favor, ingresa un título para la nota', 'error');
        return;
    }
    if (!cues && !notesContent && !summary) {
        showMessage('Por favor, completa al menos una sección de la nota', 'error');
        return;
    }

    const now = new Date().toLocaleDateString();

    if (isEditing && editingId !== null) {
        // Editar nota existente
        const idx = notes.findIndex(n => n.id === editingId);
        if (idx !== -1) {
            notes[idx] = {
                ...notes[idx],
                title,
                cues,
                notes: notesContent,
                summary,
                modified: now
            };
            showMessage('Nota actualizada correctamente ✅', 'success');
        }
    } else {
        // Crear nueva nota
        notes.push({
            id: Date.now(),
            title,
            cues,
            notes: notesContent,
            summary,
            created: now,
            modified: now
        });
        showMessage('Nota guardada correctamente ✅', 'success');
    }

    localStorage.setItem('cornell-notes', JSON.stringify(notes));
    updateNotesCount();
    showAllNotes();
}

// Cancelar creación/edición
function cancelNote() {
    if (confirm('¿Estás seguro de que quieres cancelar? Se perderán los cambios no guardados.')) {
        showAllNotes();
    }
}

// Actualizar lista de notas
function updateNotesList() {
    const container = document.getElementById('notes-container');
    if (notes.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <h3>No tienes notas guardadas</h3>
                <p>Haz clic en "Nueva Nota" para crear tu primera nota usando el método Cornell</p>
            </div>
        `;
        return;
    }
    container.innerHTML = notes.map(note => `
        <div class="note-card">
            <div class="note-card-header">
                <div>
                    <div class="note-title">${note.title}</div>
                    <div class="note-date">
                        Creada: ${note.created}
                        ${note.modified !== note.created ? ` • Modificada: ${note.modified}` : ''}
                    </div>
                </div>
                <div class="note-actions">
                    <button class="btn btn-small btn-secondary" onclick="editNote(${note.id})">
                        ✏️ Editar
                    </button>
                    <button class="btn btn-danger btn-small" onclick="deleteNote(${note.id})">
                        🗑️ Eliminar
                    </button>
                </div>
            </div>
            <div class="cornell-preview">
                ${note.cues ? `
                    <div class="preview-cues preview-section">
                        <h4>🔑 Palabras Clave</h4>
                        <div class="preview-content">${note.cues.replace(/\n/g, "<br>")}</div>
                    </div>
                ` : ''}
                ${note.notes ? `
                    <div class="preview-notes preview-section">
                        <h4>📝 Notas</h4>
                        <div class="preview-content">${note.notes.replace(/\n/g, "<br>")}</div>
                    </div>
                ` : ''}
                ${note.summary ? `
                    <div class="preview-summary preview-section">
                        <h4>📋 Resumen</h4>
                        <div class="preview-content">${note.summary.replace(/\n/g, "<br>")}</div>
                    </div>
                ` : ''}
            </div>
        </div>
    `).join('');
}

// Editar nota
function editNote(id) {
    const note = notes.find(n => n.id === id);
    if (!note) return;
    isEditing = true;
    editingId = id;
    document.getElementById('note-title').value = note.title;
    document.getElementById('cues-area').value = note.cues;
    document.getElementById('notes-area').value = note.notes;
    document.getElementById('summary-area').value = note.summary;
    showCreateNote();
}

// Eliminar nota
function deleteNote(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta nota? Esta acción no se puede deshacer.')) {
        notes = notes.filter(n => n.id !== id);
        localStorage.setItem('cornell-notes', JSON.stringify(notes));
        updateNotesList();
        updateNotesCount();
        showMessage('Nota eliminada correctamente ✅', 'success');
    }
}

// Limpiar todas las notas
function clearAllNotes() {
    if (notes.length === 0) {
        showMessage('No hay notas para eliminar', 'info');
        return;
    }
    if (confirm('¿Estás seguro de que quieres eliminar TODAS las notas? Esta acción no se puede deshacer.')) {
        notes = [];
        localStorage.setItem('cornell-notes', JSON.stringify(notes));
        updateNotesList();
        updateNotesCount();
        showMessage('Todas las notas han sido eliminadas ✅', 'success');
    }
}

// Actualizar contador de notas
function updateNotesCount() {
    const count = notes.length;
    document.getElementById('notes-count').textContent =
        count === 0 ? '0 notas guardadas' :
        count === 1 ? '1 nota guardada' :
        `${count} notas guardadas`;
}

// Mostrar mensaje
function showMessage(message, type = 'info') {
    const colors = {
        success: 'linear-gradient(135deg, #48bb78, #38a169)',
        error: 'linear-gradient(135deg, #f56565, #e53e3e)',
        info: 'linear-gradient(135deg, #4299e1, #3182ce)'
    };
    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type]};
        color: white;
        padding: 15px 25px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 1000;
        font-weight: 500;
        animation: slideInRight 0.5s ease-out;
        max-width: 300px;
    `;
    messageDiv.textContent = message;
    document.body.appendChild(messageDiv);
    setTimeout(() => {
        messageDiv.style.animation = 'slideOutRight 0.5s ease-out forwards';
        setTimeout(() => messageDiv.remove(), 500);
    }, 3000);
}

// Inicializar la aplicación
updateNotesCount();
showAllNotes();

// Guardar automáticamente cada 30 segundos si hay contenido
setInterval(() => {
    if (document.getElementById('create-note-section').style.display !== 'none') {
        const title = document.getElementById('note-title').value.trim();
        const cues = document.getElementById('cues-area').value.trim();
        const notesContent = document.getElementById('notes-area').value.trim();
        const summary = document.getElementById('summary-area').value.trim();
        if (title || cues || notesContent || summary) {
            localStorage.setItem('cornell-draft', JSON.stringify({
                title, cues, notes: notesContent, summary, timestamp: Date.now()
            }));
        }
    }
}, 30000);

// Recuperar borrador al cargar
window.addEventListener('load', () => {
    const draft = localStorage.getItem('cornell-draft');
    if (draft) {
        const draftData = JSON.parse(draft);
        const timeDiff = Date.now() - draftData.timestamp;
        // Si el borrador es de menos de 1 hora
        if (timeDiff < 3600000) {
            if (confirm('Se encontró un borrador guardado. ¿Quieres recuperarlo?')) {
                document.getElementById('note-title').value = draftData.title || '';
                document.getElementById('cues-area').value = draftData.cues || '';
                document.getElementById('notes-area').value = draftData.notes || '';
                document.getElementById('summary-area').value = draftData.summary || '';
                showCreateNote();
            }
        }
        localStorage.removeItem('cornell-draft');
    }
});