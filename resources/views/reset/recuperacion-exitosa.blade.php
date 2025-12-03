<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña Restablecida | Aula Inglés</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<x-msj-alert />
<body class="bg-gray-100 p-4">
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md p-8 border border-gray-300 rounded-md shadow bg-white">
            <h1 class="flex justify-center text-xl md:text-3xl font-bold text-gray-800">¡Contraseña Restablecida!</h1>
            <p class="text-lg font-light mt-4 mb-4 text-justify">
                La contraseña del usuario <strong>{{ $usuario->nombres }} {{ $usuario->ap_paterno }}</strong> (Matrícula: <strong>{{ $usuario->matricula }}</strong>) ha sido restablecida correctamente.
            </p>
            <p class="text-md font-light mb-6 text-justify">
                La nueva contraseña ahora es la matrícula del usuario.
            </p>
            <a
                class="flex justify-center w-full bg-teal-600 text-white font-medium mt-2 py-3 rounded-lg hover:bg-teal-700 transition duration-200 shadow-md text-center"
                href="{{ route('login') }}"
            >
                Iniciar Sesión
            </a>
        </div>
    </div>
</body>
</html>
