@extends('components.menu')

@section('title', 'Lista de actividades | Aula de idiomas')

@section('content')

    <section class="flex flex-col md:flex-row justify-between m-2 items-start md:items-center">
        <div>
            <h1 class="text-4xl font-bold text-black">Historial de Actividades</h1>
            <span class="text-gray-500 font-light mt-2">
                Revisión y gestión de actividades creadas.
            </span>
        </div>
        <div>
            <a href="{{ route('docente.crear-actividad') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg shadow hover:bg-teal-700 transition">
                Crear nueva actividad
            </a>
        </div>
    </section>
    <section class="flex flex-col md:flex-row justify-between mt-5 gap-5">
        <div class="relative w-full">
             <input
                id="buscador"
                class="bg-white border border-gray-300 rounded-lg p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
                type="text"
                placeholder="Buscar..."
                value=""
            >
        </div>
        <button id="btn-buscar" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
        <div class="flex gap-2 w-full md:w-1/3">
            <select name="" id=""  class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-1/2">
                <option value="0">Todos los Grupos</option>
                <option value="6">IDGS 10mo</option>
                <option value="7">Turismos 7mo</option>
                <option value="8">Turismos 8vo</option>
                <option value="9">Turismos 9no</option>
            </select>
            <select name="" id=""  class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-1/2">
                <option value="0">Todos los estados</option>
                <option value="6">Pendiente</option>
                <option value="8">Calificado</option>
            </select>
        </div>
    </section>
    <section class="overflow-x-auto mt-5">
        <div class="border-2 border-gray-300 rounded-xl p-2 bg-white shadow" id="tabla-listado">
            @include('partials.tabla_actividades')
        </div>

    </section>


@endsection
