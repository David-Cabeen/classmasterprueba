
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
    <title>Ayudas | ClassMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/ayudas.css">
    <link rel="stylesheet" href="../styles/sidebar.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body class="bg-gradient-to-br from-[#23233a] via-[#181824] to-[#0e0e10] min-h-screen text-gray-100 flex">
    <?php include '../components/sidebar.php'; ?>
    <main id="mainContent" class="flex-1 min-h-screen flex flex-col items-center pt-8 px-2 md:px-8 transition-all duration-300 ml-16">
        <header class="w-full max-w-4xl mx-auto mb-8">
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white drop-shadow mb-2">Ayudas y Recursos</h1>
            <p class="text-gray-400 text-base md:text-lg">Encuentra canales, páginas y herramientas útiles para tus estudios.</p>
        </header>
        <div class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Matemáticas -->
            <section class="glass flex flex-col items-center p-6 rounded-2xl shadow-lg border border-white/10">
                <ion-icon name="calculator-outline" class="text-3xl mb-2 text-accent"></ion-icon>
                <h2 class="text-xl font-semibold mb-2 text-white">Matemáticas</h2>
                <p class="text-gray-400 mb-4 text-center">Canales y recursos útiles para matemáticas. Incluye herramientas para comprobar tus respuestas.</p>
                <div class="flex flex-col gap-2 w-full items-center">
                    <a href="https://www.youtube.com/@MatematicasprofeAlex" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/ytc/AIdro_nxQQ-huxPMZTBA2C4WjSH2xPYHxHGWT0TShBq5hZaA-S0=s120-c-k-c0x00ffffff-no-rj" alt="Matemáticas Profe Alex" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Matemáticas Profe Alex</h3>
                    </a>
                    <a href="https://www.youtube.com/@danielcarreon" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/l7sq5ZuUXMJ1tSpkYTHJNBUHxyGnDCVXY8w9fA3pwMJeqECqtBa2ul73aWYUwXkzRlWhBdLhmDc=s120-c-k-c0x00ffffff-no-rj" alt="Daniel Carreón" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Daniel Carreón</h3>
                    </a>
                    <a href="https://photomath.es/" target="_blank" class="hover:scale-105 transition">
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjJ5_QKZgkB7-D7LDwcGEhtUOizd6OuU16xMui806hkFsr0omEkhkw0OZPh-WhDReA5xMdB_Lm3qjYcr6Ta8U-RhJWGLeFCd2WYa06f_JHphFyJJPCWknsUYvvAeGnnyqoE2FctAZf87q66/s1600/PM.png" alt="PhotoMath" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">PhotoMath</h3>
                    </a>
                </div>
            </section>
            <!-- Química y Biología -->
            <section class="glass flex flex-col items-center p-6 rounded-2xl shadow-lg border border-white/10">
                <ion-icon name="logo-react" class="text-3xl mb-2 text-accent"></ion-icon>
                <h2 class="text-xl font-semibold mb-2 text-white">Química y Biología</h2>
                <p class="text-gray-400 mb-4 text-center">Canales útiles para química y biología.</p>
                <div class="flex flex-col gap-2 w-full items-center">
                    <a href="https://www.youtube.com/@arribalaciencia" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/ytc/AIdro_mzaBVx5mnUV5_ijaL6yozu6MN3cTcTcSLaF4_ZodRtEuA=s120-c-k-c0x00ffffff-no-rj" alt="Arriba la ciencia" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Arriba la ciencia</h3>
                    </a>
                    <a href="https://www.youtube.com/@laquimicadeyamil" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/Cb-oofPujgEjxB4FlB8UbvHr8ORNGzuqr91tL2fylkBKkjKJPYfJWC4sW6vcZ_You9KivgMm=s120-c-k-c0x00ffffff-no-rj" alt="La química de Yamil" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">La química de Yamil</h3>
                    </a>
                </div>
            </section>
            <!-- Historia y sociales -->
            <section class="glass flex flex-col items-center p-6 rounded-2xl shadow-lg border border-white/10">
                <ion-icon name="compass-outline" class="text-3xl mb-2 text-accent"></ion-icon>
                <h2 class="text-xl font-semibold mb-2 text-white">Historia y Sociales</h2>
                <p class="text-gray-400 mb-4 text-center">Canales útiles para historia y ciencias sociales.</p>
                <div class="flex flex-col gap-2 w-full items-center">
                    <a href="https://www.youtube.com/@PeroesoesotraHistoria" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/ytc/AIdro_lE_UA0HlE9j3tCTMb6izTeLJaVXmIGSXRkzgd3510d5Gc=s120-c-k-c0x00ffffff-no-rj" alt="Pero eso es otra historia" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Pero eso es otra historia</h3>
                    </a>
                    <a href="https://www.youtube.com/@MemoriasDePez" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/ytc/AIdro_nM6wSYYyXw-uwi_URvB6vZr6rUr1uvtTghcmCnHyBa2kQ=s120-c-k-c0x00ffffff-no-rj" alt="Memorias de pez" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Memorias de pez</h3>
                    </a>
                </div>
            </section>
            <!-- Español -->
            <section class="glass flex flex-col items-center p-6 rounded-2xl shadow-lg border border-white/10">
                <ion-icon name="book-outline" class="text-3xl mb-2 text-accent"></ion-icon>
                <h2 class="text-xl font-semibold mb-2 text-white">Español</h2>
                <p class="text-gray-400 mb-4 text-center">Canal recomendado para español.</p>
                <div class="flex flex-col gap-2 w-full items-center">
                    <a href="https://www.youtube.com/@linguofilos2740" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/ytc/AIdro_nw5lQWmVJVo6SWfimcqrz9uhOqMSFVOuf-uf2ybDMkag=s120-c-k-c0x00ffffff-no-rj" alt="Linguófilos" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Linguófilos</h3>
                    </a>
                </div>
            </section>
            <!-- Más asignaturas -->
            <section class="glass flex flex-col items-center p-6 rounded-2xl shadow-lg border border-white/10">
                <ion-icon name="apps-outline" class="text-3xl mb-2 text-accent"></ion-icon>
                <h2 class="text-xl font-semibold mb-2 text-white">Más asignaturas</h2>
                <p class="text-gray-400 mb-4 text-center">Otros canales útiles para diferentes materias.</p>
                <div class="flex flex-col gap-2 w-full items-center">
                    <a href="https://www.youtube.com/@SusiProfe" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/oR9sPa72Ly8N0pGFNtzFLLu3fej1M1UbingnLyJrpQOiU1ooz9iSzDUXSEc3Hu7c2nfk1o3GFA=s120-c-k-c0x00ffffff-no-rj" alt="Susi Profe" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Susi Profe</h3>
                    </a>
                    <a href="https://www.youtube.com/@ClasesParticularesen%C3%81vila" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/tod__5P0Y2o16UAExLptSMGuXoPr0FPqbBlEjKeIcrHrHcY4Vatcko5nyef9ZX4EXqRR7Eb6=s120-c-k-c0x00ffffff-no-rj" alt="Clases particulares en Ávila" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Clases particulares en Ávila</h3>
                    </a>
                    <a href="https://www.youtube.com/@CentrodeEstudiosMayteLopez" target="_blank" class="hover:scale-105 transition">
                        <img src="https://yt3.googleusercontent.com/2-hjt_WFwVIRygJkJoDAOh12DK3F7Xc5yw56E7kaZw_e8SfE74aQpjyiX7z78E4pr32OhnSaGQ=s120-c-k-c0x00ffffff-no-rj" alt="Centro de estudios Mayte Lopez" class="rounded-full w-20 h-20 mx-auto shadow mb-1">
                        <h3 class="text-base font-medium text-white">Centro de estudios Mayte Lopez</h3>
                    </a>
                </div>
            </section>
            <!-- Información -->
            <section class="glass flex flex-col items-center p-6 rounded-2xl shadow-lg border border-white/10 col-span-full">
                <ion-icon name="information-circle-outline" class="text-3xl mb-2 text-accent"></ion-icon>
                <h2 class="text-xl font-semibold mb-2 text-white">Información</h2>
                <p class="text-gray-400 text-center">En esta página podrás encontrar links de páginas, canales o videos que te ayudarán a entender y aprender temas que necesites para tu camino estudiantil.</p>
            </section>
        </div>
        <footer class="w-full max-w-4xl mx-auto mt-10 text-center text-gray-500 border-t border-white/10 pt-6 pb-4 text-sm">
            &copy; 2025 - ClassMaster, todos los derechos reservados
        </footer>
    </main>
</body>
</html>