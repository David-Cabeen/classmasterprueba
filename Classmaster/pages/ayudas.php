<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
      exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../styles/ayudas.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
    <title>Página de ayuda</title>
</head>
<body>
    <header>
        <h1>Ayudas</h1>
    </header>
    <main>
        <section class="math">
            <span>
                <h2>Matemáticas</h2>
                <p style="width: 20ch;">Aquí tenemos canales que pueden ser útiles para matemáticas. Además de un recurso adicional para ayudar a comprobar tus respuestas</p>
            </span>
            <ion-icon name="calculator-outline"></ion-icon>
            <a href="https://www.youtube.com/@MatematicasprofeAlex"target="_blank">
                <img src="https://yt3.googleusercontent.com/ytc/AIdro_nxQQ-huxPMZTBA2C4WjSH2xPYHxHGWT0TShBq5hZaA-S0=s120-c-k-c0x00ffffff-no-rj" alt="">
                <h3>Matemáticas Profe Alex</h3>
            </a>
                <a href="https://www.youtube.com/@danielcarreon"target="_blank" >
                    <img src="https://yt3.googleusercontent.com/l7sq5ZuUXMJ1tSpkYTHJNBUHxyGnDCVXY8w9fA3pwMJeqECqtBa2ul73aWYUwXkzRlWhBdLhmDc=s120-c-k-c0x00ffffff-no-rj" alt="">
                    <h3>Daniel Carreón</h3>
                </a>
                <a href="https://photomath.es/"target="_blank">
                    <img width="120" src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjJ5_QKZgkB7-D7LDwcGEhtUOizd6OuU16xMui806hkFsr0omEkhkw0OZPh-WhDReA5xMdB_Lm3qjYcr6Ta8U-RhJWGLeFCd2WYa06f_JHphFyJJPCWknsUYvvAeGnnyqoE2FctAZf87q66/s1600/PM.png" alt="">
                    <h3>PhotoMath</h3>
                </a>
        </section>
        <section class="chem">
            <h2 >Química y Biología</h2>
            <span>
                <p style="width: 12ch;">Aquí tenemos canales que pueden ser útiles para química y biología.</p>
                <span>
                     <ion-icon name="logo-react"></ion-icon>
                        <a href="https://www.youtube.com/@arribalaciencia"target="_blank">
                            <img src="https://yt3.googleusercontent.com/ytc/AIdro_mzaBVx5mnUV5_ijaL6yozu6MN3cTcTcSLaF4_ZodRtEuA=s120-c-k-c0x00ffffff-no-rj" alt="">
                            <h3>Arriba la ciencia</h3>
                        </a>
                        <a href="https://www.youtube.com/@laquimicadeyamil"target="_blank">
                            <img src="https://yt3.googleusercontent.com/Cb-oofPujgEjxB4FlB8UbvHr8ORNGzuqr91tL2fylkBKkjKJPYfJWC4sW6vcZ_You9KivgMm=s120-c-k-c0x00ffffff-no-rj" alt="">
                            <h3>La química de Yamil</h3>
                        </a>
                </span>
            </span>
        </section>
        <section class="history">
            <h2 >Historia y sociales</h2>
            <ion-icon name="compass-outline"></ion-icon> 
            <span>
                <p style="width: 12ch;">Aquí tenemos canales que pueden ser útiles para historia y ciencias sociales.</p>
                <span>
                    <a href="https://www.youtube.com/@PeroesoesotraHistoria"target="_blank">
                        <img src="https://yt3.googleusercontent.com/ytc/AIdro_lE_UA0HlE9j3tCTMb6izTeLJaVXmIGSXRkzgd3510d5Gc=s120-c-k-c0x00ffffff-no-rj" alt="">
                        <h3>Pero eso es otra historia</h3>
                    </a>
                    <a href="https://www.youtube.com/@MemoriasDePez"target="_blank">
                        <img src="https://yt3.googleusercontent.com/ytc/AIdro_nM6wSYYyXw-uwi_URvB6vZr6rUr1uvtTghcmCnHyBa2kQ=s120-c-k-c0x00ffffff-no-rj" alt="">
                        <h3>Memorias de pez</h3>
                    </a>
                </span>
            </span>
        </section>
        <section class="spanish">
            <h2 >Español</h2>
            <p style="margin-bottom: 2rem;">Aquí hay un canal que puede ser útil para español.</p>
            <ion-icon name="book-outline"></ion-icon>
            <a href="https://www.youtube.com/@linguofilos2740"target="_blank">
                <img src="https://yt3.googleusercontent.com/ytc/AIdro_nw5lQWmVJVo6SWfimcqrz9uhOqMSFVOuf-uf2ybDMkag=s120-c-k-c0x00ffffff-no-rj" alt="">
                <h3>Linguófilos</h3>
            </a>
        </section>
        <section class="others">
            <h2>Más asignaturas</h2>
            <ion-icon name="apps-outline"></ion-icon>
            <span>
                <a href="https://www.youtube.com/@SusiProfe" target="_blank">
                    <img src="https://yt3.googleusercontent.com/oR9sPa72Ly8N0pGFNtzFLLu3fej1M1UbingnLyJrpQOiU1ooz9iSzDUXSEc3Hu7c2nfk1o3GFA=s120-c-k-c0x00ffffff-no-rj" alt="">
                    <h3>Susi Profe</h3>
                </a>
                <a href="https://www.youtube.com/@ClasesParticularesen%C3%81vila" target="_blank">
                    <img src="https://yt3.googleusercontent.com/tod__5P0Y2o16UAExLptSMGuXoPr0FPqbBlEjKeIcrHrHcY4Vatcko5nyef9ZX4EXqRR7Eb6=s120-c-k-c0x00ffffff-no-rj" alt="">
                    <h3>Clases particulares en Ávila</h3>
                </a>
                <a href="https://www.youtube.com/@CentrodeEstudiosMayteLopez" target="_blank">
                    <img src="https://yt3.googleusercontent.com/2-hjt_WFwVIRygJkJoDAOh12DK3F7Xc5yw56E7kaZw_e8SfE74aQpjyiX7z78E4pr32OhnSaGQ=s120-c-k-c0x00ffffff-no-rj" alt="">
                    <h3>Centro de estudios Mayte Lopez</h3>
                </a>
            </span>
        </section>
        <section class="info">
            <h2>Información</h2>
            En esta página podrás encontrar links de páginas,
            canales o videos los cuales te ayudarán
            a entender y aprender temas que tal vez 
            necesites para este camino estudiantil
            que estás recorriendo.
        </section>
    </main>
    <footer>
        <p>&copy; 2025 - ClassMaster, todos los derechos reservados</p>
    </footer>  
</body> 
</html>