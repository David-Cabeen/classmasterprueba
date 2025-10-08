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
    <title>Classmaster | Notas de Cornell</title>
    <link rel="stylesheet" href="../styles/flashcards.css">
    <link rel="stylesheet" href="../styles/sidebar.css" />
    <link rel="stylesheet" href="../styles/cornell.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="../scripts/cornell.js" defer></script>
    <script type="module" src="../scripts/components.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body class="min-h-screen flex flex-col selection:bg-white/10 selection:text-white">
    <?php include '../components/sidebar.php'; ?>
    <div id="mainContent" class="transition-all duration-300 ease-in-out ml-16">
        <header class="w-full">
            <div class="max-w-6xl mx-auto px-6 pt-8 pb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="size-3 rounded-full bg-white/80 shadow-[0_0_24px_4px_rgba(255,255,255,0.25)]"></div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold tracking-tight text-accent">Notas de Cornell</h1>
                </div>
                <p class="mt-2 text-sm text-white/60">Organiza tus notas con el método Cornell para un aprendizaje más efectivo</p>
            </div>
            <div class="divider"></div>
        </header>
        <main class="flex-1 w-full">
            <section class="max-w-6xl mx-auto px-6 py-8">
                <div class="glass rounded-2xl p-6 md:p-8 ring-soft border border-white/10 animate-fadeInUp mb-8">
                    <div id="cornellTabButtons" class="flex justify-center gap-2 mb-8">
                        <button class="tab active px-4 py-2 rounded-xl font-semibold bg-white/5 text-accent ring-soft border border-white/10 transition hover:bg-white/10">➕ Nueva Nota</button>
                        <button class="tab px-4 py-2 rounded-xl font-semibold bg-white/5 text-accent ring-soft border border-white/10 transition hover:bg-white/10">📚 Ver Todas las Notas</button>
                        <button class="tab px-4 py-2 rounded-xl font-semibold bg-white/5 text-accent ring-soft border border-white/10 transition hover:bg-white/10">🗑️ Limpiar Todo</button>
                        <span id="notes-count" class="ml-auto text-white/60 font-medium">0 notas guardadas</span>
                    </div>
                    <!-- Sección Crear -->
                    <div id="create-note-section" class="tab-content animate-fadeInUp" style="display: none;">
                        <div class="creator-section bg-white/5 rounded-xl ring-soft border border-white/10 p-6 mb-6 animate-fadeInUp">
                            <h2 class="text-lg font-semibold text-accent mb-4">✨ Nueva Nota Cornell</h2>
                            <div class="form-group mb-4">
                                <label for="note-title" class="block mb-2 font-medium text-white/70">Título de la nota:</label>
                                <input type="text" id="note-title" class="w-full rounded-lg bg-black/30 text-white p-3 border border-white/10 focus:outline-none focus:ring-2 focus:ring-accent" placeholder="Título de la nota..." maxlength="100">
                            </div>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="cornell-cues">
                                    <h3 class="font-semibold mb-2">🔑 Palabras Clave / Preguntas</h3>
                                    <textarea class="editable-area w-full rounded-lg bg-black/30 text-white p-3 border border-white/10 focus:outline-none focus:ring-2 focus:ring-accent" id="cues-area" placeholder="Palabras clave, preguntas, conceptos..."></textarea>
                                </div>
                                <div class="cornell-notes">
                                    <h3 class="font-semibold mb-2">📝 Notas Principales</h3>
                                    <textarea class="editable-area w-full rounded-lg bg-black/30 text-white p-3 border border-white/10 focus:outline-none focus:ring-2 focus:ring-accent" id="notes-area" placeholder="Notas principales..."></textarea>
                                </div>
                                <div class="cornell-summary">
                                    <h3 class="font-semibold mb-2">📋 Resumen</h3>
                                    <textarea class="editable-area w-full rounded-lg bg-black/30 text-white p-3 border border-white/10 focus:outline-none focus:ring-2 focus:ring-accent" id="summary-area" placeholder="Resumen de los puntos más importantes..."></textarea>
                                </div>
                            </div>
                            <div id="cornellFormButtons" class="flex gap-3 mt-6">
                                <button class="btn btn-secondary">💾 Guardar Nota</button>
                                <button class="btn btn-danger">❌ Cancelar</button>
                            </div>
                        </div>
                    </div>
                    <!-- Sección Lista -->
                    <div id="notes-list-section" class="tab-content active animate-fadeInUp">
                        <div class="flashcard-list bg-white/5 rounded-xl ring-soft border border-white/10 p-6 animate-fadeInUp">
                            <h2 class="text-lg font-semibold text-accent mb-4">📋 Tus Notas Cornell</h2>
                            <div id="notes-container">
                                <div class="empty-state text-center">
                                    <h3 class="text-lg font-semibold text-accent mb-2">No tienes notas guardadas</h3>
                                    <p class="text-white/60">Haz clic en "Nueva Nota" para crear tu primera nota usando el método Cornell</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
                </div>
            </div>
        </div>
    </div>
</body>
</html>