<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PLCTech\Presentation\Controllers\AuthController;
use PLCTech\Presentation\Controllers\CustomerController;
use PLCTech\Presentation\Controllers\EmployeeController;
use PLCTech\Presentation\Controllers\HomeController;
use PLCTech\Presentation\Controllers\UserController;
use PLCTech\Presentation\Middleware\AuthMiddleware;
use PLCTech\Presentation\Middleware\RoleMiddleware;

session_start();

// * Cargar variables de entorno...
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// * Obtener la URL solicitada...
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/Projects/PLCTech';
$path = str_replace($base_path, '', $request_uri);
$path = strtok($path, '?');
$method = $_SERVER['REQUEST_METHOD'];

// * Enrutamiento básico...
$routes = require __DIR__ . '/src/routes.php';
$route_found = false;

// * ============================================================
// * RUTAS PÚBLICAS (NO requieren autenticación)
// * ============================================================
$publicRoutes = ['/', '/login', '/do-login'];

// * Verificar autenticación para rutas protegidas...
if (!in_array($path, $publicRoutes)) {
        if (!AuthMiddleware::check()) {
                header('Location: ' . $base_path . '/login');
                exit;
        }

        // > Verificar roles basado en la ruta...
        $required_role = $routes[$path]['role'] ?? null;
        if ($required_role && !RoleMiddleware::hasRole($required_role)) {
                http_response_code(403);
                echo 'Acceso denegado: No tienes permisos suficientes.';
                exit;
        }
}

// * Buscar la ruta...
foreach ($routes as $route => $config) {
        if ($route === $path && $method === $config['method']) {
                $controller_class = $config['controller'];
                $action = $config['action'];

                $controller = new $controller_class();
                $controller->$action();
                $route_found = true;
                break;
        }
}

if (!$route_found) {
        http_response_code(404);
        echo 'Página no encontrada';
}
