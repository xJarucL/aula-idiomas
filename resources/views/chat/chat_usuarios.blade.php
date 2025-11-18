@extends('components.menu')

@section('title', 'Chats | Aula de Idiomas')

@section('content')
    <section class="sm:mx-50">
        <div class="bg-white p-2 border border-gray-300 rounded-lg shadow">
            {{-- ENCABEZADO --}}
            <div class=" flex flex-wrap items-center border-b border-gray-300 gap-2">
                <a href="{{ route('chat.inicio') }}" class="">
                    <svg class="w-10 h-10 text-teal-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20"
                        height="20" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14M5 12l4-4m-4 4 4 4" />
                    </svg>
                </a>
                <h3 class="text-teal-600 text-3xl mb-2 font-semibold">Nuevo mensaje</h3>
            </div>
            {{-- buscador --}}
            <section class="flex flex-col mt-5 gap-3">
                <div class="w-full flex items-center gap-2">
                    <div class="relative w-full">
                        <input id="buscador"
                            data-url="{{ Route::currentRouteName() == 'coordinacion.lista-coordinador' ? route('coordinacion.lista-coordinador') : route('coordinacion.lista-coordinador-deshabilitados') }}"
                            class="bg-white border border-gray-300 rounded-lg p-3 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
                            type="text" placeholder="Buscar usuario..." value="{{ request('search') }}">
                        <span id="limpiar-busqueda"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg font-bold hover:text-red-500 hover:scale-125 transition-all duration-200 hidden">
                            &times;
                        </span>
                    </div>
                    <button id="btn-buscar"
                        class="bg-teal-600 text-white p-3 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base cursor-pointer">
                        Buscar
                    </button>
                </div>
                <div class="flex flex-row p-2 gap-2">
                    <a href="#" class="p-3 text-gray-600 border border-gray-300 basis-128 rounded-full text-center hover:bg-teal-700 hover:text-white">Todos</a>
                    <a href="#" class="p-3 text-gray-600 border border-gray-300 basis-128 rounded-full text-center hover:bg-teal-700 hover:text-white">Alumnos</a>
                    <a href="#" class="p-3 text-gray-600 border border-gray-300 basis-128 rounded-full text-center hover:bg-teal-700 hover:text-white">Docentes</a>
                </div>
            </section>
            {{-- CONTENIDO --}}
            <div>
                <article class="mt-4">
                    <div class="flex flex-col gap-2 pr-2 overflow-auto h-[38rem] sm:h-[35rem]">
                        @for ($i = 0; $i < 20; $i++)
                            <a href="">
                                <div
                                    class="flex flex-row items-center w-full gap-2 p-1.5 border border-gray-300 hover:bg-gray-100 rounded-lg">
                                    <img src="{{ asset('img/default.jpg') }}"
                                        alt=""class="w-13 h-13 border border-gray-300 rounded-full">
                                    <div class="flex flex-col w-full">
                                        <h1 class="text-gray-700 text-[15px] font-semibold">
                                            Jaruny Guadalupe Cardenas Tirado
                                        </h1>
                                    </div>
                                </div>
                            </a>
                        @endfor
                    </div>
                </article>
            </div>
    </section>
@endsection
