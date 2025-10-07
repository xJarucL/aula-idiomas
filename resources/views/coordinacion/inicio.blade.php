@extends('components.menu')

@section('title', 'Coordinación')

@section('content')

<section class="flex flex-col md:flex-row justify-between m-2 items-start md:items-center">
    <div class="">
        <h1 class="text-4xl font-bold text-black">Panel del Coordinador</h1>
        <span class="text-gray-500 font-light mt-2">Bienvenido/a,  {{ Auth::user()->nombres }} {{ Auth::user()->ap_paterno }} {{ Auth::user()->ap_materno }}.</span>
    </div>
    <div class="flex md:flex-row items-center justify-center gap-2 mt-4 md:mt-0 w-full md:w-auto">
        <a href="{{ route('coordinacion.registro-docente') }}" class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow">Registrar docentes</a>
        <a href="{{ route('coordinacion.registro-alumno') }}" class="bg-teal-400 text-white m-3 p-2 rounded-lg hover:bg-teal-600 shadow">Registrar alumnos</a>
        <a href="{{ route('coordinacion.registro-grupo') }}" class="bg-teal-100 text-gray-500 m-3 p-2 rounded-lg hover:bg-teal-500 hover:text-white shadow">Crear grupos</a>
    </div>
</section>

{{-- cartas informaticas --}}
<section class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
    <x-card-info 
        title="Grupos" 
        count="12" 
        icon="users" 
        color="blue" 
    />

    <x-card-info
        title="Docentes"
        count="12"
        icon="user"
        color="green"
    />

    <x-card-info
        title="Alumnos"
        count="12"
        icon="book"
        color="teal"
    />

    <x-card-info
        title="Cursos"
        count="8"
        icon="document"
        color="gray"
    />
</section>
{{-- sections resumen --}}
<section class="grid grid-cols-7 grid-rows-1 gap-4">
    {{-- Infor de actividades --}}
    <div class="col-span-5 row-span-2 row-start-1 mt-7 mr-8">
        <h1 class="text-2xl font-bold text-black mb-3">Información de Actividades</h1>
        <div class="bg-white h-[350px] rounded-xl shadow-sm">

        </div>
    </div>
    {{-- Acceso rapido --}}
    <div class="col-span-2 col-start-6 row-start-1 mt-7">
        <h1 class="text-2xl font-bold text-black mb-3">Accesos Rapidos</h1>
        <a href="{{ route('coordinacion.lista-docente') }}" class="bg-white h-[90px] flex justify-between rounded-xl shadow-sm">
            <div class="p-3">
                <h1 class="text-[20px] font-bold mt-1">Gestión de Docente</h1>
                <h4 class="text-[15px] font-light text-gray-400">Registra, edita y actualiza datos de docente</h4>
            </div>
            <div class="flex justify-center items-center p-3 mr-2">
                <div >
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#2B877A" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="18,12 30,24 18,36"/>
                    </svg>
                </div>
            </div>    
        </a>
         <a href="{{ route('coordinacion.lista-alumnos') }}" class="bg-white h-[90px] flex justify-between rounded-xl shadow-sm mt-9">
            <div class="p-3">
                <h1 class="text-[20px] font-bold mt-1">Gestión de Alumnos</h1>
                <h4 class="text-[15px] font-light text-gray-400">Registra, edita y actualiza datos de alumnos</h4>
            </div>
            <div class="flex justify-center items-center p-3 mr-2">
                <div>
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#2B877A" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="18,12 30,24 18,36"/>
                    </svg>
                </div>
            </div>
        </a>
         <a href="{{ route('coordinacion.lista-grupos') }}" class="bg-white h-[90px] flex justify-between rounded-xl shadow-sm mt-9">
            <div class="p-3">
                <h1 class="text-[20px] font-bold mt-1">Gestión de Grupos</h1>
                <h4 class="text-[15px] font-light text-gray-400">Registra, edita y actualiza datos de grupos</h4>
            </div>
            <div class="flex justify-center items-center p-3 mr-2">
                <div >
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#2B877A" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="18,12 30,24 18,36"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>
</section>

@endsection
