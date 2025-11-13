@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])

<table class="min-w-full text-xs md:text-base">
    <thead>
        <tr class="bg-gray-100">
            <th class="py-2 px-2 md:px-4 text-left">Grupo</th>
            <th class="py-2 px-2 md:px-4 text-left">Carrera</th>
            <th class="py-2 px-2 md:px-4 text-left">Cuatrimestre</th>
            <th class="py-2 px-2 md:px-4 text-left">Año</th>
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
                <tr>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        {{$grupo->fk_cuatrimestre ?? 'Sin cuatri'}}
                        {{$grupo->nombre}}
                        {{$grupo->carrera->abreviatura ?? 'Sin carrera'}}
                        {{$grupo->año}}
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        {{$grupo->carrera->nombre}}
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        {{$grupo->fk_cuatrimestre}}
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        {{$grupo->año}}
                    </td>
                    <td class="py-2 px-1 border-b border-gray-100">
                        <div class="flex items-center justify-center gap-2">
                            @if($grupo->deleted_at)
                                <form action="{{ route('grupo.restaurar', $grupo->pk_grupo) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Habilitar grupo?"
                                            data-swal-text="El grupo será habilitado nuevamente"
                                            data-swal-icon="success"
                                            data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                        Habilitar
                                    </button>
                                </form>
                            @else
                                <a href="#" class="text-cyan-600 hover:text-cyan-800" title="Detalles">Detalles</a>
                                <a href="{{route('coordinacion.asignar-grupo', $grupo->pk_grupo)}}" class="text-green-600 hover:text-green-800" title="Asignar">Asignar</a>

                                <form action="{{ route('grupo.eliminar', $grupo->pk_grupo) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Deshabilitar grupo?"
                                            data-swal-text="El grupo será deshabilitado y ya no aparecerá en la lista de activos"
                                            data-swal-icon="warning"
                                            data-swal-confirm="Sí, deshabilitar"
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

<div class="mt-6">
    {{ $grupos->links() }}
</div>


