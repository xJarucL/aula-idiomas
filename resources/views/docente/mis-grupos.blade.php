@extends('components.menu')

@section('title', 'Mis Grupos | Aula de Idiomas')

@section('content')
    <x-msj-alert />

    <section class="flex-1 flex flex-col md:flex-row justify-between m-2 gap-2">
        <div>
            <h1 class="text-3xl md:text-5xl font-bold text-teal-700">Mis Grupos</h1>
            <span class="text-gray-500 text-sm sm:text-xl font-light sm:mt-2 block">
                Gestiona actividades a los grupos asignados.
            </span>
        </div>
    </section>

    <section class="flex flex-col md:flex-row justify-between mt-5 gap-2 sm:gap-5">
        <div class="flex justify-between gap-2 sm:gap-5 w-full">
            <div class="relative w-full">
                <input id="buscador" data-url="{{ route('docente.mis-grupos') }}"
                    class="bg-white border border-gray-300 rounded-lg p-2 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
                    type="text" placeholder="Buscar grupo o carrera...">
                <span id="limpiar-busqueda"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg font-bold hover:text-red-500 hover:scale-125 transition-all duration-200 hidden">
                    &times;
                </span>
            </div>
            <button id="btn-buscar"
                class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
        </div>
        <select name="fk_carrera" data-filter
            class="bg-white text-gray-600  border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-full md:w-50">
            <option value="0">Todas las Carreras</option>
            @foreach ($carreras as $carrera)
                <option value="{{ $carrera->pk_carrera }}">{{ $carrera->nombre }}</option>
            @endforeach
        </select>
        <div class="flex flex-row gap-2 sm:gap-5">
            <select name="fk_cuatrimestre" data-filter
                class="bg-white text-gray-600  border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-full md:w-auto">
                <option value="0">Todos los Cuatrimestres</option>
                @for ($i = 1; $i <= 11; $i++)
                    <option value="{{ $i }}">{{ $i }}° Cuatrimestre</option>
                @endfor
            </select>

            <select name="año" data-filter
                class="bg-white text-gray-600  border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base w-full md:w-auto">
                <option value="0">Todos los Años</option>
                @foreach (range(date('Y'), date('Y') - 5) as $anio)
                    <option value="{{ $anio }}">{{ $anio }}</option>
                @endforeach
            </select>
        </div>
    </section>

    <section class="overflow-x-auto mt-5">
        <div class="border-2 border-gray-300 rounded-xl p-2 bg-white shadow" id="tabla-listado">
            @include('partials.tabla_mi_grupo', ['grupos' => $grupos])
        </div>
    </section>

@endsection
