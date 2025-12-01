@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])

<div class="h-150 sm:max-h-screen overflow-auto pr-1">
    <table class="min-w-full text-xs md:text-base ">
        <thead class="sticky top-0">
            <tr class="hidden sm:table-row bg-teal-50">
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">NOMBRE DEL GRUPO</th>
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">CANTIDAD ESTUDIANTES</th>
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">CARRERA</th>
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">GRADO</th>
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @if ($grupos->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 h-100 text-center text-gray-500 text-sm sm:text-lg italic">
                        No hay grupos para mostrar
                    </td>
                </tr>
            @else
                @foreach ($grupos as $grupo)
                    <tr
                        class="flex flex-col sm:table-row border border-gray-300 rounded-lg sm:rounded-none shadow sm:shadow-none sm:border-b sm:border-gray-200 hover:bg-gray-50 mt-2 sm:mt-0 mb-4 sm:mb-0 transition">
                        <td
                            class="py-3 px-4 align-middle text-teal-700 sm:text-gray-800 text-xl sm:text-base font-semibold sm:font-normal border-b border-gray-200">
                            <div class="flex sm:flex-row justify-between sm:items-center">
                                <div class="flex flex-col w-auto">
                                    <div>
                                        {{ $grupo->grupo->fk_cuatrimestre }} {{ $grupo->grupo->nombre }}
                                        {{ $grupo->grupo->carrera->abreviatura }} {{ $grupo->grupo->año }}
                                    </div>
                                    <div class="sm:hidden">
                                        <span class="text-sm text-gray-800 font-normal">Estudiantes:</span>
                                        <span
                                            class="text-sm text-gray-800 font-semibold">{{ $grupo->grupo->alumnos_count ?? 0 }}</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('docente.detalle-grupo', $grupo->grupo->pk_grupo) }}" class="sm:hidden flex justify-center items-center w-auto">
                                        <svg class="w-8 h-8 text-teal-700" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 3v4a1 1 0 0 1-1 1H5m8 7.5 2.5 2.5M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Zm-5 9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-800 hidden sm:table-cell">
                            {{ $grupo->grupo->alumnos_count ?? 0 }}
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-800 text-sm sm:text-base">
                            {{ $grupo->grupo->carrera->nombre }}
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-800 hidden sm:table-cell">
                            {{ $grupo->grupo->fk_cuatrimestre }}
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-800 hidden sm:table-cell">
                            <a href="{{ route('docente.detalle-grupo', $grupo->grupo->pk_grupo) }}"
                                class="p-1 hover:border hover:rounded-lg hover:bg-teal-600 hover:text-white">Ver detalles</a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<script>
    function toggleRow(row) {
        const nextRow = row.nextElementSibling;
        if (nextRow && nextRow.classList.contains("hidden")) {
            nextRow.classList.remove("hidden");
        } else {
            nextRow.classList.add("hidden");
        }
    }
</script>
