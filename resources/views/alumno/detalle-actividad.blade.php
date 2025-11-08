@extends('components.menu')

@section('title', 'Actividad | Aula de Idiomas')

@section('content')
    <div class="flex justify-between gap-5">
        {{-- TITULO --}}
        <section class="">
            <div>
                <h1 class="text-2xl sm:text-4xl font-bold text-teal-800">Actividad: Past simple vs Past continuous</h1>
                <span class="text-sm sm:text-lg text-gray-500 text-justify sm:font-light mt-2 w-full">
                    Tipo de actividad: <span class="text-teal-700 hover:border-b">Preguntas</span>
                </span>
                <h4 class="text-gray-500 text-justify">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                </h4>
            </div>
            {{-- CUESTIONARIO --}}
            <div class="mt-5 sm:mt-7">
                @for ($i = 1; $i < 10; $i++)
                    <div class="">
                        hola
                    </div>
                @endfor
            </div>
        </section>
        <section class="static">
            {{-- APARTADO DE SEGUIMIENTO --}}
            <div class="bg-white p-4 w-full h-52 border border-gray-200 rounded-lg shadow-lg">
                <div>
                    <h4 class="text-2xl">hola</h4>
                </div>
            </div>
        </section>
    </div>

@endsection
