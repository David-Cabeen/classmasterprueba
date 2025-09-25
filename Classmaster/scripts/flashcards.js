let flashcards = JSON.parse(localStorage.getItem('flashcards')) || [];
let currentCardIndex = 0;

// Cambiar entre pestañas
function switchTab(tabName) {
    // Remover clase active de todas las pestañas
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    // Activar pestaña seleccionada
    event.target.classList.add('active');
    document.getElementById(tabName).classList.add('active');
    
    // Actualizar contenido según la pestaña
    if (tabName === 'study') {
        updateStudyArea();
    } else if (tabName === 'manage') {
        updateFlashcardList();
    }
}

// Crear nueva flashcard
document.getElementById('flashcard-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const question = document.getElementById('question').value.trim();
    const answer = document.getElementById('answer').value.trim();
    
    if (question && answer) {
        const newFlashcard = {
            id: Date.now(),
            question: question,
            answer: answer,
            created: new Date().toLocaleDateString()
        };
        
        flashcards.push(newFlashcard);
        localStorage.setItem('flashcards', JSON.stringify(flashcards));
        
        // Limpiar formulario
        document.getElementById('question').value = '';
        document.getElementById('answer').value = '';
        
        // Mostrar mensaje de éxito
        showSuccessMessage('¡Flashcard creada exitosamente! 🎉');
    }
});

// Mostrar mensaje de éxito
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        padding: 15px 25px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(72, 187, 120, 0.3);
        z-index: 1000;
        font-weight: 500;
        animation: slideInRight 0.5s ease-out;
    `;
    successDiv.textContent = message;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.style.animation = 'slideOutRight 0.5s ease-out forwards';
        setTimeout(() => successDiv.remove(), 500);
    }, 3000);
}

// Actualizar área de estudio
function updateStudyArea() {
    const studyArea = document.getElementById('study-area');
    const counter = document.getElementById('study-counter');
    
    if (flashcards.length === 0) {
        studyArea.innerHTML = `
            <div class="empty-state">
                <h3>No hay flashcards para estudiar</h3>
                <p>Crea algunas flashcards primero en la sección "Crear"</p>
            </div>
        `;
        counter.textContent = 'Flashcard 0 de 0';
        return;
    }
    
    currentCardIndex = Math.max(0, Math.min(currentCardIndex, flashcards.length - 1));
    const currentCard = flashcards[currentCardIndex];
    
    studyArea.innerHTML = `
    <ion-icon onclick="previousCard()" class='hover:text-blue-500' name="chevron-back-outline"></ion-icon>
        <div class="flashcard" id="current-flashcard" onclick="flipCard()">
            <div class="flashcard-inner">
                <div class="flashcard-front">
                    <div>${currentCard.question}</div>
                </div>
                <div class="flashcard-back">
                    <div>${currentCard.answer}</div>
                </div>
            </div>
        </div>
    <ion-icon onclick="nextCard()" class='hover:text-blue-500' name="chevron-forward-outline"></ion-icon>
    `;
    
    counter.textContent = `Flashcard ${currentCardIndex + 1} de ${flashcards.length}`;
}

// Voltear flashcard
function flipCard() {
    const flashcard = document.getElementById('current-flashcard');
    if (flashcard) {
        flashcard.classList.toggle('flipped');
    }
}

// Navegación entre flashcards
function nextCard() {
    if (flashcards.length > 0) {
        currentCardIndex = (currentCardIndex + 1) % flashcards.length;
        updateStudyArea();
        // Asegurar que la carta esté en posición frontal
        setTimeout(() => {
            const flashcard = document.getElementById('current-flashcard');
            if (flashcard) {
                flashcard.classList.remove('flipped');
            }
        }, 100);
    }
}

function previousCard() {
    if (flashcards.length > 0) {
        currentCardIndex = currentCardIndex === 0 ? flashcards.length - 1 : currentCardIndex - 1;
        updateStudyArea();
        // Asegurar que la carta esté en posición frontal
        setTimeout(() => {
            const flashcard = document.getElementById('current-flashcard');
            if (flashcard) {
                flashcard.classList.remove('flipped');
            }
        }, 100);
    }
}

// Actualizar lista de flashcards
function updateFlashcardList() {
    const listContent = document.getElementById('flashcard-list-content');
    
    if (flashcards.length === 0) {
        listContent.innerHTML = `
            <div class="empty-state">
                <h3>No tienes flashcards creadas</h3>
                <p>Ve a la sección "Crear" para agregar tu primera flashcard</p>
            </div>
        `;
        return;
    }
    
    listContent.innerHTML = flashcards.map(card => `
        <div class="flashcard-item">
            <div class="flashcard-preview">
                <strong>P: ${card.question.substring(0, 80)}${card.question.length > 80 ? '...' : ''}</strong>
                <span>R: ${card.answer.substring(0, 80)}${card.answer.length > 80 ? '...' : ''}</span>
            </div>
            <button class="delete-btn" onclick="deleteFlashcard(${card.id})">
                🗑️ Eliminar
            </button>
        </div>
    `).join('');
}

// Eliminar flashcard
function deleteFlashcard(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta flashcard?')) {
        flashcards = flashcards.filter(card => card.id !== id);
        localStorage.setItem('flashcards', JSON.stringify(flashcards));
        updateFlashcardList();
        
        // Mostrar mensaje de confirmación
        showSuccessMessage('Flashcard eliminada correctamente ✅');
        
        // Ajustar índice actual si es necesario
        if (currentCardIndex >= flashcards.length) {
            currentCardIndex = Math.max(0, flashcards.length - 1);
        }
    }
}

// Navegación con teclado
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

// Inicializar la aplicación
updateFlashcardList();