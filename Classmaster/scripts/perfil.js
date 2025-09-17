document.addEventListener("DOMContentLoaded", () => {
    const passwordInputs = document.querySelectorAll('.password-input');
    const eyeIcons = document.querySelectorAll('.eye-icon');
    eyeIcons.forEach((icon, idx) => {
        icon.addEventListener('click', () => {
            if (passwordInputs[idx].type === 'password') {
                passwordInputs[idx].type = 'text';
                icon.name = 'eye-outline';
            } else {
                passwordInputs[idx].type = 'password';
                icon.name = 'eye-off-outline';
            }
        });
    });
});

function toast(msg = "Hecho", type = "success") {
    const el = document.createElement("div");
    el.textContent = msg;
    el.setAttribute("role", "status");
    el.className = `fixed inset-x-0 mx-auto bottom-6 w-fit max-w-[90%] text-sm md:text-base px-4 py-2 rounded-lg glass ring-soft shadow-2xl ${type === "error" ? "bg-red-500" : "bg-green-500"}`;
    document.body.appendChild(el);
    setTimeout(() => {
        el.style.transition = "transform .25s ease, opacity .25s ease";
        el.style.transform = "translateY(8px)";
        el.style.opacity = "0";
        setTimeout(() => el.remove(), 250);
    }, 2500);
}

const form = document.getElementById('formCambiarPassword');

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    if(form.newPassword.value === form.confirmPassword.value && checkPasswordValidity(form.newPassword.value) === ""){
        const result = await pass_change(form.currentPassword.value, form.newPassword.value);
        if (result.success) {
            toast(result.message);

        } else {
            toast(result.error, "error");
        }
    }
    else {
        if(checkPasswordValidity(form.newPassword.value) !== ""){
            toast(checkPasswordValidity(form.newPassword.value), "error");
        }
        else {
            toast("Las contraseñas no coinciden.", "error");
        };
    };
});

const checkPasswordValidity = (password) => {
    if (password.length < 8) {
        return "La nueva contraseña debe tener por lo menos 8 caracteres.";
    }
    if (!/[a-z]/.test(password)) {
        return "La nueva contraseña debe incluir por lo menos una letra minúscula.";
    }
    if (!/[A-Z]/.test(password)) {
        return "La nueva contraseña debe incluir por lo menos una letra mayúscula.";
    }
    if (!/\d/.test(password)) {
        return "La nueva contraseña debe incluir por lo menos un dígito.";
    }
    return "";
};

async function pass_change(currentPassword, newPassword) {
    try {
        const response = await fetch('../php/pass_change.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `currentPassword=${encodeURIComponent(currentPassword)}&newPassword=${encodeURIComponent(newPassword)}`
        });
        return await response.json();
    } catch (err) {
        return { success: false, error: "No se pudo conectar al servidor." };
    };
};