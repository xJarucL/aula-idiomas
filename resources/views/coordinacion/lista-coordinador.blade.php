@extends('components.menu')

@section('title', 'Listado de docente')

@section('content')

{{-- Titulo y boton --}}
<section class="flex-1 flex flex-col md:flex-row justify-between m-2 gap-2">
    <div>
        <h1 class="text-2xl md:text-4xl font-bold text-black">Gestión de Coordinación</h1>
        <span class="text-gray-500 font-light mt-2 block">Listado de Información de coordinadores</span>
    </div>
    <div class="flex items-center justify-center gap-2">
        <a href="{{ route('coordinacion.registro-coordinador') }}" class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Registrar Coordinador</a>
    </div>
</section>
{{-- Buscador y filtro --}}
<section class="flex flex-col md:flex-row justify-between mt-5 gap-5">
    <input
        class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full md:w-2/3 shadow text-sm md:text-base"
        type="text" placeholder="Buscar Coordinador..."
    >
    <div class="flex md:flex-row justify-end gap-2 w-full md:w-1/3">
        <select name="filtro" id="filtro" class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full shadow text-sm md:text-base">
            <option value="" disabled selected>Permiso</option>
            <option value="nombre">Acceso</option>
            <option value="apellido">No acceso</option>
        </select>
        <select name="filtro" id="filtro" class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full shadow text-sm md:text-base">
            <option value="" disabled selected>Otro</option>
        </select>
    </div>
</section>
{{-- Tabla de coordinador --}}
<section class="overflow-x-auto mt-5">
    <div class="border-2 border-gray-300 rounded-xl p-2 bg-white shadow">
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
                                <div class="flex justify-center items-center gap-3">
                                    <a href="#" class="text-green-600 hover:text-green-800" title="Editar">
                                        Editar
                                    </a>
                                    <a href="#" class="text-red-500 hover:text-red-700" title="Eliminar">
                                        Eliminar
                                    </a>
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
    </div>
</section>

@endsection
