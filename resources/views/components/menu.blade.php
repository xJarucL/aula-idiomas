<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aula de Inglés')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-200 min-h-screen">

   <nav class="bg-teal-600 text-white p-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center p-1.5">
                    <!-- Logo aquí -->
                </div>
                <span class="font-semibold text-lg">Language Coordination</span>
            </div>

            <!-- Links -->
            <div class="flex-1 flex justify-center">
                <div class="hidden md:flex items-center gap-14">
                    <a href="#" class="text-white hover:text-teal-950 transition text-sm font-medium">Panel</a>
                    <a href="#" class="text-white hover:text-teal-950 transition text-sm font-medium">Docentes</a>
                    <a href="#" class="text-white hover:text-teal-950 transition text-sm font-medium">Alumnos</a>
                    <a href="#" class="text-white hover:text-teal-950 transition text-sm font-medium">Grupos</a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- Icono de Chat -->
                <a href="#" class="text-white hover:text-teal-100 transition" aria-label="Mensajes">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </a>
                <!-- Icono de Notificaciones -->
                <a href="#" class="text-white hover:text-teal-100 transition" aria-label="Notificaciones">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </a>
                <!-- Icono de Usuario -->
               <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="w-9 h-9 bg-white rounded-full flex items-center justify-center hover:bg-teal-50 transition" aria-label="Perfil">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </button>
                <!-- Menú desplegable -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white rounded-sm shadow-lg z-50">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-teal-50 rounded-lg">Perfil</a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                data-swal-form
                                data-target-form="logout-form"
                                data-swal-title="Cerrar sesión"
                                data-swal-text="¿Deseas cerrar tu sesión actualmente activa?"
                                data-swal-icon="warning"
                                data-swal-confirm="Sí, cerrar sesión"
                                data-swal-cancel="Cancelar"
                                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white rounded-b-sm">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="p-10">
        @yield('content')
    </main>

</body>
</html>
