@extends('components.menu')

@section('title', 'Alumno - Detalle')

@section('content')

<div class="max-w-4xl mx-auto mt-2 bg-white rounded-lg shadow p-8">
    <section class="flex flex-col items-center text-center mb-8">
        <img class="w-28 h-28 border border-l-gray-500 rounded-full mb-4 object-cover"
             src="{{ $usuario->img_user ? asset('storage/' . $usuario->img_user) : asset('img/default.jpg') }}"
             alt="foto de perfil">
        <h2 class="text-2xl font-semibold">{{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno }}</h2>
        <p class="text-sm text-gray-600 mb-3 mt-2">Alumno</p>
    </section>

    {{-- Información --}}
    <section class="pt-5 mb-6">
        <div class="border-b border-gray-200 pt-6 mb-4">
            <h3 class="text-2xl font-semibold mb-2">Información de contacto</h3>
        </div>
        <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
            <span class="font-medium mr-12">Matrícula:</span>
            <span class="text-gray-600">{{ $usuario->matricula }}</span>
        </div>
        <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
            <span class="font-medium mr-12">Carrera:</span>
            <span class="text-gray-600">{{ $carrera ?? 'No definida' }}</span>
        </div>
        <div class="flex justify-start border-b border-gray-200 pb-4 mb-4">
            <span class="font-medium mr-12">Promedio:</span>
            <span class="text-gray-600">{{$promedio ?? 'N/A' }}</span>
        </div>
    </section>
</div>

@endsection
