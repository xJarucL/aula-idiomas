@props(['nombreActividad', 'tipo', 'fecha', 'iconoA', 'iconoEntregable', 'color1', 'color2' ,'link'])

@php
    $nombreActividad = $nombreActividad ?? 'Sin titulo de actividad';
    $tipo = $tipo ?? 'Sin tipo';
    $fecha = $fecha ?? 'Sin fecha de actividad';
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
        'book' =>
            'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        'document' =>
            'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'academic' =>
            'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
        'question' => 'M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z',
        'completed' => 'M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'pending' => 'M12 2a10 10 0 100 20 10 10 0 000-20zm0 5v5l3 2',
        'closed' => 'm15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'addActivity' =>'M18 9V4a1 1 0 0 0-1-1H8.914a1 1 0 0 0-.707.293L4.293 7.207A1 1 0 0 0 4 7.914V20a1 1 0 0 0 1 1h4M9 3v4a1 1 0 0 1-1 1H4m11 6v4m-2-2h4m3 0a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z',
        'oral'=>'M19 9v3a5.006 5.006 0 0 1-5 5h-4a5.006 5.006 0 0 1-5-5V9m7 9v3m-3 0h6M11 3h2a3 3 0 0 1 3 3v5a3 3 0 0 1-3 3h-2a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3Z',
        'auditory'=>'M20 16v-4a8 8 0 1 0-16 0v4m16 0v2a2 2 0 0 1-2 2h-2v-6h2a2 2 0 0 1 2 2ZM4 16v2a2 2 0 0 0 2 2h2v-6H6a2 2 0 0 0-2 2Z'
    ];



@endphp

<div class="p-4 mb-3 border border-gray-200 rounded-lg shadow-sm ">
    <div class="flex justify-between items-center">
        <div class="flex flex-row w-full gap-3">
            <div class="flex justify-center items-center mr-3">
                <div
                    class="flex justify-center items-center w-8 h-8 sm:w-13 sm:h-13 {{$colorClass[$color1] ?? $colorClass[colorUT]}} rounded-lg sm:rounded-2xl">
                    <svg class="w-6 h-6 sm:w-10 sm:h-10" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="{{$icono[$iconoA] ?? $icono[book]}}" />
                    </svg>

                </div>
            </div>
            {{-- TITULOCION --}}
            <div class="flex flex-col w-full max-w-3/4 gap-3">
                <h3 class="text-sm sm:text-xl text-teal-700 font-bold text-justify">{{$nombreActividad }}</h3>
                <div class="flex flex-row gap-3">
                    <h4 class="text-gray-700 text-[10px] sm:text-lg">Tipo de actividad: <span
                            class="text-teal-700 text-[10px] sm:text-lg">{{$tipo}}</span></h4>
                    <h4 class="text-gray-700 text-[10px] sm:text-lg">Fecha de entrega: <span
                            class="text-teal-700 text-[10px] sm:text-lg">{{$fecha}}</span></h4>
                </div>
            </div>
        </div>
        {{-- BOTON --}}
        <div class="flex flex-row justify-center items-center gap-2 sm:gap-6">
            {{-- ICONO --}}
            <div class="flex justify-center items-center {{$colorClass[$color2] ?? $colorClass[red]}}  w-8 h-8 sm:w-13 sm:h-13 rounded-full">
                <svg class="w-6 h-6 sm:w-10 sm:h-10" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="{{$icono[$iconoEntregable] ?? $icono[book]}}" />
                </svg>
            </div>

            {{-- ESCRITORIO --}}
            <a href="{{$link}}"
                class="bg-teal-600 text-sm sm:text-lg p-1.5 sm:p-2.5 hidden sm:inline-flex text-white hover:bg-teal-800 rounded-lg">Responder
            </a>

            {{-- MOVIL --}}
            <a href="{{$link}}"
                class="bg-teal-600 text-sm sm:text-lg p-1.5 sm:p-2.5 sm:hidden text-white hover:bg-teal-800 rounded-lg">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 5V4a1 1 0 0 0-1-1H8.914a1 1 0 0 0-.707.293L4.293 7.207A1 1 0 0 0 4 7.914V20a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-5M9 3v4a1 1 0 0 1-1 1H4m11.383.772 2.745 2.746m1.215-3.906a2.089 2.089 0 0 1 0 2.953l-6.65 6.646L9 17.95l.739-3.692 6.646-6.646a2.087 2.087 0 0 1 2.958 0Z" />
                </svg>
            </a>
        </div>
    </div>
</div>
