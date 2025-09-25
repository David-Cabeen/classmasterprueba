<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuaderno Cornell Digital</title>
    <script type="module" src="../scripts/cornell.js" defer></script>
    <link rel="stylesheet" href="../styles/cornell.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Cuaderno Cornell Digital</h1>
            <p>Organiza tus notas con el método Cornell para un aprendizaje más efectivo</p>
        </div>
        <div class="toolbar">
            <div class="toolbar-left">
                <button class="btn" onclick="showCreateNote()">
                    ➕ Nueva Nota
                </button>
                <button class="btn btn-secondary" onclick="showAllNotes()">
                    📚 Ver Todas las Notas
                </button>
            </div>
            <div class="toolbar-right">
                <button class="btn btn-danger" onclick="clearAllNotes()">
                    🗑️ Limpiar Todo
                </button>
                <span id="notes-count" style="color: var(--muted); font-weight: 500;">0 notas guardadas</span>
            </div>
        </div>
        <!-- Formulario para crear nueva nota -->
        <div id="create-note-section" style="display: none;">
            <div class="note-input">
                <input type="text" id="note-title" placeholder="Título de la nota..." maxlength="100">
            </div>
            <div class="cornell-container">
                <div class="cornell-header">
                    <span>📋 Método Cornell - Nueva Nota</span>
                    <div>
                        <button class="btn btn-secondary" onclick="saveNote()" style="margin-right: 10px;">
                            💾 Guardar Nota
                        </button>
                        <button class="btn btn-danger" onclick="cancelNote()">
                            ❌ Cancelar
                        </button>
                    </div>
                </div>
                <div class="cornell-cues">
                    <h3>🔑 Palabras Clave / Preguntas</h3>
                    <textarea class="editable-area" id="cues-area" 
                        placeholder="Escribe palabras clave, preguntas importantes, conceptos principales...

Ejemplos:
• ¿Qué es...?
• Definición de...
• Fórmula principal
• Concepto clave
• Fecha importante"></textarea>
                </div>
                <div class="cornell-notes">
                    <h3>📝 Notas Principales</h3>
                    <textarea class="editable-area" id="notes-area" 
                        placeholder="Escribe tus notas principales aquí...

Puedes incluir:
• Explicaciones detalladas
• Ejemplos y casos prácticos
• Procedimientos paso a paso
• Datos importantes
• Diagramas en texto
• Referencias y citas"></textarea>
                </div>
                <div class="cornell-summary">
                    <h3>📋 Resumen</h3>
                    <textarea class="editable-area" id="summary-area" 
                        placeholder="Escribe un resumen de los puntos más importantes...

El resumen debe incluir:
• Ideas principales de la sesión
• Conclusiones importantes
• Conexiones con otros temas
• Puntos que necesitas repasar
• Aplicaciones prácticas"></textarea>
                </div>
            </div>
        </div>
        <!-- Lista de notas guardadas -->
        <div id="notes-list-section">
            <div class="notes-list" id="notes-container">
                <div class="empty-state">
                    <h3>No tienes notas guardadas</h3>
                    <p>Haz clic en "Nueva Nota" para crear tu primera nota usando el método Cornell</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>