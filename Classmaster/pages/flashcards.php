<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcards</title>
    <link rel="stylesheet" href="../styles/flashcards.css">
    <link rel="stylesheet" href="../styles/sidebar.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../scripts/flashcards.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    <div id="mainContent" class="transition-all duration-300 ease-in-out ml-16">
        <!-- Header Section (like home.php) -->
        <header class="w-full">
            <div class="max-w-6xl mx-auto px-6 pt-8 pb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="size-3 rounded-full bg-white/80 shadow-[0_0_24px_4px_rgba(255,255,255,0.25)]"></div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold tracking-tight text-accent">Flashcards</h1>
                </div>
                <p class="mt-2 text-sm text-white/60">Crea y estudia con estilo usando tus tarjetas digitales</p>
            </div>
            <div class="divider"></div>
        </header>
        <main class="flex-1 w-full">
            <section class="max-w-6xl mx-auto px-6 py-8">
                <div class="glass rounded-2xl p-6 md:p-8 ring-soft border border-white/10 animate-fadeInUp mb-8">
                    <div class="flex justify-center gap-2 mb-8">
                        <button class="tab active px-4 py-2 rounded-xl font-semibold bg-white/5 text-accent ring-soft border border-white/10 transition hover:bg-white/10" onclick="switchTab('creator')">➕ Crear Flashcards</button>
                        <button class="tab px-4 py-2 rounded-xl font-semibold bg-white/5 text-accent ring-soft border border-white/10 transition hover:bg-white/10" onclick="switchTab('study')">🎓 Estudiar</button>
                        <button class="tab px-4 py-2 rounded-xl font-semibold bg-white/5 text-accent ring-soft border border-white/10 transition hover:bg-white/10" onclick="switchTab('manage')">📋 Gestionar</button>
                    </div>
                    <!-- Sección Crear -->
                    <div id="creator" class="tab-content active animate-fadeInUp">
                        <div class="creator-section bg-white/5 rounded-xl ring-soft border border-white/10 p-6 mb-6 animate-fadeInUp">
                            <h2 class="text-lg font-semibold text-accent mb-4">✨ Crear Nueva Flashcard</h2>
                            <form id="flashcard-form" class="space-y-4">
                                <div class="form-group">
                                    <label for="question" class="block mb-2 font-medium text-white/70">💭 Pregunta:</label>
                                    <textarea id="question" class="w-full rounded-lg bg-black/30 text-white p-3 border border-white/10 focus:outline-none focus:ring-2 focus:ring-accent" placeholder="Escribe la pregunta aquí..." required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="answer" class="block mb-2 font-medium text-white/70">💡 Respuesta:</label>
                                    <textarea id="answer" class="w-full rounded-lg bg-black/30 text-white p-3 border border-white/10 focus:outline-none focus:ring-2 focus:ring-accent" placeholder="Escribe la respuesta aquí..." required></textarea>
                                </div>
                                <button type="submit" class="btn bg-accent text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-accent/90 transition">Agregar Flashcard</button>
                            </form>
                        </div>
                    </div>
                    <!-- Sección Estudiar -->
                    <div id="study" class="tab-content animate-fadeInUp">
                        <div class="study-info mb-4">
                            <h2 class="text-lg font-semibold text-accent mb-2">🎯 Modo Estudio</h2>
                            <p id="study-counter" class="text-white/60">Flashcard 0 de 0</p>
                        </div>
                        <div id="study-area" class="bg-white/5 rounded-xl ring-soft border border-white/10 p-6 animate-fadeInUp">
                            <div class="empty-state text-center">
                                <h3 class="text-lg font-semibold text-accent mb-2">No hay flashcards para estudiar</h3>
                                <p class="text-white/60">Crea algunas flashcards primero en la sección "Crear"</p>
                            </div>
                        </div>
                    </div>
                    <!-- Sección Gestionar -->
                    <div id="manage" class="tab-content animate-fadeInUp">
                        <div class="flashcard-list bg-white/5 rounded-xl ring-soft border border-white/10 p-6 animate-fadeInUp">
                            <h2 class="text-lg font-semibold text-accent mb-4">📋 Tus Flashcards</h2>
                            <div id="flashcard-list-content">
                                <div class="empty-state text-center">
                                    <h3 class="text-lg font-semibold text-accent mb-2">No tienes flashcards creadas</h3>
                                    <p class="text-white/60">Ve a la sección "Crear" para agregar tu primera flashcard</p>
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
