@extends('components.menu')

@section('title', 'Lista de actividades | Aula de Idiomas')

@section('content')
    <section>
        <h1 class="text-3xl sm:text-5xl font-bold text-teal-800">Listado de actividades</h1>
        <span class="text-xs sm:text-lg text-gray-500">Consulta en este apartado todas las actividades asignadas.</span>
    </section>

    <section class="mt-5">
        <x-msj-alert />
        <div class="flex flex-row justify-between gap-3 bg-white p-4 rounded-lg shadow-lg">
            <div class="w-3/4 sm:w-5/6 relative">
                <input id="buscador"
                    class="border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 w-full shadow text-sm md:text-base"
                    type="text" placeholder="Buscar actividades...">
                <span id="limpiar-busqueda"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg font-bold hover:text-red-500 hidden">
                    &times;
                </span>
            </div>
            <div class="flex justify-center w-1/4 sm:w-1/6">
                <button id="btn-buscar"
                    class="bg-teal-600 text-white p-3 w-full rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Buscar</button>
            </div>
        </div>
    </section>

    <section class="mt-5 sm:mt-8">
        <div class="bg-white p-2 sm:p-4 border border-gray-300 rounded-lg shadow">
            <div class="flex justify-between p-2 gap-5 border-b border-gray-200">
                <h2 class="text-[18px] sm:text-[28px] text-teal-700 font-semibold">Actividades</h2>
                <div class="flex items-center gap-4">
                    <a href="{{ route('alumno.lista-actividades', ['filtro' => 'pendientes']) }}"
                        class="text-[8px] sm:text-lg font-medium border-b-2 {{ $filtro == 'pendientes' ? 'border-teal-700 text-teal-700' : 'border-transparent text-gray-700 hover:border-teal-700' }}">
                        Pendientes
                    </a>
                    <a href="{{ route('alumno.lista-actividades', ['filtro' => 'entregadas']) }}"
                        class="text-[8px] sm:text-lg font-medium border-b-2 {{ $filtro == 'entregadas' ? 'border-teal-700 text-teal-700' : 'border-transparent text-gray-700 hover:border-teal-700' }}">
                        Terminadas
                    </a>
                    <a href="{{ route('alumno.lista-actividades', ['filtro' => 'no_entregadas']) }}"
                        class="text-[8px] sm:text-lg font-medium border-b-2 {{ $filtro == 'no_entregadas' ? 'border-teal-700 text-teal-700' : 'border-transparent text-gray-700 hover:border-teal-700' }}">
                        No entregadas
                    </a>
                </div>
            </div>

            <div class="mt-4 overflow-auto h-[28rem]">
                @forelse ($actividadesFiltradas as $actividad)
                    @php
                        if ($filtro === 'pendientes') {
                            $accion = route('alumno.responder-actividad', $actividad->pk_actividad);
                        } elseif ($filtro === 'entregadas') {
                            $accion = route('alumno.detalle-actividad', $actividad->pk_actividad);
                        } else {
                            $accion = 'javascript:void(0)';
                        }
                    @endphp

                    <x-card-actividad iconoA="book" color1="blue" nombreActividad="{{ $actividad->nom_actividad }}"
                        tipo="{{ $actividad->tipo }}"
                        fecha="{{ \Carbon\Carbon::parse($actividad->fecha_fin)->format('d/m/Y') }}"
                        iconoEntregable="{{ $filtro == 'entregadas' ? 'check' : ($filtro == 'no_entregadas' ? 'close' : 'pending') }}"
                        color2="{{ $filtro == 'entregadas' ? 'green' : ($filtro == 'no_entregadas' ? 'red' : 'orange') }}"
                        link="{{ $accion }}" data-filtro="{{ $filtro }}" />
                @empty
                    <div class="flex justify-center items-center h-[20rem]">
                        <p class="text-center text-gray-500 text-sm sm:text-lg mt-4">No hay actividades para mostrar.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('[data-card-actividad]').forEach(card => {
            card.addEventListener('click', (e) => {
                const filtro = card.getAttribute('data-filtro');
                if (filtro === 'no_entregadas') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: '⚠️ Actividad no entregada',
                        html: `
                    <p style="font-size:16px; color:#555;">
                        Esta actividad <strong>no fue entregada</strong> en los tiempos establecidos.<br>
                        Consulta con tu docente para más información.
                    </p>
                `,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#0d9488',
                        background: '#fff',
                        color: '#333',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                }
            });
        });
    </script>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('buscador');
            const limpiar = document.getElementById('limpiar-busqueda');
            const cards = document.querySelectorAll('[data-card-actividad]');

            input.addEventListener('input', () => {
                const query = input.value.toLowerCase().trim();
                limpiar.classList.toggle('hidden', query.length === 0);

                cards.forEach(card => {
                    const nombre = card.querySelector('[data-nombre-actividad]').textContent
                        .toLowerCase();
                    const tipo = card.querySelector('[data-tipo-actividad]').textContent
                        .toLowerCase();
                    card.style.display = (nombre.includes(query) || tipo.includes(query)) ? '' :
                        'none';
                });
            });

            limpiar.addEventListener('click', () => {
                input.value = '';
                limpiar.classList.add('hidden');
                cards.forEach(card => card.style.display = '');
            });
        });
    </script>
@endsection
