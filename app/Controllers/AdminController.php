<?php

require_once __DIR__ . '/../Helpers/Session.php';
require_once __DIR__ . '/../Models/User.php';

class AdminController {
    private $pdo;
    private $basePath;
    private $currentUri; // Nueva propiedad para almacenar la URI actual

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        Session::start();
        $this->basePath = '/control-plagas';

        // Obtener y normalizar la URI actual para pasarla a las vistas
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (strpos($requestUri, $this->basePath) === 0) {
            $requestUri = substr($requestUri, strlen($this->basePath));
        }
        $this->currentUri = '/' . ltrim(urldecode($requestUri), '/'); // Normalizar y decodificar

        $this->requireAdminAuth();
    }

    private function requireAdminAuth() {
        if (!Session::has('user_id') || Session::get('user_role') !== 'administrador') {
            header('Location: ' . $this->basePath . '/login');
            exit();
        }
    }

    // Método de ayuda para incluir vistas, pasando las variables comunes
    private function renderView($viewPath, $data = []) {
        // Extrae el array $data en variables individuales ($userName, $userRole, etc.)
        extract($data);
        // Pasa $basePath y $currentUri automáticamente
        $basePath = $this->basePath;
        $currentUri = $this->currentUri;

        // Incluye el archivo de la vista
        include __DIR__ . '/../../views/admin/' . $viewPath . '.php';
    }

    public function dashboard() {
        $this->renderView('admin_dashboard', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role')
        ]);
    }

    public function addClient() {
        $this->renderView('aniadir_cliente', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role')
        ]);
    }

    public function viewRegisteredClients() {
        $this->renderView('ver_clientes_registrados', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role')
        ]);
    }

    public function newService() {
        $this->renderView('nuevo_servicio', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role')
        ]);
    }

    public function viewServices() {
        $this->renderView('ver_servicios', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role')
        ]);
    }

    public function listUsers() {
        $userModel = new User($this->pdo);
        $users = [];
        $errorMessage = null;

        try {
            $stmt = $this->pdo->prepare("SELECT id, nombre_usuario, rol, nombre_completo, email, telefono, deleted_at FROM usuarios ORDER BY id ASC");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al cargar listado de usuarios: " . $e->getMessage());
            $users = [];
            $errorMessage = "Error al cargar los usuarios. Por favor, intente de nuevo más tarde.";
        }
        
        $this->renderView('listado_usuarios', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role'),
            'users' => $users,
            'errorMessage' => $errorMessage
        ]);
    }

    public function registerNewUser() {
        $oldInput = Session::get('old_input', []);
        Session::set('old_input', null);
        $this->renderView('registrar', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role'),
            'oldInput' => $oldInput
        ]);
    }

    // NUEVO MÉTODO: Para procesar el POST del formulario de registro
    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->basePath . '/admin/registrar');
            exit();
        }

        $userModel = new User($this->pdo);

        // 1. Obtener y sanear datos (ACTUALIZADO)
        // Ya no se usa FILTER_SANITIZE_STRING. trim() es suficiente para espacios.
        $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
        $nombre_completo = trim($_POST['nombre_completo'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Sigue siendo útil
        $telefono = trim($_POST['telefono'] ?? ''); // Simplemente trim para teléfono
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $rol = trim($_POST['rol'] ?? '');

        $errors = [];

        // 2. Validaciones de servidor
        if (empty($nombre_usuario) || strlen($nombre_usuario) < 3) {
            $errors[] = "El nombre de usuario es obligatorio y debe tener al menos 3 caracteres.";
        } else {
            // Verificar si el nombre de usuario ya existe
            if ($userModel->findByUsername($nombre_usuario)) {
                $errors[] = "El nombre de usuario ya está en uso. Por favor, elija otro.";
            }
        }
        
        if (empty($nombre_completo) || strlen($nombre_completo) < 5) {
            $errors[] = "El nombre completo es obligatorio y debe tener al menos 5 caracteres.";
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = "La contraseña es obligatoria y debe tener al menos 6 caracteres.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Las contraseñas no coinciden.";
        }
        
        // Validar que el rol sea uno de los permitidos
        $allowedRoles = ['administrador', 'asesor', 'fumigador'];
        if (!in_array($rol, $allowedRoles)) {
            $errors[] = "Rol seleccionado no válido.";
        }

        // Validación de Email: si no está vacío, debe ser válido Y no debe existir ya
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "El formato del correo electrónico no es válido.";
            } else {
                // NUEVA VALIDACIÓN: Verificar si el email ya existe en la base de datos
                // Necesitamos un nuevo método en User.php para buscar por email
                if ($userModel->findByEmail($email)) { // <--- Asume que creamos findByEmail
                    $errors[] = "El correo electrónico ya está registrado.";
                }
            }
        }
        
        // Puedes añadir más validaciones para el teléfono, etc.

        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $userData = [
                'nombre_usuario' => $nombre_usuario,
                'contrasena_hash' => $hashed_password,
                'rol' => $rol,
                'nombre_completo' => $nombre_completo,
                'email' => $email,
                'telefono' => $telefono
            ];

            // Depuración de inserción:
            error_log("Intentando crear usuario con datos: " . print_r($userData, true));
            
            $newUserId = $userModel->create($userData);

            if ($newUserId) {
                error_log("Usuario creado con ID: " . $newUserId);
                Session::set('form_message', "Usuario '{$nombre_usuario}' registrado exitosamente.");
                Session::set('form_message_type', 'success');
                header('Location: ' . $this->basePath . '/admin/listado_usuarios');
                exit();
            } else {
                // Depuración de fallo de inserción:
                error_log("FALLO en User::create(). Probablemente por una excepción de la DB (UNIQUE constraint).");
                Session::set('form_message', "Error al registrar el usuario. El nombre de usuario o email ya existen. Intente de nuevo."); // Mensaje más específico
                Session::set('form_message_type', 'error');
            }
        } else {
            // Depuración de errores de validación:
            error_log("Errores de validación encontrados: " . implode(' | ', $errors));
            Session::set('form_message', implode('<br>', $errors));
            Session::set('form_message_type', 'error');
            Session::set('old_input', [
                'nombre_usuario' => $nombre_usuario,
                'nombre_completo' => $nombre_completo,
                'email' => $email,
                'telefono' => $telefono,
                'rol' => $rol,
            ]);
        }
        // Esta línea redirige de vuelta al formulario de registro en caso de error o fallo de inserción
        header('Location: ' . $this->basePath . '/admin/registrar');
        exit();
    }

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('form_message', "Método no permitido.");
            Session::set('form_message_type', 'error');
            header('Location: ' . $this->basePath . '/admin/listado_usuarios');
            exit();
        }

        // Verifica que la petición provenga del formulario del modal
        if (($_POST['_method'] ?? '') !== 'DELETE') {
            Session::set('form_message', "Petición inválida.");
            Session::set('form_message_type', 'error');
            header('Location: ' . $this->basePath . '/admin/listado_usuarios');
            exit();
        }

        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        if (!$userId) {
            Session::set('form_message', "ID de usuario no válido.");
            Session::set('form_message_type', 'error');
            header('Location: ' . $this->basePath . '/admin/listado_usuarios');
            exit();
        }

        $userModel = new User($this->pdo);
        if ($userModel->softDelete($userId)) {
            Session::set('form_message', "Usuario eliminado lógicamente (inactivado) con éxito.");
            Session::set('form_message_type', 'success');
        } else {
            Session::set('form_message', "Error al eliminar lógicamente el usuario. Intente de nuevo.");
            Session::set('form_message_type', 'error');
        }
        header('Location: ' . $this->basePath . '/admin/listado_usuarios');
        exit();
    }

    // NUEVO MÉTODO: Restaurar usuario (revertir Soft Delete)
    public function restoreUser() {
        // Para simplificar, asumimos que este se activa con un GET desde un enlace
        // En una app más grande, podrías querer un POST para esto también.
        $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$userId) {
            Session::set('form_message', "ID de usuario no válido para restaurar.");
            Session::set('form_message_type', 'error');
            header('Location: ' . $this->basePath . '/admin/listado_usuarios');
            exit();
        }

        $userModel = new User($this->pdo);
        if ($userModel->restore($userId)) {
            Session::set('form_message', "Usuario restaurado (activado) con éxito.");
            Session::set('form_message_type', 'success');
        } else {
            Session::set('form_message', "Error al restaurar el usuario. Intente de nuevo.");
            Session::set('form_message_type', 'error');
        }
        header('Location: ' . $this->basePath . '/admin/listado_usuarios');
        exit();
    }
    
    public function approveAccess() {
        $this->renderView('aprobar_acceso', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role')
        ]);
    }

    public function servicesOffered() {
        $this->renderView('servicios_prestados', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role')
        ]);
    }

    public function localities() {
        $this->renderView('localidades', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role')
        ]);
    }

    
