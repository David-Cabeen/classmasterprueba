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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hogar</title>
  <link rel="stylesheet" href="../styles/home.css" />
   <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<body class="min-h-screen flex flex-col selection:bg-white/10 selection:text-white">

  <!-- Header -->
  <header class="w-full">
    <div class="max-w-6xl mx-auto px-6 pt-8 pb-6">
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="size-3 rounded-full bg-white/80 shadow-[0_0_24px_4px_rgba(255,255,255,0.25)]"></div>
          <h1 class="text-lg sm:text-xl md:text-2xl font-semibold tracking-tight">
            Bienvenido a ClassMaster, <span id="nombre-usuario" class="text-white/90">Usuario</span>
          </h1>
        </div>

        <!-- Avatar / Logo + Menú -->
        <div id="avatarMenuWrap" class="relative">
          <button id="avatarBtn" type="button" aria-haspopup="menu" aria-expanded="false"
                  class="tooltip focus-outline rounded-full p-1.5 glass ring-soft transition hover:scale-[1.02] avatar-ring"
                  data-tip="Abrir menú">
            <!-- Logo generado por SVG para evitar imágenes externas -->
            <svg aria-hidden="true" viewBox="0 0 48 48" class="size-10">
              <defs>
                <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
                  <stop offset="0" stop-color="#ffffff" stop-opacity="0.95"/>
                  <stop offset="1" stop-color="#bdbdbd" stop-opacity="0.9"/>
                </linearGradient>
              </defs>
              <rect x="3" y="3" width="42" height="42" rx="10" fill="url(#g)" opacity="0.12" />
              <circle cx="24" cy="18" r="7" fill="#ffffff" opacity="0.9"/>
              <path d="M10 38c3.5-6.5 10-10 14-10s10.5 3.5 14 10" fill="#ffffff" opacity="0.25"/>
              <circle cx="24" cy="18" r="5.5" fill="#0f0f12"/>
            </svg>
            <span class="sr-only">Abrir menú de usuario</span>
          </button>

          <!-- Menú desplegable -->
          <div id="avatarMenu"
               class="absolute right-0 mt-3 w-56 origin-top-right glass rounded-xl shadow-2xl border border-white/10 opacity-0 scale-95 pointer-events-none transition transform">
            <div class="p-3">
              <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 transition">
                <div class="size-9 rounded-full bg-white/90 text-black flex items-center justify-center font-bold">U</div>
                <div>
                  <p class="text-sm font-medium">Usuario</p>
                  <p class="text-xs text-white/60">Ver tu perfil</p>
                </div>
              </div>
              <div class="divider my-2"></div>
              <button id="btnPerfil" class="w-full text-left px-3 py-2 rounded-lg hover:bg-white/5 transition focus-outline">
                Perfil
              </button>
              <button id="btnCerrar" class="w-full text-left px-3 py-2 rounded-lg hover:bg-white/5 transition focus-outline">
                Cerrar sesión
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Subtítulo -->
      <p class="mt-4 text-sm text-white/60">¿Qué deseas hacer hoy?</p>
    </div>
    <div class="divider"></div>
  </header>

  <!-- Main -->
  <main class="flex-1 w-full">
    <section class="max-w-6xl mx-auto px-6 py-8">
      <!-- Grid de tarjetas -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="#"
           data-href="notas.php"
           class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
          <div class="flex items-center justify-between">
            <span class="text-3xl" aria-hidden="true">📚</span>
            <span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Notas</span>
          </div>
          <h3 class="mt-4 text-xl font-semibold">Notas</h3>
          <p class="text-sm text-white/60 mt-1">Organiza ideas y apuntes.</p>
        </a>

        <a href="#"
           data-href="calendario.php"
           class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
          <div class="flex items-center justify-between">
            <span class="text-3xl">🗓️</span>
            <span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Calendario</span>
          </div>
          <h3 class="mt-4 text-xl font-semibold">Calendario</h3>
          <p class="text-sm text-white/60 mt-1">Planifica tu semana.</p>
        </a>

        <a href="#"
           data-href="tareas.php"
           class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
          <div class="flex items-center justify-between">
            <span class="text-3xl">📝</span>
            <span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Tareas</span>
          </div>
          <h3 class="mt-4 text-xl font-semibold">Tareas</h3>
          <p class="text-sm text-white/60 mt-1">Gestiona tu lista.</p>
        </a>

        <a href="#"
           data-href="pomodoro.php"
           class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
          <div class="flex items-center justify-between">
            <span class="text-3xl">🍅</span>
            <span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Pomodoro</span>
          </div>
          <h3 class="mt-4 text-xl font-semibold">Pomodoro</h3>
          <p class="text-sm text-white/60 mt-1">Enfoque por intervalos.</p>
        </a>

        <a href="#"
           data-href="metodos.php"
           class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
          <div class="flex items-center justify-between">
            <span class="text-3xl">📒</span>
            <span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Métodos</span>
          </div>
          <h3 class="mt-4 text-xl font-semibold">Métodos</h3>
          <p class="text-sm text-white/60 mt-1">Técnicas de estudio.</p>
        </a>

        <a href="#"
           data-href="ayudas.php"
           class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
          <div class="flex items-center justify-between">
            <span class="text-3xl">❓</span>
            <span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Ayudas</span>
          </div>
          <h3 class="mt-4 text-xl font-semibold">Ayudas</h3>
          <p class="text-sm text-white/60 mt-1">Recursos útiles.</p>
        </a>
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
  <script>
  // Datos simulados de usuario para el saludo y avatar
    const usuario = {
      nombre: "Usuario"
    };

    // Frases motivacionales (escala de grises en diseño, no en texto)
    const FRASES = [
      { texto: "La disciplina es el puente entre metas y logros.", autor: "Jim Rohn" },
      { texto: "Haz hoy lo que otros no, para vivir mañana como otros no pueden.", autor: "Anónimo" },
      { texto: "Pequeños pasos cada día crean grandes cambios.", autor: "Anónimo" },
      { texto: "El éxito es la suma de pequeños esfuerzos repetidos día tras día.", autor: "Robert Collier" },
      { texto: "La constancia vence lo que la dicha no alcanza.", autor: "Refrán" }
    ];

    // Utilidad: seleccionar
    const $ = (sel, el = document) => el.querySelector(sel);
    const $$ = (sel, el = document) => Array.from(el.querySelectorAll(sel));

    // Saludo
    const nombreSpan = $("#nombre-usuario");
    if (nombreSpan) nombreSpan.textContent = usuario.nombre;

    // Frase aleatoria semanal (persistida en localStorage por semana)
    (function fraseSemanal() {
      try {
        const key = "classmaster.frase.semana";
        const weekId = new Date().getFullYear() + "-w" + (Math.ceil((Date.now() - new Date(new Date().getFullYear(),0,1)) / 604800000));
        const saved = JSON.parse(localStorage.getItem(key) || "null");
        let frase;
        if (saved && saved.week === weekId) {
          frase = saved.value;
        } else {
          frase = FRASES[Math.floor(Math.random() * FRASES.length)];
          localStorage.setItem(key, JSON.stringify({ week: weekId, value: frase }));
        }
        $("#frase-semanal").textContent = "“" + frase.texto + "”";
        $("#frase-autor").textContent = "— " + frase.autor;
      } catch (e) {
        // Fallback simple
        $("#frase-semanal").textContent = "“Cree en ti: vas por buen camino.”";
        $("#frase-autor").textContent = "— ClassMaster";
      }
    })();

    // Menú del avatar (logo)
    const avatarBtn = $("#avatarBtn");
    const avatarMenu = $("#avatarMenu");
    const avatarWrap = $("#avatarMenuWrap");

    const openMenu = () => {
      avatarMenu.style.opacity = "1";
      avatarMenu.style.transform = "scale(1)";
      avatarMenu.style.pointerEvents = "auto";
      avatarBtn.setAttribute("aria-expanded", "true");
      avatarWrap.classList.add("menu-open");
    };
    const closeMenu = () => {
      avatarMenu.style.opacity = "0";
      avatarMenu.style.transform = "scale(0.95)";
      avatarMenu.style.pointerEvents = "none";
      avatarBtn.setAttribute("aria-expanded", "false");
      avatarWrap.classList.remove("menu-open");
    };

    let menuOpen = false;
    avatarBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      menuOpen ? closeMenu() : openMenu();
      menuOpen = !menuOpen;
    });

    document.addEventListener("click", (e) => {
      if (!menuOpen) return;
      if (!avatarWrap.contains(e.target)) {
        closeMenu();
        menuOpen = false;
      }
    });

    // Teclado accesible para el menú (Esc para cerrar)
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && menuOpen) {
        closeMenu();
        menuOpen = false;
        avatarBtn.focus();
      }
    });

    // Acciones del menú
    $("#btnPerfil").addEventListener("click", () => {
      // Demo: mostramos un aviso elegante
      toast("Abriendo perfil…");
      // En un proyecto real, aquí podrías navegar: window.location.href = 'perfil.html';
    });

    $("#btnCerrar").addEventListener("click", () => {
      // Demo: confirmación y redirección simulada
      confirmModal({
        titulo: "¿Cerrar sesión?",
        descripcion: "Se cerrará tu sesión actual.",
        confirmarTxt: "Sí, salir",
        cancelarTxt: "Cancelar",
        onConfirm: () => {
          toast("Sesión cerrada");
          // Simulación de salida
          setTimeout(() => {
            // En un proyecto real, redirige al login:
            // window.location.href = 'login.html';
          }, 900);
        }
      });
    });

    // Navegación segura en tarjetas (usa data-href para evitar saltos en entorno sandbox)
    $$(".card-link").forEach(card => {
      card.addEventListener("click", (e) => {
        e.preventDefault();
        const url = card.getAttribute("data-href");
        if (!url) return;
        // Transición sutil antes de navegar
        document.body.style.transition = "opacity .18s ease";
        document.body.style.opacity = "0.85";
        setTimeout(() => {
          window.location.href = url;
        }, 120);
      });
    });

    // TOAST minimalista
    function toast(msg = "Hecho") {
      const el = document.createElement("div");
      el.textContent = msg;
      el.setAttribute("role", "status");
      el.className = "fixed inset-x-0 mx-auto bottom-6 w-fit max-w-[90%] text-sm md:text-base px-4 py-2 rounded-lg glass ring-soft shadow-2xl";
      document.body.appendChild(el);
      setTimeout(() => {
        el.style.transition = "transform .25s ease, opacity .25s ease";
        el.style.transform = "translateY(8px)";
        el.style.opacity = "0";
        setTimeout(() => el.remove(), 250);
      }, 1400);
    }

    // Modal de confirmación accesible
    function confirmModal({ titulo = "Confirmar", descripcion = "", confirmarTxt = "Aceptar", cancelarTxt = "Cancelar", onConfirm = () => {} } = {}) {
      const overlay = document.createElement("div");
      overlay.className = "fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-4 z-50";
      overlay.innerHTML = `
        <div class="w-full sm:max-w-md glass rounded-2xl border border-white/10 p-5 sm:p-6 animate-in" role="dialog" aria-modal="true">
          <h2 class="text-lg font-semibold">${titulo}</h2>
          <p class="text-white/70 mt-1">${descripcion}</p>
          <div class="flex gap-3 justify-end mt-5">
            <button id="cm-cancel" class="px-4 py-2 rounded-lg hover:bg-white/5 transition focus-outline">${cancelarTxt}</button>
            <button id="cm-ok" class="px-4 py-2 rounded-lg bg-white text-black hover:opacity-90 transition focus-outline">${confirmarTxt}</button>
          </div>
        </div>
      `;
      document.body.appendChild(overlay);
      const ok = overlay.querySelector("#cm-ok");
      const cancel = overlay.querySelector("#cm-cancel");

      const close = () => overlay.remove();
      ok.addEventListener("click", () => { close(); onConfirm(); });
      cancel.addEventListener("click", close);
      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) close();
      });
      document.addEventListener("keydown", function esc(e) {
        if (e.key === "Escape") {
          close();
          document.removeEventListener("keydown", esc);
        }
      });
    }
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'97fcbd9581576de3',t:'MTc1Nzk4NzcxNS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>
</body>
</html>

