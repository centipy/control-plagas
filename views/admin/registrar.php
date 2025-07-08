<?php
// views/admin/registrar.php

require_once __DIR__ . '/../../app/Helpers/Session.php';
Session::start();

$userRole = Session::get('user_role');
$userName = Session::get('user_full_name');
$basePath = '/control-plagas';
$currentUri = '/admin/registrar'; // Definir la URI actual para la sidebar

// Verificar que solo un administrador pueda acceder a esta página
if ($userRole !== 'administrador') {
    header('Location: ' . $basePath . '/login');
    exit();
}

// Mensajes de éxito/error (para mostrar después de un POST)
$message = Session::get('form_message');
$messageType = Session::get('form_message_type');
Session::set('form_message', null); // Limpiar después de mostrar
Session::set('form_message_type', null);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Usuario - Control Plagas</title>
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
        <h1 class="text-4xl font-extrabold text-indigo-700 mb-6">Registrar Nuevo Usuario</h1>

        <?php if ($message): ?>
            <div class="p-4 mb-4 rounded-lg
                <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>
                border <?php echo $messageType === 'success' ? 'border-green-400' : 'border-red-400'; ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white p-8 rounded-lg shadow-xl">
            <form action="<?php echo $basePath; ?>/admin/registrar" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nombre_usuario" class="block text-gray-700 text-sm font-bold mb-2">Nombre de Usuario:</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           value="<?php echo htmlspecialchars($oldInput['nombre_usuario'] ?? ''); ?>" required>
                </div>

                <div>
                    <label for="nombre_completo" class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo:</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           value="<?php echo htmlspecialchars($oldInput['nombre_completo'] ?? ''); ?>" required>
                </div>

                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           value="<?php echo htmlspecialchars($oldInput['email'] ?? ''); ?>">
                </div>

                <div>
                    <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           value="<?php echo htmlspecialchars($oldInput['telefono'] ?? ''); ?>">
                </div>

                <div>
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div>
                    <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="rol" class="block text-gray-700 text-sm font-bold mb-2">Rol:</label>
                    <select id="rol" name="rol" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Seleccione un Rol</option>
                        <option value="administrador" <?php echo (isset($oldInput['rol']) && $oldInput['rol'] === 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="asesor" <?php echo (isset($oldInput['rol']) && $oldInput['rol'] === 'asesor') ? 'selected' : ''; ?>>Asesor</option>
                        <option value="fumigador" <?php echo (isset($oldInput['rol']) && $oldInput['rol'] === 'fumigador') ? 'selected' : ''; ?>>Fumigador</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex justify-end mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-200">
                        Registrar Usuario
                    </button>
            </form>
        </div>
    </div>
</body>
</html>