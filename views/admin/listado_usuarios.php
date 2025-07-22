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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <style>
        /* Tus estilos personalizados */
        .sidebar { width: 250px; }
        body { min-height: 100vh; }
        .table-auto { width: 100%; border-collapse: collapse; }
        .table-auto th, .table-auto td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .table-auto th { background-color: #edf2f7; font-weight: 600; color: #4a5568; }
        .table-auto tbody tr:hover { background-color: #f7fafc; }
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
                    <tbody> <?php foreach ($users as $user): ?>
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button data-modal-target="edit-user-modal" data-modal-toggle="edit-user-modal"
                                            class="text-blue-600 hover:text-blue-900 mr-3 font-medium edit-user-btn"
                                            type="button"
                                            data-user-id="<?php echo $user['id']; ?>">
                                        Editar
                                    </button>
                                    
                                    <?php if ($user['deleted_at'] === null): ?>
                                        <button data-modal-target="popup-modal" data-modal-toggle="popup-modal"
                                                class="text-red-600 hover:text-red-900 ml-2 font-medium"
                                                type="button"
                                                data-user-id="<?php echo $user['id']; ?>"
                                                data-user-name="<?php echo htmlspecialchars($user['nombre_completo']); ?>">
                                            Desactivar
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo $basePath; ?>/admin/restaurar_usuario?id=<?php echo $user['id']; ?>"
                                           class="text-green-600 hover:text-green-900 ml-2 font-medium"
                                           onclick="return confirm('¿Está seguro de que desea restaurar a <?php echo htmlspecialchars($user['nombre_completo']); ?>?');">
                                            Activar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-700">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        ¿Estás seguro de que quieres Desactivar la cuenta de <span id="modal-user-name" class="font-bold"></span>?
                        <br>
                        (Esto lo marcará como inactivo, sus datos y servicios se mantendrán).
                    </h3>
                    <form id="deleteUserForm" method="POST" action="">
                        <input type="hidden" name="user_id" id="modal-user-id-input">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Sí, desactivar
                        </button>
                        <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-user-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Editar Usuario
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit-user-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4" id="edit-user-modal-body">
                    <p class="text-gray-500 dark:text-gray-400">Cargando formulario...</p>
                </div>
            </div>
        </div>
    </div>
 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script para el modal de eliminación
            const deleteButtons = document.querySelectorAll('button[data-modal-target="popup-modal"]');
            const modalUserIdInput = document.getElementById('modal-user-id-input');
            const modalUserNameSpan = document.getElementById('modal-user-name');
            const deleteUserForm = document.getElementById('deleteUserForm');
            const basePath = '<?php echo $basePath; ?>';

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    const userName = this.dataset.userName;

                    modalUserIdInput.value = userId;
                    modalUserNameSpan.textContent = userName;
                    deleteUserForm.action = `${basePath}/admin/eliminar_usuario`;
                });
            });

            // Script para el modal de edición
            const editButtons = document.querySelectorAll('.edit-user-btn');
            const editUserModalBody = document.getElementById('edit-user-modal-body');

            editButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const userId = this.dataset.userId;
                    const formUrl = `${basePath}/admin/editar_usuario?id=${userId}`;

                    editUserModalBody.innerHTML = '<p class="text-gray-500 dark:text-gray-400">Cargando formulario...</p>';

                    try {
                        const response = await fetch(formUrl);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const formHtml = await response.text();
                        editUserModalBody.innerHTML = formHtml;
                    } catch (error) {
                        console.error('Error al cargar el formulario de edición:', error);
                        editUserModalBody.innerHTML = '<p class="text-red-500 dark:text-red-400">Error al cargar el formulario. Intente de nuevo.</p>';
                    }
                });
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
