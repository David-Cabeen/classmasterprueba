import { toast } from "./components.js";

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
            };
        });
    });
    const form = document.getElementById('formCambiarPassword');

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        if(form.newPassword.value === form.confirmPassword.value && checkPasswordValidity(form.newPassword.value) === ""){
            const result = await pass_change(form.currentPassword.value, form.newPassword.value);
            if (result.success) {
                toast(result.message, "success");

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

    const edit = document.querySelector('.edit-icon');

    edit.addEventListener('click', () => {
        phoneChange();
    });

    function phoneChange() {
        const telefono_usuario = document.getElementById('telefono-usuario');
        telefono_usuario.contentEditable = true;
        telefono_usuario.style.outline = "transparent";
        telefono_usuario.focus();
        telefono_usuario.style.borderRadius = "5px";
        telefono_usuario.style.padding = ".5rem 1rem";
        telefono_usuario.style.border = "1px solid var(--text)";
        toast("Haz cambios y presiona Enter para guardar.", 'success');
        telefono_usuario.addEventListener('keydown', async (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                telefono_usuario.contentEditable = false;
                telefono_usuario.style.border = "none";
                telefono_usuario.style.padding = "0";
                const newTelefono = telefono_usuario.textContent.trim();
                const result = await updatePhone(newTelefono);
                if (result.success) {
                    toast("Teléfono actualizado.", "success");
                } else {
                    toast(result.error, "error");
                    telefono_usuario.textContent = result.previousPhone || '';
                };
            };
        });
    };

    const updatePhone = async (newTelefono) => {
        try {
            const response = await fetch('../php/update_phone.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `telefono=${encodeURIComponent(newTelefono)}`
            });
            return await response.json();
        } catch (err) {
            return { success: false, error: "No se pudo conectar al servidor." };
        };
    };
});
