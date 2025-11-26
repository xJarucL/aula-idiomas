@extends('components.menu')

@section('title', 'Actividades del Alumno')

@section('content')

<section class="mx-6 mt-6">

    <a href="{{ route('docente.calificar.grupo', $grupoId) }}"
        class="inline-flex items-center gap-2 text-teal-700 hover:text-teal-900 mb-4">
        ← Regresar
    </a>

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">
        Actividades del Alumno
    </h1>

    @if($promedioGeneral !== null)
        <div class="bg-teal-50 border border-teal-200 text-teal-800 px-5 py-3 rounded-lg shadow mb-6">
            <p class="text-lg font-semibold">
                Promedio por entrega de actividades:
                <span class="text-teal-700">{{ number_format($promedioGeneral, 2) }}</span> / 10
            </p>
        </div>
    @else
        <div class="bg-gray-100 border border-gray-200 text-gray-600 px-5 py-3 rounded-lg shadow mb-6">
            <p class="text-lg font-semibold">
                El alumno no tiene actividades calificadas aún.
            </p>
        </div>
    @endif

    @foreach($actividades as $item)
        <div class="bg-white border border-gray-200 rounded-lg shadow mb-6 p-5">

            <h2 class="text-xl font-semibold text-teal-700 mb-3">
                {{ $item['actividad']->titulo }}
            </h2>

            <p class="text-gray-600 mb-3">
                Tipo:
                <span class="font-medium text-gray-800">
                    {{ ucfirst($item['actividad']->tipo) }}
                </span>
            </p>

            @if($item['calificacion'] !== null)
                <div class="mb-4 px-4 py-2 border-l-4 border-teal-500 bg-teal-100 text-teal-700 rounded">
                    Calificación: <strong>{{ $item['calificacion'] }}</strong> / 10
                </div>
            @else
                <p class="text-gray-500 italic mb-4">
                    Sin calificar
                </p>
            @endif

            @if($item['actividad']->tipo === 'preguntas')

                <h3 class="text-lg font-semibold text-gray-700 mb-2">Respuestas del alumno:</h3>

                @if($item['entrega'] && count($item['entrega']) > 0)
                    <ul class="list-disc ml-6 text-gray-700">
                        @foreach($item['entrega'] as $resp)
                            <li class="mb-3">

                                <p class="font-medium text-gray-800">
                                    {{ $resp->pregunta->pregunta ?? 'Pregunta no encontrada' }}
                                </p>

                                @if(isset($resp->pregunta->descripcion))
                                    <p class="text-gray-600 text-sm mb-1">
                                        {{ $resp->pregunta->descripcion }}
                                    </p>
                                @endif

                                <p>Respuesta: <strong>{{ $resp->respuesta }}</strong></p>

                                @if($resp->calificada)
                                    <span class="{{ $resp->es_correcta ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $resp->es_correcta ? 'Correcta' : 'Incorrecta' }}
                                    </span>
                                @else
                                    <span class="text-gray-500 italic">Pendiente de calificar</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 italic">El alumno no ha respondido esta actividad.</p>
                @endif

            @elseif($item['actividad']->tipo === 'pdf')

                <h3 class="text-lg font-semibold text-gray-700 mb-2">Entrega PDF:</h3>

                @if($item['entrega'])
                    <a href="{{ asset('storage/' . $item['entrega']->archivo_alumno) }}"
                        target="_blank"
                        class="text-teal-700 underline hover:text-teal-900">
                        Ver archivo entregado
                    </a>
                @else
                    <p class="text-gray-500 italic">No hay entrega registrada.</p>
                @endif

            @elseif($item['actividad']->tipo === 'auditiva')

                <h3 class="text-lg font-semibold text-gray-700 mb-2">Entrega de audio:</h3>

                @if($item['entrega'])
                    <audio controls class="w-full mb-3">
                        <source src="{{ asset('storage/' . $item['entrega']->archivo_audio_alumno) }}" type="audio/mpeg">
                        Tu navegador no soporta la reproducción de audio.
                    </audio>
                @else
                    <p class="text-gray-500 italic">No hay entrega registrada.</p>
                @endif

            @endif
        </div>
    @endforeach

</section>

@endsection
