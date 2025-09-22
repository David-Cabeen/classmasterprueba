<?php
	session_start();
	if (!isset($_SESSION['user_id'])) {
		header('Location: ../index.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Hogar</title>
	<link rel="stylesheet" href="../styles/home.css" />
	<script src="https://cdn.tailwindcss.com"></script>
	<script type="module" src="../scripts/home.js" defer></script>
	<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>

<body class="min-h-screen flex flex-col selection:bg-white/10 selection:text-white">

	<!-- Header -->
	<header class="w-full">
		<div class="max-w-6xl mx-auto px-6 pt-8 pb-6">
			<div class="flex items-center justify-between gap-4">
				<div class="flex items-center gap-3">
					<div class="size-3 rounded-full bg-white/80 shadow-[0_0_24px_4px_rgba(255,255,255,0.25)]"></div>
					<h1 class="text-lg sm:text-xl md:text-2xl font-semibold tracking-tight">
						Bienvenid@ a ClassMaster, 
						<span class="text-white/90">
							<?php
								echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario';
							?>
						</span>
					</h1>
				</div>

				<!-- Avatar / Logo + Menú -->
				<div id="avatarMenuWrap" class="relative">
					<ion-icon id="avatarBtn" type="button" aria-haspopup="menu" aria-expanded="false"
					class="tooltip focus-outline rounded-full p-1.5 size-6 glass cursor-pointer ring-soft transition hover:scale-[1.05] avatar-ring"
					data-tip="Abrir menú" name="menu"></ion-icon>
					<span class="sr-only">Abrir menú de usuario</span>

					<!-- Menú desplegable -->
					<div id="avatarMenu"
						class="absolute right-0 z-10 mt-3 w-56 origin-top-right glass rounded-xl shadow-2xl border border-white/10 opacity-0 scale-95 pointer-events-none transition transform">
						<div class="p-3">
							<div class="flex items-center gap-3 p-3 rounded-lg">
								<div class="size-9 rounded-full bg-white/90 text-black flex items-center justify-center font-bold">
									<?php
										if (isset($_SESSION['nombre']) && isset($_SESSION['apellido'])) {
											echo strtoupper(htmlspecialchars($_SESSION['nombre'][0] . $_SESSION['apellido'][0]));
										} else {
											echo '<ion-icon style="color: #0e0e10" name="person"></ion-icon>';
										}
									?>
								</div>
								<div>
									<p class="text-sm font-medium">
										<?php
											echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario';
										?>
										<?php
											echo isset($_SESSION['apellido']) ? htmlspecialchars($_SESSION['apellido']) : '';
										?>
									</p>
									<p class="text-xs text-white/60">
										<?php
											echo isset($_SESSION['rol']) ? ucfirst(strtolower(htmlspecialchars($_SESSION['rol']))) : '';
										?>
									</p>
								</div>
							</div>
							<div class="divider my-1"></div>
							<button id="btnPerfil"
								class="w-full text-left px-3 py-2 rounded-lg cursor-pointer hover:bg-white/5 transition focus-outline">
								Perfil
							</button>
							<button id="btnCerrar"
								class="w-full text-left px-3 py-2 rounded-lg cursor-pointer hover:bg-white/5 transition focus-outline">
								Cerrar sesión
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Subtítulo -->
			<p class="mt-4 text-sm text-white/60">¿Qué deseas hacer hoy?</p>
		</div>
		<div class="divider"></div>
	</header>

	<!-- Main -->
	<main class="flex-1 w-full">
		<section class="max-w-6xl mx-auto px-6 py-8">
			<!-- Grid de tarjetas -->
				<?php
				   if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'acudiente') {
					echo '
					<div class="grid sm:grid-cols-2 lg:grid-cols-2 gap-6">
						<a href="#"
						data-href="notas.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl" aria-hidden="true">📚</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Notas</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Notas</h3>
						<p class="text-sm text-white/60 mt-1">Revisa tus notas.</p>
						</a>

						<a href="#"
						data-href="calendario.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl">🗓️</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Calendario</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Calendario</h3>
						<p class="text-sm text-white/60 mt-1">Planifica tu semana.</p>
						</a>
					</div>
                  ';
				} else if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'estudiante') {
					echo '
					<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
						<a href="#"
						data-href="notas.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl" aria-hidden="true">📚</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Notas</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Notas</h3>
						<p class="text-sm text-white/60 mt-1">Revisa tus notas.</p>
						</a>
						<a href="#"
						data-href="calendario.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl">🗓️</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Calendario</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Calendario</h3>
						<p class="text-sm text-white/60 mt-1">Planifica tu semana.</p>
						</a>
						<a href="#"
						data-href="tareas.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl">📝</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Tareas</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Tareas</h3>
						<p class="text-sm text-white/60 mt-1">Gestiona tu lista.</p>
						</a>
						<a href="#"
						data-href="pomodoro.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl">🍅</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Pomodoro</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Pomodoro</h3>
						<p class="text-sm text-white/60 mt-1">Enfoque por intervalos.</p>
						</a>
						<a href="#"
						data-href="metodos.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl">📒</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Métodos</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Métodos</h3>
						<p class="text-sm text-white/60 mt-1">Técnicas de estudio.</p>
						</a>
						<a href="#"
						data-href="ayudas.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl">❓</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Ayudas</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Ayudas</h3>
						<p class="text-sm text-white/60 mt-1">Recursos útiles.</p>
						</a>
					</div>';
				} else if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador') {
					echo '
					<div class="grid sm:grid-cols-1 lg:grid-cols-1 gap-6">
						<a href="#"
						data-href="entidades.php"
						class="card-link card-tile hover-glow group rounded-2xl p-6 focus-outline">
						<div class="flex items-center justify-between">
							<span class="text-3xl" aria-hidden="true">👥</span>
							<span class="text-xs uppercase tracking-widest text-white/50 group-hover:text-white/70 transition">Entidades</span>
						</div>
						<h3 class="mt-4 text-xl font-semibold">Entidades</h3>
						<p class="text-sm text-white/60 mt-1">Gestiona usuarios y acudientes.</p>
						</a>
					</div>';
				};
				?>
				

				<!-- Frase motivacional -->
				<section class="mt-10">
					<div class="glass rounded-2xl p-6 md:p-8 ring-soft animate-in">
						<blockquote class="text-xl md:text-2xl leading-relaxed" id="frase-semanal">
							Cargando motivación…
						</blockquote>
						<p class="mt-2 text-white/50 text-sm" id="frase-autor"></p>
					</div>
				</section>
		</section>
	</main>

	<!-- Footer -->
	<footer class="mt-auto">
		<div class="divider"></div>
		<div class="max-w-6xl mx-auto px-6 py-6 text-sm text-white/50">
			©️ 2025 · ClassMaster — todos los derechos reservados
		</div>
	</footer>
	<script>
	</script>
	<script>
		(function () {
			function c() {
				var b = a.contentDocument || a.contentWindow.document;
				if (b) {
					var d = b.createElement('script');
					d.innerHTML =
						"window.__CF$cv$params={r:'97fcbd9581576de3',t:'MTc1Nzk4NzcxNS4wMDAwMDA='};" +
						"var a=document.createElement('script');" +
						"a.nonce='';" +
						"a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';" +
						"document.getElementsByTagName('head')[0].appendChild(a);";
					b.getElementsByTagName('head')[0].appendChild(d);
				}
			}
			if (document.body) {
				var a = document.createElement('iframe');
				a.height = 1;
				a.width = 1;
				a.style.position = 'absolute';
				a.style.top = 0;
				a.style.left = 0;
				a.style.border = 'none';
				a.style.visibility = 'hidden';
				document.body.appendChild(a);
				if ('loading' !== document.readyState) {
					c();
				} else if (window.addEventListener) {
					document.addEventListener('DOMContentLoaded', c);
				} else {
					var e = document.onreadystatechange || function () { };
					document.onreadystatechange = function (b) {
						e(b);
						if ('loading' !== document.readyState) {
							document.onreadystatechange = e;
							c();
						}
					};
				}
			}
		})();
	</script>
</body>

</html>