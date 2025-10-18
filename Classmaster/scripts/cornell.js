import { toast, confirmModal } from './components.js';

document.addEventListener('DOMContentLoaded', () => {
    // Event delegation for tab buttons
    const tabButtons = document.getElementById('cornellTabButtons');
    if (tabButtons) {
        tabButtons.addEventListener('click', (e) => {
            if (e.target.tagName === 'BUTTON') {
                const text = e.target.textContent.trim();
                if (text.includes('Nueva Nota')) {
                    showCreateNote();
                } else if (text.includes('Ver Todas')) {
                    showAllNotes();
                };
            };
        });
    }

    // Event delegation for form buttons
    const formButtons = document.getElementById('cornellFormButtons');
    if (formButtons) {
        formButtons.addEventListener('click', (e) => {
            if (e.target.tagName === 'BUTTON') {
                const text = e.target.textContent.trim();
                if (text.includes('Guardar')) {
                    saveNote();
                } else if (text.includes('Cancelar')) {
                    cancelNote();
                };
            };
        });
    }
    let notes = [];
    let isEditing = false;
    let editingId = null;

    // small helper to avoid injecting raw HTML
    function escapeHtml(text) {
        if (!text && text !== 0) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

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
            toast('Por favor, ingresa un título para la nota', 'error');
            return;
        }
        if (!cues && !notesContent && !summary) {
            toast('Por favor, completa al menos una sección de la nota', 'error');
            return;
        }

        const data = new FormData();
        data.append('title', title);
        data.append('cues', cues);
        data.append('notes', notesContent);
        data.append('summary', summary);
        if (!isEditing) {
            data.append('action', 'create');
        } else {
            data.append('action', 'update');
            data.append('id', editingId);
        }

        fetch('../php/cornell.php', { method: 'POST', body: data })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                toast('Nota guardada correctamente', 'success');
                // Refresh list from server to keep canonical
                updateNotesList();
                showAllNotes();
            } else {
                toast('Error al guardar la nota: ' + (result.error || 'error desconocido'), 'error');
            }
        })
    };

    // Cancelar creación/edición
    function cancelNote() {
        confirmModal({
            titulo: 'Cancelar',
            descripcion: '¿Estás seguro de que quieres cancelar? Se perderán los cambios no guardados.',
            confirmarTxt: 'Sí, cancelar',
            cancelarTxt: 'No',
            onConfirm: showAllNotes
        });
    };

    // Actualizar lista de notas
    function updateNotesList() {
        fetch('../php/cornell.php', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                toast('Error al cargar las notas', 'error');
                return;
            }

            notes = data.notes.map(n => ({
                id: n.id,
                title: n.titulo,
                cues: n.claves,
                notes: n.principal,
                summary: n.resumen,
                created: n.creada,
                modified: n.modificada
            }));

            const container = document.getElementById('notes-container');
            if (!container) return;

            if (notes.length === 0) {
                container.innerHTML = `
                    <div class="empty-state text-center">
                        <h3 class="text-lg font-semibold text-accent mb-2">No tienes notas guardadas</h3>
                        <p class="text-white/60">Haz clic en \"Nueva Nota\" para crear tu primera nota usando el método Cornell</p>
                    </div>
                `;
                updateNotesCount();
                return;
            }

            container.innerHTML = notes.map(note => {
                const created = note.created ? new Date(note.created).toLocaleDateString() : '';
                const modified = note.modified ? new Date(note.modified).toLocaleDateString() : created;
                return `
                    <div class="note-card bg-white/10 rounded-xl p-4 mb-4 ring-soft border border-white/10 animate-fadeInUp">
                        <div class="note-card-header">
                            <div>
                                <div class="note-title">${escapeHtml(note.title)}</div>
                                <div class="note-date">
                                    Creada: ${created}
                                    ${modified !== created ? ` • Modificada: ${modified}` : ''}
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
                `;
            }).join('');

            updateNotesCount();
        })
        .catch(err => {
            console.error('Error fetching notes:', err);
            toast('Error al cargar las notas', 'error');
        });
    }

    // Eliminar nota
    window.deleteNote = function(id) {
        confirmModal({
            titulo: 'Eliminar nota',
            descripcion: '¿Estás seguro de que quieres eliminar esta nota? Esta acción no se puede deshacer.',
            confirmarTxt: 'Sí, eliminar',
            cancelarTxt: 'No',
            onConfirm: () => {
            fetch('../php/cornell.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'delete', id })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                updateNotesList();
                updateNotesCount();
                toast('Nota eliminada correctamente', 'success');
                } else {
                toast('Error al eliminar la nota: ' + (data.error || 'error desconocido'), 'error');
                }
            }).catch(err => {
                console.error('Error deleting note:', err);
                toast('Error al eliminar la nota', 'error');
            });
            }
        });
        return;
    };

    // expose editNote globally for inline onclick handlers
    window.editNote = function(id) {
        // fetch the single note from server (or reuse cached notes array)
        const found = notes.find(n => Number(n.id) === Number(id));
        if (found) {
            isEditing = true; editingId = id;
            document.getElementById('note-title').value = found.title || '';
            document.getElementById('cues-area').value = found.cues || '';
            document.getElementById('notes-area').value = found.notes || '';
            document.getElementById('summary-area').value = found.summary || '';
            showCreateNote();
            return;
        }
        // fallback: reload list then populate
        fetch('../php/cornell.php').then(r => r.json()).then(data => {
            if (data.success) {
                notes = data.notes.map(n => ({ id: n.id, title: n.titulo, cues: n.claves, notes: n.principal, summary: n.resumen, created: n.creada, modified: n.modificada }));
                const note = notes.find(n => Number(n.id) === Number(id));
                if (note) {
                    isEditing = true; editingId = id;
                    document.getElementById('note-title').value = note.title || '';
                    document.getElementById('cues-area').value = note.cues || '';
                    document.getElementById('notes-area').value = note.notes || '';
                    document.getElementById('summary-area').value = note.summary || '';
                    showCreateNote();
                }
            }
        });
    };

    // Inicializar la aplicación
    function updateNotesCount() {
        // optional: display count somewhere; fallback no-op
        const countEl = document.getElementById('notes-count');
        if (!countEl) return;
        countEl.textContent = notes.length;
    }
    updateNotesList();
    showAllNotes();

    // no autosave/draft logic for now (DB-backed). If you want drafts, we can implement server-side drafts.
});