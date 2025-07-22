<?php
// routes/web.php

// Ruta para la página de inicio (root)
$router->get('/', function() use ($basePath) {
    // Redirige al login si no está logueado, o al dashboard si sí lo está
    require_once __DIR__ . '/../app/Controllers/AuthController.php';
    require_once __DIR__ . '/../app/Helpers/Session.php';
    Session::start();
    if (!Session::has('user_id')) {
        header('Location: ' . $basePath . '/login');
        exit();
    }
    // Si ya está logueado, redirige al dashboard de su rol
    // Esto se maneja en AuthController::redirectToDashboard
    global $pdo;
    $authController = new AuthController($pdo);
    $authController->redirectToDashboard(Session::get('user_role'));
});

// Rutas de Autenticación
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');


// Rutas del Administrador
// Todas estas rutas ahora apuntan a métodos del AdminController
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/aniadir_cliente', 'AdminController@addClient');
$router->get('/admin/ver_clientes_registrados', 'AdminController@viewRegisteredClients');
$router->get('/admin/nuevo_servicio', 'AdminController@newService');
$router->get('/admin/ver_servicios', 'AdminController@viewServices');
$router->get('/admin/listado_usuarios', 'AdminController@listUsers');
$router->get('/admin/registrar', 'AdminController@registerNewUser');
$router->post('/admin/registrar', 'AdminController@storeUser');  // Ruta para registrar nuevo usuario
$router->get('/admin/aprobar_acceso', 'AdminController@approveAccess');
$router->get('/admin/servicios_prestados', 'AdminController@servicesOffered');
$router->get('/admin/localidades', 'AdminController@localities');

// RUTAS para EDITAR y ACTUALIZAR usuarios
$router->get('/admin/editar_usuario', 'AdminController@editUser'); // Para cargar el formulario en el modal
$router->post('/admin/actualizar_usuario', 'AdminController@updateUser'); // Para procesar el POST (simulando PUT)

// Rutas para eliminar y restaurar (existentes)
$router->post('/admin/eliminar_usuario', 'AdminController@deleteUser'); // <-- RUTA POST para eliminar
$router->get('/admin/restaurar_usuario', 'AdminController@restoreUser');
// Añade más rutas de administrador según sea necesario

// Ejemplo de rutas para Asesor (Crearás AsesorController similar a AdminController)
$router->get('/asesor/dashboard', 'AsesorController@dashboard');
// ... y sus otras rutas

// Ejemplo de rutas para Fumigador (Crearás FumigadorController similar a AdminController)
$router->get('/fumigador/panel', 'FumigadorController@panel');
// ... y sus otras rutas