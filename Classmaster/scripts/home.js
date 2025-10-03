import { confirmModal, toast } from "./components.js";

document.addEventListener('DOMContentLoaded', () => {
	let overlay = null;

	// Frases motivacionales (escala de grises en diseño, no en texto)
	const FRASES = [
		{ texto: "La disciplina es el puente entre metas y logros.", autor: "Jim Rohn" },
		{ texto: "Haz hoy lo que otros no, para vivir mañana como otros no pueden.", autor: "Anónimo" },
		{ texto: "Pequeños pasos cada día crean grandes cambios.", autor: "Anónimo" },
		{ texto: "El éxito es la suma de pequeños esfuerzos repetidos día tras día.", autor: "Robert Collier" },
		{ texto: "La constancia vence lo que la dicha no alcanza.", autor: "Refrán" }
	];

	if (window.rol == 'acudiente'){
		const number = Math.random();
		if (number < 0.20) {
			fetch('../php/phone.php', { method: 'GET', credentials: 'same-origin' })
			.then(res => res.json())
			.then(data => {
				if (data.success && data.hasPhone) {
					return;
				} else {
					confirmModal({
						titulo: 'No has agregado tu número de teléfono',
						descripcion: 'Por favor, agrega tu número de teléfono para mejorar la comunicación.',
						cancelarTxt: 'En otra ocasión',
						confirmarTxt: 'Agregar teléfono',
						onConfirm: () => window.location.href = '../pages/perfil.php'
					});
				}
			});
		};
	} else if (window.rol == 'estudiante') {
		const number = Math.random();
		if (number < 0.50) {
			fetch('../php/check_padre.php', { method: 'GET', credentials: 'same-origin' })
			.then(res => res.json())
			.then(data => {
				if (data.hasPadre) {
					return;
				} else {
					confirmModal({
						titulo: 'No has agregado a tu acudiente',
						descripcion: 'Por favor, agrega a tu acudiente para mejorar la experiencia.',
						cancelarTxt: 'En otra ocasión',
						confirmarTxt: 'Agregar padre',
						onConfirm: () => modalVinculo()
					});
				}
			});
		};
	};

	function modalVinculo(){
		overlay = document.createElement("div");
		overlay.className = "fixed inset-0 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-4 z-40";
		overlay.innerHTML = `
			<div class="w-full sm:max-w-md glass rounded-2xl border border-white/10 p-5 sm:p-6 animate-in" role="dialog" aria-modal="true">
				<h2 class="text-lg font-semibold">Vincular Acudiente</h2>
				<p class="text-white/70 mt-1">Por favor, ingresa el correo de tu acudiente.</p>
				<input type="email" id="cm-email" class="mt-2 p-2 rounded-lg bg-white/5 border border-white/10 focus:outline" placeholder="Correo del acudiente" required>
				<div class="flex gap-3 justify-end mt-5">
				<button id="cm-cancel" class="px-4 py-2 rounded-lg hover:bg-white/5 transition focus-outline">Cancelar</button>
				<button id="cm-ok" class="px-4 py-2 rounded-lg bg-white text-black hover:opacity-90 transition focus-outline">Vincular</button>
				</div>
			</div>
			`;
		document.body.appendChild(overlay);
		const ok = overlay.querySelector("#cm-ok");
		const cancel = overlay.querySelector("#cm-cancel");

		ok.addEventListener("click", () => { vincularAcudiente(); });
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
	};

	const close = () => overlay.remove();

	function vincularAcudiente() {
		const emailInput = overlay.querySelector("#cm-email");
		const email = emailInput.value.trim();
		fetch("../php/padre_estudiante.php", {
			method: "POST",
			credentials: "same-origin",
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify({ email })
		})
		.then(res => res.json())
		.then(data => {
			if (data.success) {
				toast("Acudiente vinculado exitosamente.", "success");
				close();
			} else {
				toast(data.error, "error");
			}
		});
	}

	// Utilidad: seleccionar
	const $ = (sel, el = document) => el.querySelector(sel);
	const $$ = (sel, el = document) => Array.from(el.querySelectorAll(sel));

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
		toast("Abriendo perfil…");
		window.location.href = '../pages/perfil.php';
	});

	$("#btnCerrar").addEventListener("click", () => {
		confirmModal({
		titulo: "¿Cerrar sesión?",
		descripcion: "Se cerrará tu sesión actual.",
		confirmarTxt: "Sí, salir",
		cancelarTxt: "Cancelar",
		onConfirm: async () => {
			toast("Sesión cerrada");
			// Llama a logout.php para destruir la sesión
			await fetch("../php/logout.php", { method: "GET", credentials: "same-origin" });
			setTimeout(() => {
				window.location.href = '../index.php';
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
});