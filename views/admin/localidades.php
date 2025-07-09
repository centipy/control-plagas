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