@extends('components.menu')

@section('title', 'Alumno')

@section('content')

{{-- Titulo y boton --}}
<section class="flex-1 flex flex-col md:flex-row justify-between m-2 gap-2">
    <div>
        <h1 class="text-2xl md:text-4xl font-bold text-black">Gestión de grupo</h1>
        <span class="text-gray-500 font-light mt-2 block">Listado de Información de los grupos</span>
    </div>
    <div class="flex items-center justify-center gap-2">
        <a href="{{ route('coordinacion.registro-grupo') }}" class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Crear grupo</a>
    </div>
</section>
{{-- Buscador y filtro --}}
<section class="flex flex-col md:flex-row justify-between mt-5 gap-5">
    <input
        class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full md:w-2/3 shadow text-sm md:text-base"
        type="text" placeholder="Buscar docente..."
    >
    <div class="flex flex-col md:flex-row justify-between gap-2 w-full md:w-1/3">
        <select name="filtro" id="filtro" class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full shadow text-sm md:text-base">
            <option value="" disabled selected>Nivel</option>
            <option value="nombre">B1</option>
            <option value="apellido">B2</option>
            <option value="matricula">B3</option>
        </select>
        <select name="filtro" id="filtro" class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full shadow text-sm md:text-base">
            <option value="" disabled selected>Otro</option>
            <option value="nombre">B1</option>
            <option value="apellido">B2</option>
            <option value="matricula">B3</option>
        </select>
    </div>
</section>
{{-- Tabla de docente --}}
<section class="overflow-x-auto mt-5">
    <div class="border-2 border-gray-300 rounded-xl p-2 bg-white shadow">
        <table class="min-w-full text-xs md:text-base">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-2 md:px-4 text-left">Grupo</th>
                    <th class="py-2 px-2 md:px-4 text-left">Carrera</th>
                    <th class="py-2 px-2 md:px-4 text-left">Cuatrimestre</th>
                    <th class="py-2 px-2 md:px-4 text-left">Año</th>
                    <th class="py-2 px-2 md:px-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($grupos->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                            No hay grupos para mostrar
                        </td>
                    </tr>
                @else
                    @foreach ($grupos as $grupo)
                        <tr>
                            <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                                {{ $grupo->fk_cuatrimestre ?? 'Sin cuatri' }}
                                {{ $grupo->nombre }}
                                {{ $grupo->carrera->abreviatura ?? 'Sin carrera' }}
                                {{$grupo->año}}
                            </td>
                            <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                                {{$grupo->carrera->nombre}}
                            </td>
                            <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                                {{$grupo->fk_cuatrimestre}}
                            </td>
                            <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                                {{$grupo->año}}
                            </td>
                            <td class="py-2 px-1 border-b border-gray-100">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="#" class="text-green-600 hover:text-green-800" title="Agregar alumno">
                                        Agregar alumno
                                    </a>
                                    <a href="#" class="text-green-600 hover:text-green-800" title="Editar">
                                        Editar
                                    </a>
                                    <a href="#" class="text-red-500 hover:text-red-700" title="Eliminar">
                                        Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="mt-6">
            {{ $grupos->links() }}
        </div>
    </div>
</section>

@endsection
