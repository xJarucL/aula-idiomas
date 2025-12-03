@extends('components.menu')

@section('title', 'Detalle del alumno | Aula de Idiomas')

@section('content')
    <x-msj-alert />

    <div class="max-w-4xl mx-auto mt-2 bg-white rounded-lg shadow p-8">
        <section class="flex flex-col items-center text-center mb-8">
            <img class="w-28 h-28 border border-l-gray-500 rounded-full mb-4 object-cover"
                src="{{ $usuario->img_user ? asset('storage/' . $usuario->img_user) : asset('img/default.jpg') }}"
                alt="foto de perfil">
            <h2 class="text-2xl font-semibold">{{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno }}</h2>
            <p class="text-sm text-gray-600 mb-3 mt-2">Alumno</p>

        </section>

        <section class="pt-5 mb-6">
            <div class="border-b border-gray-200 pt-6 mb-4">
                <h3 class="text-2xl font-semibold mb-2">Información de contacto</h3>
            </div>
            <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
                <span class="font-medium mr-12">Matrícula:</span>
                <span class="text-gray-600">{{ $usuario->matricula }}</span>
            </div>
            <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
                <span class="font-medium mr-12">Carrera:</span>
                <span class="text-gray-600">{{ $carrera ?? 'No definida' }}</span>
            </div>
            <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
                <span class="font-medium mr-12">Promedio:</span>
                <span class="text-gray-600">{{$promedio ?? 'N/A' }}</span>
            </div>
        </section>
    </div>
    <div class="max-w-4xl mx-auto mt-2 bg-white rounded-lg shadow p-4 sm:p-8">
        <section>
            <h2 class="text-2xl font-semibold">Grupos</h2>
        </section>
        <section class="mt-5">
            @forelse($grupos as $grupo)
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 shadow hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">
                        {{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura }} {{ $grupo->año }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-1">
                        Carrera: <span class="font-medium text-teal-800">{{ $grupo->carrera->nombre ?? '-' }}</span>
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        Cuatrimestre: <span class="font-medium text-teal-800">{{ $grupo->fk_cuatrimestre }}°</span>
                    </p>
                    <p class="text-sm text-gray-600 mb-2">
                        Año: <span class="font-medium text-teal-800">{{ $grupo->año }}</span>
                    </p>
                  @php
                    $authTipo = Auth::user()->fk_tipo_usuario;

                    $alumnoId = $alumno->pk_alumno;
                    $grupoId = $grupo->pk_grupo;

                    $rutaActividades = $authTipo == 2
                        ? route('docente.actividades-alumno', ['alumno' => $alumnoId, 'grupo' => $grupoId])
                        : ($authTipo == 3
                            ? route('coordinacion.actividades-alumno', ['alumno' => $alumnoId, 'grupo' => $grupoId])
                            : '#');
                  @endphp

                    <a href="{{ $rutaActividades }}"
                    class="bg-teal-600 text-white px-3 py-1 rounded hover:bg-teal-700 text-sm">
                        Ver Actividades
                    </a>
                </div>
            @empty
                <p class="col-span-2 text-gray-500 italic">No tiene grupos asignados.</p>
            @endforelse
        </section>
    </div>
@endsection
