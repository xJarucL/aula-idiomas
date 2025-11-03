@extends('components.menu')

@section('title', 'Asignar Actividad | Aula de idiomas')

@section('content')
<div class="flex justify-center mt-10 px-2 md:px-0">
    <div class="w-full max-w-3xl">
        <x-msj-alert />

        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl md:text-4xl font-bold text-black mb-3 md:mb-0">Asignación de Actividad</h1>
            <a href="{{ route('docente.lista-actividades') }}"
               class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition cursor-pointer">
                Volver
            </a>
        </div>

        <form id="form-insertar" data-url="{{ route('docente.asignando-actividad', $actividad->pk_actividad) }}" action="" method="POST">
            @csrf
            <div class="bg-white rounded-2xl shadow-2xs border border-gray-300 p-6 space-y-6">

                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <label class="text-gray-500 text-[17px] font-light md:w-1/3">Fecha de entrega</label>
                    <input type="date" name="fecha_entrega"
                           class="p-2 w-full md:w-2/3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                           required>
                </div>

                <div>
                    <h3 class="text-gray-600 text-[18px] font-semibold mb-2 text-center md:text-left">Seleccione los grupos</h3>
                    <div id="listaGrupos" class="flex flex-wrap justify-center md:justify-start gap-3">
                        @foreach($grupos as $grupo)
                            <button type="button" data-nombre="{{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura }} {{ $grupo->año }}" data-id="{{ $grupo->pk_grupo }}"
                                    class="grupoBtn border border-gray-300 px-3 py-2 rounded-lg hover:bg-teal-500 hover:text-white transition">
                                {{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura }} {{ $grupo->año }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-gray-600 text-[18px] font-semibold mb-2 text-center md:text-left">Grupos asignados</h3>
                    <div id="asignadosContainer" class="flex flex-wrap justify-center md:justify-start gap-3 min-h-[60px] border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <p class="text-gray-400 text-sm">No hay grupos asignados aún.</p>
                    </div>
                </div>

                <div class="flex justify-center md:justify-end">
                    <button type="submit"
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg shadow hover:bg-teal-700 transition">
                        Asignar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const grupoBtns = document.querySelectorAll('.grupoBtn');
    const asignadosContainer = document.getElementById('asignadosContainer');

    grupoBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const nombre = btn.dataset.nombre;
            const id = btn.dataset.id;

            const mensaje = asignadosContainer.querySelector('p');
            if(mensaje) mensaje.remove();

            if (document.querySelector(`#asignadosContainer input[value="${id}"]`)) return;

            const item = document.createElement('div');
            item.className = 'flex items-center gap-2 border border-gray-300 px-3 py-2 rounded-lg bg-white';
            item.innerHTML = `
                <span class="text-slate-700">${nombre}</span>
                <input type="hidden" name="grupos[]" value="${id}">
                <button type="button" class="text-gray-400 hover:text-red-500 transition font-semibold">&times;</button>
            `;

            item.querySelector('button').addEventListener('click', () => item.remove());

            asignadosContainer.appendChild(item);
        });
    });
});
</script>
@endsection
