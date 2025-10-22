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
            <div class="flex justify-between gap-2">
                <a href="{{ route('docente.lista-actividades') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition">
                    Volver
                </a>
                <button form="formularioDinamico" type="submit"
                    class="bg-teal-600 text-white px-4 py-2 w-full rounded-lg hover:bg-teal-700 transition shadow">
                    Guardar Actividad
                </button>
            </div>
        </section>
        <section class="mt-8">
            <form id="formularioDinamico" class="md:pl-15 md:pr-15" action="" method="post">
                @csrf
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-gray-300">
                    <div class="flex flex-col">
                        <div class="flex justify-between gap-3 w-full">
                            <div class="w-2/3">
                                <label for="" class="text-gray-500 font-light mb-2">Nombre de la actividad</label>
                                <input
                                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                                    type="text" placeholder="Ej: Past simple vs Past continuous"  required>
                            </div>
                            <div class="w-1/3">
                                <label for="" class="text-gray-500 font-light mb-2">TIpo de actividad:</label>
                                <select name="" id=""
                                    class="p-2.5 w-full text-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition">
                                    <option class="text-gray-500 font-light" value="">Seleciona la Opcion
                                    </option>
                                    <option value="">Opcional</option>
                                    <option value="">Archivo</option>
                                    <option value="">Auditiva</option>
                                    <option value="">No se</option>
                                </select>
                            </div>
                        </div>
                        <label for="" class="text-gray-500 font-light mb-2">Descripción de la actividad</label>
                        <textarea
                            class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                            required style="height: 132px;">
                        </textarea>
                    </div>
                </div>
                <div class="flex justify-between mt-5">
                    <h3 class="text-2xl font-semibold">Preguntas Agregadas</h3>
                    <button id="modalPreguntas" type="button" data-dialog-target="modal"
                        class="bg-teal-600 text-white px-4 py-2 rounded-lg shadow hover:bg-teal-700 transition">
                        Agregar pregunta
                    </button>
                </div>
                {{-- Contenedor de preuntas --}}
                <div id="preguntasContainer" class="flex flex-col gap-8 mt-5">

                    {{-- Opción multiple --}}
                    <div id="opciónMultiple" class="bg-white rounded-2xl shadow-2xs border border-gray-300 hidden">
                        <div class="flex flex-col p-4 relative">
                            <div class="flex justify-between gap-4 mb-3">
                                <div>
                                    <h3 class="text-[20px] text-black font-semibold">Pregunta 1</h3>
                                    <span class="text-gray-500 font-light mb-3">Opción Múltiple</span>
                                </div>
                                <div class="flex justify-end items-start">
                                    <button
                                        class="btn-eliminar-pregunta text-gray-400 hover:text-red-500 transition text-[30px] font-bold"
                                        type="button">
                                        ×
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col mb-4">
                                <label class="text-gray-500 font-light mb-2">Título de la pregunta:</label>
                                <input
                                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                    type="text" required placeholder="Ej: " >
                            </div>

                            <!-- RESPUESTAS -->
                            <div class="flex flex-col gap-4">
                                <label class="text-gray-500 font-light mb-1">Respuestas:</label>

                                <div class="flex justify-between gap-4 w-full">
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light">Opción A</label>
                                        <input
                                            class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                            type="text" required>
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light">Opción B</label>
                                        <input
                                            class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                            type="text" required>
                                    </div>
                                </div>

                                <div class="flex justify-between gap-4 w-full">
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light">Opción C</label>
                                        <input
                                            class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                            type="text" required>
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light">Opción D</label>
                                        <input
                                            class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                            type="text" required>
                                    </div>
                                </div>

                                <div class="flex justify-between gap-4 w-full">
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light">Opción correcta:</label>
                                        <select
                                            class="p-2.5 w-full text-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition">
                                            <option value="">Selecciona la opción</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-gray-500 font-light">Valor:</label>
                                        <input
                                            class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                            type="text" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Descriptiva --}}
                    <div id="descriptiva" class="bg-white rounded-2xl shadow-2xs border border-gray-300 hidden">
                        <div class="flex flex-col p-4 relative">
                            <div class="flex justify-between gap-4 mb-3">
                                <div>
                                    <h3 class="text-[20px] text-black font-semibold">Pregunta 2</h3>
                                    <span class="text-gray-500 font-light mb-3">Descriptiva</span>
                                </div>
                                <div class="flex justify-end items-start">
                                    <button
                                        class="btn-eliminar-pregunta text-gray-400 hover:text-red-500 transition text-[30px] font-bold"
                                        type="button">×</button>
                                </div>
                            </div>
                            <label class="text-gray-500 font-light mb-2">Título:</label>
                            <input
                                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                type="text" required>

                            <label class="text-gray-500 font-light mb-2 mt-2">Descripción de la actividad</label>
                            <textarea
                                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                style="height: 232px;"></textarea>
                        </div>
                    </div>

                    {{-- Verdadero o falso --}}
                    <div id="VerdadFalso" class="bg-white rounded-2xl shadow-2xs border border-gray-300 hidden">
                        <div class="flex flex-col p-4 relative">
                            <div class="flex justify-between gap-4 mb-3">
                                <div>
                                    <h3 class="text-[20px] text-black font-semibold">Pregunta</h3>
                                    <span class="text-gray-500 font-light mb-3">Verdadero o Falso</span>
                                </div>
                                <div class="flex justify-end items-start">
                                    <button
                                        class="btn-eliminar-pregunta text-gray-400 hover:text-red-500 transition text-[30px] font-bold"
                                        type="button">×</button>
                                </div>
                            </div>
                            <label class="text-gray-500 font-light mb-2 mt-2">Descripción de la actividad</label>
                            <textarea
                                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                style="height: 150px;"></textarea>

                            <div class="flex justify-between gap-4 w-full">
                                <div class="w-1/2">
                                    <label>Opción correcta:</label>
                                    <select
                                        class="p-2.5 w-full text-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition">
                                        <option value="">Selecciona opción correcta</option>
                                        <option value="Verdadero">Verdadero</option>
                                        <option value="Falso">Falso</option>
                                    </select>
                                </div>
                                <div class="w-1/2">
                                    <label>Valor:</label>
                                    <input
                                        class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                        type="text" required>
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

            // Función para reenumerar preguntas
            function reenumerarPreguntas() {
                const preguntas = contenedor.querySelectorAll(".pregunta-item");
                preguntas.forEach((pregunta, index) => {
                    const titulo = pregunta.querySelector("h3");
                    if (titulo) titulo.textContent = `Pregunta ${index + 1}`;
                });
            }

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

            // Formulario dinámico
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
                        nuevaPregunta.classList.remove("hidden");
                        nuevaPregunta.classList.add("pregunta-item");
                        nuevaPregunta.id = "";

                        contenedor.appendChild(nuevaPregunta);
                        reenumerarPreguntas();

                        // Agregar funcionalidad para eliminar la pregunta
                        const botonEliminar = nuevaPregunta.querySelector(".btn-eliminar-pregunta");
                        if (botonEliminar) {
                            botonEliminar.addEventListener("click", () => {
                                nuevaPregunta.remove();
                                reenumerarPreguntas();
                            });
                        }
                    }

                    // cerrar modal
                    backdrop.classList.add("opacity-0", "pointer-events-none");
                    backdrop.classList.remove("opacity-100", "pointer-events-auto");
                });
            });

        });
    </script>


@endsection
