<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Helpers\PathHelper;
use PLCTech\Presentation\Middleware\AuthMiddleware;

class HomeController
{
        public function index(): void
        {
                $user = AuthMiddleware::getUser();
                $role = $user['role'] ?? 'Guest';

                $viewsPath = PathHelper::getViewsPath();

                require_once $viewsPath . '/layouts/navbar.php';
                require_once $viewsPath . '/home/index.php';
        }
}
