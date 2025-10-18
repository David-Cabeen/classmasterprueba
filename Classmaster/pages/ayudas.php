
<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
      header('Location: inicio.html');
      exit();
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classmaster | Ayudas</title>
    <link rel="stylesheet" href="../styles/home.css" />
    <link rel="stylesheet" href="../styles/sidebar.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="../scripts/home.js" defer></script>
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
                    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold tracking-tight text-accent flex items-center gap-2">
                        Ayudas y Recursos
                    </h1>
                </div>
                <p class="mt-2 text-sm text-white/60">Encuentra canales, páginas y herramientas útiles para tus estudios.</p>
            </div>
            <div class="divider"></div>
        </header>
        <main class="flex-1 w-full">
            <section class="max-w-6xl mx-auto px-6 py-8">
                <!-- Matemáticas -->
                <div class="mb-10">
                    <h2 class="text-lg font-bold text-white/80 mb-4 flex items-center gap-2"><ion-icon name="calculator-outline"></ion-icon> Matemáticas</h2>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="https://www.youtube.com/@MatematicasprofeAlex" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">🧮</span>
                            <h3 class="text-xl font-semibold mb-2">Matemáticas Profe Alex</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">Canal de YouTube con explicaciones claras de matemáticas.</p>
                        </a>
                        <a href="https://www.youtube.com/@danielcarreon" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">📐</span>
                            <h3 class="text-xl font-semibold mb-2">Daniel Carreón</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">Canal de YouTube con recursos de matemáticas y física.</p>
                        </a>
                        <a href="https://photomath.es/" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">📱</span>
                            <h3 class="text-xl font-semibold mb-2">PhotoMath</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">App para resolver problemas matemáticos con la cámara.</p>
                        </a>
                        <a href="https://www.geogebra.org/" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">🧊</span>
                            <h3 class="text-xl font-semibold mb-2">GeoGebra</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">Herramienta interactiva para matemáticas y geometría.</p>
                        </a>
                        <a href="https://es.symbolab.com/" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">🔢</span>
                            <h3 class="text-xl font-semibold mb-2">Symbolab</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">Calculadora paso a paso para álgebra y cálculo.</p>
                        </a>
                    </div>
                </div>
                <!-- Química y Biología -->
                <div class="mb-10">
                    <h2 class="text-lg font-bold text-white/80 mb-4 flex items-center gap-2"><ion-icon name="flask-outline"></ion-icon> Química y Biología</h2>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="https://www.youtube.com/@arribalaciencia" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">�</span>
                            <h3 class="text-xl font-semibold mb-2">Arriba la ciencia</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">Canal de YouTube para química y biología.</p>
                        </a>
                        <a href="https://www.youtube.com/@laquimicadeyamil" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">🧬</span>
                            <h3 class="text-xl font-semibold mb-2">La Química de Yamil</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">Canal de YouTube con recursos de química.</p>
                        </a>
                    </div>
                </div>
                <!-- Historia y Sociales -->
                <div class="mb-10">
                    <h2 class="text-lg font-bold text-white/80 mb-4 flex items-center gap-2"><ion-icon name="book-outline"></ion-icon> Historia y Sociales</h2>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="https://www.youtube.com/@AcademiaPlay" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">📚</span>
                            <h3 class="text-xl font-semibold mb-2">Academia Play</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">Canal de YouTube con videos de historia y sociales.</p>
                        </a>
                    </div>
                </div>
                <!-- Herramientas Generales -->
                <div class="mb-10">
                    <h2 class="text-lg font-bold text-white/80 mb-4 flex items-center gap-2"><ion-icon name="globe-outline"></ion-icon> Herramientas Generales</h2>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="https://www.khanacademy.org/" target="_blank" class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline flex flex-col items-center">
                            <span class="text-3xl mb-2">🌐</span>
                            <h3 class="text-xl font-semibold mb-2">Khan Academy</h3>
                            <p class="text-sm text-white/60 mb-2 text-center">Plataforma educativa gratuita con ejercicios y videos.</p>
                        </a>
                    </div>
                </div>
                <!-- Frase motivacional -->
                <section class="mt-10">
                    <div class="glass rounded-2xl p-6 md:p-8 ring-soft animate-in">
                        <blockquote class="text-xl md:text-2xl leading-relaxed" id="frase-semanal">
                            Cargando motivación…
                        </blockquote>
                        <p class="mt-2 text-white/50 text-sm" id="frase-autor"></p>
                    </div>
                </section>
            </section>
        </main>
        <!-- Footer -->
        <footer class="mt-auto">
            <div class="divider"></div>
            <div class="max-w-6xl mx-auto px-6 py-6 text-sm text-white/50">
                ©️ 2025 · ClassMaster — todos los derechos reservados
            </div>
        </footer>
    </div>
    <script>
        window.rol = "<?php echo $_SESSION['rol']; ?>";
    </script>
</body>
</html>