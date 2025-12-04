@extends('components.menu')

@section('title', 'Asignar Actividad | Aula de idiomas')

@section('content')
    <div class="flex justify-center items-center mt-10 px-1 md:px-0">
        <div class="w-full max-w-3xl">
            <x-msj-alert />
            <section class="bg-white rounded-2xl shadow-2xs border border-gray-300 p-6 space-y-6">
                <div class="flex flex-row items-center w-full gap-2 mb-3 sm:mb-6">
                    <a href="{{ route('docente.lista-actividades') }}" class="flex flex-row items-center">
                        <svg class="w-8 h-8 sm:w-8 sm:h-8 text-teal-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                        <span class="text-teal-600 font-light hidden sm:block">Regresar</span>
                    </a>
                    <div class="flex justify-center items-center w-full">
                        <h1 class="text-2xl md:text-4xl font-bold text-teal-700 md:mb-0">Asignación de Actividad</h1>
                    </div>
                </div>

                <form id="form-insertar" data-url="{{ route('docente.asignando-actividad', $actividad->pk_actividad) }}"
                    action="" method="POST">
                    @csrf
                    <div class="flex flex-col gap-3">

                        <div class="flex flex-col w-full gap-2">
                            <label class="text-gray-500 text-[17px] font-light">Fecha de entrega:</label>
                            <input type="date" name="fecha_entrega"
                                class="p-2 w-full text-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                required>
                        </div>

                        <div>
                            <h3 class="text-gray-500 text-[18px] font-semibold mb-2 text-center md:text-left">Seleccione los
                                grupos</h3>
                            <div id="listaGrupos" class="flex flex-wrap justify-center md:justify-start gap-3">
                                @foreach ($grupos as $grupo)
                                    <button type="button"
                                        data-nombre="{{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura }} {{ $grupo->año }}"
                                        data-id="{{ $grupo->pk_grupo }}"
                                        class="grupoBtn border border-gray-300 px-3 py-2 rounded-lg hover:bg-teal-600 hover:text-white transition cursor-pointer">
                                        {{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura }}
                                        {{ $grupo->año }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h3 class="text-gray-500 text-[18px] font-semibold mb-2 text-center md:text-left">Grupos
                                asignados
                            </h3>
                            <div id="asignadosContainer"
                                class="flex flex-wrap justify-center md:justify-start items-center gap-3 min-h-[60px] border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <p class="text-gray-400 text-sm">No hay grupos asignados aún.</p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-teal-600 text-white px-6 py-2 rounded-lg shadow hover:bg-teal-700 transition">
                                Asignar
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const grupoBtns = document.querySelectorAll('.grupoBtn');
            const asignadosContainer = document.getElementById('asignadosContainer');

            grupoBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const nombre = btn.dataset.nombre;
                    const id = btn.dataset.id;

                    const mensaje = asignadosContainer.querySelector('p');
                    if (mensaje) mensaje.remove();

                    if (document.querySelector(`#asignadosContainer input[value="${id}"]`)) return;

                    const item = document.createElement('div');
                    item.className =
                        'flex items-center gap-2 border border-gray-300 px-3 py-2 rounded-lg bg-white';
                    item.innerHTML = `
                <span class="text-slate-700">${nombre}</span>
                <input type="hidden" name="grupos[]" value="${id}">
                <button type="button" class="text-gray-400 hover:text-red-500 transition font-semibold">&times;</button>
            `;

                    item.querySelector('button').addEventListener('click', () => item.remove());

                    asignadosContainer.appendChild(item);
                });
            });
        });
    </script>
@endsection
