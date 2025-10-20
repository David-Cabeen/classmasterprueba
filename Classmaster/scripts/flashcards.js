import { toast, confirmModal } from './components.js';

document.addEventListener('DOMContentLoaded', () => {
    // Estado global de la aplicación de flashcards
    // Almacena la colección de tarjetas y controla el índice de la actual
    let flashcards = [];              // Colección de tarjetas
    let currentCardIndex = 0;         // Índice de la tarjeta actualmente mostrada
    
    // Obtener referencia al contenedor de botones de pestañas
    const tabButtons = document.getElementById('tabButtons');

    tabButtons.addEventListener('click', (e) => {
        if (e.target.classList.contains('tab')) {
            const tabName = e.target.textContent.includes('Crear') ? 'creator' : e.target.textContent.includes('Estudiar') ? 'study' : 'manage';
            switchTab(tabName, e.target);
        }
    });

    // Gestiona el cambio entre pestañas (Crear, Estudiar, Gestionar)
    // Actualiza la interfaz y ejecuta acciones específicas por pestaña
    function switchTab(tabName, tabElement) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        if (tabElement) tabElement.classList.add('active');
        document.getElementById(tabName).classList.add('active');
        if (tabName === 'study') {
            updateStudyArea();
        } else if (tabName === 'manage') {
            updateFlashcardList();
        }
    }

    // Maneja la creación de nuevas flashcards
    // Procesa el formulario, envía los datos al servidor y actualiza el array local
    document.getElementById('flashcard-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const question = document.getElementById('question').value.trim();
        const answer = document.getElementById('answer').value.trim();
        if (question && answer) {
            const response = await fetch('../php/flashcards.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `question=${encodeURIComponent(question)}&answer=${encodeURIComponent(answer)}&action=create`
            });
            const data = await response.json();
        if (data.success) {
            // Añadir la nueva tarjeta al array local (al inicio)
            flashcards.unshift({ id: data.id, question, answer, creada: new Date().toISOString().slice(0, 10) });
                updateFlashcardList();
                toast("Flashcard creada exitosamente.", "success");
            } else {
                toast("Error al crear la flashcard.", "error");
            }
            document.getElementById('question').value = '';
            document.getElementById('answer').value = '';
        }
    });

    // Actualiza la interfaz del área de estudio
    // Muestra la flashcard actual, gestiona estado vacío y configura controles de navegación
    function updateStudyArea() {
        const studyArea = document.getElementById('study-area');
        const counter = document.getElementById('study-counter');
        if (flashcards.length === 0) {
            studyArea.innerHTML = `
                <div class="empty-state">
                    <h3>No hay flashcards para estudiar</h3>
                    <p>Crea algunas flashcards primero en la sección \"Crear\"</p>
                </div>
            `;
            counter.textContent = 'Flashcard 0 de 0';
            return;
        }
        currentCardIndex = Math.max(0, Math.min(currentCardIndex, flashcards.length - 1));
        const currentCard = flashcards[currentCardIndex];
        studyArea.innerHTML = `
        <ion-icon id="prevCardBtn" class='hover:text-blue-500 cursor-pointer' name="chevron-back-outline"></ion-icon>
            <div class="flashcard" id="current-flashcard">
                <div class="flashcard-inner">
                    <div class="flashcard-front">
                        <div>${currentCard.question}</div>
                    </div>
                    <div class="flashcard-back">
                        <div>${currentCard.answer}</div>
                    </div>
                </div>
            </div>
        <ion-icon id="nextCardBtn" class='hover:text-blue-500 cursor-pointer' name="chevron-forward-outline"></ion-icon>
        `;
        counter.textContent = `Flashcard ${currentCardIndex + 1} de ${flashcards.length}`;
        // Agrega listeners para navegación y volteo
        document.getElementById('current-flashcard').addEventListener('click', flipCard);
        document.getElementById('prevCardBtn').addEventListener('click', previousCard);
        document.getElementById('nextCardBtn').addEventListener('click', nextCard);
    }

    // Maneja la animación de volteo de la flashcard actual
    // Alterna entre pregunta y respuesta
    function flipCard() {
        const flashcard = document.getElementById('current-flashcard');
        if (flashcard) {
            flashcard.classList.toggle('flipped');
        }
    }

    // Funciones de navegación entre flashcards
    // Avanza o retrocede según el control invocado
    function nextCard() {
        if (flashcards.length > 0) {
            currentCardIndex = (currentCardIndex + 1) % flashcards.length;
            updateStudyArea();
        }
    }

    function previousCard() {
        if (flashcards.length > 0) {
            currentCardIndex = currentCardIndex === 0 ? flashcards.length - 1 : currentCardIndex - 1;
            updateStudyArea();
        }
    }

    // Actualiza la vista de lista de todas las flashcards
    // Muestra cada tarjeta con detalles y botones de acción
    function updateFlashcardList() {
        const listContent = document.getElementById('flashcard-list-content');
        if (flashcards.length === 0) {
            listContent.innerHTML = `
                <div class="empty-state">
                    <h3>No tienes flashcards creadas</h3>
                    <p>Ve a la sección \"Crear\" para agregar tu primera flashcard</p>
                </div>
            `;
            return;
        }
        listContent.innerHTML = flashcards.map(card => `
            <div class="flashcard-list-item flex items-center justify-between bg-black/20 rounded-lg p-4 mb-2">
                <div>
                    <div class="font-semibold text-accent">${card.question}</div>
                    <div class="text-white/60 mt-1">${card.answer}</div>
                    <div class="text-xs text-white/30 mt-1">Creada: ${card.creada || ''}</div>
                </div>
                <button class="delete-btn text-red-500 hover:text-red-700 font-bold ml-4" data-id="${card.id}">Eliminar</button>
            </div>
        `).join('');
        // Agregar listeners de eliminación
        listContent.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteFlashcard(this.getAttribute('data-id'));
            });
        });
    }

    // Maneja la eliminación de una flashcard específica
    // Muestra confirmación, borra en el servidor y actualiza la interfaz
    async function deleteFlashcard(id) {
        confirmModal({'titulo':'Confirmar Eliminación','descripcion':'¿Estás seguro de que quieres eliminar esta flashcard?',
            onConfirm: async function() {
                const response = await fetch('../php/flashcards.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}&action=delete`
                });
                const data = await response.json();
                if (data.success) {
                    flashcards = flashcards.filter(card => card.id != id);
                    if (currentCardIndex >= flashcards.length) {
                        currentCardIndex = Math.max(0, flashcards.length - 1);
                    };
                    updateFlashcardList();
                    updateStudyArea();
                    toast("Flashcard eliminada exitosamente.", "success");
                } else {
                    toast("Error al eliminar la flashcard.", "error");
                };
            }
        });
    };

    // Configura la navegación mediante teclado
    // Flechas izquierda/derecha para navegar; espacio para voltear
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('study').classList.contains('active')) {
            if (e.key === 'ArrowLeft') {
                previousCard();
            } else if (e.key === 'ArrowRight') {
                nextCard();
            } else if (e.key === ' ') {
                e.preventDefault();
                flipCard();
            }
        }
    });

    // Inicializa la aplicación de flashcards
    // Carga las tarjetas desde el servidor, las mapea y actualiza la UI
    async function init() {
        const response = await fetch('../php/flashcards.php', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });
        let data = await response.json();
        // Map backend fields to frontend
        flashcards = data.map(card => ({
            id: card.id,
            question: card.pregunta,
            answer: card.respuesta,
            creada: card.creada
        }));
        updateFlashcardList();
        updateStudyArea();
    }
    init();
});
