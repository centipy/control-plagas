<?php
// views/fumigador/fumigador_panel.php
require_once __DIR__ . '/../../app/Helpers/Session.php';
Session::start();

$userRole = Session::get('user_role');
$userName = Session::get('user_full_name');

if ($userRole !== 'fumigador') {
    header('Location: /control-plagas/login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Fumigador - Control Plagas</title>
    <link href="/control-plagas/public/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-xl text-center">
            <h1 class="text-4xl font-extrabold text-teal-700 mb-4">
                Bienvenido, Fumigador <?php echo htmlspecialchars($userName); ?>!
            </h1>
            <p class="text-xl text-gray-700 mb-6">
                Consulta tus servicios asignados.
            </p>
            <div class="mb-6">
                <a href="#" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-lg text-lg transition duration-300">
                    Ver Servicios Asignados
                </a>
            </div>
            <a href="/control-plagas/logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Cerrar Sesión
            </a>
        </div>
    </div>
</body>
</html>