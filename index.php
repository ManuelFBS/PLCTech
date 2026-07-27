<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PLCTech\Presentation\Controllers\AuthController;
use PLCTech\Presentation\Controllers\CartController;
use PLCTech\Presentation\Controllers\CustomerController;
use PLCTech\Presentation\Controllers\EmployeeController;
use PLCTech\Presentation\Controllers\HomeController;
use PLCTech\Presentation\Controllers\ProductController;
use PLCTech\Presentation\Controllers\PurchaseController;
use PLCTech\Presentation\Controllers\UserController;
use PLCTech\Presentation\Middleware\AuthMiddleware;
use PLCTech\Presentation\Middleware\RoleMiddleware;

session_start();

// * ============================================================
// * CARGAR VARIABLES DE ENTORNO
// * ============================================================
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// * ============================================================
// * DETECTAR RUTA BASE AUTOMÁTICAMENTE
// * ============================================================
// * Obtener la ruta del script actual...
$scriptName = $_SERVER['SCRIPT_NAME'];
$scriptDir = dirname($scriptName);

// * Determinar la ruta base...
// * Si el script está en la raíz del dominio, $base_path = ''...
// * Si está en una subcarpeta, $base_path = '/subcarpeta'...
$base_path = ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') ? '' : $scriptDir;

// * Si la APP_URL está definida en .env, usarla para determinar la base path...
if (isset($_ENV['APP_URL']) && !empty($_ENV['APP_URL'])) {
        $parsedUrl = parse_url($_ENV['APP_URL']);
        if (isset($parsedUrl['path']) && $parsedUrl['path'] !== '/') {
                $base_path = rtrim($parsedUrl['path'], '/');
        }
}

// * ============================================================
// * OBTENER LA URL SOLICITADA
// * ============================================================
$request_uri = $_SERVER['REQUEST_URI'];
$path = str_replace($base_path, '', $request_uri);
$path = strtok($path, '?');
$method = $_SERVER['REQUEST_METHOD'];

// * ============================================================
// * RUTAS PÚBLICAS (NO requieren autenticación)
// * ============================================================
$publicRoutes = ['/', '/login', '/do-login'];

// * ============================================================
// * CARGAR RUTAS
// * ============================================================
$routes = require __DIR__ . '/src/routes.php';

// * ============================================================
// * VERIFICAR AUTENTICACIÓN
// * ============================================================
if (!in_array($path, $publicRoutes)) {
        if (!AuthMiddleware::check()) {
                header('Location: ' . rtrim($base_path, '/') . '/login');
                exit;
        }

        $required_role = $routes[$path]['role'] ?? null;
        if ($required_role && !RoleMiddleware::hasRole($required_role)) {
                http_response_code(403);
                echo 'Acceso denegado: No tienes permisos suficientes.';
                exit;
        }
}

// * ============================================================
// * ENRUTAMIENTO
// * ============================================================
$route_found = false;

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
