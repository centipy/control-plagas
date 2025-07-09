<?php
// app/Controllers/AuthController.php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Helpers/Session.php';
require_once __DIR__ . '/../../config/database.php'; // Para acceder a $pdo

class AuthController {
    private $userModel;

    public function __construct(PDO $pdo) {
        $this->userModel = new User($pdo);
    }

    public function showLoginForm() {
        // Si el usuario ya está logueado, redirigir según su rol
        if (Session::has('user_id')) {
            $this->redirectToDashboard(Session::get('user_role'));
            exit();
        }
        // Incluir la vista del formulario de login
        include __DIR__ . '/../../public/login.php';
    }

    public function login() {
        Session::start(); // Asegurarse de que la sesión esté iniciada

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Limpieza y validación básica de entrada
            $username = htmlspecialchars(trim($username));
            // No limpiar la contraseña aquí, password_verify la maneja

            if (empty($username) || empty($password)) {
                Session::set('login_error', 'Por favor, ingrese usuario y contraseña.');
                header('Location: /control-plagas/login');
                exit();
            }

            $user = $this->userModel->findByUsername($username);

            // Verificar usuario y contraseña
            if ($user && password_verify($password, $user['contrasena'])) {
                // Autenticación exitosa
                Session::regenerateId(); // Regenerar ID de sesión para prevenir Session Fixation
                Session::set('user_id', $user['id']);
                Session::set('user_username', $user['nombre_usuario']);
                Session::set('user_role', $user['rol']);
                Session::set('user_full_name', $user['nombre_completo']);

                // Redirigir según el rol
                $this->redirectToDashboard($user['rol']);
            } else {
                // Autenticación fallida
                Session::set('login_error', 'Usuario o contraseña incorrectos.');
                header('Location: /control-plagas/login');
                exit();
            }
        } else {
            // Si no es POST, redirigir a la página de login
            header('Location: /control-plagas/login');
            exit();
        }
    }

    public function logout() {
        Session::destroy();
        header('Location: /control-plagas/login'); // Redirigir al login después de cerrar sesión
        exit();
    }

    public function redirectToDashboard($role) {
        switch ($role) {
            case 'administrador':
                header('Location: /control-plagas/admin/dashboard');
                break;
            case 'asesor':
                header('Location: /control-plagas/asesor/dashboard');
                break;
            case 'fumigador':
                header('Location: /control-plagas/fumigador/dashboard');
                break;
            default:
                header('Location: /control-plagas/login'); // En caso de rol desconocido
                break;
        }
        exit();
    }
}