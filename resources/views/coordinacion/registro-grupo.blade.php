@extends('components.menu')

@section('title', 'Crea un grupo')

@section('content')

<section class="flex-1 flex justify-center items-center mt-15">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-3xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-black">Crea un Grupo</h1>
                <span class="text-gray-500 font-light pt-2 block">Complete el siguiente formulario</span>
            </div>
            <button 
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow"
                type="button"
            >
                Cancelar
            </button>
        </div>
        <form class="w-full gap-6" action="" method="post">
            @csrf
            <div class="flex flex-col gap-2">
                <label class="text-gray-500 font-light">Nombre:</label>
                <input 
                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    type="text" name="nombre" required
                >
               
                <label class="text-gray-500 font-light" for="">Docente asignado:</label>
                <select 
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    name="carrera" id="">
                    <option class="text-gray-500 font-light" value="">Seleccione un docente</option>
                    <option value="">Jaruny</option>
                    <option value="">Roberto</option>
                    <option value="">Melissa</option>
                   
                </select>
                      <label class="text-gray-500 font-light">Capacidad de alumnos:</label>
                <input 
                    class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    type="number" name="capacidad_alumnos" required
                >
                <input type="hidden" name="fk_tipo_usuario" value="1">
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