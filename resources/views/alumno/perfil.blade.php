@extends('components.menu')

@section('title', 'Alumno - Perfil')

@section('content')

    <div class="max-w-4xl mx-auto mt-2 bg-white rounded-lg shadow p-8">
        {{-- perfil --}}
        <section class="flex flex-col items-center text-center mb-8">
            {{-- <div class="w-28 h-28 bg-gray-300 rounded-full mb-4"></div> --}}
            <img class="w-28 h-28 border border-l-gray-500 rounded-full mb-4" src="{{ asset('img/default.jpg') }}"
                alt="foto de perfil">
            <h2 class="text-2xl font-semibold">Angel Ariel Salazar Medina</h2>
            <p class="text-sm text-gray-600 mb-3 mt-2">Alumno con potencial</p>
            <a class="bg-teal-600 px-3 py-2 text-white hover:bg-teal-800 transition rounded-xl shadow"
                href="{{ route('alumno.editar-perfil') }}">Editar perfil
            </a>
        </section>

        {{-- Información --}}
        <section class="pt-5 mb-6">
            <div class="border-b border-gray-200 pt-6 mb-4">
                <h3 class="text-2xl font-semibold mb-2">Información de contacto</h3>
            </div>
            <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
                <span class="font-medium mr-12">Matricula:</span>
                <span class="text-gray-600">202200412</span>
            </div>
            <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
                <span class="font-medium mr-12">Carrera:</span>
                <span class="text-gray-600">Ingeniería en Tecnologías de la Información e Innovación Digital</span>
            </div>
            <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
                <span class="font-medium mr-12">Promedio:</span>
                <span class="text-gray-600">9.2</span>
            </div>
        </section>
        {{-- cargo
        <section class="mt-15">
            <h3 class="text-2xl font-semibold mb-3">Carga de trabajo</h3>
            <div class="flex flex-col md:flex-row justify-around gap-4">
                <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs  text-start shadow">
                    <p class="text-gray-600">Grupos Creados</p>
                    <p class="text-4xl font-semibold mt-2">24</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs text-start shadow">
                    <p class="text-gray-600">Estudiante bajo enseñanza</p>
                    <p class="text-4xl font-semibold mt-2">200</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs text-start shadow">
                    <p class="text-gray-600">Actividades creadas</p>
                    <p class="text-4xl font-semibold mt-2">500</p>
                </div>
            </div>
        </section>
        <!-- Historia de grupos -->
        <div class="pt-6 mt-3">
            <h3 class="text-2xl font-semibold mb-4">Historia de grupos</h3>
            <div class="bg-gray-100 rounded-md h-60 shadow">
                 Contediod 
            </div>
        </div> --}}
    </div>

@endsection
