// scripts/modalVinculo.js
// Modal para vincular acudientes a estudiantes.
// Funcionalidades:
// - Muestra un modal para ingresar el correo del acudiente.
// - Llama a php/padre_estudiante.php para crear la vinculación.
// - Expone funciones globales para uso desde HTML.
import { toast } from './components.js';

let overlay = null;

export function openModalVinculo(){
    // Crear overlay y modal para vincular acudiente
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
}

export function close(){
    if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
    overlay = null;
}

export function vincularAcudiente(){
    if (!overlay) return;
    const emailInput = overlay.querySelector("#cm-email");
    const email = (emailInput && emailInput.value) ? emailInput.value.trim() : '';
    if (!email) { toast('Ingrese un correo válido', 'error'); return; }
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
            // Opcional: recargar la página de perfil para mostrar el nuevo acudiente (suavizado con timeout)
            setTimeout(() => { try { window.location.reload(); } catch(e){} }, 600);
        } else {
            toast(data.error || 'Error al vincular', "error");
        }
    }).catch(err => {
        console.error(err);
        toast('Error de red al vincular: ' + err, 'error');
    });
}

// Exponer funciones globalmente para llamadas inline o dinámicas desde HTML
window.openModalVinculo = openModalVinculo;
window.closeModalVinculo = close;
window.vincularAcudiente = vincularAcudiente;
