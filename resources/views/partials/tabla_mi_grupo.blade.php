@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])

<div class="overflow-x-auto h-auto">
    <table class="min-w-full text-xs md:text-base border border-gray-200 rounded-lg">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-2 md:px-4 text-left">Nombre del grupo</th>
                <th class="py-2 px-2 md:px-4 text-left">Cantidad Estudiantes</th>
                <th class="py-2 px-2 md:px-4 text-left">Carrera</th>
                <th class="py-2 px-2 md:px-4 text-left">Grado</th>
                <th class="py-2 px-2 md:px-4 text-left">Acciones</th>
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
                            {{ $grupo->grupo->fk_cuatrimestre }} {{ $grupo->grupo->nombre }} {{ $grupo->grupo->carrera->abreviatura }} {{ $grupo->grupo->año }}
                        </td>
                         <td class="py-3 px-4 align-middle text-gray-800">
                            {{ $grupo->grupo->alumnos_count ?? 0 }}
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-800">
                            {{ $grupo->grupo->carrera->nombre }}
                        </td>
                         <td class="py-3 px-4 align-middle text-gray-800">
                            {{ $grupo->grupo->fk_cuatrimestre }}
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-800">
                            <a href="{{ route('docente.detalle-grupo', $grupo->grupo->pk_grupo) }}">Ver detalles</a>
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
