@extends('components.menu')

@section('title', 'Lista de actividades | Aula de Idiomas')

@section('content')
    {{-- BUSCADOR --}}
    <section>
        <h1 class="text-2xl sm:text-5xl font-bold text-teal-800">Listado de actividades</h1>
        <span class="text-sm sm:text-lg text-gray-500 text-justify sm:font-light mt-2 w-full">
            Consulta en este apartado todas las actividades asignadas.
        </span>
    </section>
    {{-- BUSCADOR --}}
    <section class="mt-5">
        <div class="flex flex-row justify-between gap-3 bg-white p-4 rounded-lg shadow-lg">
            <div class="w-3/4 sm:w-5/6">
                {{-- INPUT --}}
                <div class="relative w-full">
                    <input id="buscador" data-url=""
                        class="border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
                        type="text" placeholder="Buscar actividades...">
                    <span id="limpiar-busqueda"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg font-bold hover:text-red-500 hover:scale-125 transition-all duration-200 hidden">
                        &times;
                    </span>
                </div>
            </div>
            {{-- BOTON DE BUSCAR --}}
            <div class="flex justify-center w-1/4 sm:w-1/6">
                <button id="btn-buscar"
                    class="bg-teal-600 text-white p-3 w-full rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
            </div>
        </div>
    </section>
    {{-- LISTADO --}}
    <section class="mt-8">
        <div class="bg-white p-4 h-140 sm:h-135 border border-gray-300 rounded-lg shadow">
            {{-- ENCABEZADO --}}
            <div class="flex justify-between p-2 gap-5 border-b border-gray-200">
                <div class="flex justify-center items-center">
                    <h2 class="text-[18px] sm:text-[28px] text-teal-700 font-semibold">Actividades</h2>
                </div>
                {{-- OPCIONES DE FILTRAR EL CONTENIDO --}}
                <div class="flex justify-center items-center gap-3 sm:gap-5">
                    <a href=""
                        class="text-[12px] sm:text-lg text-gray-700 font-medium hover:border-b hover:border-teal-700">Pendientes</a>
                    <a href=""
                        class="text-[12px] sm:text-lg text-gray-700 font-medium hover:border-b hover:border-teal-700">Terminadas</a>
                    <a href=""
                        class="text-[12px] sm:text-lg text-gray-700 font-medium hover:border-b hover:border-teal-700">No
                        entregadas</a>
                </div>
            </div>
            <div class="mt-3">
                {{-- CONTENIDO ACTIVIDADES --}}
                <div class="overflow-auto pr-1 sm:max-h-108">
                    <x-card-actividad
                        iconoA="question"
                        color1="blue"
                        nombreActividad="Past simple vs Past continuous"
                        tipo="Pregunta"
                        fecha="15/12/2022"
                        iconoEntregable="pending"
                        color2="orange"
                       link="{{ route('alumno.detalle-actividad') }}"
                    />
                </div>
            </div>
        </div>
    </section>
@endsection
