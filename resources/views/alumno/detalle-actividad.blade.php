@extends('components.menu')

@section('title', 'Actividad | Aula de Idiomas')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between gap-4 sm:gap-20">
        {{-- TITULO --}}
        <section class="w-full sm:w-3/4">
            <div>
                <h1 class="text-2xl sm:text-4xl font-bold text-teal-800">Actividad: Past simple vs Past continuous</h1>
                <span class="text-sm sm:text-lg text-gray-500 text-justify sm:font-light mt-2 w-full">
                    Tipo de actividad: <span class="text-teal-700 hover:border-b">Preguntas</span>
                </span>
                <h4 class="text-gray-500 text-[10px] sm:text-lg text-justify">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
                </h4>
            </div>
            {{-- CUESTIONARIO --}}
            <div class="mt-5 sm:mt-7">
                @for ($i = 1; $i < 10; $i++)
                    <div class="mb-5">
                        <form id="enviarRespuesta" action="">
                            <div class="bg-white border border-gray-300 rounded-lg shadow">
                                @include('partials.actividad.opciones')
                            </div>
                        </form>
                    </div>
                @endfor
            </div>
        </section>
        <section class="w-full sm:w-1/4 sticky top-30 self-start">
            {{-- APARTADO DE SEGUIMIENTO --}}
            <div class="bg-white p-4 w-full h-auto border border-gray-200 rounded-lg shadow-lg">
                <div>
                    <h3 class="text-teal-700 text-xl font-semibold">Progreso de actividad</h3>
                    <h4 class="text-gray-600 font-medium">Preguntas:</h4>
                    <div class="flex flex-wrap justify-start items-center gap-4 mt-4">
                        @for ($i = 1; $i <= 10; $i++)
                            <a href="#pregunta{{$i}}" class="">
                                <div class="border border-gray-300 rounded-lg px-3 py-3 hover:border-teal-700">{{$i}}</div>
                            </a>
                        @endfor
                    </div>

                    <button type="submit" form="enviarRespuesta"
                        class="mt-5 w-full bg-teal-600 hover:bg-teal-800 text-white font-semibold py-2 px-4 rounded-lg transition-colors cursor-pointer">
                        Enviar respuesta
                    </button>
                </div>
            </div>
        </section>
    </div>

@endsection
