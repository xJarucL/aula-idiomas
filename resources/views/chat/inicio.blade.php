@extends('components.menu')

@section('title', 'Mensajeria | Aula de Idiomas')

@section('chat')
    <div class="flex flex-row">
        {{-- MENU MENSAJES --}}
        <section class="w-1/5 border-r border-gray-300">
            <div class="flex flex-col">
                {{-- ENCABEZADO --}}
                <article class="p-2 h-15 border-b border-gray-300">
                    <div class="flex justify-between items-center">
                        <h3 class="text-teal-700 text-xl font-semibold">Mensajeria</h3>
                        <button
                            class="bg-teal-600 p-2 text-white border rounded-lg hover:bg-teal-700 shadow transition duration-200 cursor-pointer">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z"
                                    clip-rule="evenodd" />
                                <path fill-rule="evenodd"
                                    d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </article>
                {{-- USUARIOS --}}
                <article class="flex flex-col items-center gap-3">
                    <div class="overflow-auto h-screen w-full p-2">
                        @for ($i = 0; $i < 40; $i++)
                            <div class="flex flex-row items-center gap-3 p-2 hover:bg-gray-200 rounded-lg cursor-pointer">
                                <div class="bg-teal-700 px-5 py-5 rounded-full"></div>
                                <div class="flex flex-wrap gap-3">
                                    <div>
                                        <h1 class="text-gray-700 text-xs font-semibold">Angel Ariel Salazar Medina</h1>
                                        <span class="text-gray-500 text-[10px]">Hola, ¿cómo estás?</span>
                                    </div>
                                    <span class="text-[10px] text-gray-500">09:30</span>
                                </div>
                            </div>
                        @endfor
                    </div>
                </article>
            </div>
        </section>
        {{-- BANDEJA DE MENSAJES --}}
        <section class="w-4/5">
            {{-- ENCABEZADO --}}
            <article class="p-2 h-15 border-b border-gray-300">
                <div class="flex flex-row items-center gap-5 ">
                    <div class="bg-blue-700 px-5 py-5 rounded-full"></div>
                    <h1 class="text-teal-700 text-xl font-semibold">Jaruny Lupe Cardenas Tirado</h1>
                </div>
            </article>
            {{-- MENSAJE --}}
            <article class="bg-gray-200 h-screen p-3 overflow-auto">
                hola
            </article>
        </section>
    </div>
@endsection
