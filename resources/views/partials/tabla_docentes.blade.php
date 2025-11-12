@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])
<div class="h-150 sm:h-125 overflow-auto pr-1">
    <table class="min-w-full text-xs md:text-base">
        <thead class="sticky top-0">
            <tr class="hidden sm:table-row bg-gray-100">
                <th class="py-2 px-2 md:px-4 text-left">Nombre Completo</th>
                <th class="py-2 px-2 md:px-4 text-left">Correo</th>
                <th class="py-2 px-2 md:px-4 text-left">Permiso</th>
                <th class="py-2 px-2 md:px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if ($docentes->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 h-100 text-center text-gray-500 text-sm sm:text-lg italic">
                        No hay docentes para mostrar
                    </td>
                </tr>
            @else
                @foreach ($docentes as $docente)
                    <tr
                        class="flex flex-col sm:table-row border sm:border-none border-gray-300 rounded-lg sm:rounded-none shadow sm:shadow-none mt-4 sm:mt-0 p-3 sm:p-0">
                        <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $docente->img_user ? asset('storage/' . $docente->img_user) : asset('img/default.jpg') }}"
                                    alt="docente" class="w-15 h-15 sm:w-10 sm:h-10 rounded-full object-cover border" />
                                <div>
                                    <span class="text-gray-800 text-lg sm:text-sm font-medium">
                                        {{ $docente->nombres }}
                                        {{ $docente->ap_paterno }}
                                        {{ $docente->ap_materno }}
                                    </span>
                                    {{-- VISTA MOVIL --}}
                                    <div class="sm:hidden ">
                                        {{ $docente->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 px-2 md:px-4 border-b border-gray-200 hidden sm:table-cell">{{ $docente->email }}</td>
                        <td class="py-4 px-2 md:px-4 sm:py-2 border-b border-gray-200">
                            <div class="flex sm:flex-none justify-center items-center gap-2 sm:gap-0">
                                <h4 class="sm:hidden text-center">Permiso:</h4>
                                <select name="tipo_usuario"
                                        class="select-tipo-usuario bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full shadow text-sm md:text-base"
                                        data-id="{{ $docente->pk_usuario }}">
                                    <option value="2" {{ $docente->fk_tipo_usuario == 2 ? 'selected' : '' }}>Docente</option>
                                    <option value="3" {{ $docente->fk_tipo_usuario == 3 ? 'selected' : '' }}>Coordinador</option>
                                </select>
                            </div>
                        </td>
                        <td class="py-2 px-2 md:px-4 border-b border-gray-100">
                            <div class="flex items-center justify-center gap-2">
                                @if ($docente->deleted_at)
                                    <form action="{{ route('docente.restaurar', $docente->pk_usuario) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" data-swal-form data-swal-title="¿Habilitar Docente?"
                                            data-swal-text="El docente volverá a tener acceso al sistema."
                                            data-swal-icon="success" data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                            Habilitar
                                        </button>
                                    </form>
                                @else
                                    <a href="{{route('docente.detalle', $docente->pk_usuario)}}" class="text-cyan-600 hover:text-cyan-800"
                                        title="Detalles">Detalles</a>
                                    <a href="{{route('docente.cargar', $docente->pk_usuario)}}" class="text-green-600 hover:text-green-800"
                                        title="Editar">Editar</a>

                                    <form action="{{ route('docente.eliminar', $docente->pk_usuario) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" data-swal-form data-swal-title="¿Deshabilitar docente?"
                                            data-swal-text="El docente perderá acceso al sistema hasta que se vuelva a habilitar."
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
    {{ $docentes->links() }}
</div>
