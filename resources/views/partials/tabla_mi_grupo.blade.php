@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])

<table class="min-w-full text-xs md:text-base">
    <thead>
        <tr class="bg-gray-100">
            <th class="py-2 px-2 md:px-4 text-left">Grupo Asignado</th>
            <th class="py-2 px-2 md:px-4 text-left">Cantidad Estudiantes</th>
            <th class="py-2 px-2 md:px-4 text-left">No se poner</th>
            <th class="py-2 px-2 md:px-4 text-left">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                IDGS 10mo
            </td>
            <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                20
            </td>
            <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                No se que poner
            </td>
            <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                <div class="flex items-center justify-center gap-2">
                    {{-- <a href="#" class="text-cyan-600 hover:text-cyan-800" title="Detalles">Detalles</a> --}}
                    <a href="#" class="text-green-600 hover:text-green-800" title="Editar">Asignar actividad</a>
                    {{-- <form action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" data-swal-form data-swal-title="¿Deshabilitar grupo?"
                            data-swal-text="El grupo será deshabilitado y ya no aparecerá en la lista de activos"
                            data-swal-icon="warning" data-swal-confirm="Sí, deshabilitar"
                            class="text-red-500 hover:text-red-700">
                            Deshabilitar
                        </button>
                    </form> --}}
                </div>
            </td>
        </tr>
    </tbody>
</table>

{{-- <div class="mt-6">
    {{ $grupos->links() }}
</div> --}}
