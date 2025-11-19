<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-teal-50">
        <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-teal-800 uppercase tracking-wider">Alumno</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-teal-800 uppercase tracking-wider">Grupo</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-teal-800 uppercase tracking-wider">Actividad</th>
            <th class="px-6 py-3 text-center text-sm font-medium text-teal-800 uppercase tracking-wider">Acciones</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse($pendientes as $pendiente)
            <tr class="hover:bg-teal-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                    {{ $pendiente->alumno->usuario->nombres }} {{ $pendiente->alumno->usuario->ap_paterno }} {{ $pendiente->alumno->usuario->ap_materno ?? '' }}
                </td>
                <td class="px-6 py-4 text-gray-600">
                    {{ $pendiente->actividad->grupos->map(fn($g) => $g->fk_cuatrimestre . $g->nombre .  $g->carrera->abreviatura . ' ' . $g->año )->join(', ') }}
                </td>
                <td class="px-6 py-4 text-gray-700 font-medium">
                    {{ $pendiente->actividad->nom_actividad ?? 'Sin título' }}
                </td>
                <td class="px-6 py-4 text-center">
                    <a href="#"
                        class="inline-block px-4 py-2 bg-teal-600 text-white font-medium rounded-lg shadow hover:bg-teal-700 hover:scale-105 transform transition-all duration-200">
                        Revisar
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay entregas pendientes.</td>
            </tr>
        @endforelse
    </tbody>
</table>
