@extends('components.menu')

@section('title', 'Responder actividad | Aula de Idiomas')

@section('content')
<div class="mt-10 md:ml-20 md:mr-20">
    <x-msj-alert />

    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h1 class="text-3xl font-bold text-teal-700 mb-2">{{ $actividad->nom_actividad }}</h1>
        <p class="text-gray-600 mb-6">{{ $actividad->descripcion }}</p>

        @if ($tipo === 'preguntas')
            <form action="{{ route('alumno.guardar-respuesta', $actividad->pk_actividad) }}" method="POST">
                @csrf
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
                                        class="text-teal-600 focus:ring-teal-500">
                                    <label for="pregunta_{{ $pregunta->pk_pregunta }}_opcion_{{ $opcion->pk_opcion }}"
                                        class="ml-2 text-gray-700 cursor-pointer">
                                        {{ $opcion->texto_opcion }}
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

                <div class="text-right">
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-md">
                        Enviar respuestas
                    </button>
                </div>
            </form>

        @elseif ($tipo === 'pdf')
            <div class="flex flex-col items-center">
                <p class="text-gray-600 mb-4">Descarga el documento y adjunta tu respuesta en formato PDF.</p>
                <a href="{{ asset('storage/' . $pdf->archivo_docente) }}" target="_blank"
                    class="bg-teal-600 text-white px-6 py-3 rounded-lg shadow hover:bg-teal-700 mb-6">
                    📄 Descargar archivo
                </a>

                <form action="{{ route('alumno.subir-respuesta-pdf', $actividad->pk_actividad) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <label class="block mb-2 text-gray-700 font-medium">Sube tu respuesta (PDF)</label>
                    <input type="file" name="archivo_alumno"
                        accept="application/pdf"
                        class="border border-gray-300 p-3 rounded-lg w-full mb-4 focus:outline-none focus:ring-2 focus:ring-teal-500" required>

                    <div class="text-right">
                        <button type="submit"
                            class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-md">
                            Enviar archivo
                        </button>
                    </div>
                </form>
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

                <form action="{{ route('alumno.guardar-respuesta-auditiva', $actividad->pk_actividad) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <label class="block mb-2 text-gray-700 font-medium">Graba o sube tu respuesta (audio)</label>
                    <input type="file" name="archivo_respuesta"
                        accept="audio/*"
                        class="border border-gray-300 p-3 rounded-lg w-full mb-4 focus:outline-none focus:ring-2 focus:ring-teal-500" required>

                    <div class="text-right">
                        <button type="submit"
                            class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-md">
                            Enviar respuesta
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
