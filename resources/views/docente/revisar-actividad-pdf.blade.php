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

        @if($entrega->archivo_alumno)
            <iframe
                src="{{ asset('storage/' . $entrega->archivo_alumno) }}"
                class="w-full h-[800px] border rounded-xl shadow"
            ></iframe>
        @else
            <p class="text-gray-500 mt-4">El alumno no subió un archivo PDF.</p>
        @endif

        <form id="form-insertar" data-url="{{ route('docente.calificar.pdf') }}" action="" method="POST" class="mt-6">
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

                <input type="hidden" name="pk_entrega" value="{{$entrega->pk_entrega}}">

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
