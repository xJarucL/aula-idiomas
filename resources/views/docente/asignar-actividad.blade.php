@extends('components.menu')

@section('title', 'Asignar Actividad | Aula de idiomas')

@section('content')

    <div class="p-2 mt-15 md:ml-20 md:mr-20">
        <section class="flex flex-col md:flex-row justify-between  items-start md:items-center">
            <div>
                <h1 class="text-2xl md:text-4xl font-bold text-black">Asignación de Actividad</h1>

            </div>
            <div>
                <a href="{{ route('docente.lista-actividades') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition">
                    Volver
                </a>
            </div>
        </section>
        {{-- Configuración --}}
        <div class="md:pl-15 md:pr-15">
        <div class="bg-white rounded-2xl shadow-2xs border border-gray-300 mt-5">
            <div class="flex flex-col p-4">
                <h3 class="text-[18px] text-black font-semibold">Configuración de actividad</h3>
                <div class="flex flex-wrap justify-start gap-4">
                    <div class="flex justify-between gap-4 w-full">
                        <div class="w-1/2">
                            <label class="text-gray-500 font-light">Límite de tiempo</label>
                            <input
                                class="p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                type="text" placeholder="Ej: 60 (tiempo en minutos)" required>
                        </div>
                        <div class="w-1/2">
                            <label class="text-gray-500 font-light">Fecha de entrega</label>
                            <input
                                class="p-2 w-full border text-gray-500 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                                type="date" required>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between gap-4 w-full">
                        <div class="w-full md:w-1/2">
                            <label class="text-gray-500 font-light">Grupo:</label>
                            <select
                                class="p-2.5 w-full text-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition">
                                <option value="">Selecciona la opción</option>
                                <option value="IDGS 10mo">IDGS 10mo</option>
                                <option value="Turismo">Turismo</option>
                                <option value="Mecatrónica">Mecatrónica</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 flex items-end">
                            <button type="submit"
                                class="bg-teal-600 text-white px-4 py-2 w-full rounded-lg hover:bg-teal-700 transition shadow">
                                Guardar Asignación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    </div>


@endsection
