@extends('components.menu')

@section('title', 'Gestión de Grupos | Aula Idiomas')

@section('content')

    <x-msj-alert />

    <section class="flex sm:flex-row justify-between gap-2 sm:gap-2">
        <div>
            <h1 class="text-2xl md:text-4xl font-bold text-teal-700">Gestión de Grupos</h1>
            <span class="text-gray-500 font-light mt-2 block">Listado de información de los grupos</span>
        </div>
        {{-- ESCRITORIO --}}
        <div class="hidden sm:flex items-center justify-center">
            <a href="{{ route('coordinacion.registro-grupo') }}"
                class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">
                Crear grupo
            </a>
        </div>
        {{-- MOVIL --}}
        <div class="sm:hidden flex items-center justify-center">
            <a href="{{ route('coordinacion.registro-grupo') }}" class="bg-teal-600 p-3 rounded-lg hover:bg-teal-700 shadow">
                <div class="flex sm:flex-none justify-between">
                    <span class="text-white">+</span>
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.6144 7.19994c.3479.48981.5999 1.15357.5999 1.80006 0 1.6569-1.3432 3-3 3-1.6569 0-3.00004-1.3431-3.00004-3 0-.67539.22319-1.29865.59983-1.80006M6.21426 6v4m0-4 6.00004-3 6 3-6 2-2.40021-.80006M6.21426 6l3.59983 1.19994M6.21426 19.8013v-2.1525c0-1.6825 1.27251-3.3075 2.95093-3.6488l3.04911 2.9345 3-2.9441c1.7026.3193 3 1.9596 3 3.6584v2.1525c0 .6312-.5373 1.1429-1.2 1.1429H7.41426c-.66274 0-1.2-.5117-1.2-1.1429Z" />
                    </svg>
                </div>
            </a>
        </div>
    </section>

    <section class="flex flex-col md:flex-row justify-between mt-5 gap-3 sm:gap-5">
        <div class="flex justify-between gap-2 w-full">
            <div class="relative w-full">
                <input id="buscador"
                    data-url="{{ Route::currentRouteName() == 'coordinacion.lista-grupos' ? route('coordinacion.lista-grupos') : route('coordinacion.lista-grupos-deshabilitados') }}"
                    class="bg-white border border-gray-300 rounded-lg p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
                    type="text" placeholder="Buscar grupo, carrera o cuatrimestre..." value="{{ request('search') }}">
                <span id="limpiar-busqueda"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg font-bold hover:text-red-500 hover:scale-125 transition-all duration-200 hidden">
                    &times;
                </span>
            </div>
            <button id="btn-buscar"
                class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
        </div>

        <div class="flex gap-2 w-full md:w-1/3">
            <select name="cuatrimestre" data-filter
                class="bg-white text-gray-700 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-1/2">
                <option value="" selected>Todos los cuatrimestres</option>
                @foreach ($cuatrimestres as $c)
                    <option value="{{ $c->pk_cuatrimestre }}">{{ $c->num_cuatri }}</option>
                @endforeach
            </select>
            <select name="carrera" data-filter
                class="bg-white text-gray-700 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-1/2">
                <option value="" selected>Todas las carreras</option>
                @foreach ($carreras as $c)
                    <option value="{{ $c->pk_carrera }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
    </section>

    <section class="overflow-x-auto mt-5">
        <div class="border-2 border-gray-300 rounded-xl p-2 bg-white shadow" id="tabla-listado">
            @include('partials.tabla_grupos', ['grupos' => $grupos])
        </div>
    </section>

    <div class="flex items-center justify-end gap-2 mt-4">
        @if (Route::currentRouteName() == 'coordinacion.lista-grupos')
            <a href="{{ route('coordinacion.lista-grupos-deshabilitados') }}"
                class="bg-yellow-600 text-white m-3 p-2 rounded-lg hover:bg-yellow-700 shadow text-sm md:text-base">
                Ver Grupos Deshabilitados
            </a>
        @elseif(Route::currentRouteName() == 'coordinacion.lista-grupos-deshabilitados')
            <a href="{{ route('coordinacion.lista-grupos') }}"
                class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">
                Ver Grupos Habilitados
            </a>
        @endif
    </div>
@endsection
