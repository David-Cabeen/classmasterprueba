import { toast } from "./components.js";

const sidebar = document.getElementById("sidebar");
const sidebarToggle = document.getElementById("sidebarToggle");
const sidebarTexts = document.querySelectorAll(".sidebar-text");
const sideLogout = document.getElementById("sideLogout");

sideLogout.addEventListener("click", () => {
  logout();
});

let sidebarOpen = false;
sidebarToggle.addEventListener("click", () => {
  sidebarOpen = !sidebarOpen;
  if (sidebarOpen) {
    sidebar.classList.add("sidebar-open");
    sidebarToggle.classList.add("open");
    setTimeout(() => {
      sidebarToggle.classList.add("rotate");
    }, 100);
    sidebarTexts.forEach((t) => t.classList.remove("hidden"));
  } else {
    sidebar.classList.remove("sidebar-open");
    setTimeout(() => {
      sidebarToggle.classList.remove("rotate");
    }, 100);
    setTimeout(() => {
      sidebarToggle.classList.remove("open");
      sidebarTexts.forEach((t) => t.classList.add("hidden"));
    }, 300);

  }
});

function logout() {
  confirmModal({
    titulo: "¿Cerrar sesión?",
    descripcion: "Se cerrará tu sesión actual.",
    confirmarTxt: "Sí, salir",
    cancelarTxt: "Cancelar",
    onConfirm: async () => {
      toast("Sesión cerrada");
      // Llama a logout.php para destruir la sesión
      await fetch("../php/logout.php", {
        method: "GET",
        credentials: "same-origin",
      });
      setTimeout(() => {
        window.location.href = "../index.php";
      }, 900);
    },
  });
}