public function editUser() {
        $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$userId) {
            Session::set('form_message', "ID de usuario no válido para edición.");
            Session::set('form_message_type', 'error');
            header('Location: ' . $this->basePath . '/admin/listado_usuarios');
            exit();
        }

        $userModel = new User($this->pdo);
        $userToEdit = $userModel->findById($userId); // Obtener datos del usuario

        if (!$userToEdit) {
            Session::set('form_message', "Usuario no encontrado.");
            Session::set('form_message_type', 'error');
            header('Location: ' . $this->basePath . '/admin/listado_usuarios');
            exit();
        }

        // Pasa los datos del usuario a la vista para pre-rellenar
        $this->renderView('editar_usuario_form', [ // Nota: Aquí se incluye solo el formulario, no la página completa
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role'),
            'userToEdit' => $userToEdit,
            'message' => Session::get('form_message'), // Para mostrar mensajes después de una redirección POST fallida
            'messageType' => Session::get('form_message_type'),
            'oldInput' => Session::get('old_input_edit', []), // Para rellenar si hubo error en update
        ]);
        Session::set('form_message', null); // Limpiar después de mostrar
        Session::set('form_message_type', null);
        Session::set('old_input_edit', null); // Limpiar old_input_edit
    }

    // NUEVO MÉTODO: Procesar la actualización del usuario
    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('form_message', "Método no permitido para la actualización.");
            Session::set('form_message_type', 'error');
            header('Location: ' . $this->basePath . '/admin/listado_usuarios');
            exit();
        }

        // Simular método PUT
        if (($_POST['_method'] ?? '') !== 'PUT') {
            Session::set('form_message', "Petición inválida para actualización.");
            Session::set('form_message_type', 'error');
            header('Location: ' . $this->basePath . '/admin/listado_usuarios');
            exit();
        }

        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        // Validaciones del lado del servidor (similar a storeUser)
        $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
        $nombre_completo = trim($_POST['nombre_completo'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $telefono = trim($_POST['telefono'] ?? '');
        $password = $_POST['password'] ?? ''; // Nueva contraseña (puede estar vacía)
        $confirm_password = $_POST['confirm_password'] ?? '';
        $rol = trim($_POST['rol'] ?? '');

        $errors = [];

        if (!$userId) {
            $errors[] = "ID de usuario no válido para actualizar.";
        } else {
            $userModel = new User($this->pdo);
            $existingUser = $userModel->findById($userId); // Obtener usuario existente para validaciones
            if (!$existingUser) {
                $errors[] = "Usuario a editar no encontrado.";
            } else {
                // Validar nombre de usuario (único y no igual al actual)
                if (empty($nombre_usuario) || strlen($nombre_usuario) < 3) {
                    $errors[] = "El nombre de usuario es obligatorio y debe tener al menos 3 caracteres.";
                } elseif ($nombre_usuario !== $existingUser['nombre_usuario'] && $userModel->findByUsername($nombre_usuario)) {
                    $errors[] = "El nombre de usuario ya está en uso.";
                }

                // Validar email (único y no igual al actual, si no está vacío)
                if (!empty($email)) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "El formato del correo electrónico no es válido.";
                    } elseif ($email !== $existingUser['email'] && $userModel->findByEmail($email)) {
                        $errors[] = "El correo electrónico ya está registrado.";
                    }
                }

                // Validar contraseñas solo si se proporcionaron
                if (!empty($password)) {
                    if (strlen($password) < 6) {
                        $errors[] = "La nueva contraseña debe tener al menos 6 caracteres.";
                    }
                    if ($password !== $confirm_password) {
                        $errors[] = "Las nuevas contraseñas no coinciden.";
                    }
                }

                $allowedRoles = ['administrador', 'asesor', 'fumigador'];
                if (!in_array($rol, $allowedRoles)) {
                    $errors[] = "Rol seleccionado no válido.";
                }
            }
        }
        
        if (empty($errors)) {
            $updateData = [
                'nombre_usuario' => $nombre_usuario,
                'nombre_completo' => $nombre_completo,
                'email' => $email,
                'telefono' => $telefono,
                'rol' => $rol,
            ];

            if (!empty($password)) { // Solo hashear y añadir si se va a cambiar la contraseña
                $updateData['contrasena'] = password_hash($password, PASSWORD_BCRYPT);
            }
            
            // Llama a un nuevo método updateUser en el User Model
            if ($userModel->update($userId, $updateData)) { // <--- Asume que creamos User::update
                Session::set('form_message', "Usuario actualizado exitosamente.");
                Session::set('form_message_type', 'success');
                header('Location: ' . $this->basePath . '/admin/listado_usuarios');
                exit();
            } else {
                Session::set('form_message', "Error al actualizar el usuario. Verifique los datos.");
                Session::set('form_message_type', 'error');
            }

        } else {
            Session::set('form_message', implode('<br>', $errors));
            Session::set('form_message_type', 'error');
            Session::set('old_input_edit', [ // Usar old_input_edit para este formulario
                'nombre_usuario' => $nombre_usuario,
                'nombre_completo' => $nombre_completo,
                'email' => $email,
                'telefono' => $telefono,
                'rol' => $rol,
                'user_id' => $userId // Es importante mantener el ID del usuario para el reenvío
            ]);
        }
        // Redirigir de vuelta al formulario de edición (en el modal)
        header('Location: ' . $this->basePath . '/admin/editar_usuario?id=' . $userId);
        exit();
    }
}

    
