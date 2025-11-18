@extends('components.menu')

@section('title', 'Panel docente | Aula de Idiomas')

@section('content')

    <x-msj-alert />

    <section>
        <div>
            <h1 class="text-4xl font-bold text-black">Panel del Docente</h1>
            <span class="text-gray-500 font-light mt-2">
                Bienvenido/a, Jaruny Guadalupe Cardenas Tirado.
            </span>
        </div>
    </section>
    {{-- Cartas de información --}}
    <section class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-10">
        <x-card-info
            title="Grupos Asignados"
            :count="$gruposCount"
            icon="users"
            color="blue"
            link="{{ route('docente.mis-grupos') }}"
        />

        <x-card-info
            title="Actividades Creadas"
            :count="$actividadesCount"
            icon="book"
            color="purple"
            link="{{ route('docente.lista-actividades') }}"
        />

        <x-card-info
            title="Pendientes de Revisión"
            :count="$actividadesRevisionCount"
            icon="pending"
            color="yellow"
            {{-- link="{{ route('docente.lista-materias') }}" --}}
        />

        <x-card-info
            title="Alumnos"
            :count="$alumnosCount"
            icon="academic"
            color="teal"
        />
    </section>

    {{--  --}}
    <section>

    </section>

@endsection
