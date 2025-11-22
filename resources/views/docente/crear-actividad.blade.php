@extends('components.menu')

@section('title', 'Registrar Actividad | Aula de Idiomas')

@section('content')
    <div class="bg-white rounded-2xl shadow-lg p-8 md:p-10 mx-auto mt-10 max-w-4xl">
        <x-msj-alert />
        <div class="flex flex-row gap-2 w-full mb-8">
            <a href="{{ route('docente.lista-actividades') }}" class="flex flex-row items-center gap-2">
                <svg class="w-10 h-10 sm:w-8 sm:h-8 text-teal-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
                <span class="text-teal-600 font-light hidden sm:block">Regresar</span>
            </a>
            <div class="flex justify-center items-center w-full">
                <h1 class="text-3xl font-bold text-teal-700">Registrar nueva actividad</h1>
            </div>
        </div>

        <form id="form-actividad" method="POST" data-url="{{ route('actividad.guardar') }}" action=""
            enctype="multipart/form-data" x-data="{ tipo: 'preguntas' }">
            @csrf

            <div class="mb-6">
                <label for="nom_actividad" class="block text-gray-700 font-semibold mb-2">Nombre de la actividad</label>
                <input type="text" name="nom_actividad" id="nom_actividad" required
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 shadow-sm placeholder-gray-400">
            </div>

            <div class="mb-6">
                <label for="descripcion" class="block text-gray-700 font-semibold mb-2">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" required
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 shadow-sm placeholder-gray-400"></textarea>
            </div>

            <div class="mb-6">
                <label for="tipo" class="block text-gray-700 font-semibold mb-2">Tipo de actividad</label>
                <select name="tipo" id="tipo" x-model="tipo"
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 shadow-sm cursor-pointer">
                    <option value="preguntas">Preguntas</option>
                    <option value="pdf">PDF</option>
                    <option value="auditiva">Auditiva</option>
                </select>
            </div>

            <div x-show="tipo === 'preguntas'" x-transition class="space-y-5 mt-8">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-teal-700">Preguntas</h2>
                    <button type="button" id="btn-agregar-pregunta"
                        class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition shadow">
                        + Agregar pregunta
                    </button>
                </div>

                <div id="contenedor-preguntas" class="space-y-6"></div>
            </div>

            <div x-show="tipo === 'pdf'" x-transition class="mt-8">
                <h2 class="text-xl font-semibold text-teal-700 mb-2">Cargar documento PDF</h2>
                <div
                    class="border-2 border-dashed border-teal-300 rounded-xl p-5 text-center bg-gray-50 hover:bg-gray-100 transition">
                    <input type="file" name="archivo_docente" accept="application/pdf"
                        class="w-full text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                              file:text-sm file:font-semibold file:bg-teal-100 file:text-teal-700 hover:file:bg-teal-200 cursor-pointer">
                    <p class="text-sm text-gray-500 mt-2">Arrastra o selecciona un archivo PDF.</p>
                </div>
            </div>

            <div x-show="tipo === 'auditiva'" x-transition class="mt-8 space-y-5">
                <h2 class="text-xl font-semibold text-teal-700">Actividad auditiva y oral</h2>

                <div>
                    <label for="texto_frase" class="block text-gray-700 font-semibold mb-2">Frase o texto</label>
                    <textarea name="texto_frase" id="texto_frase" rows="3"
                        class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 shadow-sm"></textarea>
                </div>

                <div>
                    <label for="archivo_audio_docente" class="block text-gray-700 font-semibold mb-2">Archivo de audio (MP3
                        o WAV)</label>
                    <input type="file" name="archivo_audio_docente" accept="audio/*"
                        class="w-full text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                              file:text-sm file:font-semibold file:bg-teal-100 file:text-teal-700 hover:file:bg-teal-200 cursor-pointer">
                </div>
            </div>

            <div class="mt-10 flex justify-end">
                <button type="submit"
                    class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-8 py-3 rounded-xl shadow-md transition">
                    Guardar actividad
                </button>
            </div>
        </form>
    </div>
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const contenedorPreguntas = document.getElementById('contenedor-preguntas');
            const btnAgregar = document.getElementById('btn-agregar-pregunta');
            let contadorPreguntas = 0;

            btnAgregar.addEventListener('click', () => {
                contadorPreguntas++;
                const preguntaHTML = `
        <div class="bg-gray-50 border border-gray-200 p-5 rounded-xl shadow-sm pregunta" data-index="${contadorPreguntas}">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-semibold text-teal-700">Pregunta ${contadorPreguntas}</h3>
                <button type="button" class="text-red-600 hover:text-red-800 eliminar-pregunta text-sm font-medium">Eliminar</button>
            </div>

            <label class="block text-gray-700 font-medium mb-1">Texto de la pregunta</label>
            <input type="text" name="preguntas[${contadorPreguntas}][pregunta]" required
                   class="w-full bg-white border border-gray-300 rounded-lg p-2.5 mb-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 shadow-sm">

            <label class="block text-gray-700 font-medium mb-1">Descripción / Instrucción</label>
            <textarea name="preguntas[${contadorPreguntas}][descripcion]" rows="2"
                      class="w-full bg-white border border-gray-300 rounded-lg p-2.5 mb-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 shadow-sm"></textarea>

            <label class="block text-gray-700 font-medium mb-1">Tipo de pregunta</label>
            <select name="preguntas[${contadorPreguntas}][tipo]" class="tipo-pregunta bg-white border border-gray-300 rounded-lg p-2.5 mb-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 shadow-sm">
                <option value="opcion_multiple">Opción múltiple</option>
                <option value="abierta">Abierta</option>
            </select>

            <!-- Contenedor de opciones -->
            <div class="opciones space-y-2">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold text-teal-600">Opciones</h4>
                    <button type="button" class="agregar-opcion bg-teal-500 text-white px-3 py-1 rounded-md hover:bg-teal-600 text-sm">+ Opción</button>
                </div>
                <div class="lista-opciones mt-2"></div>
            </div>
        </div>`;
                contenedorPreguntas.insertAdjacentHTML('beforeend', preguntaHTML);
            });

            contenedorPreguntas.addEventListener('click', (e) => {
                if (e.target.classList.contains('eliminar-pregunta')) {
                    e.target.closest('.pregunta').remove();
                }

                if (e.target.classList.contains('agregar-opcion')) {
                    const pregunta = e.target.closest('.pregunta');
                    const listaOpciones = pregunta.querySelector('.lista-opciones');
                    const index = pregunta.dataset.index;
                    const totalOpciones = listaOpciones.children.length + 1;

                    const opcionHTML = `
            <div class="flex items-center gap-2 bg-white border border-gray-200 p-2 rounded-lg shadow-sm">
                <input type="text" name="preguntas[${index}][opciones][${totalOpciones}][texto_opcion]"
                       placeholder="Texto de opción"
                       class="flex-1 bg-gray-50 border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-teal-400 focus:border-teal-500">
                <label class="flex items-center gap-1 text-sm text-gray-700">
                    <input type="checkbox" name="preguntas[${index}][opciones][${totalOpciones}][es_correcta]" value="1">
                    Correcta
                </label>
                <button type="button" class="eliminar-opcion text-red-600 hover:text-red-800 text-xs font-bold">✕</button>
            </div>`;
                    listaOpciones.insertAdjacentHTML('beforeend', opcionHTML);
                }

                if (e.target.classList.contains('eliminar-opcion')) {
                    e.target.closest('div').remove();
                }
            });

            contenedorPreguntas.addEventListener('change', (e) => {
                if (e.target.classList.contains('tipo-pregunta')) {
                    const opciones = e.target.closest('.pregunta').querySelector('.opciones');
                    opciones.style.display = e.target.value === 'opcion_multiple' ? 'block' : 'none';
                }
            });
        });
    </script>
@endsection
