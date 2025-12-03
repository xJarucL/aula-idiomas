<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Soliciitud enviada | Aula Ingles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<x-msj-alert />
<body class="bg-gray-100 p-4">
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md p-8 border border-gray-300 rounded-md shadow bg-white">
            <h1 class="flex justify-center text-xl md:text-3xl font-bold text-teal-700">Recuperar contraseña</h1>
            <p class="text-[10px] sm:text-base font-light mt-2 mb-2 text-justify">Para recuperar el acceso a tu cuenta, por favor ingresa tu matrícula en el campo correspondiente.</p>
            <p></p>
            <form action="{{ route('recuperar.enviar') }}" method="POST">
                @csrf
                <input
                    class="bg-white w-full mt-1 px-4 py-3 border border-gray-300 my-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus-border-transparent transition"
                    type="text" inputmode="numeric" pattern="[0-9]*" id="matricula" name="matricula"
                    placeholder="Matrícula" required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >
                <input class="w-full bg-teal-600 text-white font-medium mt-1 py-3 rounded-lg hover:bg-teal-700 transition duration-200 shadow-md" type="submit" value="Enviar petición">
            </form>
            <a
                class="flex justify-center text-teal-800 mt-3"
                href="{{ route('login') }}">
                Volver e Iniciar Sesión
            </a>
        </div>

    </div>
</body>
</html>
