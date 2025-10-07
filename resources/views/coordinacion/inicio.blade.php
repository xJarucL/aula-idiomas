@extends('components.menu')

@section('title', 'Coordinación')

@section('content')

<section class="flex-1 flex justify-between m-2">
    <div class="">
        <h1 class="text-4xl font-bold text-black">Panel del Coordinador</h1>
        <span class="text-gray-500 font-light mt-2">Bienvenido/a,  {{ Auth::user()->nombres }} {{ Auth::user()->ap_paterno }} {{ Auth::user()->ap_materno }}.</span>
    </div>
    <div class="flex items-center justify-center gap-2">
        <a href="{{route('coordinacion.lista-docente')}}" class="bg-teal-600 text-white m-3 p-2 rounded-lg hover:bg-teal-700 shadow">Gestionar docentes</a>
        <a href="{{ route('coordinacion.lista-alumnos') }}" class="bg-teal-200 text-white m-3 p-2 rounded-lg hover:bg-teal-400 shadow">Gestionar alumnos</a>
        <a href="#" class="bg-teal-100 text-gray-500 m-3 p-2 rounded-lg hover:bg-teal-300 hover:text-white shadow">Gestionar grupos</a>
    </div>
</section>
<section class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-10">
    {{-- cartas informaticas --}}
    <x-card-info
        title="Grupos"
        :count="$gruposCount"
        icon="users"
        color="blue"
        link="{{ route('coordinacion.lista-grupos') }}"
    />

    <x-card-info
        title="Docentes"
        :count="$docentesCount"
        icon="user"
        color="green"
        link="{{ route('coordinacion.lista-docente') }}"
    />

    <x-card-info
        title="Alumnos"
        :count="$alumnosCount"
        icon="book"
        color="teal"
        link="{{ route('coordinacion.lista-alumnos') }}"
    />

    <!-- <x-card-info
        title="Cursos"
        count="8"
        icon="document"
        color="gray"
    /> -->
</section>
{{-- sections resumen --}}
<section class="flex-1 flex justify-between mt-10">
    <div class="bg-white p-6 rounded-lg shadow-md w-2/4">
        <h2 class="text-2xl font-bold text-black">Resumen</h2>
        <span class="text-gray-500 font-light mt-2">Información general</span>
    </div>
    <div class="bg-white w-1/4">
        <a href="#" class="bg-white text-gray-500 m-3 p-2 rounded-lg hover:bg-teal-700 shadow">Ver más</a>
    </div>
</section>

@endsection
