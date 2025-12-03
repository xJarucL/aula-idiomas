@extends('components.menu')

@section('title', 'Asignar grupo de alumnos')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white shadow rounded-lg p-8">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Asignar alumnos de grupo</h2>
    <x-msj-alert />

    <p class="mb-6 text-gray-600">
        Vas a asignar los alumnos del grupo
        <span class="font-bold text-teal-700">{{ $grupo_origen->fk_cuatrimestre }}{{ $grupo_origen->nombre }}{{ $grupo_origen->carrera->abreviatura }} {{ $grupo_origen->año }}</span>
        ({{ $grupo_origen->carrera->nombre }}{{ $grupo_origen->cuatrimestre->nombre }})
        a otro grupo existente.
    </p>

    <form action="{{ route('coordinacion.guardar-asignacion-grupo') }}" method="POST">
        @csrf
        <input type="hidden" name="grupo_origen" value="{{ $grupo_origen->pk_grupo }}">

        <div class="mb-4">
            <label for="grupo_destino" class="block text-gray-700 font-medium mb-2">Seleccionar grupo destino</label>
            <select name="grupo_destino" id="grupo_destino" class="w-full border rounded-lg p-2 focus:ring focus:ring-teal-300">
                <option value="">Selecciona un grupo</option>
                @foreach ($grupos_destino as $grupo)
                    <option value="{{ $grupo->pk_grupo }}">{{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura }} {{ $grupo->año }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 rounded-lg">
            Asignar alumnos
        </button>
    </form>
</div>
@endsection
