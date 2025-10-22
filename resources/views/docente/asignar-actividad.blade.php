@extends('components.menu')

@section('title', 'Asignar Actividad | Aula de idiomas')

@section('content')

    <div class="p-2 mt-10 md:ml-20 md:mr-20">
        <section class="flex flex-col md:flex-row justify-between  items-start md:items-center">
            <div>
                <h1 class="text-2xl md:text-4xl font-bold text-black">Asignación de Actividad</h1>

            </div>
            <div>
                <a href="{{ route('docente.lista-actividades') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition cursor-pointer">
                    Volver
                </a>
            </div>
        </section>
        {{-- Configuración --}}
        <form action="" method="post">
            <section class="md:pl-15 md:pr-15">
                <div class="bg-white rounded-2xl shadow-2xs border border-gray-300 mt-5">
                    <div class="flex flex-col p-4">
                        <h3 class="text-[18px] text-gray-600 font-semibold mb-3">Configuración de actividad</h3>
                        <div class="flex flex-wrap justify-start gap-4">
                            <div class="flex justify-between gap-4 w-full">
                                <div class="w-1/2">
                                    <label class="text-gray-500 text-[17px] font-light">Límite de tiempo</label>
                                    <input
                                        class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                        type="text" placeholder="Ej: 60 (tiempo en minutos)" required  maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                                </div>
                                <div class="w-1/2">
                                    <label class="text-gray-500 text-[17px]  font-light">Fecha de entrega</label>
                                    <input
                                        class="p-2 w-full border text-gray-500 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                        type="date" required>
                                </div>
                            </div>
                            <div class="flex justify-between w-full">
                                <div class=" flex justify-center items-center">
                                    <h1 class="text-gray-600 text-[18px] font-semibold">Asignar actividad a:</h1>
                                </div>
                                <div class="flex justify-end">
                                    <button id="modalAsignación" type="button" data-dialog-target="modal"
                                        class="bg-teal-400 text-white px-3 py-2 rounded-lg hover:bg-teal-600 transition shadow cursor-pointer"
                                        type="button">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="2" fill="none" />
                                            <line x1="12" y1="8" x2="12" y2="16"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                            <line x1="8" y1="12" x2="16" y2="12"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            {{-- Contenedor asignados --}}
                            <div id="asignarContainer" class="flex flex-col w-full">
                                {{-- Grupos --}}
                                <h3 class="text-gray-600 text-[18px]">Grupos</h3>
                                <div id="listaGrupos"
                                    class="flex flex-wrap justify-center mb-5 sm:justify-start w-full gap-3"></div>
                                {{-- Alumnos --}}
                                <h3 class="text-gray-600 text-[18px]">Alumnos</h3>
                                <div id="listaAlumnos" class="flex flex-col sm:flex-row sm:flex-wrap w-full gap-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 flex justify-end">
                        <button class="bg-teal-600 text-white px-4 py-2 rounded-lg shadow hover:bg-teal-700 transition">
                            Guardar Actividad
                        </button>
                    </div>
                </div>
            </section>
        </form>
    </div>
    {{-- Modal asignación de actividades --}}
    <div data-dialog-backdrop="modal" data-dialog-backdrop-close="true"
        class="pointer-events-none fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black/40 backdrop-blur-none opacity-0 transition-opacity duration-300">
        <div class="flex justify-center items-center w-full px-6 sm:px-0">
            <div data-dialog="modal"
                class="relative w-full max-w-3xl h-[80vh] mx-4 sm:mx-auto p-6 rounded-2xl bg-white shadow-lg transition-all duration-300 flex flex-col">
                {{-- Titulo del modal --}}
                <div class="flex justify-between items-center border-b border-slate-200 pb-4">
                    <div class="flex items-center text-[18px] md:text-2xl font-semibold text-slate-800">
                        Panel asignar actividad
                    </div>
                    <button data-dialog-close="true"
                        class="text-gray-400 hover:text-red-500 transition text-[30px] font-semibold cursor-pointer">
                        ×
                    </button>
                </div>
                {{-- Barra superior de filtrado --}}
                <div class="flex flex-col sm:flex-row sm:justify-between gap-3 mt-4 mb-2">
                    {{-- Input de búsqueda --}}
                    <div class="w-full sm:w-4/5">
                        <input
                            class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                            type="text" placeholder="Buscar..">
                    </div>
                    {{-- Botones de flitrado --}}
                    <div class="flex justify-between gap-2 sm:justify-start sm:gap-3 w-full sm:w-auto">
                        <button id="filtrarGrupos"
                            class="border border-gray-300 text-gray-600 px-3 py-2 rounded-lg hover:bg-teal-600 hover:text-white transition w-1/2 sm:w-auto cursor-pointer">
                            Grupos
                        </button>
                        <button id="filtrarAlumnos"
                            class="border border-gray-300 text-gray-600 px-3 py-2 rounded-lg hover:bg-teal-600 hover:text-white transition shadow w-1/2 sm:w-auto cursor-pointer">
                            Alumnos
                        </button>
                    </div>
                </div>
                {{-- Contenido con scroll --}}
                <div class="flex-1 overflow-y-auto border-t border-slate-200 mt-2 p-2 space-y-2">
                    {{-- contenido largo grupo --}}
                    <div class="flex flex-col gap-4">
                        @for ($i = 0; $i < 10; $i++)
                            <a href="" data-tipo="grupo"
                                class="text-slate-700 border border-gray-300 px-2 py-3 rounded-lg hover:bg-teal-500 hover:text-white transition ">
                                {{ $i + 1 }} A ITIID 2025
                            </a>
                        @endfor
                    </div>
                    {{-- Contenido largo alumno --}}
                    <div>
                        <div class="flex flex-col gap-4">
                            @for ($i = 0; $i < 10; $i++)
                                <a href="" data-tipo="alumno"
                                    class="text-slate-700 border border-gray-300 px-2 py-3 rounded-lg hover:bg-teal-500 hover:text-white transition ">
                                    Angel Ariel Salazar Medina {{ $i + 1 }}
                                </a>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openModalBtn = document.getElementById('modalAsignación');
            const modalBackdrop = document.querySelector('[data-dialog-backdrop="modal"]');
            const closeModalBtn = modalBackdrop.querySelector('[data-dialog-close="true"]');
            const listaGrupos = document.getElementById('listaGrupos');
            const listaAlumnos = document.getElementById('listaAlumnos');
            // Botones filtrado
            const btnGrupos = document.getElementById('filtrarGrupos');
            const btnAlumnos = document.getElementById('filtrarAlumnos');

            const todosItems = modalBackdrop.querySelectorAll('a[data-tipo]');

            // Abrir modal
            openModalBtn.addEventListener('click', () => {
                modalBackdrop.classList.remove('pointer-events-none', 'opacity-0');
                modalBackdrop.classList.add('pointer-events-auto', 'opacity-100');
            });

            // Cerrar modal
            const cerrarModal = () => {
                modalBackdrop.classList.add('pointer-events-none', 'opacity-0');
                modalBackdrop.classList.remove('pointer-events-auto', 'opacity-100');
            };
            closeModalBtn.addEventListener('click', cerrarModal);
            modalBackdrop.addEventListener('click', e => {
                if (e.target === modalBackdrop) cerrarModal();
            });

            // Filtrar por tipo
            const filtrarItems = (tipo) => {
                todosItems.forEach(item => {
                    if (tipo === 'todos' || item.dataset.tipo === tipo) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            };
            // Resaltar botón activo
            const botones = [btnGrupos, btnAlumnos];
            const activarBoton = (activo) => {
                botones.forEach(b => b.classList.remove('bg-teal-600', 'text-white'));
                activo.classList.add('bg-teal-600', 'text-white');
            };

            btnGrupos.addEventListener('click', () => {
                filtrarItems('grupo');
                activarBoton(btnGrupos);
            });
            btnAlumnos.addEventListener('click', () => {
                filtrarItems('alumno');
                activarBoton(btnAlumnos);
            });

            // Mostrar todos al abrir modal
            filtrarItems('todos');

            // Función para agregar asignados
            const agregarAsignado = (tipo, nombre) => {
                const contenedor = tipo === 'grupo' ? listaGrupos : listaAlumnos;

                // Diseño de elemento
                const item = document.createElement('div');
                item.className = 'flex justify-between gap-6 border border-gray-300 px-2 py-3 rounded-lg';
                item.innerHTML = `
                    <div class="flex justify-center items-center">
                        <span class="text-slate-700 text-[15px]">${nombre}</span>
                    </div>
                    <div>
                        <button type="button" class="text-gray-400 hover:text-red-500 transition text-[20px] font-semibold cursor-pointer">×</button>
                    </div>
                `;

                // Eliminar elemento
                item.querySelector('button').addEventListener('click', () => item.remove());

                contenedor.appendChild(item);
            };

            // Capturar clics dentro del modal
            modalBackdrop.addEventListener('click', e => {
                const target = e.target.closest('a');
                if (!target) return;
                e.preventDefault();

                const nombre = target.textContent.trim();
                const tipo = target.dataset.tipo; // 'grupo' o 'alumno'
                agregarAsignado(tipo, nombre);
            });
        });
    </script>


@endsection
