@extends('components.menu')

@section('title', 'Alumno - Perfil')

@section('content')

<div class="max-w-5xl mx-auto mt-6 bg-white rounded-2xl shadow-lg overflow-hidden">
    <section class="flex flex-col items-center text-center p-8">
        <img class="w-28 h-28 border-4 border-white rounded-full mb-4 object-cover shadow-xl"
             src="{{ $usuario->img_user ? asset('storage/' . $usuario->img_user) : asset('img/default.jpg') }}"
             alt="Foto de perfil">

        <h2 class="text-3xl font-semibold tracking-wide">{{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno }}</h2>
        <p class="text-sm opacity-90 mt-1">Alumno</p>

        <a class="mt-4 bg-white text-teal-700 px-4 py-2 rounded-lg font-semibold hover:bg-teal-200 transition shadow"
           href="{{ route('alumno.editar') }}">
           Editar perfil
        </a>
    </section>

    <section class="p-8">
        <div class="border-b border-gray-200 mb-6 pb-2">
            <h3 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                </svg>
                Información General
            </h3>
        </div>

        <div class="grid md:grid-cols-2 gap-4 text-gray-700">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <span class="block text-sm text-gray-500">Matrícula</span>
                <p class="font-semibold text-lg text-gray-800">{{ $usuario->matricula }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <span class="block text-sm text-gray-500">Carrera</span>
                <p class="font-semibold text-lg text-gray-800">{{ $carrera ?? 'No definida' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <span class="block text-sm text-gray-500">Promedio general</span>
                <p class="font-semibold text-lg text-teal-700">{{ $promedio ?? 'N/A' }}</p>
            </div>
        </div>
    </section>

    @php
        $grupoActual = $historialGrupos->first();
    @endphp

    @if($grupoActual)
    <section class="bg-teal-50 p-6 border-t border-gray-200">
        <h3 class="text-xl font-semibold text-teal-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
            </svg>
            Grupo Actual
        </h3>
        <div class="bg-white rounded-xl p-5 shadow-md border border-gray-100 flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <p class="font-bold text-gray-800 text-lg">
                    {{ $grupoActual->grupo->fk_cuatrimestre ?? '' }}{{ $grupoActual->grupo->nombre ?? '' }}{{ $grupoActual->grupo->carrera->abreviatura ?? '' }} {{ $grupoActual->grupo->año ?? '' }}
                </p>
                <p class="text-sm text-gray-600">
                    Carrera: {{ $grupoActual->grupo->carrera->nombre ?? 'Sin carrera' }}
                </p>
            </div>
            <p class="text-xs text-gray-500 mt-2 md:mt-0">
                Desde: {{ $grupoActual->created_at->format('d/m/Y') }}
            </p>
        </div>
    </section>
    @endif

    <section class="p-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
            </svg>
            Historial de Grupos
        </h3>

        @if($historialGrupos->count() > 1)
            <div class="grid md:grid-cols-2 gap-5">
                @foreach($historialGrupos->skip(1) as $hist)
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-4 shadow hover:shadow-lg transition relative overflow-hidden">
                        <div class="absolute right-2 top-2 bg-teal-500 text-white px-2 py-1 text-xs rounded-full shadow">
                            Pasado
                        </div>
                        <p class="font-bold text-gray-800 text-lg">
                            {{ $hist->grupo->fk_cuatrimestre ?? '' }}{{ $hist->grupo->nombre ?? '' }}{{ $hist->grupo->carrera->abreviatura ?? '' }} {{ $hist->grupo->año ?? '' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Carrera: {{ $hist->grupo->carrera->nombre ?? 'Sin carrera' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Fecha de registro: {{ $hist->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic text-center">No hay grupos anteriores registrados.</p>
        @endif
    </section>
</div>

@endsection
