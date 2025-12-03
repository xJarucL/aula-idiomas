@extends('components.menu')

@section('title', 'Detalle del Grupo | Aula de Idiomas')

@section('content')
    <x-msj-alert />

    <div class="sm:p-6 space-y-8">
        <div class="text-left pt-6">
            <a href="{{ route('docente.mis-grupos') }}"
                class="inline-block bg-teal-600 text-white px-6 py-2 rounded-lg shadow hover:bg-teal-700 transition">
                ← Volver al listado
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 mt-3 sm:mt-0 border-t-4 border-teal-600">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                {{ $grupo->fk_cuatrimestre ?? '' }}{{ $grupo->nombre ?? '' }}{{ $grupo->carrera->abreviatura ?? '' }}
                {{ $grupo->año ?? '' }}
            </h1>
            <div class="text-gray-600 space-y-1">
                <p><span class="font-semibold text-teal-700">Carrera:</span> {{ $grupo->carrera->nombre ?? 'N/A' }}</p>
                <p><span class="font-semibold text-teal-700">Cuatrimestre:</span>
                    {{ $grupo->cuatrimestre->pk_cuatrimestre ?? 'N/A' }}</p>
                <p><span class="font-semibold text-teal-700">Año:</span> {{ $grupo->año ?? 'N/A' }}</p>
                <p><span class="font-semibold text-teal-700">Participantes:</span> {{ count($grupo->alumnos) ?? 0 }}</p>
            </div>
        </div>

            @forelse ($grupo->alumnos as $alumno)
                @php
                    $authTipo = Auth::user()->fk_tipo_usuario;
                    $id = $alumno->usuario->pk_usuario;

                    $ruta = $authTipo == 2
                        ? route('docente.detalle-alumno', $id)
                        : ($authTipo == 3
                            ? route('coordinacion.detalle-alumno', $id)
                            : '#');
                @endphp

                <a href="{{ $ruta }}">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 mb-3 flex items-center justify-between hover:shadow-lg transition">
                        <div>
                            <h3 class="text-gray-800 font-semibold">
                                {{ $alumno->usuario->nombres ?? 'N/A' }}
                                {{ $alumno->usuario->ap_paterno ?? '' }}
                                {{ $alumno->usuario->ap_materno ?? '' }}
                            </h3>
                            <p class="text-gray-500 text-sm">{{ $alumno->usuario->matricula ?? 'Sin matrícula' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic">No hay actividades registradas.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
