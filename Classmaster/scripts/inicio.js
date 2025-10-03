// Importa funciones utilitarias para mostrar notificaciones (toast) y determinar el tipo de usuario (checkType)
import { toast, checkType } from "./components.js";

// Elementos del DOM para manipular la UI de inicio/registro de sesión
const container = document.querySelector(".container");
const btnsignin = document.getElementById("btn-sign-in");
const btnsignup = document.getElementById("btn-sign-up");
const passwords = document.querySelectorAll(".password");
const icons = document.querySelectorAll(".eye-icon");
const signin = document.querySelector(".sign-in");
const signup = document.querySelector(".sign-up");
const overlay = document.getElementById("overlay");
const emailInput = document.getElementById("email");
const numberInput = document.getElementById("number");

// Alterna entre los formularios de inicio de sesión y registro
btnsignin.addEventListener("click", () => {
    container.classList.remove("toggle");
});

btnsignup.addEventListener("click", () => {
    container.classList.add("toggle");
});

// Permite mostrar/ocultar la contraseña al hacer clic en el icono de ojo
icons.forEach((icon, index) => {
    icon.addEventListener("click", () => {
        if (passwords[index].type === "password") {
            passwords[index].type = "text";
            icon.name = "eye-outline";
        } else {
            passwords[index].type = "password";
            icon.name = "eye-off-outline";
        }
    });
});

// Valida la seguridad de la contraseña según criterios mínimos
const checkPasswordValidity = (password) => {
    if (password.length < 8) {
        return "La contraseña debe tener por lo menos 8 caracteres.";
    }
    if (!/[a-z]/.test(password)) {
        return "La contraseña debe incluir por lo menos una letra minúscula.";
    }
    if (!/[A-Z]/.test(password)) {
        return "La contraseña debe incluir por lo menos una letra mayúscula.";
    }
    if (!/\d/.test(password)) {
        return "La contraseña debe incluir por lo menos un dígito.";
    }
    return "";
};

// Valida el formato del correo electrónico
const checkEmailValidity = (email) => {
    const pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!pattern.test(email)) {
        return "Formato de correo invalido.";
    }
    return "";
};

// Maneja el registro de nuevos estudiantes
// Valida los campos y envía los datos al backend (php/registro.php), que los guarda en la base de datos
signup.addEventListener("submit", async (e) => {
    e.preventDefault();
    if(!emailInput.value || !passwords[1].value){
        toast("Todos los campos obligatorios deben estar completos.", "error");
    }
    else {
        const emailError = checkEmailValidity(emailInput.value);
        const passwordError = checkPasswordValidity(passwords[1].value);
        
        if(emailError == "" && passwordError == ""){
            // Enviar datos al servidor para registrar estudiante
            const result = await registerStudent(emailInput.value, passwords[1].value);
            if (result.success) {
                toast(result.message, "success");
                setTimeout(() => {
                    window.location.assign("./pages/home.php");
                }, 1000);
            } else {
                toast(result.error, "error");
            };
        }
        else {
            toast(`${emailError}${emailError && passwordError ? '\n' : ''}${passwordError}`, "error");
        };
    };
});

// Maneja el inicio de sesión de cualquier tipo de usuario
// Envía los datos a php/inicio.php, que valida contra la base de datos y retorna el resultado
signin.addEventListener('submit', async (e) => {
    e.preventDefault();
    const idInput = document.getElementById("id"),
    passwordInput = document.getElementById("password");
    let type = checkType(idInput.value.trim());

    const result = await checkAccount(idInput.value, passwordInput.value, type);
    if (result.success) {
        toast(result.message, "success");
        setTimeout(() => {
            window.location.assign('./pages/home.php');
        }, 1000);
    } else {
        toast(result.error, "error");
    };
});

// Consulta al backend para validar credenciales de usuario
// Relación con la BBDD: php/inicio.php consulta las tablas de usuarios, profesores o acudientes según el tipo
async function checkAccount(id, password, type) {
    try {
        const response = await fetch('./php/inicio.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${encodeURIComponent(id)}&password=${encodeURIComponent(password)}&type=${encodeURIComponent(type)}`
        });
        return await response.json();
    } catch (err) {
        return { success: false, error: 'No se pudo conectar al servidor \n' + err};
    }
}

// Envía los datos del nuevo estudiante al backend para registrarlo en la base de datos (tabla users)
// php/registro.php se encarga de insertar el usuario y retornar el resultado
async function registerStudent(email, password) {
    try {
        const response = await fetch('./php/registro.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&nombre=${encodeURIComponent(document.getElementById("nombre").value)}`
        });
        return await response.json();
    } catch (err) {
        return { success: false, error: "No se pudo conectar al servidor." };
    }
}