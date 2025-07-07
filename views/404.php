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
</head>
<body class="bg-gray-100 flex flex-col justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-xl text-center">
        <h1 class="text-4xl font-extrabold text-red-700 mb-4">404 - Página no Encontrada</h1>
        <p class="text-xl text-gray-700 mb-6">La URL solicitada no existe en esta aplicación.</p>
        <a href="<?php echo $basePath; ?>/login" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Volver al Inicio de Sesión</a>
    </div>
</body>
</html>