@extends('components.menu')

@section('title', 'Editar información')

@section('content')

<div class="max-w-4xl mx-auto mt-6 bg-white rounded-lg shadow p-8">
    <x-msj-alert />
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-black">Editar correo de acceso para:</h1>
            <span class="text-gray-500 font-light pt-2 block">{{$coordinador->nombres}} {{$coordinador->ap_paterno}} {{$coordinador->ap_materno ?? ''}}</span>
        </div>
        <a href="{{ route('coordinacion.lista-coordinador') }}"
            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow">
            Cancelar
        </a>
    </div>

    <form id="form-insertar" data-url="{{ route('coordinador.actualizar-correo') }}" class="w-full gap-6" action="" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-2">


            <label class="text-gray-500 font-light">Correo electrónico: *</label>
            <input
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                type="email" name="email" value="{{ $coordinador->email }}" required
            >

            <input type="hidden" name="pk_usuario" value="{{$coordinador->pk_usuario}}">

            <div class="flex justify-center mt-6">
                <button class="bg-teal-600 text-white w-full px-6 py-2 rounded-lg hover:bg-teal-700 shadow"
                    type="submit">
                    Guardar cambios
                </button>
            </div>
        </div>
    </form>
</div>

@endsection
