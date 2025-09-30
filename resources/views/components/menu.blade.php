<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aula de Inglés')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-purple-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Navegación test</h1>
    </nav>

    <main class="p-6">
        @yield('content')
    </main>

</body>
</html>
