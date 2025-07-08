<?php
// views/admin/admin_dashboard.php (ACTUALIZADO)
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
        .sidebar { width: 250px; }
        body { min-height: 100vh; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased flex">

    <?php include __DIR__ . '/admin_dashboard_sidebar.php'; ?>

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