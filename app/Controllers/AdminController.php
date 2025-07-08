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
            'users' => $users, // Pasamos la variable $users a la vista
            'errorMessage' => $errorMessage // Pasamos el mensaje de error también
        ]);
    }

    public function registerNewUser() {
        // Recuperar datos enviados previamente si hubo un error de validación
        $oldInput = Session::get('old_input', []);
        Session::set('old_input', null); // Limpiar después de obtener

        $this->renderView('registrar', [
            'userName' => Session::get('user_full_name'),
            'userRole' => Session::get('user_role'),
            'oldInput' => $oldInput // Pasar los datos antiguos a la vista
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
            // ... (hashear y crear usuario) ...
        } else {
            // Si hay errores de validación
            Session::set('form_message', implode('<br>', $errors));
            Session::set('form_message_type', 'error');
            // Guardar los datos enviados (excepto la contraseña) para rellenar el formulario
            Session::set('old_input', [
                'nombre_usuario' => $nombre_usuario,
                'nombre_completo' => $nombre_completo,
                'email' => $email,
                'telefono' => $telefono,
                'rol' => $rol,
            ]);
        }
        header('Location: ' . $this->basePath . '/admin/registrar');
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
}