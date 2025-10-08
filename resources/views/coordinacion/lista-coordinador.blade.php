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
        <a href="#" class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow text-sm md:text-base">Registrar Coordinador</a>
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
                <tr>
                    <td class="px-2 py-2 flex items-center space-x-3 border-b border-gray-200">
                        <img
                        class="w-10 h-10 mr-2 rounded-full object-cover border" 
                        src="{{asset('img/default.jpg')}}" alt="">
                        Jaruny Lupe Cardenas Tirado
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">Jaru@gmail.com</td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        <select name="filtro" id="filtro" class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full shadow text-sm md:text-base">
                            <option value="" >Acceso</option>
                            <option value="">No acceso</option>
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
                                <tr>
                    <td class="px-2 py-2 flex items-center space-x-3 border-b border-gray-200">
                        <img
                        class="w-10 h-10 mr-2 rounded-full object-cover border" 
                        src="{{asset('img/default.jpg')}}" alt="">
                        Jaruny Lupe Cardenas Tirado
                    </td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">Jaru@gmail.com</td>
                    <td class="py-2 px-2 md:px-4 border-b border-gray-200">
                        <select name="filtro" id="filtro" class="bg-white border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition w-full shadow text-sm md:text-base">
                            <option value="" >Acceso</option>
                            <option value="">No acceso</option>
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
            </tbody>
        </table>
        <div class="flex justify-end items-center mt-3 gap-2 text-sm mr-2" >
            <a href="#" class="text-gray-500">Anterior</a>
            <a href="#" class="bg-green-700 text-white rounded-full px-3 py-1">1</a>
            <a href="#" class="text-gray-500">Siguiente</a>
        </div>

    </div>
</section>

@endsection