@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js', 'resources/js/buscador.js'])
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
        @if($docentes->isEmpty())
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                    No hay docentes para mostrar
                </td>
            </tr>
        @else
            @foreach ($docentes as $docente)
                <tr>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <img
                                src="{{ $docente->img_user ? asset('storage/'.$docente->img_user) : asset('img/default.jpg') }}"
                                alt="docente"
                                class="w-10 h-10 rounded-full object-cover border"
                            />
                            <span class="text-gray-800 font-medium">
                                {{ $docente->nombres }}
                                {{ $docente->ap_paterno }}
                                {{ $docente->ap_materno }}
                            </span>
                        </div>
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">{{$docente->email}}</td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200"> Docente </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-100">
                        <div class="flex items-center justify-center gap-2">
                            @if($docente->deleted_at)
                                <form action="{{ route('docente.restaurar', $docente->pk_usuario) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Habilitar Docente?"
                                            data-swal-text="El docente volverá a tener acceso al sistema."
                                            data-swal-icon="success"
                                            data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                        Habilitar
                                    </button>
                                </form>
                            @else
                                <a href="#" class="text-cyan-600 hover:text-cyan-800" title="Detalles">Detalles</a>
                                <a href="#" class="text-green-600 hover:text-green-800" title="Editar">Editar</a>

                                <form action="{{ route('docente.eliminar', $docente->pk_usuario) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Deshabilitar docente?"
                                            data-swal-text="El docente perderá acceso al sistema hasta que se vuelva a habilitar."
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
    {{ $docentes->links() }}
</div>
