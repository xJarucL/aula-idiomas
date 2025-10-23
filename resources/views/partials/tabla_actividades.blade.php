<table class="min-w-full text-xs md:text-base">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="py-3 px-4">Código</th>
            <th class="py-3 px-4">Nombre de Actividad</th>
            <th class="py-3 px-4">Descripción</th>
            <th class="py-3 px-4">Tipo</th>
            <th class="py-3 px-4">Fecha de Creación</th>
            <th class="py-3 px-4">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if($actividades->isEmpty())
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                    No hay actividades para mostrar
                </td>
            </tr>
        @else
            @foreach ($actividades as $actividad)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="py-3 px-4 align-middle text-gray-800">
                        {{ $actividad->cod_actividad ?? 'Sin código' }}
                    </td>
                    <td class="py-3 px-4 align-middle font-semibold text-gray-800">
                        {{ $actividad->nom_actividad ?? 'Sin nombre'}}
                    </td>
                    <td class="py-3 px-4 align-middle text-gray-600">
                        {{ Str::limit($actividad->descripcion, 60, '...') }}
                    </td>
                    <td class="py-3 px-4 align-middle text-gray-600">
                        {{
                            [
                                'preguntas' => 'Preguntas',
                                'pdf' => 'Carga de PDF',
                                'auditiva' => 'Auditiva y Oral',
                            ][$actividad->tipo] ?? 'Sin tipo'
                        }}
                    </td>
                    <td class="py-3 px-4 align-middle text-gray-800">
                        {{ $actividad->created_at->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-4 align-middle">
                        <div class="flex items-center justify-center gap-2">
                            @if($actividad->deleted_at)
                                <form action="{{ route('actividad.restaurar', $actividad->pk_actividad) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Habilitar Actividad?"
                                            data-swal-text="La actividad volverá a estar activa."
                                            data-swal-icon="success"
                                            data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                        Habilitar
                                    </button>
                                </form>
                            @else
                                <a href="#" class="text-cyan-600 hover:text-cyan-800" title="Asignar">Asignar</a>

                                <form action="{{ route('actividad.eliminar', $actividad->pk_actividad) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Deshabilitar actividad?"
                                            data-swal-text="La actividad será deshabilitada temporalmente."
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
    {{ $actividades->links() }}
</div>
