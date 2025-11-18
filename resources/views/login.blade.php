@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<main class="bg-gray-100">

    <x-msj-alert />

    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        <div
            class="w-full max-w-xs sm:max-w-sm md:max-w-md
            border border-gray-200 p-5 bg-white rounded-2xl shadow-sm        
        ">
            <div class="flex justify-center items-center">
                <img src="{{ asset('img/logo-ingles.png') }}" class="w-28 h-23 sm:w-39 sm:h-34" alt="logo idioma">

            </div>
            <div class="text-center mb-5 sm:mb-8">
                <h1 class="text-2xl sm:text-4xl font-bold text-teal-700 mb-2">Bienvenido de nuevo</h1>
                <h3 class="text-gray-600 text-[13px] sm:text-sm">Inicia sesión para continuar con tus actividades</h3>
            </div>
            <form id="form-insertar" data-url="{{ route('iniciando') }}" class="space-y-4" action="" method="post">
                @csrf
                <div>
                    <input
                        class="bg-white w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                        type="text" inputmode="numeric" pattern="[0-9]*" id="matricula" name="matricula"
                        placeholder="Matrícula" required maxlength="9"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)">
                </div>
                <div>
                    <input
                        class="bg-white w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                        type="password" id="password" name="password" placeholder="Contraseña" required>
                </div>
                <div class="text-right">
                    <a href="{{ route('recuperar-contrasena') }}"
                        class="text-[10px] sm:text-sm text-teal-500 hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                <input
                    class="w-full bg-teal-600 text-white font-medium py-3 rounded-lg hover:bg-teal-700 transition duration-200 shadow-md"
                    type="submit" value="Iniciar sesión">
            </form>
            <a href="{{ route('google.login') }}"
                class="flex items-center justify-center w-full mt-4 bg-white border border-gray-300 text-gray-700 font-medium py-3 rounded-lg shadow-md hover:bg-gray-50 transition duration-200">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 533.5 544.3">
                    <path
                        d="M533.5 278.4c0-17.4-1.5-34.1-4.3-50.4H272v95.4h147.5c-6.4 34.5-25.8 63.7-55 83.2v68h88.8c51.8-47.7 80.2-118 80.2-196.2z"
                        fill="#4285F4" />
                    <path
                        d="M272 544.3c74.7 0 137.4-24.7 183.2-66.9l-88.8-68c-24.7 16.5-56.4 26.3-94.4 26.3-72.7 0-134.2-49-156.2-114.8h-91.6v71.8C70.1 482 163.7 544.3 272 544.3z"
                        fill="#34A853" />
                    <path
                        d="M115.8 320.9c-5.6-16.5-8.8-34.3-8.8-52.4s3.2-35.9 8.8-52.4v-71.8h-91.6C7.7 183.3 0 227.6 0 268.5s7.7 85.2 24.2 124.2l91.6-71.8z"
                        fill="#FBBC05" />
                    <path
                        d="M272 107.7c40.6 0 77 13.9 105.7 41.1l79.1-79.1C409.4 24.7 346.7 0 272 0 163.7 0 70.1 62.3 24.2 159.1l91.6 71.8c22-65.8 83.5-114.8 156.2-114.8z"
                        fill="#EA4335" />
                </svg>
                Iniciar sesión con Google
            </a>
        </div>
</main>
