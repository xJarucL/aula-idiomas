@props([
    'nombreActividad',
    'tipo',
    'fecha',
    'iconoA',
    'iconoEntregable',
    'color1',
    'color2',
    'link',
    'dataFiltro' => null
])

@php
    $nombreActividad = $nombreActividad ?? 'Sin título de actividad';
    $tipo = $tipo ?? 'Sin tipo';
    $fecha = $fecha ?? 'Sin fecha';
    $iconoA = $iconoA ?? 'book';
    $iconoEntregable = $iconoEntregable ?? 'document';
    $color1 = $color1 ?? 'colorUT';
    $color2 = $color2 ?? 'blue';
    $link = $link ?? '#';

    $colorClass = [
        'colorUT' => 'bg-teal-600 text-white',
        'red' => 'bg-red-300 text-red-700',
        'green' => 'bg-green-300 text-green-700',
        'orange' => 'bg-orange-300 text-orange-700',
        'purple' => 'bg-purple-300 text-purple-700',
        'blue' => 'bg-blue-300 text-blue-700',
        'yellow' => 'bg-yellow-300 text-yellow-700',
        'sky' => 'bg-sky-300 text-sky-700'
    ];

    $icono = [
        'book' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253',
        'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586l6 6V19a2 2 0 01-2 2z',
        'pending' => 'M12 2a10 10 0 100 20 10 10 0 000-20zm0 5v5l3 2',
        'check' => 'M5 13l4 4L19 7',
        'close' => 'M6 18L18 6M6 6l12 12'
    ];

    $estadoClass = match($dataFiltro) {
        'entregadas' => 'hover:border-green-400 hover:shadow-green-100',
        'pendientes' => 'hover:border-orange-400 hover:shadow-orange-100',
        'no_entregadas' => 'opacity-80 hover:opacity-100 hover:border-red-400 hover:shadow-red-100',
        default => ''
    };
@endphp

<div data-card-actividad data-filtro="{{ $dataFiltro }}"
     class="p-5 mb-4 border border-gray-200 rounded-2xl shadow-sm bg-white transition transform hover:-translate-y-1 hover:shadow-lg {{ $estadoClass }}">

    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 w-full">
            <div class="flex justify-center items-center w-12 h-12 {{ $colorClass[$color1] ?? $colorClass['colorUT'] }} rounded-xl shadow-sm">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="{{ $icono[$iconoA] ?? $icono['book'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <div class="flex flex-col">
                <h3 data-nombre-actividad class="text-teal-700 font-bold text-lg">{{ $nombreActividad }}</h3>
                <p class="text-gray-600 text-sm">
                    Tipo: <span data-tipo-actividad class="text-teal-700 font-medium">{{ ucfirst($tipo) }}</span>
                </p>
                <p class="text-gray-500 text-xs">
                    Fecha de entrega: <span class="text-teal-600">{{ $fecha }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex justify-center items-center {{ $colorClass[$color2] ?? $colorClass['red'] }} w-10 h-10 rounded-full shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="{{ $icono[$iconoEntregable] ?? $icono['document'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <a href="{{ $link }}"
               class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition-all shadow-md hover:shadow-lg">
               Ver Detalles
            </a>
        </div>
    </div>
</div>
