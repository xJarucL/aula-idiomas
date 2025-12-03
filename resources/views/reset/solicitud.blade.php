<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperar Contraseña | Aula Ingles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<x-msj-alert />
<body class="bg-gray-100 p-4">
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md p-8 border border-gray-300 rounded-md shadow bg-white">
            <h1 class="flex justify-center text-xl md:text-3xl font-bold text-gray-800">Solicitud enviada</h1>
            <p class="text-xl font-light mt-2 mb-2 text-justify">Se ha enviado tu solicitud al coordinador. Se te informara cuando tu contraseña ya se haya restablecido.</p>
            <h3 class="flex justify-center text-xl md:text-3xl font-bold text-gray-800">¡Este Atento!</h3>
            <a
                class="flex justify-center text-teal-800 mt-3"
                href="{{ route('login') }}">
                Volver e Iniciar sesión
            </a>
        </div>
    </div>
</body>
