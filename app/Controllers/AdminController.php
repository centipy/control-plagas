<?php
// app/Controllers/AdminController.php

require_once __DIR__ . '/../Helpers/Session.php';
// Podrías necesitar importar modelos aquí, por ejemplo:
// require_once __DIR__ . '/../Models/Client.php';

class AdminController {
    private $pdo; // Para inyectar la conexión a la base de datos

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        // Opcional: Iniciar sesión aquí si no se hace globalmente en index.php,
        // o usar un middleware de autenticación.
        Session::start();
        $this->requireAdminAuth(); // Función de ayuda para asegurar que es admin
    }

    private function requireAdminAuth() {
        $basePath = '/control-plagas'; // Asegúrate de que esto coincida
        if (!Session::has('user_id') || Session::get('user_role') !== 'administrador') {
            header('Location: ' . $basePath . '/login');
            exit();
        }
    }

    public function dashboard() {
        $userName = Session::get('user_full_name');
        // Incluir la vista del dashboard del administrador
        include __DIR__ . '/../../views/admin/admin_dashboard.php';
    }

    public function addClient() {
        $userName = Session::get('user_full_name'); // Asegurar que $userName esté disponible en la vista
        include __DIR__ . '/../../views/admin/aniadir_cliente.php';
    }

    public function viewRegisteredClients() {
        $userName = Session::get('user_full_name');
        include __DIR__ . '/../../views/admin/ver_clientes_registrados.php';
    }

    public function newService() {
        $userName = Session::get('user_full_name');
        include __DIR__ . '/../../views/admin/nuevo_servicio.php';
    }

    public function viewServices() {
        $userName = Session::get('user_full_name');
        include __DIR__ . '/../../views/admin/ver_servicios.php';
    }

    public function listUsers() {
        $userName = Session::get('user_full_name');
        include __DIR__ . '/../../views/admin/listado_usuarios.php';
    }

    public function registerNewUser() {
        $userName = Session::get('user_full_name');
        include __DIR__ . '/../../views/admin/registrar.php'; // Asumo que `registrar.php` es para nuevos usuarios
    }
    
    public function approveAccess() {
        $userName = Session::get('user_full_name');
        include __DIR__ . '/../../views/admin/aprobar_acceso.php';
    }

    public function servicesOffered() {
        $userName = Session::get('user_full_name');
        include __DIR__ . '/../../views/admin/servicios_prestados.php';
    }

    public function localities() {
        $userName = Session::get('user_full_name');
        include __DIR__ . '/../../views/admin/localidades.php';
    }
    
    // Si tenías listado_asesores.php y listado_trabajadores.php,
    // podrías manejarlos aquí o unirlos en listado_usuarios con filtros
    // Por ahora, solo pondré la que está en tu carpeta: listado_usuarios.php
}