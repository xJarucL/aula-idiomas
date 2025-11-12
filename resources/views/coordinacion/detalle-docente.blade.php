@extends('components.menu')

@section('title', 'Docente - Detalle')

@section('content')

<div class="max-w-4xl mx-auto mt-2 bg-white rounded-lg shadow p-8">

    <section class="flex flex-col items-center text-center mb-8">
        <img
            class="w-28 h-28 border border-l-gray-500 rounded-full mb-4 object-cover"
            src="{{ $docente->img_user ? asset('storage/' . $docente->img_user) : asset('img/default.jpg') }}"
            alt="foto de perfil"
        >
        <h2 class="text-2xl font-semibold">{{ $docente->nombres }} {{ $docente->ap_paterno }} {{ $docente->ap_materno }}</h2>
        <p class="text-sm text-gray-600 mb-3 mt-2">Docente del departamento de idiomas</p>
    </section>

    <section class="pt-5 mb-6">
        <div class="border-b border-gray-200 pt-6 mb-4">
            <h3 class="text-2xl font-semibold mb-2">Información de contacto</h3>
        </div>
        <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
            <span class="font-medium mr-12">Correo Electronico:</span>
            <span class="text-gray-600">{{ $docente->email }}</span>
        </div>
    </section>

    <section class="mt-15">
        <h3 class="text-2xl font-semibold mb-3">Información de trabajo</h3>
        <div class="flex flex-col md:flex-row justify-around gap-4">
            <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs text-start shadow">
                <p class="text-gray-600">Grupos asignados</p>
                <p class="text-4xl font-semibold mt-2">{{ $grupos_asignados ?? 0 }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs text-start shadow">
                <p class="text-gray-600">Estudiantes bajo enseñanza</p>
                <p class="text-4xl font-semibold mt-2">{{ $estudiantes ?? 0 }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs text-start shadow">
                <p class="text-gray-600">Actividades creadas</p>
                <p class="text-4xl font-semibold mt-2">{{ $actividades ?? 0 }}</p>
            </div>
        </div>
    </section>

    <div class="pt-6 mt-3">
        <h3 class="text-2xl font-semibold mb-4">Historial de grupos</h3>

        @if ($docente->gruposAsignados->isEmpty())
            <div class="bg-gray-100 rounded-md shadow p-6 text-center text-gray-500 italic">
                No hay grupos asignados a este docente.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($docente->gruposAsignados as $asignacion)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow hover:shadow-lg transition">
                        <div class="flex flex-col h-full justify-between">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-1">
                                    {{ $asignacion->grupo->fk_cuatrimestre ?? 'Sin nombre de grupo' }}{{ $asignacion->grupo->nombre ?? '' }}{{ $asignacion->grupo->carrera->abreviatura ?? '' }} {{ $asignacion->grupo->año ?? '' }}
                                </h4>
                                <p class="text-gray-600 text-sm mb-2">
                                    Materia: <span class="font-medium">{{ $asignacion->materia->nombre ?? 'Sin materia' }}</span>
                                </p>
                            </div>
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-xs text-gray-500">
                                    Cuatrimestre: {{ $asignacion->grupo->fk_cuatrimestre ?? 'N/A' }}
                                </span>
                                <!-- <a href="#" class="text-teal-600 hover:underline text-sm font-medium">
                                    Ver detalles
                                </a> -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
