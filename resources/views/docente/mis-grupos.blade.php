@extends('components.menu')

@section('title', 'Mis Grupos | Aula de idiomas')

@section('content')

    <section class="flex flex-col md:flex-row justify-between m-2 items-start md:items-center">
        <div>
            <h1 class="text-4xl font-bold text-black">Mis Grupos</h1>
            <span class="text-gray-500 font-light mt-2">
                Gestiona actividades a los grupos asignados.
            </span>
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
    <div class="flex gap-2 w-full md:w-auto">
        <button id="btn-buscar" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
        <select name="" id="" class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow text-sm md:text-base flex-1">
            <option value="0">Todos los Grupos</option>
            <option value="6">IDGS 10mo</option>
            <option value="7">Turismos 7mo</option>
            <option value="8">Turismos 8vo</option>
            <option value="9">Turismos 9no</option>
        </select>
    </div>
</section>
    <section class="overflow-x-auto mt-5">
        <div class="border-2 border-gray-300 rounded-xl p-2 bg-white shadow" id="tabla-listado">
            @include('partials.tabla_mi_grupo', ['grupos' => $grupos])
        </div>

    </section>
@endsection
