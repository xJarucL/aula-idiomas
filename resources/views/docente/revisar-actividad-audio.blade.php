@extends('components.menu')

@section('title', 'Revisar | Aula de Idiomas')

@section('content')
<x-msj-alert />
<section class="sm:mx-6 mt-6">

    <a href="{{ route('docente.actividades-pendientes') }}"
        class="inline-flex items-center gap-2 bg-teal-100 text-gray-700 px-4 py-2 rounded-lg shadow hover:bg-teal-200 hover:scale-105 transition">
        ← Guardar y volver
    </a>

    <div class="bg-white shadow-xl rounded-2xl p-6 mt-4">
        <h2 class="text-3xl font-bold text-teal-700">{{ $actividad->nom_actividad }}</h2>

        <p class="mt-2 text-gray-600 text-lg">
            Alumno:
            <strong class="text-gray-900">
                {{ $alumno->usuario->nombres }} {{ $alumno->usuario->ap_paterno }} {{ $alumno->usuario->ap_materno ?? ''}}
            </strong>
        </p>

        <hr class="my-4">

        <h class="text-2xl font-bold text-teal-700"3>Frase:</h>
        <span>{{$act_audio->texto_frase}}</span>

        @if($entrega->archivo_audio_alumno)
            <div class="mt-4">
                <p class="text-lg font-semibold text-teal-700 mb-2">Grabación del alumno:</p>

                <audio controls class="w-full mt-2">
                    <source src="{{ asset('storage/' . $entrega->archivo_audio_alumno) }}" type="audio/mpeg">
                    Tu navegador no soporta la reproducción de audio.
                </audio>
            </div>
        @else
            <p class="text-gray-500 mt-4">El alumno no subió un archivo PDF.</p>
        @endif

        <form id="form-insertar" data-url="{{ route('docente.calificar.audio') }}" action="" method="POST" class="mt-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="fk_actividad" value="{{ $actividad->pk_actividad }}">
            <input type="hidden" name="fk_alumno" value="{{ $alumno->pk_alumno }}">

            <div class="bg-gray-50 border rounded-2xl p-6 shadow-inner">

                <h3 class="text-2xl font-semibold text-teal-700 mb-4">Calificación</h3>

                <label class="block text-gray-700 font-semibold">Calificación (0 - 10)</label>
                <input
                    type="number"
                    name="calificacion"
                    min="0"
                    max="10"
                    step="0.01"
                    required
                    class="mt-1 w-32 px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:outline-none"
                >

                <label class="block text-gray-700 font-semibold mt-4">Observaciones</label>
                <textarea
                    name="observaciones"
                    rows="4"
                    class="mt-1 w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:outline-none"
                    placeholder="Escribe comentarios sobre el desempeño del alumno..."
                ></textarea>

                <input type="hidden" name="pk_respuesta" value="{{$entrega->pk_respuesta}}">

                <div class="mt-6">
                    <button
                        type="submit"
                        class="bg-teal-600 text-white px-6 py-3 rounded-xl shadow-lg hover:bg-teal-700 hover:scale-105 transition-all font-medium"
                    >
                        Guardar calificación
                    </button>
                </div>

            </div>
        </form>


    </div>

</section>
@endsection
