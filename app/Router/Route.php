<?php
// app/Router/Route.php

class Route {
    public $method;
    public $uri;
    public $action;
    public $middlewares = []; // Para futuras implementaciones de middleware

    public function __construct($method, $uri, $action) {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
    }

    // Método para añadir middlewares a una ruta específica (uso futuro)
    public function middleware($middlewares) {
        $this->middlewares = is_array($middlewares) ? $middlewares : [$middlewares];
        return $this;
    }
}