import { toast } from './components.js';

const emailInput = document.getElementById("email");
const form = document.getElementById("pass");

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const result = await resetPassword(emailInput.value);
    if (result.success) {
        toast(result.message, "success");
        setTimeout(() => {
            window.location.assign("../index.php");
        }, 2000);
    } else {
        toast(result.error, "error");
    };
});

async function resetPassword(email) {
    try {
        const response = await fetch('./php/pass_recovery.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `email=${encodeURIComponent(email)}}`
        });
        return await response.json();
    } catch (err) {
        return { success: false, error: "No se pudo conectar al servidor." };
    }
}