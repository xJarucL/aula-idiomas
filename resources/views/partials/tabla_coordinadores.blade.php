<table class="min-w-full text-xs md:text-base">
    <thead>
        <tr class="bg-gray-100">
            <th class="py-2 px-2 md:px-4 text-left">Nombre Completo</th>
            <th class="py-2 px-2 md:px-4 text-left">Correo</th>
            <th class="py-2 px-2 md:px-4 text-left">Permiso</th>
            <th class="py-2 px-2 md:px-4 text-left">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if($coordinadores->isEmpty())
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                    No hay coordinadores para mostrar
                </td>
            </tr>
        @else
            @foreach ($coordinadores as $coordinador)
                <tr>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <img
                                src="{{ $coordinador->img_user ? asset('storage/'.$coordinador->img_user) : asset('img/default.jpg') }}"
                                alt="coordinador"
                                class="w-10 h-10 rounded-full object-cover border"
                            />
                            <span class="text-gray-800 font-medium">
                                {{ $coordinador->nombres }}
                                {{ $coordinador->ap_paterno }}
                                {{ $coordinador->ap_materno }}
                            </span>
                        </div>
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        {{ $coordinador->email }}
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        <select name="filtro" id="filtro" class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full shadow text-sm md:text-base">
                            <option value="" >Coordinador</option>
                            <option value="">Docente</option>
                        </select>
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-100">
                        <div class="flex items-center justify-center gap-2">
                            @if($coordinador->deleted_at)
                                <form action="{{ route('coordinador.restaurar', $coordinador->pk_usuario) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Habilitar Coordinador?"
                                            data-swal-text="El coordinador volverá a tener acceso al sistema."
                                            data-swal-icon="success"
                                            data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                        Habilitar
                                    </button>
                                </form>
                            @else
                                <a href="#" class="text-cyan-600 hover:text-cyan-800" title="Detalles">Detalles</a>
                                <a href="#" class="text-green-600 hover:text-green-800" title="Editar">Editar</a>

                                <form action="{{ route('coordinador.eliminar', $coordinador->pk_usuario) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Deshabilitar coordinador?"
                                            data-swal-text="El coordinador perderá acceso al sistema hasta que se vuelva a habilitar."
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
    {{ $coordinadores->links() }}
</div>
