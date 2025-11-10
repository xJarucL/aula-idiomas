<div class="h-150 sm:h-125 overflow-auto pr-1">
    <table class="min-w-full text-xs md:text-base">
        <thead class="sticky top-0">
            <tr class="hidden sm:table-row bg-gray-100 text-left">
                <th class="py-3 px-4">Matrícula</th>
                <th class="py-3 px-4">Nombre Completo</th>
                <th class="py-3 px-4">Carrera</th>
                <th class="py-3 px-4">Promedio</th>
                <th class="py-3 px-4">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if ($alumnos->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 h-100 text-center text-gray-500 text-sm sm:text-lg italic">
                        No hay alumnos para mostrar
                    </td>
                </tr>
            @else
                @foreach ($alumnos as $alumno)
                    <tr
                        class="flex flex-col sm:table-row border sm:border-none border-gray-300 rounded-lg sm:rounded-none  shadow sm:shadow-none mt-4 sm:mt-0 p-4 sm:p-0 hover:bg-gray-50 transition">
                        {{-- Matrícula --}}
                        <td class="py-3 border-b border-gray-200 px-4 align-middle text-gray-800 hidden sm:table-cell">
                            {{ $alumno->usuario->matricula }}
                        </td>

                        {{-- Nombre e imagen --}}
                        <td class="py-3 px-4 border-b border-gray-200 align-middle">
                            <div class="flex w-full sm:items-center space-x-3">
                                <img src="{{ $alumno->usuario->img_user ? asset('storage/' . $alumno->usuario->img_user) : asset('img/default.jpg') }}"
                                    alt="alumno" class="w-13 h-13 sm:w-10 sm:h-10 rounded-full object-cover border" />
                                <div class="flex sm:flex-none flex-col w-full">
                                    <span class="text-gray-800 text-lg sm:text-[16px] font-medium">
                                        {{ $alumno->usuario->nombres }}
                                        {{ $alumno->usuario->ap_paterno }}
                                        {{ $alumno->usuario->ap_materno }}
                                    </span>
                                    <div class="flex sm:flex-none justify-between">
                                        {{-- VISTA MOVIL MATRICULA --}}
                                        <div class="sm:hidden">
                                            {{ $alumno->usuario->matricula }}
                                        </div>
                                        <span class="sm:hidden">|</span>
                                        {{-- VISTA MOVIL CALIFICACION --}}
                                        <div class="sm:hidden">
                                            @if (is_numeric($alumno->promedio))
                                                <span
                                                    class="inline-block bg-green-100 text-green-800 font-semibold px-3 py-1 rounded-full">
                                                    {{ $alumno->promedio }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-block bg-gray-200 text-gray-700 font-medium px-3 py-1 rounded-full">
                                                    {{ $alumno->promedio }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 border-b text-justify border-gray-200 align-middle text-gray-800">
                            {{ $alumno->grupos->first()->grupo->carrera->nombre ?? 'Sin carrera' }}
                        </td>
                        <td class="py-3 px-4 border-b border-gray-200 align-middle hidden sm:table-cell">
                            @if (is_numeric($alumno->promedio))
                                <span
                                    class="inline-block bg-green-100 text-green-800 font-semibold px-3 py-1 rounded-full">
                                    {{ $alumno->promedio }}
                                </span>
                            @else
                                <span class="inline-block bg-gray-200 text-gray-700 font-medium px-3 py-1 rounded-full">
                                    {{ $alumno->promedio }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 border-b border-gray-200 align-middle">
                            <div class="flex items-center justify-center gap-2">
                                @if ($alumno->deleted_at)
                                    <form action="{{ route('alumno.restaurar', $alumno->pk_alumno) }}" method="POST">
                                        @csrf
                                        <button type="submit" data-swal-form data-swal-title="¿Habilitar Alumno?"
                                            data-swal-text="El alumno volverá a tener acceso al sistema."
                                            data-swal-icon="success" data-swal-confirm="Sí, habilitar"
                                            class="text-green-600 hover:text-green-800">
                                            Habilitar
                                        </button>
                                    </form>
                                @else
                                    <a href="#" class="text-cyan-600 hover:text-cyan-800"
                                        title="Detalles">Detalles</a>
                                    <a href="#" class="text-green-600 hover:text-green-800"
                                        title="Editar">Editar</a>

                                    <form action="{{ route('alumno.eliminar', $alumno->pk_alumno) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" data-swal-form data-swal-title="¿Deshabilitar alumno?"
                                            data-swal-text="El alumno perderá acceso al sistema hasta que se vuelva a habilitar."
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
    {{ $alumnos->links() }}
</div>
