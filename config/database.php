<?php
// tu-proyecto-control-plagas/config/database.php

// Definir las constantes de conexión
// Asegúrate de que estos valores coincidan con tu configuración de Laragon
define('DB_HOST', 'localhost');
define('DB_NAME', 'control_plagas_db'); // ¡El nombre que acabamos de crear!
define('DB_USER', 'root');              // Usuario por defecto de Laragon para MySQL
define('DB_PASS', '');                 // Contraseña vacía por defecto en Laragon

// Opciones adicionales para PDO:
// Estas opciones son cruciales para un comportamiento seguro y predecible de PDO.
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // Lanza excepciones en errores, esencial para depuración y manejo de errores.
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,            // Devuelve arrays asociativos por defecto (ej. $row['nombre_columna']).
    PDO::ATTR_EMULATE_PREPARES   => false,                       // Desactiva la emulación de sentencias preparadas en PDO.
                                                                 // ¡MUY IMPORTANTE para prevenir inyecciones SQL en ciertos escenarios!
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci" // Asegura la codificación correcta
]);

try {
    // Construye el DSN (Data Source Name)
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    // Crea una nueva instancia de PDO
    $pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);

    // Si estás depurando, puedes descomentar la siguiente línea para confirmar la conexión.
    // Recuerda comentarla o quitarla en un entorno de producción.
    // echo "¡Conexión a la base de datos exitosa con PDO!<br>";

} catch (PDOException $e) {
    // Si hay un error, captura la excepción PDO y la muestra.
    // ¡ADVERTENCIA! En un entorno de producción, NUNCA muestres $e->getMessage() directamente al usuario.
    // En su lugar, registra el error en un archivo de log y muestra un mensaje genérico al usuario.
    die("Error de conexión a la base de datos: " . $e->getMessage());
}