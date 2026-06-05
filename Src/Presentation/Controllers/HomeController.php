<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Presentation\Middleware\AuthMiddleware;

class HomeController
{
        public function index(): void
        {
                $user = AuthMiddleware::getUser();
                $role = $user['role'] ?? 'Guest';

                require_once __DIR__ . '../Views/layouts/navbar.php';
                require_once __DIR__ . '../Views/home/index.php';
        }
}
