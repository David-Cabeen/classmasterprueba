const container = document.querySelector(".container");
const btnsignin = document.getElementById("btn-sign-in");
const btnsignup = document.getElementById("btn-sign-up");
const passwords = document.querySelectorAll(".password");
const icons = document.querySelectorAll(".eye-icon");
const signin = document.querySelector(".sign-in");
const signup = document.querySelector(".sign-up");
const feedback = document.getElementById("feedback");
const feedbackWindow = document.getElementById("feedback-window");
const overlay = document.getElementById("overlay");
const emailInput = document.getElementById("email");
const numberInput = document.getElementById("number");
const feedbackClose = document.getElementById("feedback-close");

btnsignin.addEventListener("click", () => {
    container.classList.remove("toggle");
});

btnsignup.addEventListener("click", () => {
    container.classList.add("toggle");
});

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

const checkEmailValidity = (email) => {
    const pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!pattern.test(email)) {
        return "Formato de correo invalido.";
    }
    return "";
};

signup.addEventListener("submit", async (e) => {
    e.preventDefault();
    if(!emailInput.value || !passwords[1].value){
        feedbackWindow.style.display = "flex";
        overlay.style.display = "block";
        setTimeout(() => {
            overlay.style.opacity = 1; 
            feedbackWindow.style.right = "0.5rem"
        }, 1);   
        feedback.textContent = "Por favor, completa todos los campos.";
    }
    else {
        const emailError = checkEmailValidity(emailInput.value);
        const passwordError = checkPasswordValidity(passwords[1].value);
        
        if(emailError == "" && passwordError == ""){
            // Enviar datos al servidor
            const result = await registerStudent(emailInput.value, passwords[1].value);
            if (result.success) {
                alert(result.message);
                window.location.assign("home.php");
            } else {
                feedbackWindow.style.display = "flex";
                overlay.style.display = "block";
                setTimeout(() => {
                    overlay.style.opacity = 1; 
                    feedbackWindow.style.right = "0.5rem"
                }, 1);   
                feedback.textContent = result.error;
            }
        }
        else {
            feedbackWindow.style.display = "flex";
            overlay.style.display = "block";
            setTimeout(() => {
                overlay.style.opacity = 1; 
                feedbackWindow.style.right = "0.5rem"
            }, 1);   
            feedback.textContent = `${emailError}${emailError && passwordError ? '\n' : ''}${passwordError}`;
        }
    }
});

signin.addEventListener('submit', async (e) => {
    e.preventDefault();
    const idInput = document.getElementById("id");
    const passwordInput = document.getElementById("password");
    const result = await checkAccount(idInput.value, passwordInput.value);
    if (result.success) {
        window.location.assign('home.php');
    } else {
        feedbackWindow.style.display = "flex";
        overlay.style.display = "block";
        setTimeout(() => {
            overlay.style.opacity = 1; 
            feedbackWindow.style.right = "0.5rem"
        }, 1);   
        feedback.textContent = result.error || "Error desconocido.";
    }
});

async function checkAccount(id, password) {
    try {
        const response = await fetch('../php/inicio.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${encodeURIComponent(id)}&password=${encodeURIComponent(password)}`
        });
        return await response.json();
    } catch (err) {
        return { success: false, error: "No se pudo conectar al servidor." };
    }
}

feedbackClose.addEventListener("click", () => {
    overlay.style.opacity = 0; 
    feedbackWindow.style.right = "-20rem"
    setTimeout(() => {
        feedbackWindow.style.display = "none";
        overlay.style.display = "none";
    }, 500);   
});

async function registerStudent(email, password) {
    try {
        const response = await fetch('../php/registro.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&nombre=${encodeURIComponent(document.getElementById("nombre").value)}`
        });
        return await response.json();
    } catch (err) {
        return { success: false, error: "No se pudo conectar al servidor." };
    }
}