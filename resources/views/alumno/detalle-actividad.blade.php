@extends('components.menu')

@section('title', 'Resultados de Actividad | Aula de Idiomas')

@section('content')
<div class="p-6 md:p-10 bg-gradient-to-b from-teal-50 to-white min-h-screen">
    <x-msj-alert />
    <a href="{{ route('alumno.lista-actividades') }}"
       class="inline-flex items-center text-sm text-gray-700 hover:text-teal-700 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Regresar
    </a>

    <div class="bg-white rounded-3xl shadow-xl p-8 mb-8 text-center">
        <h1 class="text-3xl font-extrabold text-teal-700">{{ $actividad->nom_actividad }}</h1>
        <p class="text-sm text-gray-500 mt-1 capitalize">{{ $actividad->tipo }}</p>
    </div>

    @if(isset($preguntas) && count($preguntas) > 0)
        @php
            $correctas = $preguntas->where('es_correcta', 1)->count();
            $pendientes = $preguntas->where('calificada', 0)->count();
            $total = count($preguntas);
            $porcentaje = $total > 0 ? round(($correctas / $total) * 100) : 0;
            $color = $porcentaje >= 80 ? 'green' : ($porcentaje >= 50 ? 'yellow' : 'red');
        @endphp

        <div class="bg-white rounded-3xl shadow-lg p-8 mb-10 text-center relative overflow-hidden">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mr-2 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                </svg>
                Resultados de evaluación
            </h2>

            <div class="mb-6 flex flex-col items-center">
                <div class="bg-{{ $color }}-100 text-{{ $color }}-700 px-10 py-6 rounded-3xl shadow-md text-center transition-all hover:scale-105">
                    <p class="text-lg font-semibold">Tu calificación general</p>
                    <h1 class="text-5xl font-extrabold mt-2">{{ $porcentaje }}%</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $porcentaje >= 80 ? '¡Excelente trabajo!' : ($porcentaje >= 50 ? 'Buen esfuerzo, puedes mejorar.' : 'Sigue practicando, tú puedes!') }}
                    </p>
                </div>

                <div class="w-full md:w-2/3 h-3 bg-gray-200 rounded-full mt-6 overflow-hidden">
                    <div class="h-3 bg-{{ $color }}-500 rounded-full transition-all duration-700" style="width: {{ $porcentaje }}%;"></div>
                </div>

                @if($pendientes > 0)
                    <p class="mt-3 text-yellow-600 text-sm font-semibold">
                        {{ $pendientes }} respuesta(s) pendiente(s) de calificación.
                    </p>
                @endif
            </div>

            <div class="grid md:grid-cols-2 gap-5 mt-8">
                @foreach ($preguntas as $p)
                    <div class="border border-gray-200 rounded-2xl p-5 bg-white hover:shadow-lg transition-all duration-200 text-left">
                        <p class="text-gray-800 font-semibold mb-2">{{ $p->pregunta }}</p>

                        <p class="text-sm mb-2">
                            <strong>Tu respuesta:</strong>
                            <span class="{{ $p->es_correcta ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                {{ $p->respuesta }}
                            </span>
                        </p>

                        @if ($p->tipo_pregunta === 'abierta')
                            <div class="mt-2">
                                @if ($p->calificada)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Calificada
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Pendiente de calificación
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="mt-3 text-right">
                                @if($p->es_correcta)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Correcta
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Incorrecta
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(isset($pdfs) && count($pdfs) > 0)
        @foreach ($pdfs as $pdf)
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-10 text-center">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mr-2 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6" />
                </svg>
                Entrega en PDF
            </h2>

            <a href="{{ asset('storage/' . $pdf->ruta_archivo) }}" target="_blank"
                class="inline-block bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-lg shadow transition">
                Ver archivo PDF
            </a>

            <div class="mt-5">
                @if($pdf->calificacion)
                    <div class="inline-block bg-green-100 text-green-700 px-6 py-3 rounded-2xl text-lg font-bold shadow">
                        Calificación: {{ $pdf->calificacion }}
                    </div>
                @else
                    <p class="text-gray-500 mt-2">Pendiente de calificación</p>
                @endif

                <p class="mt-4 text-gray-600 italic">
                    <strong>Retroalimentación:</strong> {{ $pdf->retroalimentacion ?? 'Sin comentarios' }}
                </p>
            </div>
        </div>
        @endforeach
    @endif

    @if(isset($audios) && count($audios) > 0)
        @foreach ($audios as $audio)
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-10 text-center">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13l-12 3zM3 6v13l6 1.5V7.5L3 6z" />
                </svg>
                Respuesta Auditiva
            </h2>

            <audio controls class="mx-auto w-full max-w-md mb-4 rounded-lg">
                <source src="{{ asset('storage/' . $audio->ruta_archivo) }}" type="audio/mpeg">
                Tu navegador no soporta audio.
            </audio>

            @if($audio->calificacion)
                <div class="inline-block bg-green-100 text-green-700 px-6 py-3 rounded-2xl text-lg font-bold shadow">
                    Calificación: {{ $audio->calificacion }}
                </div>
            @else
                <p class="text-gray-500 mt-2">Pendiente de calificación</p>
            @endif

            <p class="mt-4 text-gray-600 italic">
                <strong>Retroalimentación:</strong> {{ $audio->retroalimentacion ?? 'Sin comentarios' }}
            </p>
        </div>
        @endforeach
    @endif
</div>
@endsection
