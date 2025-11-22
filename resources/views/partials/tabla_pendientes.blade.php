<div class="h-150 sm:h-[36rem] overflow-auto pr-1">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-teal-50 sticky top-0">
            <tr class="hidden sm:table-row">
                <th class="px-6 py-3 text-left text-sm font-medium text-teal-800 uppercase tracking-wider">Alumno</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-teal-800 uppercase tracking-wider">Grupo</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-teal-800 uppercase tracking-wider">Actividad</th>
                <th class="px-6 py-3 text-center text-sm font-medium text-teal-800 uppercase tracking-wider">Acciones
                </th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($pendientes as $pendiente)
                @php
                    $alumno = $pendiente['alumno'];
                    $actividad = $pendiente['actividad'];
                @endphp

                <tr
                    class="flex flex-col sm:table-row border sm:border-none border-gray-300 rounded-lg sm:rounded-lg shadow sm:shadow mt-2 sm:mt-0 mb-4 sm:mb-0 hover:bg-teal-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap border-b sm:border-none border-gray-300 mx-2 sm:mx-0 text-gray-700">
                        <div>
                            <div class="text-teal-600 sm:text-black text-xl sm:text-base font-medium sm:font-normal">
                                {{ $alumno->usuario->nombres }}
                                {{ $alumno->usuario->ap_paterno }}
                                {{ $alumno->usuario->ap_materno }}
                            </div>
                            <div class="sm:hidden">
                                {{ $actividad->grupos->map(fn($g) => $g->fk_cuatrimestre . $g->nombre . $g->carrera->abreviatura . ' ' . $g->año)->join(', ') }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 hidden sm:table-cell">
                        {{ $actividad->grupos->map(fn($g) => $g->fk_cuatrimestre . $g->nombre . $g->carrera->abreviatura . ' ' . $g->año)->join(', ') }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 font-medium">
                        {{ $actividad->nom_actividad ?? 'Sin título' }}
                        @if ($pendiente['tipo'] === 'pdf')
                            <span class="text-red-500 ml-2">(PDF)</span>
                        @elseif ($pendiente['tipo'] === 'audio')
                            <span class="text-blue-500 ml-2">(AUDITIVA)</span>
                        @else
                            <span class="text-green-500 ml-2">(PREGUNTAS)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-end sm:text-center">
                        @if ($pendiente['tipo'] === 'pdf')
                            <a href="{{ route('docente.revisar-pdf', [
                                'pk_actividad' => $pendiente['fk_actividad'],
                                'pk_alumno' => $pendiente['fk_alumno'],
                            ]) }}"
                                class="inline-block px-4 py-2 bg-teal-600 text-white font-medium rounded-lg shadow hover:bg-teal-700 hover:scale-105 transform transition-all duration-200">
                                Revisar
                            </a>
                        @elseif($pendiente['tipo'] === 'audio')
                            <a href="{{ route('docente.revisar-audio', [
                                'pk_actividad' => $pendiente['fk_actividad'],
                                'pk_alumno' => $pendiente['fk_alumno'],
                            ]) }}"
                                class="inline-block px-4 py-2 bg-teal-600 text-white font-medium rounded-lg shadow hover:bg-teal-700 hover:scale-105 transform transition-all duration-200">
                                Revisar
                            </a>
                        @else
                            <a href="{{ route('docente.revisar', [
                                'pk_actividad' => $pendiente['fk_actividad'],
                                'pk_alumno' => $pendiente['fk_alumno'],
                            ]) }}"
                                class="inline-block px-4 py-2 bg-teal-600 text-white font-medium rounded-lg shadow hover:bg-teal-700 hover:scale-105 transform transition-all duration-200">
                                Revisar
                            </a>
                        @endif
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        No hay entregas pendientes.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
