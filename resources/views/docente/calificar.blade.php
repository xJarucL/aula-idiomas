@extends('components.menu')

@section('title', 'Calificar | Aula de Idiomas')

@section('content')

<div class="h-150 sm:h-[35rem] overflow-auto pr-2">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Mis grupos</h2>
        <p class="text-gray-500 text-sm">Selecciona un grupo para ver detalles o calificar.</p>
    </div>

    @if ($grupos->isEmpty())
        <div class="w-full p-8 text-center text-gray-500 italic bg-white rounded-xl shadow">
            No hay grupos para mostrar
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @foreach ($grupos as $grupo)

                <div class="bg-white shadow-md hover:shadow-xl transition
                            rounded-2xl p-5 border border-gray-200 cursor-pointer
                            hover:scale-[1.02]">

                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $grupo->grupo->fk_cuatrimestre }}
                                {{ $grupo->grupo->nombre }}
                                {{ $grupo->grupo->carrera->abreviatura }}
                                {{ $grupo->grupo->año }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $grupo->grupo->carrera->nombre }}
                            </p>
                        </div>

                        <a href="{{ route('docente.detalle-grupo', $grupo->grupo->pk_grupo) }}"
                            class="p-2 rounded-full hover:bg-gray-100 transition">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 3v4a1 1 0 0 1-1 1H5m8 7.5 2.5 2.5M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Zm-5 9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                            </svg>
                        </a>
                    </div>

                    <div class="mt-2 text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium text-gray-800">Grado:</span> {{ $grupo->grupo->fk_cuatrimestre }}</p>
                        <p><span class="font-medium text-gray-800">Año:</span> {{ $grupo->grupo->año }}</p>
                        <p><span class="font-medium text-gray-800">Materia:</span> {{ $grupo->materia->nombre }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('docente.calificar.grupo', $grupo->grupo->pk_grupo) }}"
                            class="w-full inline-flex justify-center items-center py-2 px-4
                                   bg-teal-600 hover:bg-teal-700 transition text-white font-medium
                                   rounded-xl shadow">
                            Calificar
                        </a>
                    </div>

                </div>

            @endforeach

        </div>
    @endif

</div>

@endsection
