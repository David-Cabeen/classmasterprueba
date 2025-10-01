<div id="sidebarWrap" class="fixed top-0 left-0 h-full z-30 flex">
    <aside id="sidebar" class="sidebar-cm glass ring-soft transition-all duration-300 ease-in-out flex flex-col items-center py-6 px-2 gap-4">
        <button id="sidebarToggle" class="bg-white/10 hover:bg-white/20 p-2 transition focus:outline-none">
            <ion-icon name="chevron-forward-outline"></ion-icon>
        </button>
        <nav class="flex-1 flex flex-col gap-4 w-full">
            <a href="home.php" class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-white/10">
                <ion-icon name="home" class="text-xl"></ion-icon>
                <span class="sidebar-text hidden">Inicio</span>
            </a>
            <a href="perfil.php" class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-white/10">
                <ion-icon name="person" class="text-xl"></ion-icon>
                <span class="sidebar-text hidden">Perfil</span>
            </a>
            <a href="notas.php" class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-white/10">
                <ion-icon name="book" class="text-xl"></ion-icon>
                <span class="sidebar-text hidden">Notas</span>
            </a>
            <a href="calendario.php" class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-white/10">
                <ion-icon name="calendar" class="text-xl"></ion-icon>
                <span class="sidebar-text hidden">Calendario</span>
            </a>
            <a href="flashcards.php" class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-white/10">
                <ion-icon name="albums" class="text-xl"></ion-icon>
                <span class="sidebar-text hidden">Flashcards</span>
            </a>
            <a href="pomodoro.php" class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-white/10">
                <ion-icon name="timer" class="text-xl"></ion-icon>
                <span class="sidebar-text hidden">Pomodoro</span>
            </a>
            <a href="cornell.php" class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-white/10">
                <ion-icon name="document-text" class="text-xl"></ion-icon>
                <span class="sidebar-text hidden">Cornell</span>
            </a>
            <a href="ayudas.php" class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-white/10">
                <ion-icon name="help-circle" class="text-xl"></ion-icon>
                <span class="sidebar-text hidden">Ayudas</span>
            </a>
            <a id='sideLogout' class="sidebar-link flex items-center gap-3 p-2 rounded-lg transition hover:bg-red-500/10">
                <ion-icon name="log-out" class="text-xl text-red-500"></ion-icon>
                <span class="sidebar-text hidden text-red-500">Cerrar Sesión</span>
            </a>
        </nav>
    </aside>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const mainContent = document.getElementById('mainContent');
        const sideLogout = document.getElementById('sideLogout');
        sideLogout.addEventListener('click', () => {
            // Use confirmModal from components.js
            if (typeof confirmModal === 'function') {
                confirmModal({
                    titulo: 'Cerrar sesión',
                    descripcion: '¿Estás seguro de que deseas cerrar sesión?',
                    confirmarTxt: 'Cerrar sesión',
                    cancelarTxt: 'Cancelar',
                    onConfirm: () => {
                        window.location.href = 'logout.php';
                    }
                });
            } else {
                if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                    window.location.href = 'logout.php';
                }
            }
        });
        let sidebarOpen = false;
        sidebarToggle.addEventListener('click', () => {
            sidebarOpen = !sidebarOpen;
            if (sidebarOpen) {
                sidebar.classList.add('sidebar-open');
                sidebarToggle.classList.add('open');
                setTimeout(() => {
                    sidebarToggle.classList.add('rotate');
                }, 100);
                sidebarTexts.forEach(t => t.classList.remove('hidden'));
                mainContent.classList.add('ml-64');
                mainContent.classList.remove('ml-16');
            } else {
                sidebar.classList.remove('sidebar-open');
                setTimeout(() => {
                    sidebarToggle.classList.remove('rotate');
                }, 100);
                setTimeout(() => {
                    sidebarToggle.classList.remove('open');
                    sidebarTexts.forEach(t => t.classList.add('hidden'));
                }, 300);
                
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-16');
            }
        });
    </script>
</div>