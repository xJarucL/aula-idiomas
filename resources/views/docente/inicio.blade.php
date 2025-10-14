@extends('components.menu')

@section('title', 'Panel docente | Aula de Idiomas')

@section('content')

    <section>
        <div>
            <h1 class="text-4xl font-bold text-black">Panel del Docente</h1>
            <span class="text-gray-500 font-light mt-2">
                Bienvenido/a, Jaruny Guadalupe Cardenas Tirado.
            </span>
        </div>
    </section>
    {{-- Cartas de información --}}
    <section class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-10">
        <x-card-info
            title="Actividades"
            {{-- :count="$gruposCount" --}}
            icon="activity"
            color="blue"
            link="{{ route('docente.lista-actividades') }}"
        />

        <x-card-info
            title="Actividades pendientes"
            {{-- :count="$alumnosCount" --}}
            icon="pending"
            color="purple"
            {{-- link="{{ route('docente.lista-alumnos') }}" --}}
        />

        <x-card-info
            title="Actividades completadas"
            {{-- :count="$materiasCount" --}}
            icon="completed"
            color="teal"
            {{-- link="{{ route('docente.lista-materias') }}" --}}
        />

        <x-card-info
            title="Actividades totales"
            {{-- :count="$actividadesCount" --}}
            icon="total"
            color="yellow"
            {{-- link="{{ route('docente.lista-actividades') }}" --}}
        />
    </section>

@endsection
