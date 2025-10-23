@extends('components.menu')

@section('title', 'Actividades')

@section('content')
<x-msj-alert />

<section class="flex-1 flex flex-col md:flex-row justify-between m-2 gap-2">
    <div>
        <h1 class="text-2xl md:text-4xl font-bold text-black">Gestión de Actividades</h1>
        <span class="text-gray-500 font-light mt-2 block">Listado de actividades creadas por ti</span>
    </div>
    <div class="flex items-center justify-center gap-2">
        <a href="{{ route('docente.crear-actividad') }}" class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">
            Crear Actividad
        </a>
    </div>
</section>

<section class="flex flex-col md:flex-row justify-between mt-5 gap-5">
    <div class="relative w-full">
        <input
            id="buscador"
            data-url="{{ Route::currentRouteName() == 'docente.lista-actividades' ? route('docente.lista-actividades') : route('docente.lista-actividades-deshabilitadas') }}"
            class="bg-white border border-gray-300 rounded-lg p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
            type="text"
            placeholder="Buscar actividad..."
            value="{{ request('search') }}"
        >
        <span id="limpiar-busqueda"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg font-bold hover:text-red-500 hover:scale-125 transition-all duration-200 hidden">
            &times;
        </span>
    </div>

    <button id="btn-buscar" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
    <select name="tipo" data-filter class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-auto">
        <option value="">Todas las actividades</option>
        <option value="preguntas">Preguntas</option>
        <option value="pdf">Carga de PDF</option>
        <option value="auditiva">Auditiva y Oral</option>
    </select>
</section>

<section class="overflow-x-auto mt-5">
    <div class="border-2 border-gray-300 rounded-xl p-2 bg-white shadow" id="tabla-listado">
        @include('partials.tabla_actividades', ['actividades' => $actividades])
    </div>
</section>

<div class="flex items-center justify-end gap-2 mt-4">
    @if(Route::currentRouteName() == 'docente.lista-actividades')
        <a href="{{ route('docente.lista-actividades-deshabilitadas') }}"
           class="bg-yellow-600 text-white m-3 p-2 rounded-lg hover:bg-yellow-700 shadow text-sm md:text-base">
           Ver Actividades Deshabilitadas
        </a>
    @else
        <a href="{{ route('docente.lista-actividades') }}"
           class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">
           Ver Actividades Habilitadas
        </a>
    @endif
</div>
@endsection
