@extends('components.menu')

@section('title', 'Registro de Coordinador')

@section('content')

    <section class="flex-1 flex justify-center items-center mt-10">
        <x-msj-alert />
        <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md w-full max-w-3xl">
            <div class="flex items-center w-full mb-6">
                <div class="flex flex-row items-center w-full gap-2">
                    <a href="{{ route('coordinacion.lista-coordinador') }}" class="w-auto flex justify-center items-center">
                        <svg class="w-8 h-8 text-teal-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                        <span class="text-teal-600 font-light hidden sm:block">Regresar</span>
                    </a>
                    <div class="flex justify-center items-center w-full">
                        <h1 class="text-2xl sm:text-4xl font-bold text-teal-600">Registro de Coordinador</h1>
                    </div>
                </div>
                {{-- <a href="{{route('coordinacion.lista-coordinador')}}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow">
                Cancelar
            </a> --}}
            </div>
            <form id="form-insertar" data-url="{{ route('coordinacion.guardar-coordinador') }}" class="w-full gap-6"
                action="" method="post">
                @csrf
                <div class="flex flex-col gap-2">
                    <label class="text-gray-500 font-light">Nombre: *</label>
                    <input
                        class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                        type="text" name="nombres" required>

                    <label class="text-gray-500 font-ligh">Apellido paterno: *</label>
                    <input
                        class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                        type="text" name="ap_paterno" required>

                    <label class="text-gray-500 font-light">Apellido materno:</label>
                    <input
                        class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                        type="text" name="ap_materno">
                    <label class="text-gray-500 font-light">Correo electrónico: *</label>
                    <input
                        class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                        type="email" name="email" required>
                    <div class="flex justify-center mt-6">
                        <button class="bg-teal-600 text-white w-full px-6 py-2 rounded-lg hover:bg-teal-700 shadow cursor-pointer"
                            type="submit">
                            Registrar
                        </button>
                    </div>
                </div>


            </form>
        </div>
    </section>

@endsection
