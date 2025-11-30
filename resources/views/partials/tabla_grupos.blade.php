@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])

<div class="h-150 sm:max-h-screen overflow-auto pr-1">
    <table class="min-w-full text-xs md:text-base">
        <thead class="sticky top-0">
            <tr class="hidden sm:table-row bg-teal-50 text-left">
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">GRUPO</th>
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">CARRERA</th>
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">CUATRIMESTRE</th>
                <th class="py-2 px-2 md:px-4 text-left text-teal-800">AÑO</th>
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
                    <tr class="flex flex-col sm:table-row border sm:border-none border-gray-300 rounded-lg sm:rounded-none shadow sm:shadow-none mt-2 sm:mt-0 mb-4 sm:mb-0 p-4 sm:p-0 hover:bg-gray-50 transition">
                        <td class="py-2 px-2 text-teal-600 sm:text-black text-xl sm:text-base font-semibold sm:font-normal md:px-4 border-b border-gray-200">
                            {{ $grupo->fk_cuatrimestre ?? 'Sin cuatri' }}
                            {{ $grupo->nombre }}
                            {{ $grupo->carrera->abreviatura ?? 'Sin carrera' }}
                            {{ $grupo->año }}
                        </td>
                        <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                            {{ $grupo->carrera->nombre }}
                        </td>
                        <td class="py-2 px-2 md:px-4 border-b border-gray-200 hidden sm:table-cell">
                            {{ $grupo->fk_cuatrimestre }}
                        </td>
                        <td class="py-2 px-2 md:px-4 border-b border-gray-200 hidden sm:table-cell">
                            {{ $grupo->año }}
                        </td>
                        <td class="py-2 px-1 border-b border-gray-100">
                            <div class="flex items-center justify-center gap-2">
                                @if ($grupo->deleted_at)
                                    <form action="{{ route('grupo.restaurar', $grupo->pk_grupo) }}" method="POST">
                                        @csrf
                                        <button type="submit" data-swal-form data-swal-title="¿Habilitar grupo?"
                                            data-swal-text="El grupo será habilitado nuevamente"
                                            data-swal-icon="success" data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                            Habilitar
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('coordinador.detalle-grupo', $grupo->pk_grupo) }}" class="text-cyan-600 hover:text-cyan-800"
                                        title="Detalles">Detalles</a>
                                    <a href="{{ route('coordinacion.asignar-grupo', $grupo->pk_grupo) }}"
                                        class="text-green-600 hover:text-green-800" title="Asignar">Asignar</a>

                                    <form action="{{ route('grupo.eliminar', $grupo->pk_grupo) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" data-swal-form data-swal-title="¿Deshabilitar grupo?"
                                            data-swal-text="El grupo será deshabilitado y ya no aparecerá en la lista de activos"
                                            data-swal-icon="warning" data-swal-confirm="Sí, deshabilitar"
                                            class="text-red-500 hover:text-red-700">
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
    {{ $grupos->links() }}
</div>
