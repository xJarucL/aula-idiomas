@extends('components.menu')

@section('title', 'Docente - Editar Perfil')

@section('content')

    <div class="max-w-4xl mx-auto mt-6 bg-white rounded-lg shadow p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-black">Editar Perfil</h1>
                <span class="text-gray-500 font-light pt-2 block">Completa el siguiente formulario para editar su
                    información
                </span>
            </div>
            <a href="{{ route('docente.perfil') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow">
                Cancelar
            </a>
        </div>
        <form class="w-full gap-6" action="" method="post">
            @csrf
            <div class="flex flex-col gap-2">
                <div class="flex justify-center mt-2">
                    {{-- imagen de perfil --}}
                    <label for="imagen de usuario" class="cursor-pointer">
                        <img class="w-28 h-28 border border-l-gray-500 rounded-full mb-4"
                            src="{{ asset('img/default.jpg') }}" alt="foto de perfil">
                    </label>
                    <input type="file" name="foto_perfil" class="hidden" accept="image/*" />
                </div>
                <label class="text-gray-500 font-light">Nombres:</label>
                <input
                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    type="text" name="nombres" value="" required>

                <label class="text-gray-500 font-ligh">Apellido paterno:</label>
                <input
                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    type="text" name="ap_paterno" value="" required>

                <label class="text-gray-500 font-light">Apellido materno:</label>
                <input
                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    type="text" name="ap_materno" value="">
                <label class="text-gray-500 font-light">Correo electrónico:</label>
                <input
                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    type="email" name="email" value="" required>
                <div class="flex justify-center mt-6">
                    <button class="bg-teal-600 text-white w-full px-6 py-2 rounded-lg hover:bg-teal-700 shadow"
                        type="submit">
                        Guardar cambios
                    </button>
                </div>
        </form>
    </div>

@endsection
