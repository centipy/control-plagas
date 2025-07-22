<?php
// views/admin/editar_usuario_form.php
// Esta vista es para ser incluida vía AJAX en el modal de edición.
// Asume que las variables $userToEdit, $basePath, $message, $messageType ya están disponibles (pasadas por extract en AdminController::editUser)

// Recuperar datos enviados previamente si hubo un error de validación en la actualización
$oldInput = Session::get('old_input_edit', []); // Usar una clave diferente para editar
Session::set('old_input_edit', null); // Limpiar después de obtener

// Verificar si $userToEdit está disponible (debería venir del controlador)
if (!isset($userToEdit) || !$userToEdit) {
    // Si no hay usuario para editar, mostrar un mensaje de error o formulario vacío con advertencia
    ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error:</strong>
        <span class="block sm:inline"> Usuario no encontrado o ID no válido para edición.</span>
    </div>
    <?php
    exit(); // Detener la ejecución si no hay usuario válido
}
?>

<?php if ($message): ?>
    <div class="p-4 mb-4 rounded-lg
        <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>
        border <?php echo $messageType === 'success' ? 'border-green-400' : 'border-red-400'; ?>" role="alert">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<form action="<?php echo $basePath; ?>/admin/actualizar_usuario" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userToEdit['id']); ?>">
    <input type="hidden" name="_method" value="PUT"> <div>
        <label for="edit_nombre_usuario" class="block text-gray-700 text-sm font-bold mb-2">Nombre de Usuario:</label>
        <input type="text" id="edit_nombre_usuario" name="nombre_usuario" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               value="<?php echo htmlspecialchars($oldInput['nombre_usuario'] ?? $userToEdit['nombre_usuario']); ?>" required>
    </div>

    <div>
        <label for="edit_nombre_completo" class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo:</label>
        <input type="text" id="edit_nombre_completo" name="nombre_completo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               value="<?php echo htmlspecialchars($oldInput['nombre_completo'] ?? $userToEdit['nombre_completo']); ?>" required>
    </div>

    <div>
        <label for="edit_email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
        <input type="email" id="edit_email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               value="<?php echo htmlspecialchars($oldInput['email'] ?? $userToEdit['email']); ?>">
    </div>

    <div>
        <label for="edit_telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
        <input type="text" id="edit_telefono" name="telefono" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               value="<?php echo htmlspecialchars($oldInput['telefono'] ?? $userToEdit['telefono']); ?>">
    </div>

    <div>
        <label for="edit_password" class="block text-gray-700 text-sm font-bold mb-2" >Nueva Contraseña</label>
        <input type="password" id="edit_password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="dejar vacio si no se va a cambiar">
    </div>

    <div>
        <label for="edit_confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Nueva Contraseña:</label>
        <input type="password" id="edit_confirm_password" name="confirm_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="dejar vacio si no se va a cambiar">
    </div>

    <div class="col-span-1 md:col-span-2">
        <label for="edit_rol" class="block text-gray-700 text-sm font-bold mb-2">Rol:</label>
        <select id="edit_rol" name="rol" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <option value="administrador" <?php echo (isset($oldInput['rol']) ? ($oldInput['rol'] === 'administrador' ? 'selected' : '') : ($userToEdit['rol'] === 'administrador' ? 'selected' : '')); ?>>Administrador</option>
            <option value="asesor" <?php echo (isset($oldInput['rol']) ? ($oldInput['rol'] === 'asesor' ? 'selected' : '') : ($userToEdit['rol'] === 'asesor' ? 'selected' : '')); ?>>Asesor</option>
            <option value="fumigador" <?php echo (isset($oldInput['rol']) ? ($oldInput['rol'] === 'fumigador' ? 'selected' : '') : ($userToEdit['rol'] === 'fumigador' ? 'selected' : '')); ?>>Fumigador</option>
        </select>
    </div>

    <div class="md:col-span-2 flex justify-end mt-6">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-200">
            Guardar Cambios
        </button>
    </div>
</form>