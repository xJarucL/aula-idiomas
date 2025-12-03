<div class="h-150 sm:h-125 overflow-auto pr-1">
    <table class="min-w-full text-xs md:text-base">
        <thead class="sticky top-0">
            <tr class="bg-gray-100 text-left hidden sm:table-row">
                <th class="py-2 px-2">Código</th>
                <th class="py-2 px-2">Nombre de Actividad</th>
                <th class="py-2 px-2">Descripción</th>
                <th class="py-2 px-2">Tipo</th>
                <th class="py-2 px-2">Fecha de Creación</th>
                <th class="py-2 px-2">Autor</th>
                <th class="py-2 px-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if ($actividades->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 h-100 text-center text-gray-500 text-sm sm:text-lg italic">
                        No hay actividades para mostrar
                    </td>
                </tr>
            @else
                @foreach ($actividades as $actividad)
                    <tr
                        class="flex flex-col sm:table-row border sm:border-b border-gray-200 rounded-lg sm:rounded-none shadow sm:shadow-none mt-2 sm:mt-0 mb-4 sm:mb-0 hover:bg-gray-50 transition">
                        {{-- MOVIL --}}
                        <td
                            class="py-2 px-2 mx-2 align-middle border-b border-gray-200 font-semibold text-teal-600 sm:hidden">
                            <div>
                                <h4 class="text-lg font-semibold text-justify">
                                    {{ $actividad->nom_actividad ?? 'Sin nombre' }}</h4>
                                <p class="text-sm font-light">{{ $actividad->cod_actividad ?? 'Sin código' }}</p>
                            </div>
                        </td>
                        <td class="p-2 mx-2 align-middle text-gray-600 border-b border-gray-200 sm:hidden">
                            <div>
                                <div class="flex flex-row gap-2">
                                    <p class="font-medium">Tipo de actividad:</p>
                                    {{ [
                                        'preguntas' => 'Preguntas',
                                        'pdf' => 'Carga de PDF',
                                        'auditiva' => 'Auditiva y Oral',
                                    ][$actividad->tipo] ?? 'Sin tipo' }}
                                </div>
                                <div class="flex flex-row gap-2">
                                    <p class="font-medium">Fecha de creación:</p>
                                    {{ $actividad->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </td>

                        {{-- ESCRITORIO --}}
                        <td class="py-3 px-4 align-middle text-gray-800 hidden sm:table-cell">
                            {{ $actividad->cod_actividad ?? 'Sin código' }}
                        </td>
                        <td class="py-2 px-2 align-middle font-semibold text-gray-800 hidden sm:table-cell">
                            {{ $actividad->nom_actividad ?? 'Sin nombre' }}
                        </td>
                        <td class="py-3 px-4 mx-2 sm:mx-none text-justify align-middle text-gray-600 border-b sm:border-none border-gray-200">
                            {{ Str::limit($actividad->descripcion, 60, '...') }}
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-600 hidden sm:table-cell">
                            {{ [
                                'preguntas' => 'Preguntas',
                                'pdf' => 'Carga de PDF',
                                'auditiva' => 'Auditiva y Oral',
                            ][$actividad->tipo] ?? 'Sin tipo' }}
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-800 hidden sm:table-cell">
                            {{ $actividad->created_at->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 align-middle text-gray-800 hidden sm:table-cell">
                            {{ $actividad->docente->nombres }} {{ $actividad->docente->ap_paterno }} {{ $actividad->docente->ap_materno ?? '' }}
                        </td>
                        <td class="py-3 px-4 align-middle">
                            <div class="flex items-center justify-center gap-2">
                                @if ($actividad->deleted_at)
                                    <form action="{{ route('actividad.restaurar', $actividad->pk_actividad) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" data-swal-form data-swal-title="¿Habilitar Actividad?"
                                            data-swal-text="La actividad volverá a estar activa."
                                            data-swal-icon="success" data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                            Habilitar
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('docente.asignar-actividad', $actividad->pk_actividad) }}"
                                        class="text-cyan-600 hover:text-cyan-800" title="Asignar">Asignar</a>

                                    <a href="{{route('docente.detalle-actividad', $actividad->pk_actividad)}}" class="text-cyan-600 hover:text-cyan-800"
                                        title="Detalles">Detalles</a>

                                    <form action="{{ route('actividad.eliminar', $actividad->pk_actividad) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" data-swal-form data-swal-title="¿Deshabilitar actividad?"
                                            data-swal-text="La actividad será deshabilitada temporalmente."
                                            data-swal-icon="warning" data-swal-confirm="Sí, deshabilitar"
                                            class="text-red-500 hover:text-red-700 cursor-pointer">
                                            Deshabilitar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<div class="mt-6">
    {{ $actividades->links() }}
</div>
