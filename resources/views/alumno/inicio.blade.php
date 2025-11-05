@extends('components.menu')

@section('title', 'Panel alumno | Aula de Idiomas')

@section('content')

    <section>
        <h1 class="text-3xl sm:text-4xl font-bold text-black">Panel del Alumno</h1>
        <span class="text-sm sm:text-lg text-gray-500 sm:font-light mt-2">
            Bienvenido/a, Ángel Ariel Salazar Medina.
        </span>
    </section>

    <section class="mt-7">
        <div class="flex flex-col sm:flex-row  sm:justify-between gap-3">
            <div class="bg-white p-5 w-full h-auto sm:w-2/3 border border-gray-200 rounded-lg shadow">
                {{-- CONTENIDO PENDIENTE --}}
                <h3 class="text-lg text-gray-700 mb-2 font-semibold">Actividades pendientes</h3>
                <div class="max-h-79 sm:max-h-64 overflow-y-auto pr-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="bg-white border border-gray-300 p-4 mb-2 rounded-lg shadow">
                            <div class="">
                                <h1 class="text-gray-800 font-semibold">Actividad {{ $i }}</h1>
                                <p class="text-gray-600 text-sm">Descripción de la actividad número {{ $i }}.</p>
                            </div>
                            <div>

                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="bg-white p-5 w-full sm:w-1/3 sm:max-h-70 h-90 border border-gray-200 rounded-lg shadow">
                <div>
                    <h3 class="text-lg text-gray-700 mb-2 font-semibold">Mi progreso</h3>
                    <div>
                        {{-- BARRA DE PROGRESO --}}
                        <div>
                            <h4 class="text-lg text-gray-400 mb-2">General</h4>
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-300">
                                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    style="width: 45%"> 45%</div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg text-gray-400 mb-2">Auditiva</h4>
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-300">
                                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    style="width: 70%"> 70%</div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg text-gray-400 mb-2">Lectura</h4>
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-300">
                                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    style="width: 45%"> 45%</div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg text-gray-400 mb-2">Oral</h4>
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-300">
                                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    style="width: 45%"> 45%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-7 flex">
            <div class="bg-white p-4 w-full sm:w-2/3 border border-gray-200 rounded-lg shadow">
                {{-- CONTENIDO PENDIENTE --}}
                <h3 class="text-lg text-gray-700 mb-2 font-semibold">Actividades terminadas</h3>
                <div class="max-h-43 overflow-y-auto pr-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="bg-white border border-gray-300 p-4 mb-2 rounded-lg shadow">
                            <h1 class="text-gray-800 font-semibold">Actividad {{ $i }}</h1>
                            <p class="text-gray-600 text-sm">Descripción de la actividad número {{ $i }}.</p>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
@endsection
