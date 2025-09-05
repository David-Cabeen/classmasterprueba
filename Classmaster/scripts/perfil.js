function toggleTheme() {
            const body = document.body;
            const button = document.querySelector('.theme-toggle');
            
            if (body.getAttribute('data-theme') === 'dark') {
                body.removeAttribute('data-theme');
                button.textContent = '🌙 Cambiar Tema';
                localStorage.setItem('theme', 'light');
            } else {
                body.setAttribute('data-theme', 'dark');
                button.textContent = '☀️ Cambiar Tema';
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const button = document.querySelector('.theme-toggle');
            
            if (savedTheme === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
                button.textContent = '☀️ Cambiar Tema';
            }
        });

        // Photo change functionality
        function changePhoto() {
            document.getElementById('photoInput').click();
        }

        function handlePhotoChange(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePhoto').src = e.target.result;
                    showSuccessMessage();
                };
                reader.readAsDataURL(file);
            }
        }

        // Form submission handlers
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Simulate saving data
            showSuccessMessage();
        });

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                alert('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
                return;
            }
            
            if (newPassword.length < 6) {
                alert('La nueva contraseña debe tener al menos 6 caracteres.');
                return;
            }
            
            // Simulate password change
            showSuccessMessage();
            
            // Clear password fields
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        });

        function showSuccessMessage() {
            const message = document.getElementById('successMessage');
            message.style.display = 'block';
            setTimeout(() => {
                message.style.display = 'none';
            }, 3000);
        }