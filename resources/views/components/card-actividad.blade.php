@props(['nombreActividad', 'tipo', 'fecha', 'iconoA', 'iconoEntregable', 'color1', 'color2', 'link'])

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
@endphp

<div data-card-actividad class="p-4 mb-3 border border-gray-200 rounded-lg shadow-sm bg-white">
    <div class="flex justify-between items-center">
        <div class="flex flex-row w-full gap-3">
            <div class="flex justify-center items-center">
                <div class="flex justify-center items-center w-10 h-10 {{ $colorClass[$color1] ?? $colorClass['colorUT'] }} rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="{{ $icono[$iconoA] ?? $icono['book'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="flex flex-col justify-center">
                <h3 data-nombre-actividad class="text-teal-700 font-bold text-lg">{{ $nombreActividad }}</h3>
                <p class="text-gray-700 text-sm">
                    Tipo: <span data-tipo-actividad class="text-teal-700">{{ $tipo }}</span>
                </p>
                <p class="text-gray-700 text-sm">
                    Fecha de entrega: <span class="text-teal-700">{{ $fecha }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex justify-center items-center {{ $colorClass[$color2] ?? $colorClass['red'] }} w-10 h-10 rounded-full">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="{{ $icono[$iconoEntregable] ?? $icono['document'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <a href="{{ $link }}"
               class="bg-teal-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-teal-800 transition">
               Detalles
            </a>
        </div>
    </div>
</div>
