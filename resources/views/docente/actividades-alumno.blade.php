@extends('components.menu')

@section('title', 'Actividades del Alumno | Aula de Idiomas')

@section('content')
<div class="max-w-5xl mx-auto mt-6">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">
        Actividades de {{ $alumno->usuario->nombres }} {{ $alumno->usuario->ap_paterno }} en {{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura }} {{ $grupo->año }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($actividades as $item)
            @if($item['estado'] === 'Entregada' && $item['entrega'])
                <a href="{{ route('actividad.respuestas', ['actividad' => $item['actividad']->pk_actividad, 'alumno' => $alumno->pk_alumno]) }}" class="relative block bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer">
            @else
                <div class="relative bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
            @endif

                <div @class([
                    'absolute left-0 top-0 h-full w-1',
                    'bg-yellow-400' => $item['estado'] === 'Pendiente',
                    'bg-green-500'  => $item['estado'] === 'Entregada',
                    'bg-red-500'    => $item['estado'] === 'Caducada',
                ])></div>

                <div class="p-5 ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $item['actividad']->nom_actividad }}</h3>
                    <p class="text-gray-600 text-sm mb-3">{{ $item['actividad']->descripcion }}</p>

                    <div class="flex flex-wrap gap-2 text-xs text-gray-500 mb-3">
                        <span class="px-2 py-1 bg-gray-100 rounded-full">Tipo: {{ strtoupper($item['actividad']->tipo) }}</span>
                        @if($item['entrega'])
                            <p class="text-gray-500 text-xs mt-0.5">
                                Entregada el: <span class="font-medium">{{ $item['entrega']->created_at?->format('d/m/Y') ?? '-' }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <p class="font-medium text-gray-700">
                            Estado:
                            <span @class([
                                'font-semibold',
                                'text-yellow-500' => $item['estado'] === 'Pendiente',
                                'text-green-500'  => $item['estado'] === 'Entregada',
                                'text-red-500'    => $item['estado'] === 'Caducada',
                            ])>{{ $item['estado'] }}</span>
                        </p>
                        @if($item['entrega'])
                            <!-- <p class="text-gray-700">
                                Calificación: <span class="font-semibold">{{ $item['entrega']->calificacion ?? 'N/A' }}</span>
                            </p> -->
                        @endif
                    </div>
                </div>

            @if($item['estado'] === 'Entregada' && $item['entrega'])
                </a>
            @else
                </div>
            @endif
        @empty
            <p class="col-span-2 text-gray-400 text-center">No hay actividades asignadas a este alumno.</p>
        @endforelse
    </div>
</div>
@endsection
