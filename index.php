<?php
// C:\laragon\www\control-plagas\index.php

// Carga de la configuración de la base de datos
require_once __DIR__ . '/config/database.php'; // Asegúrate de que $pdo esté disponible

// Carga del Router
require_once __DIR__ . '/app/Router/Router.php';

// Iniciar sesión globalmente (o manejarlo con Middlewares más tarde)
require_once __DIR__ . '/app/Helpers/Session.php';
Session::start();

// Definir el basePath para las URLs del router
// Este $basePath se pasará al constructor del Router
$basePath = '/control-plagas'; 

// Crear una instancia del Router
$router = new Router($basePath);

// Cargar las definiciones de rutas desde el archivo web.php
// Este archivo web.php es donde ahora defines TODAS tus rutas (incluyendo login, logout, y todos los dashboards)
require_once __DIR__ . '/routes/web.php';

// Despachar la petición: el Router se encarga de todo el enrutamiento y la inclusión de vistas.
$router->dispatch();

// No debe haber NADA más aquí después de $router->dispatch();
// ¡Ningún switch, ni includes directos de vistas, ni lógica de ruteo antigua!

?>