@extends('components.menu')

@section('title', 'Alumno - Editar perfil')

@section('content')
<div class="max-w-4xl mx-auto mt-6 bg-white rounded-lg shadow p-8" x-data="perfil()">
    <x-msj-alert />
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-black">Editar Perfil</h1>
            <span class="text-gray-500 font-light pt-2 block">
                Completa el siguiente formulario para editar su información
            </span>
        </div>
        <a href="{{ route('alumno.perfil') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow">
            Cancelar
        </a>
    </div>

    <form id="form-insertar" data-url="{{ route('alumno.actualizar-perfil') }}" class="w-full gap-6" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col gap-2">
            <div class="flex justify-center mt-2">
                <label for="img_user" class="cursor-pointer">
                    <img
                        :src="preview || '{{ Auth::user()->img_user ? asset('storage/' . Auth::user()->img_user) : asset('img/default.jpg') }}'"
                        class="w-28 h-28 border border-gray-500 rounded-full mb-4 object-cover"
                        alt="Foto de perfil"
                    >
                </label>
                <input type="file" name="img_user" id="img_user" class="hidden" accept="image/*" @change="previewImage($event)" />
            </div>

            <div class="flex justify-center mt-6">
                <button
                    class="bg-teal-600 text-white w-full px-6 py-2 rounded-lg hover:bg-teal-700 shadow cursor-pointer"
                    type="submit">
                    Guardar cambios
                </button>
            </div>
        </div>
    </form>
</div>

<div class="max-w-4xl mx-auto mt-6 bg-white rounded-lg shadow p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-black">Cambiar contraseña</h1>
            <span class="text-gray-500 font-light pt-2 block">¿Desea cambiar su contraseña? Por favor llene el formulario</span>
        </div>
    </div>
    <form class="w-full gap-6" data-url="{{ route('alumno.actualizar-password') }}" action="" method="post">
        @csrf
        <div class="flex flex-col gap-2">
            <label class="text-gray-500 font-light">Contraseña actual:</label>
            <input
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                type="password" name="password_antigua" value="" required
            >
            <label class="text-gray-500 font-ligh">Nueva contraseña:</label>
            <input
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                type="password" name="password_nueva" value="" required
            >
            <label class="text-gray-500 font-light">Confirmar nueva contraseña:</label>
            <input
                class="p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                type="password" name="password_confirmar" value=""
            >
            <div class="flex justify-center mt-6">
                <button
                    class="bg-teal-600 text-white w-full px-6 py-2 rounded-lg hover:bg-teal-700 shadow cursor-pointer"
                    type="submit">
                    Guardar cambios
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function perfil() {
    return {
        preview: null,
        previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    this.preview = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    }
}
</script>
@endsection
