<?php
// views/admin/listado_usuarios.php

// Incluir el helper de sesión para verificar autenticación y rol
require_once __DIR__ . '/../../app/Helpers/Session.php';
Session::start();

// Las variables $userRole, $userName, $basePath, $users, $errorMessage
// son pasadas desde el AdminController que incluye esta vista.
// No necesitamos require_once 'User.php' ni 'database.php' aquí.

// Verificación de rol: Solo administradores pueden ver esta página
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
    <title>Listado de Usuarios - Control Plagas</title>
    <link href="<?php echo $basePath; ?>/public/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos personalizados para la sidebar */
        .sidebar {
            width: 250px; /* Ancho fijo de la sidebar */
        }
        /* Asegura que la página completa tenga min-h-screen */
        body {
            min-height: 100vh;
        }
        /* Estilo para tablas */
        .table-auto {
            width: 100%;
            border-collapse: collapse;
        }
        .table-auto th, .table-auto td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0; /* gray-200 */
        }
        .table-auto th {
            background-color: #edf2f7; /* gray-100 */
            font-weight: 600; /* semibold */
            color: #4a5568; /* gray-700 */
        }
        .table-auto tbody tr:hover {
            background-color: #f7fafc; /* gray-50 */
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased flex">

    <?php include __DIR__ . '/admin_dashboard_sidebar.php'; ?>

    <div class="flex-1 p-8">
        <h1 class="text-4xl font-extrabold text-indigo-700 mb-6">Listado de Usuarios</h1>

        <?php if (isset($errorMessage)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error:</strong>
                <span class="block sm:inline"> <?php echo htmlspecialchars($errorMessage); ?></span>
            </div>
        <?php endif; ?>

        <?php if (empty($users)): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Información:</strong>
                <span class="block sm:inline"> No hay usuarios registrados en la base de datos o todos están eliminados lógicamente.</span>
            </div>
        <?php else: ?>
            <div class="bg-white p-6 rounded-lg shadow-xl overflow-x-auto">
                <table class="table-auto min-w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['nombre_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($user['nombre_completo']); ?></td>
                                <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['telefono'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($user['rol'])); ?></td>
                                <td>
                                    <?php if ($user['deleted_at'] === null): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Eliminado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo $basePath; ?>/admin/editar_usuario?id=<?php echo $user['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                    <?php if ($user['deleted_at'] === null): ?>
                                        <a href="<?php echo $basePath; ?>/admin/eliminar_usuario?id=<?php echo $user['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Está seguro de que desea eliminar lógicamente este usuario?');">Eliminar</a>
                                    <?php else: ?>
                                        <a href="<?php echo $basePath; ?>/admin/restaurar_usuario?id=<?php echo $user['id']; ?>" class="text-green-600 hover:text-green-900" onclick="return confirm('¿Está seguro de que desea restaurar este usuario?');">Restaurar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>