@extends('components.menu')

@section('title', 'Lista de actividades | Aula de idiomas')

@section('content')
    <div class="p-2 md:ml-20 md:mr-20">
        <x-msj-alert />
        <section class="flex flex-col md:flex-row justify-between items-start md:items-center">
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
                <button form="form-insertar" type="submit"
                    class="bg-teal-600 text-white px-4 py-2 w-full rounded-lg hover:bg-teal-700 transition shadow">
                    Guardar Actividad
                </button>
            </div>
        </section>

        <section class="mt-8">
            <form id="form-insertar" data-url="{{ route('actividad.guardar') }}" class="md:pl-15 md:pr-15" method="POST">
                @csrf
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-gray-300">
                    <div class="flex flex-col">
                        <div class="flex justify-between gap-3 w-full">
                            <div class="w-2/3">
                                <label class="text-gray-500 font-light mb-2">Nombre de la actividad</label>
                                <input name="nom_actividad"
                                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                    type="text" placeholder="Ej: Past simple vs Past continuous" required>
                            </div>
                            <div class="w-1/3">
                                <label class="text-gray-500 font-light mb-2">Tipo de actividad:</label>
                                <select name="tipo_actividad"
                                    class="p-2.5 w-full text-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition">
                                    <option value="">Selecciona la opción</option>
                                    <option value="preguntas">Preguntas</option>
                                    <option value="pdf">Cargar PDF</option>
                                    <option value="auditiva">Auditiva y oral</option>
                                </select>
                            </div>
                        </div>

                        <label class="text-gray-500 font-light mb-2">Descripción de la actividad</label>
                        <textarea name="descripcion"
                            class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                            required style="height: 132px;"></textarea>
                    </div>
                </div>

                <div class="flex justify-between mt-5">
                    <h3 class="text-2xl font-semibold">Preguntas Agregadas</h3>
                    <button id="modalPreguntas" type="button" data-dialog-target="modal"
                        class="bg-teal-600 text-white px-4 py-2 rounded-lg shadow hover:bg-teal-700 transition">
                        Agregar pregunta
                    </button>
                </div>

                {{-- CARGAR PDF --}}
                <div id="cargarPDF">
                    <div class="bg-white rounded-2xl shadow-2xs border border-gray-300 pregunta-item">
                        <input type="" name="preguntas[][tipo]" value="cargarPDF">
                        <div class="flex flex-col p-4 relative">
                            <div class="flex justify-between gap-4 mb-3">
                                <div>
                                    <h3 class="text-[20px] text-black font-semibold">Cargar actividad</h3>
                                    <span class="text-gray-500 font-light">Cargar PDF</span>
                                </div>
                                <button
                                    class="btn-eliminar-pregunta text-gray-400 hover:text-red-500 transition text-[30px] font-bold"
                                    type="button">×</button>
                            </div>
                            <label class="text-gray-500 font-light mb-2">Nombre de la tarea:</label>
                            <input class="p-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-teal-500"
                                type="text" required
                            >
                            <label class="text-gray-500 font-light mt-3 mb-2">Cargar actividad:</label>
                        </div>
                    </div>
                    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
                </div>


                <div id="preguntasContainer" class="flex flex-col gap-8 mt-5"></div>
            </form>
            {{-- FORMULARIO OPCION MULTIPLE --}}
            <template id="opcionMultiple">
                <div class="bg-white rounded-2xl shadow-2xs border border-gray-300 pregunta-item">
                    <input type="hidden" name="preguntas[][tipo]" value="opcion_multiple">
                    <div class="flex flex-col p-4 relative">
                        <div class="flex justify-between gap-4 mb-3">
                            <div>
                                <h3 class="text-[20px] text-black font-semibold">Pregunta</h3>
                                <span class="text-gray-500 font-light">Opción Múltiple</span>
                            </div>
                            <button
                                class="btn-eliminar-pregunta text-gray-400 hover:text-red-500 transition text-[30px] font-bold"
                                type="button">×</button>
                        </div>
                        <label class="text-gray-500 font-light mb-2">Título:</label>
                        <input name="preguntas[][titulo]" type="text"
                            class="p-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-teal-500" required>

                        <div class="flex flex-wrap gap-4 mt-3">
                            @foreach (['A', 'B', 'C', 'D'] as $op)
                                <div class="w-1/2">
                                    <label class="text-gray-500 font-light">Opción {{ $op }}</label>
                                    <input name="preguntas[][opcion_{{ strtolower($op) }}]"
                                        class="p-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"
                                        type="text" required>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-between gap-4 w-full mt-3">
                            <div class="w-1/2">
                                <label>Opción correcta:</label>
                                <select name="preguntas[][respuesta_correcta]"
                                    class="p-2.5 w-full text-gray-500 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="">Selecciona la opción</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                            <div class="w-1/2">
                                <label>Valor:</label>
                                <input name="preguntas[][valor]" type="number"
                                    class="p-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            {{-- PREGUNTA ABIERTA --}}
            <template id="abierta">
                <div class="bg-white rounded-2xl shadow-2xs border border-gray-300 pregunta-item">
                    <input type="hidden" name="preguntas[][tipo]" value="abierta">
                    <div class="flex flex-col p-4 relative">
                        <div class="flex justify-between gap-4 mb-3">
                            <div>
                                <h3 class="text-[20px] text-black font-semibold">Pregunta</h3>
                                <span class="text-gray-500 font-light">Abierta</span>
                            </div>
                            <button
                                class="btn-eliminar-pregunta text-gray-400 hover:text-red-500 transition text-[30px] font-bold"
                                type="button">×</button>
                        </div>
                        <label class="text-gray-500 font-light mb-2">Título:</label>
                        <input name="preguntas[][titulo]" type="text"
                            class="p-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-teal-500" required>
                        <label class="text-gray-500 font-light mb-2 mt-2">Descripción:</label>
                        <textarea name="preguntas[][descripcion]"
                            class="p-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-teal-500" style="height: 132px;"></textarea>
                    </div>
                </div>
            </template>

            {{-- FORMULARIO VERDAD O FALSO --}}
            <template id="verdaderoFalso">
                <div class="bg-white rounded-2xl shadow-2xs border border-gray-300 pregunta-item">
                    <input type="hidden" name="preguntas[][tipo]" value="verdadero_falso">
                    <div class="flex flex-col p-4 relative">
                        <div class="flex justify-between gap-4 mb-3">
                            <div>
                                <h3 class="text-[20px] text-black font-semibold">Pregunta</h3>
                                <span class="text-gray-500 font-light">Verdadero o Falso</span>
                            </div>
                            <button
                                class="btn-eliminar-pregunta text-gray-400 hover:text-red-500 transition text-[30px] font-bold"
                                type="button">×</button>
                        </div>
                        <label class="text-gray-500 font-light mb-2">Descripción:</label>
                        <textarea name="preguntas[][descripcion]"
                            class="p-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-teal-500" style="height: 150px;"></textarea>
                        <div class="flex justify-between gap-4 w-full mt-3">
                            <div class="w-1/2">
                                <label>Opción correcta:</label>
                                <select name="preguntas[][respuesta_correcta]"
                                    class="p-2.5 w-full text-gray-500 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="">Selecciona opción correcta</option>
                                    <option value="Verdadero">Verdadero</option>
                                    <option value="Falso">Falso</option>
                                </select>
                            </div>
                            <div class="w-1/2">
                                <label>Valor:</label>
                                <input name="preguntas[][valor]" type="number"
                                    class="p-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- MODAL AGREGAR PREGUNTAS --}}
            <div data-dialog-backdrop="modal"
                class="pointer-events-none fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black/40 opacity-0 transition-opacity duration-300">
                <div class="flex justify-center items-center px-6 sm:px-0">
                    <div data-dialog="modal"
                        class="relative w-full max-w-md mx-4 sm:mx-auto p-4 rounded-lg bg-white shadow-sm">
                        <div class="flex justify-between">
                            <div class="pb-4 text-xl font-medium text-slate-800">Agregar Preguntas</div>
                            <button data-dialog-close="true"
                                class="absolute top-4 right-4 text-slate-500 hover:text-slate-800">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="border-t border-slate-200 py-4 text-slate-600 font-light">
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" data-tipo="opcionMultiple"
                                    class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700">Opción
                                    múltiple</button>
                                <button type="button" data-tipo="abierta"
                                    class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700">Abierta</button>
                                <button type="button" data-tipo="verdaderoFalso"
                                    class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700">Verdadero/Falso</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const openBtn = document.querySelector('#modalPreguntas');
            const backdrop = document.querySelector('[data-dialog-backdrop="modal"]');
            const closeBtns = backdrop.querySelectorAll('[data-dialog-close="true"]');
            const container = document.querySelector('#preguntasContainer');

            const templates = {
                opcionMultiple: document.querySelector('#opcionMultiple').content,
                abierta: document.querySelector('#abierta').content,
                verdaderoFalso: document.querySelector('#verdaderoFalso').content
            };

            let preguntaIndex = 0;

            function reenumerar() {
                container.querySelectorAll('.pregunta-item').forEach((p, i) => {
                    const h3 = p.querySelector('h3');
                    if (h3) h3.textContent = `Pregunta ${i + 1}`;
                });
            }

            function cerrarModal() {
                backdrop.classList.add('opacity-0', 'pointer-events-none');
            }

            openBtn.addEventListener('click', () => {
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
            });

            closeBtns.forEach(btn => btn.addEventListener('click', cerrarModal));
            backdrop.addEventListener('click', e => {
                if (e.target === backdrop) cerrarModal();
            });

            backdrop.querySelectorAll('button[data-tipo]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tipo = btn.dataset.tipo;
                    const clone = templates[tipo].cloneNode(true);

                    clone.querySelectorAll('input, textarea, select').forEach(input => {
                        if (input.name.includes('preguntas[]')) {
                            input.name = input.name.replace('preguntas[]',
                                `preguntas[${preguntaIndex}]`);
                        }
                    });

                    container.appendChild(clone);
                    preguntaIndex++;
                    reenumerar();
                    cerrarModal();
                });
            });

            container.addEventListener('click', e => {
                if (e.target.classList.contains('btn-eliminar-pregunta')) {
                    e.target.closest('.pregunta-item').remove();
                    reenumerar();
                }
            });
        });
    </script>
@endsection
