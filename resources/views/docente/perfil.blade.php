@extends('components.menu')

@section('title', 'Docente - Perfil')

@section('content')

<div class="max-w-4xl mx-auto mt-2 bg-white rounded-lg shadow p-8">
    <x-msj-alert />

    <section class="flex flex-col items-center text-center mb-8">
        <img
            class="w-28 h-28 border border-l-gray-500 rounded-full mb-4 object-cover"
            src="{{ Auth::user()->img_user ? asset('storage/' . Auth::user()->img_user) : asset('img/default.jpg') }}"
            alt="foto de perfil"
        >
        <h2 class="text-2xl font-semibold">{{ Auth::user()->nombres }} {{ Auth::user()->ap_paterno }} {{ Auth::user()->ap_materno }}</h2>
        <p class="text-sm text-gray-600 mb-3 mt-2">Docente del departamento de idiomas</p>
        <a
            class="bg-teal-600 px-3 py-2 text-white hover:bg-teal-800 transition rounded-xl shadow"
            href="{{ route('docente.editar') }}">Editar perfil
        </a>
    </section>

    <section class="pt-5 mb-6">
        <div class="border-b border-gray-200 pt-6 mb-4">
            <h3 class="text-2xl font-semibold mb-2">Información de contacto</h3>
        </div>
        <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
            <span class="font-medium mr-12">Correo Electronico:</span>
            <span class="text-gray-600">{{ Auth::user()->email }}</span>
        </div>
    </section>

    <section class="mt-15">
        <h3 class="text-2xl font-semibold mb-3">Información de trabajo</h3>
        <div class="flex flex-col md:flex-row justify-around gap-4">
            <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs text-start shadow">
                <p class="text-gray-600">Grupos asignados</p>
                <p class="text-4xl font-semibold mt-2">{{ $grupos_asignados ?? 0 }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs text-start shadow">
                <p class="text-gray-600">Estudiantes bajo enseñanza</p>
                <p class="text-4xl font-semibold mt-2">{{ $estudiantes ?? 0 }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-2 w-3xs text-start shadow">
                <p class="text-gray-600">Actividades creadas</p>
                <p class="text-4xl font-semibold mt-2">{{ $actividades ?? 0 }}</p>
            </div>
        </div>
    </section>

    <div class="pt-6 mt-3">
        <h3 class="text-2xl font-semibold mb-4">Historia de actividades</h3>
        <div class="bg-gray-100 rounded-md h-60 shadow">
            {{-- Contenido - agregar después --}}
        </div>
    </div>
</div>

@endsection
