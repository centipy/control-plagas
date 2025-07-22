<?php
// views/404.php
// Usa $basePath que viene del Router para los enlaces
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no Encontrada</title>
    <link href="<?php echo $basePath; ?>/public/css/output.css" rel="stylesheet">
    <style>
        /* Puedes añadir aquí estilos específicos si los de Tailwind no son suficientes */
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased flex items-center justify-center min-h-screen">
    <main class="text-center p-6"> <p class="text-base font-semibold text-indigo-600">404</p>
        <h1 class="mt-4 text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl">Página no encontrada</h1>
        <p class="mt-6 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">Lo sentimos, no hemos podido encontrar la página que busca.</p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="<?php echo $basePath; ?>/login" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Volver al Inicio de Sesión</a>
            <a href="#" class="text-sm font-semibold text-gray-900">Contactar con el servicio de asistencia<span aria-hidden="true">&rarr;</span></a>
        </div>
    </main>
</body>
</html>