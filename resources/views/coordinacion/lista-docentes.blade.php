@extends('components.menu')

@section('title', 'Listado de docente | Aula de Idiomas')

@section('content')
    <x-msj-alert />

    <section class=" flex sm:flex-row justify-between gap-6 sm:gap-2">
        <div class="">
            <h1 class="text-2xl md:text-4xl font-bold text-teal-700">Gestión de docente</h1>
            <span class="text-gray-500 font-light text-justify sm:mt-2 block">Listado de Información de los docentes</span>
        </div>
        {{-- ESCRITORIO --}}
        <div class="hidden sm:flex justify-end items-center">
            <a href="{{ route('coordinacion.registro-docente') }}"
                class="bg-teal-600 text-white text-center p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">
                Registrar docente
            </a>
        </div>
        {{-- MOVIL --}}
        <div class="sm:hidden flex items-center">
            <a href="{{ route('coordinacion.registro-docente') }}"
                class="bg-teal-600 p-3 rounded-2xl hover:bg-teal-700 shadow text-sm md:text-base">
                <svg fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" class="w-6 h-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0m1.5-8.25h-3m1.5-1.5v3" />
                </svg>

            </a>
        </div>
    </section>
    {{-- BUSCADOR --}}
    <section class="flex md:flex-row justify-between mt-5 gap-3">
        <div class="relative w-full">
            <input id="buscador"
                data-url="{{ Route::currentRouteName() == 'coordinacion.lista-docentes' ? route('coordinacion.lista-docentes') : route('coordinacion.lista-docentes-deshabilitados') }}"
                class="bg-white border border-gray-300 rounded-lg p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
                type="text" placeholder="Buscar nombre o correo electrónico..." value="{{ request('search') }}">
            <span id="limpiar-busqueda"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg font-bold hover:text-red-500 hover:scale-125 transition-all duration-200 hidden">
                &times;
            </span>
        </div>
        <button id="btn-buscar"
            class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
    </section>
    {{-- CONTENIDO TABLA --}}
    <section class="overflow-x-auto mt-5">
        <div class="border-2 border-gray-300 rounded-xl p-2 bg-white shadow" id="tabla-listado">
            @include('partials.tabla_docentes', ['docentes' => $docentes])
        </div>
    </section>

    {{--  --}}
    <div class="flex items-center justify-end gap-2 mt-4">
        @if (Route::currentRouteName() == 'coordinacion.lista-docentes')
            <a href="{{ route('coordinacion.lista-docentes-deshabilitados') }}"
                class="bg-yellow-600 text-white m-3 p-2 rounded-lg hover:bg-yellow-700 shadow text-sm md:text-base">
                Ver Docentes Deshabilitados
            </a>
        @elseif(Route::currentRouteName() == 'coordinacion.lista-docentes-deshabilitados')
            <a href="{{ route('coordinacion.lista-docentes') }}"
                class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">
                Ver Docentes Habilitados
            </a>
        @endif
    </div>
@endsection
