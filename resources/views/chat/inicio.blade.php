@extends('components.menu')

@section('title', 'Mensajeria | Aula de Idiomas')

@section('chat')
    <div class="flex flex-row h-full">
        {{-- MENU MENSAJES --}}
        <section class="w-1/5 border-r border-gray-300">
            <div class="flex flex-col">
                {{-- ENCABEZADO --}}
                <article class="p-2 h-15 border-b border-gray-300">
                    <div class="flex justify-between items-center">
                        <h3 class="text-teal-700 text-xl font-semibold">Mensajeria</h3>
                        {{-- BOTON LISTADO USUARIOS --}}
                        <a href="{{ route('chat.usuarios') }}"  class="bg-teal-600 p-2 text-white rounded-lg hover:bg-teal-700 shadow transition duration-200">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z"
                                    clip-rule="evenodd" />
                                <path fill-rule="evenodd"
                                    d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </article>
                {{-- USUARIOS --}}
                <article class="flex flex-col items-center gap-2">
                    <div class="overflow-auto h-screen w-full p-2">
                        {{-- CHAT --}}
                        <a href="" class="">
                            <div class="flex flex-row items-center w-full gap-2 p-1.5  hover:bg-gray-200 rounded-lg">
                                <img src="{{ asset('img/logo-ingles.png') }}"
                                    alt=""class="w-10 h-10 border border-gray-300 rounded-full">
                                <div class="flex flex-col w-52">
                                    <h1 class="text-gray-700 text-[15px] font-semibold block truncate">
                                        Jaruny Guadalupe Cardenas Tirado
                                    </h1>
                                    <div class="flex items-center w-full gap-2">
                                        <div class="w-52">
                                            <span class="text-gray-500 text-[10px] block truncate">
                                                Hola, ¿Te sientes muy onichan? espero y estes bien sakdjfhsdjkf sdhfkjsahd
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="">
                            <div class="flex flex-row items-center w-full gap-2 p-1.5  hover:bg-gray-200 rounded-lg">
                                <img src="{{ asset('img/logo-ingles.png') }}"
                                    alt=""class="w-10 h-10 border border-gray-300 rounded-full">
                                <div class="flex flex-col w-52">
                                    <h1 class="text-gray-700 text-[15px] font-semibold block truncate">
                                        Jaruny Guadalupe Cardenas Tirado
                                    </h1>
                                    <div class="flex items-center w-full gap-2">
                                        <div class="w-52">
                                            <span class="text-gray-500 text-[10px] block truncate">
                                                Hola, ¿Te sientes muy onichan? espero y estes bien sakdjfhsdjkf sdhfkjsahd
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </article>
            </div>
        </section>
        {{-- BANDEJA DE MENSAJES --}}
        <section class="w-4/5">
            {{-- ENCABEZADO --}}
            <article class="p-2 h-15 border-b border-gray-300">
                <div class="flex flex-row items-center gap-2 ">
                    <img src="{{ asset('img/logo-ingles.png') }}"
                        alt=""class="w-12 h-12 border border-gray-300 rounded-full">
                    <h1 class="text-teal-700 text-xl font-semibold">Jaruny Lupe Cardenas Tirado</h1>
                </div>
            </article>
            {{-- APARTADO DE MENSAJE --}}
            <article class="bg-gray-200 h-screen">
                <div class="overflow-auto h-full flex flex-col-reverse">
                    <div class="p-3 ">
                        {{-- MENSAJE RECIBIDO --}}
                        @for ($i = 0; $i < 3; $i++)
                            <div class="mb-4 flex w-full justify-start">
                                <div
                                    class="bg-white p-3 border border-gray-300 rounded-br-2xl rounded-tl-2xl rounded-tr-2xl shadow-md max-w-6xl">
                                    <p class="text-gray-600 font-light">¿Te sientes muy onichan?</p>
                                </div>
                            </div>
                            {{-- MENSAJE ENVIADO --}}
                            <div class="mb-4 flex w-full justify-end">
                                <div
                                    class="bg-teal-700 p-3 border border-gray-300 rounded-bl-2xl rounded-tr-2xl rounded-tl-2xl shadow-md max-w-6xl">
                                    <p class="text-white font-light">
                                        lorem
                                    </p>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </article>
            <div>
                {{-- INPUT MENSAJE --}}
                <div class="flex flex-row items-center gap-3 p-3 border-t border-gray-300">
                    <input type="text" placeholder="Escribe un mensaje..."
                        class="w-full p-2 text-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600" />
                    <button
                        class="bg-teal-700 p-2 text-white border rounded-lg hover:bg-teal-800 shadow transition duration-200 cursor-pointer">
                        Enviar
                    </button>
                </div>
            </div>
        </section>
    </div>
@endsection
