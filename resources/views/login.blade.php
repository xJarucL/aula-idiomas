@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<main class="bg-gray-100">
    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-xs sm:max-w-sm md:max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Bienvenido de nuevo</h1>
                <h3 class="text-gray-600 text-sm">Inicia sesión para continuar con tus actividades</h3>
            </div>
            <form class="space-y-4" action="" method="post">
                @csrf
                <div>
                    <input
                        class="bg-white w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                        type="text" inputmode="numeric" pattern="[0-9]*" id="matricula" name="matricula"
                        placeholder="Matrícula" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div>
                    <input
                        class="bg-white w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                        type="password" id="password" name="password" placeholder="Contraseña" required>
                </div>
                <div class="text-right">
                    <a href="#" class="text-sm text-teal-500 hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                <button
                    class="w-full bg-teal-600 text-white font-medium py-3 rounded-lg hover:bg-teal-700 transition duration-200 shadow-md"
                    type="submit">
                    Iniciar Sesión
                </button>
            </form>
        </div>
</main>
