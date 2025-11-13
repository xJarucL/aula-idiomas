@extends('components.menu')

@section('title', 'Crea un grupo')

@section('content')

<section class="flex-1 flex justify-center items-center mt-12">
    <x-msj-alert />
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-3xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-black">Crea un Grupo</h1>
                <span class="text-gray-500 font-light pt-2 block">Complete el siguiente formulario</span>
            </div>
            <a href="{{route('coordinacion.lista-grupos')}}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow">
                Cancelar
            </a>
        </div>
        <form id="form-insertar" data-url="{{ route('coordinacion.guardar-grupo') }}" class="w-full gap-6" action="" method="post">
            @csrf
            <div class="flex flex-col gap-2">
                <label class="text-gray-500 font-light">Nombre del grupo:</label>
                <select
                class="p-2 pr-10 border border-gray-300 rounded-lg w-full sm:w-full focus:outline-none focus:ring-2 focus:ring-teal-500 transition text-base appearance-none"
                    name="nombre" id="">
                    <option value="">Seleccione un nombre</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>

                </select>

                <label class="text-gray-500 font-light" for="">Carrera:</label>
                <select
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    name="fk_carrera" id="">
                    <option value="">Seleccione una carrera</option>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->pk_carrera }}">
                            {{ $carrera->nombre}} - {{ $carrera->abreviatura}}
                        </option>
                    @endforeach
                </select>
                <label class="text-gray-500 font-light">Cuatrimestre:</label>
                <select
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    name="fk_cuatrimestre" id="">
                    <option value="">Seleccione un cuatrimestre</option>
                    @foreach ($cuatrimestres as $cuatrimestre)
                        <option value="{{ $cuatrimestre->pk_cuatrimestre }}">
                            {{ $cuatrimestre->num_cuatri}}
                        </option>
                    @endforeach
                </select>
                <label class="text-gray-500 font-light">Materia:</label>
                <select
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    name="fk_materia" id="">
                    <option value="">Seleccione una materia</option>
                    @foreach ($materias as $materia)
                        <option value="{{ $materia->pk_materia }}">
                            {{ $materia->nombre}}
                        </option>
                    @endforeach
                </select>
                <label class="text-gray-500 font-light">Asignar docente:</label>
                <select
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    name="fk_docente" id="">
                    <option value="">Seleccione un docente</option>
                    @foreach ($docentes as $docente)
                        <option value="{{ $docente->pk_usuario }}">
                            {{ $docente->nombres}} {{ $docente->ap_paterno}} {{ $docente->ap_materno ?? ''}}
                        </option>
                    @endforeach
                </select>
                <label class="text-gray-500 font-light">Año:</label>
                <input
                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    type="text" name="año" required maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)"
                >
                <div class="flex justify-center mt-6">
                    <button
                        class="bg-teal-600 text-white w-full px-6 py-2 rounded-lg hover:bg-teal-700 shadow"
                        type="submit"
                    >
                        Registrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection
