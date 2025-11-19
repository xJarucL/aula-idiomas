<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aula de Inglés')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" href="{{ asset('img/logo-ingles.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-200 min-h-screen">
    <nav class="bg-teal-600 text-white px-3 sm:px-10 py-3 sm:py-1 sticky top-0 z-50">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-1">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-lg flex items-center justify-center p-2">
                    <img src="{{ asset('img/logo-ingles.png') }}" class="w-10 h-10 sm:w-12 sm:h-12 object-contain"
                        alt="logo">
                </div>
                <span class="font-semibold text-sm sm:text-lg">Language Coordination</span>
            </div>

            <!-- Links -->
            <div class="flex-1 flex justify-center">
                <div class="hidden md:flex items-center gap-14">
                    @auth
                        @if (auth()->user()->fk_tipo_usuario == '1')
                            <a href="{{ route('alumno.inicio') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Panel</a>
                            <a href="{{ route('alumno.lista-actividades') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Mis
                                actividades</a>
                            <a href="{{ route('alumno.progreso') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Progreso</a>
                        @elseif(auth()->user()->fk_tipo_usuario == '2')
                            <a href="{{ route('docente.inicio') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Panel</a>
                            <a href="{{ route('docente.mis-grupos') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Mis
                                Grupos</a>
                            <a href="{{ route('docente.lista-actividades') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 transition text-m px-2 py-5 rounded">Actividades</a>
                            <a href="{{ route('docente.actividades-pendientes') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 transition text-m px-2 py-5 rounded">Pendientes</a>
                        @elseif(auth()->user()->fk_tipo_usuario == '3')
                            <a href="{{ route('coordinacion.inicio') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Panel</a>
                            <a href="{{ route('coordinacion.lista-docentes') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Docentes</a>
                            <a href="{{ route('coordinacion.lista-alumnos') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Alumnos</a>
                            <a href="{{ route('coordinacion.lista-grupos') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 transition text-m px-2 py-5 rounded">Grupos</a>
                            <a href="{{ route('coordinacion.lista-coordinador') }}"
                                class="font-semibold text-white hover:text-white hover:bg-teal-800 transition text-m px-2 py-5 rounded">Coordinación</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Icono de Chat -->
                <a href="{{route('chat.inicio')}}" class="text-white hover:text-teal-100 transition" aria-label="Mensajes">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                </a>
                <!-- Icono de Notificaciones -->
                <a href="#" class="text-white hover:text-teal-100 transition" aria-label="Notificaciones">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </a>
                <!-- Icono de Usuario -->
                <div x-data="{ open: false }" class="relative hidden sm:block">
                    <button @click="open = !open"
                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center hover:bg-teal-50 transition cursor-pointer"
                        aria-label="Perfil">
                        <img src="{{ Auth::user()->img_user ? asset('storage/' . Auth::user()->img_user) : asset('img/default.jpg') }}"
                            alt="Perfil" class="w-12 h-12 rounded-full object-cover border">

                    </button>
                    <!-- Menú desplegable -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-40 bg-white rounded-sm shadow-lg z-50">
                        @auth
                            @if (auth()->user()->fk_tipo_usuario == '1')
                                <a href="{{ route('alumno.perfil') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-teal-200 rounded-t">Perfil</a>
                            @elseif(auth()->user()->fk_tipo_usuario == '2')
                                <a href="{{ route('docente.perfil') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-teal-200 rounded-t">Perfil</a>
                            @elseif(auth()->user()->fk_tipo_usuario == '3')
                                <a href="{{ route('coordinador.perfil') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-teal-200 rounded-t">Perfil</a>
                            @endif
                        @endauth
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" data-swal-form data-target-form="logout-form"
                                data-swal-title="Cerrar sesión"
                                data-swal-text="¿Deseas cerrar tu sesión actualmente activa?" data-swal-icon="warning"
                                data-swal-confirm="Sí, cerrar sesión" data-swal-cancel="Cancelar"
                                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white rounded-b-sm cursor-pointer">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>

                {{-- MENU HAMBURGESA --}}
                <div x-data="{ openMenu: false }" class="block sm:hidden">
                    {{-- BOTON HAMBURGESA --}}
                    <button @click="openMenu = true" class="text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- FONDO --}}
                    <template x-if="openMenu">
                        <div class="fixed inset-0 bg-gray-700 opacity-20 z-40 transition-opacity duration-300"
                            @click="openMenu = false" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transitiopn:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        </div>
                    </template>

                    {{-- MENU --}}
                    <template x-if="openMenu">
                        <div class="fixed top-0 right-0 w-64 bg-teal-600 h-full shadow-lg z-50 transform transition-transform duration-300"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                            <div class="flex flex-col justify-between h-full">
                                <div>
                                    <div class="p-4 rounded-l-full flex justify-between items-center">
                                        <h2 class="font-semibold text-lg text-white">Menú</h2>
                                        <button @click="openMenu = false"
                                            class="text-white hover:text-red-500 text-2xl font-bold">×</button>
                                    </div>

                                    {{-- ICONO PERFIL --}}
                                    <div>
                                        <div x-data="{ open: false }" class="flex justify-center items-center mt-3">
                                            <button @click="open = !open"
                                                class="w-20 h-20 bg-white rounded-full flex items-center justify-center hover:bg-teal-50 transition cursor-pointer"
                                                aria-label="Perfil">
                                                <img src="{{ Auth::user()->img_user ? asset('storage/' . Auth::user()->img_user) : asset('img/default.jpg') }}"
                                                    alt="Perfil" class="w-12 h-12 rounded-full object-cover border">

                                            </button>
                                        </div>
                                    </div>

                                    <div class="p-4 flex flex-col gap-3">
                                        {{-- BOTONES MENU --}}
                                        @auth
                                            @if (auth()->user()->fk_tipo_usuario == '1')
                                                <a href="{{ route('alumno.inicio') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Panel</a>
                                                <a href="{{ route('alumno.lista-actividades') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Mis
                                                    actividades</a>
                                                <a href="{{ route('alumno.progreso') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Progreso</a>
                                            @elseif(auth()->user()->fk_tipo_usuario == '2')
                                                <a href="{{ route('docente.inicio') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Panel</a>
                                                <a href="{{ route('docente.mis-grupos') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Mis
                                                    Grupos</a>
                                                <a href="{{ route('docente.lista-actividades') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 transition text-m px-2 py-5 rounded">Actividades</a>
                                                <a href="{{ route('docente.actividades-pendientes') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 transition text-m px-2 py-5 rounded">Pendientes</a>
                                            @elseif(auth()->user()->fk_tipo_usuario == '3')
                                                <a href="{{ route('coordinacion.inicio') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Panel</a>
                                                <a href="{{ route('coordinacion.lista-docentes') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Docentes</a>
                                                <a href="{{ route('coordinacion.lista-alumnos') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 px-2 py-5 rounded text-m">Alumnos</a>
                                                <a href="{{ route('coordinacion.lista-grupos') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 transition text-m px-2 py-5 rounded">Grupos</a>
                                                <a href="{{ route('coordinacion.lista-coordinador') }}"
                                                    class="font-semibold text-white hover:text-white hover:bg-teal-800 transition text-m px-2 py-5 rounded">Coordinación</a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                {{-- PERFIL Y CERRAR SECION --}}
                                <div class="p-4 border-t">
                                    @auth
                                        @if (auth()->user()->fk_tipo_usuario == '1')
                                            <a href="{{ route('alumno.perfil') }}"
                                                class="block p-5 text-white hover:bg-teal-800 rounded-t">Perfil</a>
                                        @elseif(auth()->user()->fk_tipo_usuario == '2')
                                            <a href="{{ route('docente.perfil') }}"
                                                class="block p-5 text-white hover:bg-teal-800 rounded-t">Perfil</a>
                                        @elseif(auth()->user()->fk_tipo_usuario == '3')
                                            <a href="{{ route('coordinador.perfil') }}"
                                                class="block p-5 text-white hover:bg-teal-800 rounded-t">Perfil</a>
                                        @endif
                                    @endauth
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" data-swal-form data-target-form="logout-form"
                                            data-swal-title="Cerrar sesión"
                                            data-swal-text="¿Deseas cerrar tu sesión actualmente activa?"
                                            data-swal-icon="warning" data-swal-confirm="Sí, cerrar sesión"
                                            data-swal-cancel="Cancelar"
                                            class="w-full text-left p-5 text-white hover:bg-red-500 hover:text-white rounded-b-sm cursor-pointer">
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </nav>

    <main class="p-5 md:mr-20 md:ml-20">
        @yield('content')
    </main>

    <main class="bg-white">
        @yield('chat')
    </main>

    @yield('scripts')
</body>

</html>
