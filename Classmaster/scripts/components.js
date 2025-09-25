// Modal de confirmación
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
};

// Toast simple
function toast(msg = "Hecho", type = "") {
    const el = document.createElement("div");
    el.textContent = msg;
    el.setAttribute("role", "status");
    el.className = `toast fixed inset-x-0 mx-auto bottom-6 w-fit max-w-[90%] text-sm md:text-base px-4 py-2 rounded-lg glass ring-soft shadow-2xl ${type === "error" ? "bg-red-500" : type === "success" ? "bg-green-500" : ""}`;
    document.body.appendChild(el);
    setTimeout(() => {
        el.style.transition = "transform .25s ease, opacity .25s ease";
        el.style.transform = "translateY(8px)";
        el.style.opacity = "0";
        setTimeout(() => el.remove(), 250);
    }, 2500);
};

function checkType(id) {
    if(/^[0-9]+$/.test(id) ){
        return "estudiante";
    } else if(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(id) ){
        return "acudiente";
    } else if (/^P[0-9]{6}$/.test(id)) {
		return "profesor";
	} else if (/^[A-Za-z0-9]+$/.test(id)) {
        return "administrador";
    };
}

export { confirmModal, toast, checkType };