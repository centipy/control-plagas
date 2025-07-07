<?php
// app/Router/Router.php

require_once __DIR__ . '/Route.php'; // Incluye la clase Route

class Router {
    protected $routes = [];
    protected $basePath;

    public function __construct($basePath = '') {
        // Asegura que basePath tenga un slash inicial y no final (a menos que sea solo '/')
        $this->basePath = '/' . trim($basePath, '/');
        if ($this->basePath === '/') { // Si el base path es solo '/', se mantiene así
             $this->basePath = ''; // O se elimina si no se necesita prefijo
        } else {
             $this->basePath .= '/'; // Añade el slash final si no es la raíz
        }
    }


    /**
     * Registra una ruta GET.
     * @param string $uri La URI de la ruta (ej. '/dashboard')
     * @param mixed $action La acción a ejecutar (callback o 'Controller@method')
     * @return Route
     */
    public function get($uri, $action) {
        $route = new Route('GET', $uri, $action);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Registra una ruta POST.
     * @param string $uri
     * @param mixed $action
     * @return Route
     */
    public function post($uri, $action) {
        $route = new Route('POST', $uri, $action);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Resuelve la ruta actual y ejecuta la acción correspondiente.
     */
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Elimina el base path de la URI
        if (strpos($requestUri, $this->basePath) === 0) {
            $requestUri = substr($requestUri, strlen($this->basePath));
        }

        // *** CAMBIO CLAVE AQUÍ: Asegurar que la URI siempre comience con '/' ***
        // Si la URI es vacía después de limpiar, debe ser '/' (la ruta raíz)
        // Si no es vacía, asegúrate de que empiece con '/'
        $requestUri = '/' . ltrim($requestUri, '/');


        foreach ($this->routes as $route) {
            // Coincidencia de URI y método HTTP
            if ($route->uri === $requestUri && $route->method === $requestMethod) {
                // Si la acción es una cadena 'Controller@method'
                if (is_string($route->action) && strpos($route->action, '@') !== false) {
                    list($controllerName, $methodName) = explode('@', $route->action);
                    
                    // Incluye el archivo del controlador
                    // Asume que los controladores están en app/Controllers/
                    $controllerPath = __DIR__ . '/../Controllers/' . $controllerName . '.php';
                    if (!file_exists($controllerPath)) {
                        $this->show404();
                        return;
                    }
                    require_once $controllerPath;

                    // Crea una instancia del controlador y llama al método
                    // Necesitamos pasar la conexión PDO si el controlador la requiere
                    global $pdo; // Accede a la variable global $pdo
                    $controllerInstance = new $controllerName($pdo); // Pasa $pdo al constructor
                    
                    if (method_exists($controllerInstance, $methodName)) {
                        // Antes de ejecutar la acción, podríamos ejecutar middlewares aquí (futuro)
                        $controllerInstance->$methodName();
                        return; // Ruta encontrada y ejecutada
                    }
                } 
                // Si la acción es una función anónima (closure)
                elseif (is_callable($route->action)) {
                    // Antes de ejecutar la acción, podríamos ejecutar middlewares aquí (futuro)
                    $route->action->__invoke(); // Llama a la función
                    return; // Ruta encontrada y ejecutada
                }
            }
        }

        // Si ninguna ruta coincide, mostrar 404
        $this->show404();
    }

    protected function show404() {
        http_response_code(404);
        global $basePath; // Para usar $basePath en la vista 404
        include __DIR__ . '/../../views/404.php'; // Creamos un archivo 404.php
    }
}