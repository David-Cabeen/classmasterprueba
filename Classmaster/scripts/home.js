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
				const weekId = new Date().getFullYear() + "-w" + (Math.ceil((Date.now() - new Date(new Date().getFullYear(), 0, 1)) / 604800000));
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
					setTimeout(() => {
						window.location.href = '../pages/inicio.html';
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
		function confirmModal({ titulo = "Confirmar", descripcion = "", confirmarTxt = "Aceptar", cancelarTxt = "Cancelar", onConfirm = () => { } } = {}) {
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