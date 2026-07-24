<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Helpers\PathHelper;
use PLCTech\Presentation\Middleware\AuthMiddleware;

class HomeController
{
        // * ============================================================
        // * LANDING PAGE (Bienvenida)
        // * ============================================================
        public function landing(): void
        {
                // > Si el usuario ya está logueado, redirigir al dashboard
                if (isset($_SESSION['user_id'])) {
                        header('Location: ' . PathHelper::getBaseUrl() . '/dashboard');
                        exit;
                }

                // > Mostrar landing page
                require_once __DIR__ . '/../Views/landing.php';
        }

        // * ============================================================
        // * DASHBOARD (Home después de login)
        // * ============================================================
        public function index(): void
        {
                // > Redirigir al dashboard si está logueado...
                if (!isset($_SESSION['user_id'])) {
                        header('Location: ' . PathHelper::getBaseUrl());
                        exit;
                }

                $user = AuthMiddleware::getUser();
                $role = $user['role'] ?? 'Guest';

                $viewsPath = PathHelper::getViewsPath();
                require_once $viewsPath . '/layouts/navbar.php';
                require_once $viewsPath . '/home/index.php';
        }
}
