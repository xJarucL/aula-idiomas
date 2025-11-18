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
                            data-url="{{ route('chat.usuarios') }}"
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
                    <a href="#" class="p-3 text-gray-600 border border-gray-300 basis-128 rounded-full text-center hover:bg-teal-700 hover:text-white" data-filter="">Todos</a>
                    <a href="#" class="p-3 text-gray-600 border border-gray-300 basis-128 rounded-full text-center hover:bg-teal-700 hover:text-white" data-filter="1">Alumnos</a>
                    <a href="#" class="p-3 text-gray-600 border border-gray-300 basis-128 rounded-full text-center hover:bg-teal-700 hover:text-white" data-filter="2">Docentes</a>
                </div>
            </section>
            {{-- CONTENIDO --}}
            <div id="tabla-listado">
                <article class="mt-4">
                    <div class="flex flex-col gap-2 pr-2 overflow-auto h-[38rem] sm:h-[35rem]">
                        @foreach($usuarios as $usuario)
                            <a href="{{route('chat.conversacion', $usuario->pk_usuario)}}">
                                <div
                                    class="flex flex-row items-center w-full gap-2 p-1.5 border border-gray-300 hover:bg-gray-100 rounded-lg">
                                    <img src="{{ $usuario->img_user ? asset('storage/'.$usuario->img_user) : asset('img/default.jpg') }}"
                                        alt=""class="w-13 h-13 border border-gray-300 rounded-full">
                                    <div class="flex flex-col w-full">
                                        <h1 class="text-gray-700 text-[15px] font-semibold">
                                            {{$usuario->nombres}} {{$usuario->ap_paterno}} {{$usuario->ap_materno ?? ''}}
                                        </h1>
                                        <span>
                                            @if($usuario->fk_tipo_usuario == 1)
                                                Alumno
                                            @elseif($usuario->fk_tipo_usuario == 2)
                                                Docente
                                            @elseif($usuario->fk_tipo_usuario == 3)
                                                Coordinador
                                            @else
                                                Desconocido
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </article>
            </div>
    </section>
@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const inputBuscar = document.getElementById("buscador");
        const btnBuscar = document.getElementById("btn-buscar");
        const btnLimpiar = document.getElementById("limpiar-busqueda");
        const listaContenedor = document.getElementById("tabla-listado");
        const filtroBotones = document.querySelectorAll("[data-filter]");

        let filtroSeleccionado = "";

        function cargarUsuarios() {
            const url = inputBuscar.dataset.url;
            const search = inputBuscar.value;

            fetch(url + "?search=" + encodeURIComponent(search) + "&tipo=" + filtroSeleccionado, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(resp => resp.text())
            .then(html => {
                listaContenedor.innerHTML = html;
                mostrarOcultarLimpiar();
            })
            .catch(e => {
                console.error(e);
                listaContenedor.innerHTML = '<p class="text-center text-red-500">Error al cargar usuarios.</p>';
            });
        }

        function mostrarOcultarLimpiar() {
            if (inputBuscar.value.length > 0 || filtroSeleccionado !== "") {
                btnLimpiar.classList.remove("hidden");
            } else {
                btnLimpiar.classList.add("hidden");
            }
        }

        inputBuscar.addEventListener("keyup", e => {
            mostrarOcultarLimpiar();
            if (e.key === "Enter") cargarUsuarios();
        });

        btnBuscar.addEventListener("click", cargarUsuarios);

        btnLimpiar.addEventListener("click", function () {
            inputBuscar.value = "";
            filtroSeleccionado = "";
            cargarUsuarios();
        });

        filtroBotones.forEach(btn => {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                filtroSeleccionado = this.dataset.filter;
                cargarUsuarios();
            });
        });

    });
    </script>
@endsection
