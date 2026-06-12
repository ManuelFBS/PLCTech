<?php

return [
        // * Auth...
        '/login' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\AuthController::class,
                'action' => 'showLogin'
        ],
        '/do-login' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\AuthController::class,
                'action' => 'login'
        ],
        '/logout' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\AuthController::class,
                'action' => 'logout'
        ],
        // * Home...
        '/' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\HomeController::class,
                'action' => 'index',
                'role' => null
        ],
        // * Users (Solo Admin)...
        '/users' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\UserController::class,
                'action' => 'index',
                'role' => 'Admin'
        ],
        '/users/create' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\UserController::class,
                'action' => 'create',
                'role' => 'Admin'
        ],
        '/users/search' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\UserController::class,
                'action' => 'search',
                'role' => 'Admin'
        ],
        '/users/store' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\UserController::class,
                'action' => 'store',
                'role' => 'Admin'
        ],
        '/users/edit' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\UserController::class,
                'action' => 'edit',
                'role' => 'Admin'
        ],
        '/users/update' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\UserController::class,
                'action' => 'update',
                'role' => 'Admin'
        ],
        '/users/delete' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\UserController::class,
                'action' => 'delete',
                'role' => 'Admin'
        ],
        // * Employees (Solo Admin)...
        '/employees' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\EmployeeController::class,
                'action' => 'index',
                'role' => 'Admin'
        ],
        '/employees/create' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\EmployeeController::class,
                'action' => 'create',
                'role' => 'Admin'
        ],
        '/employees/store' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\EmployeeController::class,
                'action' => 'store',
                'role' => 'Admin'
        ],
        '/employees/edit' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\EmployeeController::class,
                'action' => 'edit',
                'role' => 'Admin'
        ],
        '/employees/update' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\EmployeeController::class,
                'action' => 'update',
                'role' => 'Admin'
        ],
        '/employees/delete' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\EmployeeController::class,
                'action' => 'delete',
                'role' => 'Admin'
        ],
        // * Customers (Admin y Employee)...
        '/customers' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\CustomerController::class,
                'action' => 'index',
                'role' => 'Admin|Employee'
        ],
        '/customers/create' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\CustomerController::class,
                'action' => 'create',
                'role' => 'Admin'
        ],
        '/customers/store' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\CustomerController::class,
                'action' => 'store',
                'role' => 'Admin'
        ],
        '/customers/edit' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\CustomerController::class,
                'action' => 'edit',
                'role' => 'Admin'
        ],
        '/customers/update' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\CustomerController::class,
                'action' => 'update',
                'role' => 'Admin'
        ],
        '/customers/delete' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\CustomerController::class,
                'action' => 'delete',
                'role' => 'Admin'
        ],
];
