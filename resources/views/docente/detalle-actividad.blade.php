@extends('components.menu')

@section('title', 'Actividad | Aula de Idiomas')

@section('content')
<div class="mt-10 md:ml-20 md:mr-20">
    <x-msj-alert />

    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h1 class="text-3xl font-bold text-teal-700 mb-2">{{ $actividad->nom_actividad }}</h1>
        <p class="text-gray-600 mb-6">{{ $actividad->descripcion }}</p>

        @if ($tipo === 'preguntas')
                @foreach ($preguntas as $index => $pregunta)
                    <div class="mb-6 border-b border-gray-200 pb-4">
                        <h2 class="font-semibold text-lg text-teal-700 mb-2">
                            {{ $index + 1 }}. {{ $pregunta->pregunta }}
                        </h2>

                       @if ($pregunta->tipo === 'opcion_multiple')
                            @php
                                $groupName = 'respuestas_' . $pregunta->pk_pregunta;
                            @endphp
                            @foreach ($pregunta->opciones as $opcion)
                                <div class="flex items-center mb-2 bg-gray-50 hover:bg-gray-100 rounded-lg p-2">
                                    <input type="radio"
                                        id="pregunta_{{ $pregunta->pk_pregunta }}_opcion_{{ $opcion->pk_opcion }}"
                                        name="{{ $groupName }}"
                                        value="{{ $opcion->texto_opcion }}"

                                        @if ($opcion->es_correcta)
                                            checked
                                        @endif

                                        class="text-teal-600 focus:ring-teal-500">
                                    <label for="pregunta_{{ $pregunta->pk_pregunta }}_opcion_{{ $opcion->pk_opcion }}"
                                        class="ml-2 text-gray-700 cursor-pointer">
                                        {{ $opcion->texto_opcion }}

                                        @if ($opcion->es_correcta)
                                            <span class="text-green-600 font-bold">(Correcta)</span>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <textarea name="respuestas[{{ $pregunta->pk_pregunta }}]" rows="3"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50"
                                placeholder="Escribe tu respuesta aquí..."></textarea>
                        @endif
                    </div>
                @endforeach

        @elseif ($tipo === 'pdf')
            <div class="flex flex-col items-center">

                @if ($pdf->archivo_docente)
                    <iframe
                        src="{{ asset('storage/' . $pdf->archivo_docente) }}"
                        class="w-full h-[600px] border rounded-lg shadow"
                    ></iframe>
                @else
                    <p class="text-red-600 font-semibold mt-4">No se encontró el archivo PDF del docente.</p>
                @endif
            </div>
        @elseif ($tipo === 'auditiva')
            <div class="text-center">
                <p class="text-gray-600 mb-4">{{ $auditiva->texto_frase }}</p>

                @if ($auditiva->archivo_audio_docente)
                    <audio controls class="mx-auto mb-6">
                        <source src="{{ asset('storage/' . $auditiva->archivo_audio_docente) }}" type="audio/mpeg">
                        Tu navegador no soporta audio.
                    </audio>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
