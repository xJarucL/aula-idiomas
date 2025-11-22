@extends('components.menu')

@section('title', 'Revisar | Aula de Idiomas')

@section('content')
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

        <hr class="my-4 border-gray-300">

        @foreach($respuestas as $respuesta)
            <div class="border border-gray-200 bg-gray-50 p-5 rounded-xl shadow-sm my-5 hover:shadow-md transition">

                <p class="font-semibold text-teal-700 text-lg">
                    {{ $respuesta->pregunta->pregunta }}
                </p>

                <p class="mt-3 text-gray-700 leading-relaxed">
                    <span class="font-medium text-gray-800">Respuesta del alumno:</span><br>
                    {{ $respuesta->respuesta ?? 'Sin respuesta' }}
                </p>

                @if($respuesta->archivo)
                    <a href="{{ asset('storage/'.$respuesta->archivo) }}" target="_blank"
                        class="inline-block mt-3 text-teal-600 font-medium underline hover:text-teal-800">
                        📎 Ver archivo adjunto
                    </a>
                @endif

                <div class="mt-5 flex gap-3">

                    <form action="{{ route('docente.calificar.respuesta', $respuesta->pk_respuesta) }}" method="POST">
                        @csrf
                        <input type="hidden" name="calificacion" value="1">

                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm transition-all duration-200
                            {{ $respuesta->es_correcta === 1
                                ? 'bg-green-600 text-white scale-105 shadow-lg'
                                : 'bg-green-100 text-green-700 hover:bg-green-200'
                            }}">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.8"
                                class="w-5 h-5 {{ $respuesta->es_correcta === 1 ? 'stroke-white' : 'stroke-green-700' }}">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m4.5 12.75 6 6 9-13.5" />
                            </svg>

                            Bien
                        </button>
                    </form>

                    <form action="{{ route('docente.calificar.respuesta', $respuesta->pk_respuesta) }}" method="POST">
                        @csrf
                        <input type="hidden" name="calificacion" value="0">

                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm transition-all duration-200
                            {{ $respuesta->es_correcta === 0 && $respuesta->calificada === 1
                                ? 'bg-red-600 text-white scale-105 shadow-lg'
                                : 'bg-red-100 text-red-700 hover:bg-red-200'
                            }}">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.8"
                                class="w-5 h-5 {{ ($respuesta->es_correcta === 0 && $respuesta->calificada === 1) ? 'stroke-white' : 'stroke-red-700' }}">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18 18 6M6 6l12 12" />
                            </svg>

                            Mal
                        </button>
                    </form>

                </div>

            </div>
        @endforeach
    </div>

</section>
@endsection
