<table class="min-w-full text-xs md:text-base">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="py-3 px-4">Matrícula</th>
            <th class="py-3 px-4">Nombre Completo</th>
            <th class="py-3 px-4">Carrera</th>
            <th class="py-3 px-4">Promedio</th>
            <th class="py-3 px-4">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if($alumnos->isEmpty())
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                    No hay alumnos para mostrar
                </td>
            </tr>
        @else
            @foreach ($alumnos as $alumno)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    {{-- Matrícula --}}
                    <td class="py-3 px-4 align-middle text-gray-800">
                        {{ $alumno->usuario->matricula }}
                    </td>

                    {{-- Nombre e imagen --}}
                    <td class="py-3 px-4 align-middle">
                        <div class="flex items-center space-x-3">
                            <img
                                src="{{ $alumno->usuario->img_user ? asset('storage/'.$alumno->usuario->img_user) : asset('img/default.jpg') }}"
                                alt="alumno"
                                class="w-10 h-10 rounded-full object-cover border"
                            />
                            <span class="text-gray-800 font-medium">
                                {{ $alumno->usuario->nombres }}
                                {{ $alumno->usuario->ap_paterno }}
                                {{ $alumno->usuario->ap_materno }}
                            </span>
                        </div>
                    </td>
                    <td class="py-3 px-4 align-middle text-gray-800">
                        {{ $alumno->grupos->first()->grupo->carrera->nombre ?? 'Sin carrera' }}
                    </td>
                    <td class="py-3 px-4 align-middle">
                        @if(is_numeric($alumno->promedio))
                            <span class="inline-block bg-green-100 text-green-800 font-semibold px-3 py-1 rounded-full">
                                {{ $alumno->promedio }}
                            </span>
                        @else
                            <span class="inline-block bg-gray-200 text-gray-700 font-medium px-3 py-1 rounded-full">
                                {{ $alumno->promedio }}
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-4 align-middle">
                        <div class="flex items-center justify-center gap-2">
                            @if($alumno->deleted_at)
                                <form action="{{ route('alumno.restaurar',  $alumno->pk_alumno) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Habilitar Alumno?"
                                            data-swal-text="El alumno volverá a tener acceso al sistema."
                                            data-swal-icon="success"
                                            data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                        Habilitar
                                    </button>
                                </form>
                            @else
                                <a href="#" class="text-cyan-600 hover:text-cyan-800" title="Detalles">Detalles</a>
                                <a href="#" class="text-green-600 hover:text-green-800" title="Editar">Editar</a>

                                <form action="{{ route('alumno.eliminar', $alumno->pk_alumno) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            data-swal-form
                                            data-swal-title="¿Deshabilitar alumno?"
                                            data-swal-text="El alumno perderá acceso al sistema hasta que se vuelva a habilitar."
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
    {{ $alumnos->links() }}
</div>
