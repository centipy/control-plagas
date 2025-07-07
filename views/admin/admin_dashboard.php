<?php
// views/admin/admin_dashboard.php
require_once __DIR__ . '/../../app/Helpers/Session.php';
Session::start();

$userRole = Session::get('user_role');
$userName = Session::get('user_full_name');
$basePath = '/control-plagas';

if ($userRole !== 'administrador') {
    header('Location: ' . $basePath . '/login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Control Plagas</title>
    <link href="<?php echo $basePath; ?>/public/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos personalizados para la sidebar */
        .sidebar {
            width: 250px; /* Ancho fijo de la sidebar */
        }
        /* Ajuste para que la página completa tenga min-h-screen */
        body {
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased flex">
    <div class="sidebar bg-indigo-800 text-white flex flex-col p-4 shadow-lg h-screen">
        <div class="logo text-2xl font-bold mb-8 text-center">
            <i class="fas fa-user-shield text-indigo-300 mr-2"></i> Administrador
        </div>
        <nav class="flex flex-col space-y-3">
            <a href="<?php echo $basePath; ?>/admin/aniadir_cliente" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-user-plus mr-3"></i> Añadir Nuevo Cliente
            </a>
            <a href="<?php echo $basePath; ?>/admin/ver_clientes_registrados" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-users mr-3"></i> Ver Clientes Registrados
            </a>
            <a href="<?php echo $basePath; ?>/admin/nuevo_servicio" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-concierge-bell mr-3"></i> Registrar nuevo servicio
            </a>
            <a href="<?php echo $basePath; ?>/admin/ver_servicios" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-tasks mr-3"></i> Ver Servicios
            </a>
            <a href="<?php echo $basePath; ?>/admin/listado_usuarios" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-users mr-3"></i> Listado de Usuarios
            </a>
            <a href="<?php echo $basePath; ?>/admin/registrar" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-user-plus mr-3"></i> Registrar Nuevo Usuario
            </a>
            <a href="<?php echo $basePath; ?>/admin/aprobar_acceso" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-user-plus mr-3"></i> Aprobar Acceso
            </a>
            <a href="<?php echo $basePath; ?>/admin/servicios_prestados" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-clipboard-list mr-3"></i> Servicios Que Se Prestan
            </a>
            <a href="<?php echo $basePath; ?>/admin/localidades" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-map-marker-alt mr-3"></i> Localidades
            </a>
        </nav>
        <div class="mt-auto">
            <a href="<?php echo $basePath; ?>/logout" class="flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors duration-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Salir
            </a>
        </div>
    </div>

    <div class="flex-1 p-8">
        <h1 class="text-4xl font-extrabold text-indigo-700 mb-4">
            Bienvenido, Administrador <?php echo htmlspecialchars($userName); ?>!
        </h1>
        <p class="text-xl text-gray-700 mb-6">
            Este es el panel de control para administradores. Utiliza la barra lateral para navegar.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Resumen de Clientes</h3>
                <p class="text-gray-600">Total de clientes: 125</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Servicios Pendientes</h3>
                <p class="text-gray-600">Servicios asignados: 15</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Últimos Reportes</h3>
                <p class="text-gray-600">Ver últimos 5 reportes.</p>
            </div>
        </div>
    </div>
</body>
</html>