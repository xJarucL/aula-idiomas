@extends('components.menu')

@section('title', 'Panel alumno | Aula de Idiomas')

@section('content')

    <section>
        <h1 class="text-3xl sm:text-5xl font-bold text-teal-700  ">Panel del Alumno</h1>
        <span class="text-sm sm:text-lg text-gray-500 sm:font-light mt-2">
            Bienvenido/a, {{Auth::user()->nombres}} {{Auth::user()->ap_paterno}} {{Auth::user()->ap_materno ?? ''}}
        </span>
    </section>
    {{-- CARTAS INFOR --}}
    <!-- <section class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5 sm:mt-7">
        <x-card-info title="General" icon="activity" color="blue" />

        <x-card-info title="Escritura" icon="pending" color="purple" />

        <x-card-info title="Lectura" icon="completed" color="teal" />

        <x-card-info title="Nos" icon="total" color="yellow" />
    </section> -->

    {{-- APRATDO DE INFOR ADICIONALL --}}
    <section class="grid grid-cols-6 sm:grid-cols-10 gap-3 mt-5">
        {{-- CALIFICACION --}}
        <div class="col-span-3 sm:col-span-2 bg-white p-3 border border-gray-200 rounded-lg shadow">
            <div class="flex justify-between items-center gap-2 h-full">
                <div class="flex w-full flex-col gap-1 justify-center h-full">
                    <h3 class="text-sm sm:text-lg text-teal-700 font-medium">Calificacion</h3>
                    <span class="text-teal-500 text-[20px] sm:text-2xl font-bold">{{$promedio ?? 'N/A'}}</span>
                </div>
            </div>
        </div>
        {{-- MATERIA --}}
        <div class="col-span-6 sm:col-span-2 bg-white p-3 border border-gray-200 rounded-lg shadow">
            <div class="flex justify-between items-center h-full gap-2">
                <div class="flex w-full flex-col gap-1 justify-center h-full">
                    <h3 class="text-sm sm:text-lg text-teal-700 font-medium">Materia</h3>
                    <span class="text-[20px] sm:text-2xl text-gray-700 font-bold">{{$materia}}</span>
                </div>
            </div>
        </div>
        {{-- PENDIENTE --}}
        <div class="col-span-3 sm:col-span-2 bg-white p-3 border border-gray-200 rounded-lg shadow">
            <div class="flex justify-between items-center h-full gap-2">
                <div class="flex w-full flex-col gap-1 justify-center h-full">
                    {{-- VISUALIZACION ESCRITORIO --}}
                    <h3 class="text-sm sm:text-lg hidden sm:inline-block text-teal-700 font-medium">Pendientes
                    </h3>
                    {{-- VISUALIZACION MOVIL --}}
                    <h3 class="text-sm sm:text-lg sm:hidden text-teal-700 font-medium">Act. pendientes</h3>
                    <span class="text-[20px] sm:text-2xl text-gray-700 font-bold">{{$count_pendientes}}</span>

                </div>
            </div>
        </div>
        {{-- TERMINADA --}}
        <div class="col-span-3 sm:col-span-2 bg-white p-3 border border-gray-200 rounded-lg shadow">
            <div class="flex justify-between items-center h-full w-full gap-2 ">
                <div class="flex w-full flex-col gap-1 justify-center h-full ">
                    <h3 class="text-sm sm:text-lg hidden sm:inline-block text-teal-700 font-medium">Entregadas
                    </h3>
                    <h3 class="text-sm sm:text-lg sm:hidden text-teal-700 font-medium">Act. terminadas</h3>

                    <span class="text-[20px] sm:text-2xl text-gray-700 font-bold">{{$count_entregadas}}</span>
                </div>
            </div>
        </div>

        <!-- NO TERMINADAS -->
         <div class="col-span-3 sm:col-span-2 bg-white p-3 border border-gray-200 rounded-lg shadow">
            <div class="flex justify-between items-center h-full w-full gap-2 ">
                <div class="flex w-full flex-col gap-1 justify-center h-full ">
                    <h3 class="text-sm sm:text-lg hidden sm:inline-block text-teal-700 font-medium">No entregadas
                    </h3>
                    <h3 class="text-sm sm:text-lg sm:hidden text-teal-700 font-medium">Act. NO Terminadas</h3>

                    <span class="text-[20px] sm:text-2xl text-gray-700 font-bold">{{$count_no_entregadas}}</span>
                </div>
            </div>
        </div>
    </section>


    <section class="mt-7">
        <div class="flex flex-col-reverse sm:flex-row  sm:justify-between gap-3">
            <div class="bg-white p-5 w-full h-auto sm:w-2/3 border border-gray-200 rounded-lg shadow">
                {{-- CONTENIDO PENDIENTE --}}
                <h3 class="text-lg text-gray-700 mb-2 font-semibold">Actividades pendientes</h3>
                <div class="max-h-79 sm:max-h-80 overflow-y-auto pr-1">
                    @foreach($pendientes as $actividad)
                        <x-card-actividad iconoA="question" color1="blue" nombreActividad="{{ $actividad['nom_actividad'] }}"
                            tipo="{{ $actividad['tipo'] }}" fecha="{{ $actividad['fecha_inicio'] }}" iconoEntregable="pending" color2="orange" link="{{ route('alumno.detalle-actividad', $actividad['pk_actividad']) }}"
                        />
                    @endforeach
                </div>
            </div>
            <div class=" w-full sm:w-1/3 ">
                <div class="bg-white p-5 mb-5 sm:max-h-70 h-70 border border-gray-200 rounded-lg shadow">
                    <h3 class="text-lg text-teal-700 mb-2 font-semibold">Mi progreso</h3>
                        <div>
                            <h4 class="text-lg text-gray-400 mb-2">General</h4>
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-300">
                                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    style="width: {{ $porcentaje_general }}%">
                                    {{ $porcentaje_general }}%
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg text-gray-400 mb-2">Entregadas</h4>
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-300">
                                <div class="bg-green-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    style="width: {{ $porcentaje_entregadas }}%">
                                    {{ $porcentaje_entregadas }}%
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg text-gray-400 mb-2">Pendientes</h4>
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-300">
                                <div class="bg-yellow-500 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    style="width: {{ $porcentaje_pendientes }}%">
                                    {{ $porcentaje_pendientes }}%
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg text-gray-400 mb-2">No entregadas</h4>
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-300">
                                <div class="bg-red-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    style="width: {{ $porcentaje_no_entregadas }}%">
                                    {{ $porcentaje_no_entregadas }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- PARTE DE ACCESO --}}
                <a href="{{ route('alumno.progreso') }}">
                    <div class=" bg-white p-4 h-auto border border-gray-300 rounded-lg shadow">
                        <div class="flex justify-between">
                            <div class="w-5/6 text-justify">
                                <h3 class="text-lg text-teal-700 font-semibold">Ver mi progreso</h3>
                                <span class="text-sm text-gray-600 font-normal">Visualiza los avances que has logrado
                                    durante tu carrera</span>
                            </div>
                            <div class="flex justify-center items-center w-1/6 pl-1">
                                <svg width="48" height="48" class="text-center" viewBox="0 0 48 48" fill="none"
                                    stroke="#2B877A" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="18,12 30,24 18,36" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection
