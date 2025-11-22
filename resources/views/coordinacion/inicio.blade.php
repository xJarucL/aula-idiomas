@extends('components.menu')

@section('title', 'Panel coordinación | Aula de Idiomas')

@section('content')

    <section class="flex flex-col md:flex-row justify-between m-2 items-start md:items-center">
        <div class="">
            <h1 class="text-2xl sm:text-4xl font-bold text-teal-700">Panel del Coordinador</h1>
            <span class="text-gray-500 text-[15px] sm:text-lg font-light mt-2">Bienvenido/a, {{ Auth::user()->nombres }}
                {{ Auth::user()->ap_paterno }} {{ Auth::user()->ap_materno }}.</span>
        </div>
    </section>
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-5 sm:mt-10">
        {{-- CARTAS INFORAMACION --}}

        <x-card-info title="Grupos" :count="$gruposCount" icon="users" color="blue"
            link="{{ route('coordinacion.lista-grupos') }}" />

        <x-card-info title="Docentes" :count="$docentesCount" icon="user" color="green"
            link="{{ route('coordinacion.lista-docentes') }}" />

        <x-card-info title="Alumnos" :count="$alumnosCount" icon="book" color="teal"
            link="{{ route('coordinacion.lista-alumnos') }}" />

        <x-card-info title="Coordinación" :count="$coordinadoresCount" icon="user" color="blue"
            link="{{ route('coordinacion.lista-coordinador') }}" />

    </section>
    {{-- APARTADOS ADICIONALES --}}
    <section class="grid grid-cols-1 sm:grid-cols-10 gap-5 sm:gap-10">
        {{-- HISTORIAL --}}
        <div class="col-span-1 sm:col-span-7 mt-7">
            <div class="bg-white p-4 border border-gray-200 h-125 rounded-xl shadow-sm">
                <div class="border-b border-gray-300">
                    <h1 class="text-xl sm:text-2xl font-semibold text-teal-700 mb-3">Historial de actividades</h1>
                </div>
                <div>
                    <div>

                    </div>
                </div>

            </div>
        </div>
        {{-- ACCESSOS RAPIDOS --}}
        <div class="col-span-1 sm:col-span-3 mt-7">
            <div class="bg-white w-full p-4 h-125 border border-gray-200 rounded-lg shadow">
                <h1 class="text-xl sm:text-2xl font-semibold text-teal-700 mb-3">Accesos Rapidos</h1>
                {{-- FORMULARIO DOCENTE --}}
                <a href="{{ route('coordinacion.lista-docentes') }}"
                    class="h-auto flex justify-between border border-gray-300 rounded-xl shadow-sm">
                    <div class="p-3 flex items-center">
                        <h1 class="text-[15px] sm:text-[20px] text-gray-600 font-bold mt-1">Registrar Docente</h1>
                        {{-- <h4 class="text-[15px] font-light text-gray-400">Registra, edita y actualiza datos de docente</h4> --}}
                    </div>
                    <div class="flex justify-center items-center p-3">
                        <div>
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#2B877A"
                                stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18,12 30,24 18,36" />
                            </svg>
                        </div>
                    </div>
                </a>
                {{-- FORMULARIO ALUMNOS --}}
                <a href="{{ route('coordinacion.lista-alumnos') }}"
                    class="h-auto flex justify-between border border-gray-300 rounded-xl shadow-sm mt-8">
                    <div class="p-3 flex items-center">
                        <h1 class="text-[15px] sm:text-[20px] text-gray-600 font-bold mt-1">Registrar Alumnos</h1>
                        {{-- <h4 class="text-[15px] font-light text-gray-400">Registra, edita y actualiza datos de alumnos</h4> --}}
                    </div>
                    <div class="flex justify-center items-center p-3">
                        <div>
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#2B877A"
                                stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18,12 30,24 18,36" />
                            </svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('coordinacion.lista-grupos') }}"
                    class="h-auto flex justify-between border border-gray-300 rounded-xl shadow-sm mt-8">
                    <div class="p-3 flex items-center">
                        <h1 class="text-[15px] sm:text-[20px] text-gray-600 font-bold mt-1">Registrar Grupos</h1>
                        {{-- <h4 class="text-[15px] font-light text-gray-400">Registra, edita y actualiza datos de grupos</h4> --}}
                    </div>
                    <div class="flex justify-center items-center p-3">
                        <div>
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#2B877A"
                                stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18,12 30,24 18,36" />
                            </svg>
                        </div>
                    </div>
                </a>
                <a href="#" class="h-auto flex justify-between border border-gray-300 rounded-xl shadow-sm mt-8">
                    <div class="p-3 flex items-center">
                        <h1 class="text-[15px] sm:text-[20px] text-gray-600 font-bold mt-1">Registrar Coordinador</h1>
                        {{-- <h4 class="text-[15px] font-light text-gray-400">Registra, edita y actualiza datos de grupos</h4> --}}
                    </div>
                    <div class="flex justify-center items-center p-3">
                        <div>
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#2B877A"
                                stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18,12 30,24 18,36" />
                            </svg>
                        </div>
                    </div>
                </a>

            </div>

        </div>
    </section>

@endsection
