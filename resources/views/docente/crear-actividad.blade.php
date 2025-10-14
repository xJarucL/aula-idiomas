@extends('components.menu')

@section('title', 'Lista de actividades | Aula de idiomas')

@section('content')

    <div class="p-2 md:ml-20 md:mr-20">
        <section class="flex flex-col md:flex-row justify-between  items-start md:items-center">
            <div>
                <h1 class="text-2xl md:text-4xl font-bold text-black">Crear Nueva Actividad</h1>
                <span class="text-gray-500 font-light mt-2">
                    Completa el siguiente formulario para crear una nueva actividad.
                </span>
            </div>
            <div class="">
                <a href="{{ route('docente.crear-actividad') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition">
                    Volver
                </a>
            </div>
        </section>
        <section class="mt-12">
            <form class="md:pl-15 md:pr-15" action="" method="post">
                @csrf
                <div class="bg-white p-5 rounded-2xl shadow-2xs ">
                    <div class="flex flex-col">
                        <span>Actividad</span>
                        <label for="" class="text-gray-500 font-light mb-2">Nombre de la actividad</label>
                        <input class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition" type="text" required>
                        <label for="" class="text-gray-500 font-light mb-2">Descripción de la actividad</label>
                        <textarea class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition" required style="height: 132px;"></textarea>
                    </div>
                </div>
                <div class="flex justify-between mt-5">
                    <h3 class="text-2xl font-semibold">Preguntas</h3>
                    <button class="bg-teal-600 text-white px-4 py-2 rounded-lg shadow hover:bg-teal-700 transition">Agregar pregunta</button>
                </div>
                <div class="bg-white">
                    <div>
                        <h3>Pregunta 1</h3>
                        <span>Opción Multiple</span>
                    </div>
                </div>
            </form>
        </section>
    </div>


@endsection
