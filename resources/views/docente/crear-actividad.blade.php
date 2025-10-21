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
            <form id="formularioDinamico" class="md:pl-15 md:pr-15" action="" method="post">
                @csrf
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-gray-300">
                    <div class="flex flex-col">
                        <span>Actividad</span>
                        <label for="" class="text-gray-500 font-light mb-2">Nombre de la actividad</label>
                        <input
                            class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                            type="text" required>
                        <label for="" class="text-gray-500 font-light mb-2">Descripción de la actividad</label>
                        <textarea
                            class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                            required style="height: 132px;">
                        </textarea>
                    </div>
                </div>
                <div class="flex justify-between mt-5">
                    <h3 class="text-2xl font-semibold">Preguntas</h3>
                    <button id="modalPreguntas" type="button" data-dialog-target="modal"
                        class="bg-teal-600 text-white px-4 py-2 rounded-lg shadow hover:bg-teal-700 transition">
                        Agregar pregunta
                    </button>
                </div>

                {{-- Espacio para los formularios --}}
                <div id="preguntasContainer" class="flex flex-col gap-8 mt-5">
                    <div id="opciónMultiple" class="bg-white rounded-2xl shadow-2xs border border-gray-300 hidden">
                        <div class="flex flex-col p-4">
                            <h3 class="text-[20px] text-balck font-semibold">Pregunta 1</h3>
                            <span class="text-gray-500 font-light mb-3">Opción Multiple</span>
                            <label for="" class="text-gray-500 font-light mb-2">Titulo de la pregunta:</label>
                            <input
                                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                type="text" required>
                            <label for="" class="text-gray-500 font-light mb-2 mt-2">Respuestas:</label>
                            <div class="flex flex-wrap justify-start gap-4">
                                <div class="flex justify-between gap-4 w-full">
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light" for="">Opcion A</label>
                                        <input
                                            class="p-2 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                            type="text" required>
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light" for="">Opcion B</label>
                                        <input
                                            class="p-2 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                            type="text" required>
                                    </div>
                                </div>
                                <div class="flex justify-between gap-4 w-full">
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light" for="">Opcion C</label>
                                        <input
                                            class="p-2 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                            type="text" required>
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light" for="">Opcion D</label>
                                        <input
                                            class="p-2 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                            type="text" required>
                                    </div>
                                </div>
                                <div class="flex justify-between gap-4 w-full">
                                    <div class="w-1/2">
                                        <label for="" class="text-gray-500 font-light">Opción correcta:</label>
                                        <select name="" id=""
                                            class="p-2.5 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition">
                                            <option class="text-gray-500 font-light" value="">Seleciona la Opcion
                                            </option>
                                            <option value="">A</option>
                                            <option value="">B</option>
                                            <option value="">C</option>
                                            <option value="">D</option>
                                        </select>
                                    </div>
                                    <div class="w-1/2">
                                        <label for="" class="text-gray-500 font-light">Valor:</label>
                                        <input
                                            class="p-2 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                            type="text" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="descriptiva" class="bg-white rounded-2xl shadow-2xs border border-gray-300 hidden">
                        <div class="flex flex-col p-4">
                            <h3 class="text-[20px] text-balck font-semibold">Pregunta 2</h3>
                            <span class="text-gray-500 font-light mb-3">Opción Multiple</span>
                            <label for="" class="text-gray-500 font-light mb-2">Titulo:</label>
                            <input
                                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                type="text" required>
                            <label for="" class="text-gray-500 font-light mb-2 mt-2">Descripción de la
                                actividad</label>
                            <textarea
                                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                style="height: 232px;">
                        </textarea>
                        </div>
                    </div>
                    <div id="VerdadFalso" class="bg-white rounded-2xl shadow-2xs border border-gray-300 hidden">
                        <div class="flex flex-col p-4">
                            <h3 class="text-[20px] text-balck font-semibold">Pregunta 3</h3>
                            <span class="text-gray-500 font-light mb-3">Verdad o Falso</span>
                            <label for="" class="text-gray-500 font-light mb-2 mt-2">Descripción de la
                                actividad</label>
                            <textarea
                                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                style="height: 150px;">
                            </textarea>
                            <div class="flex justify-between gap-4 w-full">
                                <div class="w-1/2">
                                    <label for="">Opción correcta:</label>
                                    <select name="" id=""
                                        class="p-2.5 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition">
                                        <option value="">Seleciona opción correcta</option>
                                        <option value="">Verdaderp</option>
                                        <option value="">Falso</option>
                                    </select>
                                </div>
                                <div class="w-1/2">
                                    <label for="">Valor:</label>
                                    <input
                                        class="p-2 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                        type="text" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Configuración --}}
                <div class="bg-white rounded-2xl shadow-2xs border border-gray-300 mt-5">
                    <div class="flex flex-col p-4">
                        <h3 class="text-[18px] text-balck font-semibold">Configuración de actividad</h3>
                        <div class="flex flex-wrap justify-start gap-4">
                            <div class="flex justify-between gap-4 w-full">
                                <div class="w-1/2">
                                    <label class="text-gray-500 font-light" for="">Limite de tiempo</label>
                                    <input
                                        class="p-2 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                        type="text" required>
                                </div>
                                <div class="w-1/2">
                                    <label class="text-gray-500 font-light" for="">Tiempo de entrega</label>
                                    <input
                                        class="p-2 w-full min-w-auto  border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                        type="text" required>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between gap-4 w-full">
                                <div class="w-full md:w-1/2">
                                    <label class="text-gray-500 font-light">Opción correcta:</label>
                                    <select
                                        class="p-2.5 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition">
                                        <option class="text-gray-500 font-light" value="">Selecciona la opción
                                        </option>
                                        <option value="">IDGS 10mo</option>
                                        <option value="">Turismo</option>
                                        <option value="">Mecatrónica</option>
                                    </select>
                                </div>
                                <div class="w-full md:w-1/2 flex items-end">
                                    <button type="submit"
                                        class="bg-teal-600 text-white px-4 py-2 w-full rounded-lg hover:bg-teal-700 transition shadow">
                                        Guardar Actividad
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {{-- Modal --}}
            <div data-dialog-backdrop="modal" data-dialog-backdrop-close="true"
                class="pointer-events-none fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black/40 backdrop-blur-none opacity-0 transition-opacity duration-300">
                <div class="flex justify-center items-center px-6 sm:px-0">
                    <div data-dialog="modal"
                        class="relative w-full max-w-md mx-4 sm:mx-auto p-4 rounded-lg bg-white shadow-sm">
                        <div class="flex justify-between">
                            <div class="flex shrink-0 items-center pb-4 text-xl font-medium text-slate-800">
                                Agregar Preguntas
                            </div>
                            <button data-dialog-close="true"
                                class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 pointer-coarse:">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="relative border-t border-slate-200 py-4 leading-normal text-slate-600 font-light">
                            <div class="flex flex-wrap  justify-center gap-4">
                                <button type="button" data-tipo-pregunta="opcion_multiple"
                                    class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700 transition-colors">
                                    Opción múltiple
                                </button>
                                <button type="button" data-tipo-pregunta="descriptiva"
                                    class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700 transition-colors">Descriptiva</button>
                                <button type="button" data-tipo-pregunta="verdadero_falso"
                                    class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700 transition-colors">Verdadero/Flaso</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const openBtn = document.querySelector('[data-dialog-target="modal"]');
            const backdrop = document.querySelector('[data-dialog-backdrop="modal"]');
            const closeButtons = document.querySelectorAll('[data-dialog-close="true"]');
            const contenedor = document.getElementById("preguntasContainer");

            const plantillaMultiple = document.getElementById("opciónMultiple");
            const plantillaDescriptiva = document.getElementById("descriptiva");
            const plantillaVerdadero = document.getElementById("VerdadFalso");

            let contadorPreguntas = 0;

            // Abrir modal
            openBtn.addEventListener("click", () => {
                backdrop.classList.remove("opacity-0", "pointer-events-none");
                backdrop.classList.add("opacity-100", "pointer-events-auto");
            });

            // Cerrar modal
            closeButtons.forEach(btn => btn.addEventListener("click", () => {
                backdrop.classList.add("opacity-0", "pointer-events-none");
                backdrop.classList.remove("opacity-100", "pointer-events-auto");
            }));

            if (backdrop.hasAttribute("data-dialog-backdrop-close")) {
                backdrop.addEventListener("click", (e) => {
                    if (e.target === backdrop) {
                        backdrop.classList.add("opacity-0", "pointer-events-none");
                        backdrop.classList.remove("opacity-100", "pointer-events-auto");
                    }
                });
            }

            // Formulario dinamico
            const botonesModal = backdrop.querySelectorAll('button[data-tipo-pregunta]');

            botonesModal.forEach(btn => {
                btn.addEventListener("click", () => {
                    const tipo = btn.getAttribute("data-tipo-pregunta");
                    let nuevaPregunta;

                    if (tipo === "opcion_multiple") {
                        nuevaPregunta = plantillaMultiple.cloneNode(true);
                    } else if (tipo === "descriptiva") {
                        nuevaPregunta = plantillaDescriptiva.cloneNode(true);
                    } else if (tipo === "verdadero_falso") {
                        nuevaPregunta = plantillaVerdadero.cloneNode(true);
                    }

                    if (nuevaPregunta) {
                        contadorPreguntas++;
                        nuevaPregunta.classList.remove("hidden");
                        nuevaPregunta.id = "";
                        const titulo = nuevaPregunta.querySelector("h3");
                        const subtitulo = nuevaPregunta.querySelector("span");
                        if (titulo) titulo.textContent = `Pregunta ${contadorPreguntas}`;
                        if (subtitulo) subtitulo.textContent = btn.textContent.trim();

                        contenedor.appendChild(nuevaPregunta);
                    }

                    // cerrar modal
                    backdrop.classList.add("opacity-0", "pointer-events-none");
                    backdrop.classList.remove("opacity-100", "pointer-events-auto");
                });
            });

        });
    </script>


@endsection
