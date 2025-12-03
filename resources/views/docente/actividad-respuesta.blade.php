@extends('components.menu')

@section('title', 'Respuestas del Alumno | Aula de Idiomas')

@section('content')
<div class="max-w-5xl mx-auto mt-8">
    <x-msj-alert />

    <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $actividad->nom_actividad }}</h2>
    <p class="text-sm text-gray-600 mb-6">
        Alumno:
        <strong>{{ $alumno->nombre ?? ($alumno->usuario->nombres ?? 'Alumno') }}</strong>
    </p>

    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm text-center">
            <p class="text-xs text-gray-500">Preguntas</p>
            <p class="text-2xl font-bold">{{ $totalPreguntas }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm text-center">
            <p class="text-xs text-gray-500">Correctas</p>
            <p class="text-2xl font-bold text-green-600">{{ $correctas }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm text-center">
            <p class="text-xs text-gray-500">Calificación</p>
            <p class="text-2xl font-bold text-teal-600">{{ $calificacion }}%</p>
        </div>
    </div>

    <div class="space-y-4">
        @foreach ($evaluacion as $item)
            @php
                $esCorrecta = $item['es_correcta'];
            @endphp

            @switch($esCorrecta)
                @case(true)
                    @php $bg = 'bg-green-50 border-green-200'; @endphp
                    @break
                @case(false)
                    @php $bg = 'bg-red-50 border-red-200'; @endphp
                    @break
                @default
                    @php $bg = 'bg-gray-50 border-gray-200'; @endphp
            @endswitch

            <div class="p-5 rounded-lg border {{ $bg }}">
                <p class="font-semibold text-gray-800 mb-2">
                    {{ $loop->iteration }}. {{ $item['pregunta'] }}
                </p>

                <p class="text-sm">
                    <strong>Respuesta del alumno:</strong>
                    <span class="@if($esCorrecta === true) text-green-600
                                 @elseif($esCorrecta === false) text-red-600
                                 @else text-gray-700 @endif">
                        {{ $item['respuesta_alumno'] }}
                    </span>
                </p>

                <p class="text-sm text-gray-600 mt-1">
                    <strong>Respuesta correcta:</strong> {{ $item['respuesta_correcta'] }}
                </p>

                @if ($item['tipo'] === 'abierta')
                    <p class="text-xs text-gray-500 mt-2">
                        Pregunta abierta — revisar manualmente.
                    </p>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
