@extends('components.menu')

@section('title', 'Pendientes | Aula de Idiomas')

@section('content')
<x-msj-alert />

<section class="sm:mx-6 mt-6">
    <div class="bg-white shadow-lg rounded-2xl p-4">
        <h2 class="text-2xl font-semibold text-teal-600 mb-4">Entregas pendientes de revisión</h2>

        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4 mb-4">
            <div class="relative w-full">
                <input
                    id="buscador"
                    data-url="{{ route('docente.pendientes.filtrar') }}"
                    class="bg-white border border-gray-300 rounded-lg p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
                    type="text"
                    placeholder="Buscar por alumno o actividad..."
                >
                <span id="limpiar-busqueda"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg font-bold hover:text-red-500 hover:scale-125 transition-all duration-200 hidden">
                    &times;
                </span>
            </div>
            <button id="btn-buscar" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
            <select
                id="filtro-grupo"
                name="grupo"
                data-filter="true"
                class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-full sm:w-1/4"
            >
                <option value="">Todos los grupos</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->pk_grupo }}">{{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura ?? '' }} {{ $grupo->año }}</option>
                @endforeach
            </select>

        </div>

        <div class="overflow-x-auto" id="tabla-listado">
            @include('partials.tabla_pendientes', ['pendientes' => $pendientes])
        </div>
    </div>
</section>
@endsection
