@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])

<div class="overflow-x-auto h-auto">
    <table class="min-w-full text-xs md:text-base border border-gray-200 rounded-lg">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-2 md:px-4 text-left">Nombre del grupo</th>
                <th class="py-2 px-2 md:px-4 text-left">Cantidad Estudiantes</th>
                <th class="py-2 px-2 md:px-4 text-left">Carrera</th>
                <th class="py-2 px-2 md:px-4 text-left">Grado</th>
            </tr>
        </thead>
        <tbody>
            @if($grupos->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                        No hay grupos para mostrar
                    </td>
                </tr>
            @else
                @foreach ($grupos as $grupo)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-3 px-4 align-middle text-gray-800">
                            {{ $grupo->grupo->nombre }}
                        </td>
                    </tr>
                @endforeach
            @endif
            {{-- CONTENIDO PRINCIPAL --}}
            <tr class="cursor-pointer hover:bg-gray-50" onclick="toggleRow(this)">
                <td class="py-2 px-2 md:px-4 border-b border-gray-200 font-medium">IDGS 10mo</td>
                <td class="py-2 px-2 md:px-4 border-b border-gray-200">20</td>
                <td class="py-2 px-2 md:px-4 border-b border-gray-200">Ingenieria en Desarrollo y Gestion de Software
                </td>
                <td class="py-2 px-2 md:px-4 border-b border-gray-200">2 A</td>
            </tr>
            {{-- CONTENIDO DESPLEGABLE --}}
            <tr class="hidden bg-gray-50">
                <td colspan="4" class="p-4">
                    <div class="space-y-4">
                        {{-- PARTICIPANTE --}}
                        <div>
                            <h3 class="text-gray-700 font-semibold mb-2">Participantes del grupo:</h3>
                            <div class="flex flex-wrap justify-start gap-6">
                                @for ($i = 1; $i < 34; $i++)
                                    <ul class="list-disc list-inside text-gray-600 text-sm">
                                        <li>Angel Ariel Salazar Medina {{ $i }}</li>
                                    </ul>
                                @endfor
                            </div>
                        </div>
                        {{-- CARD --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200">
                                {{-- CARD ENTREGADAS --}}
                                <div class="flex">
                                    <div class="w-1/5">
                                        <svg width="320" height="60" viewBox="0 0 320 60" role="img"
                                            aria-labelledby="entregadoTitle">
                                            <rect width="320" height="60" rx="8" fill="none" />
                                            <g transform="translate(8,6)">
                                                <circle cx="22" cy="22" r="20" fill="#e6fffa"
                                                    stroke="#06b6d4" stroke-width="2" />
                                                <path d="M14 23l5 5 11-13" fill="none" stroke="#047857"
                                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="w-4/5">
                                        <h4 class="font-semibold text-teal-600">Actividades entregadas</h4>
                                        <p class="text-gray-600 text-2xl font-semibold">2</p>
                                    </div>
                                </div>
                            </div>
                            {{-- CARD PEDIENTE --}}
                            <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200">
                                <div class="flex">
                                    <div class="w-1/5">
                                        <svg width="360" height="60" viewBox="0 0 360 60" role="img"
                                            aria-labelledby="pendienteTitle">
                                            <title id="pendienteTitle">actividades pendientes</title>
                                            <rect width="360" height="60" rx="8" fill="none" />
                                            <g transform="translate(8,6)">
                                                <circle cx="22" cy="22" r="20" fill="#fffbeb"
                                                    stroke="#f59e0b" stroke-width="2" />
                                                <path d="M22 12v10l8 4" fill="none" stroke="#92400e"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M22 4a18 18 0 1 0 0 36 18 18 0 0 0 0-36" fill="none"
                                                    stroke="#f59e0b" stroke-width="0" />
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="w-4/5">
                                        <h4 class="font-semibold text-teal-600">Actividades pendientes</h4>
                                        <p class="text-gray-600 text-2xl font-semibold">6</p>
                                    </div>
                                </div>
                            </div>
                            {{-- CARD SIN ENTREGAR --}}
                            <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200">
                                <div class="flex">
                                    <div class="w-1/5">
                                        <svg width="380" height="60" viewBox="0 0 380 60" role="img"
                                            aria-labelledby="sinEntregarTitle">
                                            <title id="sinEntregarTitle">actividades sin entregar</title>
                                            <rect width="380" height="60" rx="8" fill="none" />
                                            <g transform="translate(8,6)">
                                                <circle cx="22" cy="22" r="20" fill="#fff1f2"
                                                    stroke="#ef4444" stroke-width="2" />
                                                <path d="M14 14l16 16M30 14L14 30" fill="none" stroke="#b91c1c"
                                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="w-4/5">
                                        <h4 class="font-semibold text-teal-600">Actividad sin entregar</h4>
                                        <p class="text-gray-600 text-2xl font-semibold">3</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- TAREAS ASIGNADAS --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-gray-600 font-semibold mb-2">Actividades asignadas:</h3>
                                <a href="#" class="text-white bg-teal-600 px-4 py-2 hover:bg-teal-700 rounded-lg">Asignar</a>
                            </div>
                            <table class="w-full text-sm border border-gray-200 rounded-md">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="text-left p-2 border-b border-gray-300">Nombre de actividad</th>
                                        <th class="text-left p-2 border-b border-gray-300">Fecha de entrega</th>
                                        <th class="text-left p-2 border-b border-gray-300">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-2 border-b border-gray-300">Past Simple vs Past Continuos</td>
                                        <td class="p-2 border-b border-gray-300">20/10/2025</td>
                                        <td class="p-2 border-b border-gray-300 font-semibold">
                                            <P class="text-green-600 bg-green-300 px-2 py-2 w-25 rounded-2xl">
                                                Completado
                                            </P>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 border-b border-gray-300">Past Simple vs Past Continuos</td>
                                        <td class="p-2 border-b border-gray-300">20/10/2025</td>
                                        <td class="p-2 border-b border-gray-300 font-semibold">
                                            <P class="text-yellow-600 bg-yellow-300 px-2 py-2 w-25 rounded-2xl">En
                                                progreso</P>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 border-b border-gray-300">Past Simple vs Past Continuos</td>
                                        <td class="p-2 border-b border-gray-300">20/10/2025</td>
                                        <td class="p-2 border-b border-gray-300 font-semibold">
                                            <P class="text-red-600 bg-red-300 px-2 py-2 w-25 rounded-2xl">Pendiente</P>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
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
