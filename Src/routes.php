<?php

return [
        // * ============================================================
        // * AUTH...
        // * ============================================================
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
        // * ============================================================
        // * HOME
        // * ============================================================
        '/' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\HomeController::class,
                'action' => 'index',
                'role' => null
        ],
        // * ============================================================
        // * USERS (Solo Admin)
        // * ============================================================
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
        // * ============================================================
        // * EMPLOYEES (Solo Admin)
        // * ============================================================
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
        // * ============================================================
        // * CUSTOMERS (Admin y Employee)
        // * ============================================================
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
        '/customers/search' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\CustomerController::class,
                'action' => 'searchByDni',
                'role' => 'Admin|Employee'
        ],
        // * ============================================================
        // * PRODUCTS (Admin, Employee, Customers)
        // * ============================================================
        '/products' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\ProductController::class,
                'action' => 'index',
                'role' => 'Admin|Employee|Customer'
        ],
        '/products/show' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\ProductController::class,
                'action' => 'show',
                'role' => 'Admin|Employee|Customer'
        ],
        // * ============================================================
        // * PRODUCTS (Admin)
        // * ============================================================
        '/products/create' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\ProductController::class,
                'action' => 'create',
                'role' => 'Admin'
        ],
        '/products/store' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\ProductController::class,
                'action' => 'store',
                'role' => 'Admin'
        ],
        '/products/edit' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\ProductController::class,
                'action' => 'edit',
                'role' => 'Admin'
        ],
        '/products/update' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\ProductController::class,
                'action' => 'update',
                'role' => 'Admin'
        ],
        '/products/delete' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\ProductController::class,
                'action' => 'delete',
                'role' => 'Admin'
        ],
        // * ============================================================
        // * CATALOG (acceso público para todos los roles)
        // * ============================================================
        '/products/catalog' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\ProductController::class,
                'action' => 'catalog',
                'role' => 'Admin|Employee|Customer'
        ],
        // * ============================================================
        // * VENTAS (Purchases)...
        // * ============================================================
        '/purchases' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\PurchaseController::class,
                'action' => 'index',
                'role' => 'Admin|Employee'
        ],
        '/purchases/show' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\PurchaseController::class,
                'action' => 'show',
                'role' => 'Admin|Employee|Customer'
        ],
        '/purchases/create' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\PurchaseController::class,
                'action' => 'create',
                'role' => 'Admin|Employee'
        ],
        '/purchases/store' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\PurchaseController::class,
                'action' => 'store',
                'role' => 'Admin|Employee'
        ],
        '/purchases/cancel' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\PurchaseController::class,
                'action' => 'cancel',
                'role' => 'Admin'
        ],
        '/purchases/invoice' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\PurchaseController::class,
                'action' => 'invoice',
                'role' => 'Admin|Employee|Customer'
        ],
        // * ============================================================
        // * MIS COMPRAS (Solo para clientes)
        // * ============================================================
        '/purchases/customer' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\PurchaseController::class,
                'action' => 'customerPurchases',
                'role' => 'Customer'
        ],
        // * ============================================================
        // * CHECKOUT (Clientes)...
        // * ============================================================
        '/purchases/checkout' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\PurchaseController::class,
                'action' => 'checkout',
                'role' => 'Customer'
        ],
        // * ============================================================
        // * CARRITO (Solo para clientes)
        // * ============================================================
        '/cart' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\CartController::class,
                'action' => 'index',
                'role' => 'Customer'
        ],
        '/cart/add' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\CartController::class,
                'action' => 'add',
                'role' => 'Customer'
        ],
        '/cart/update' => [
                'method' => 'POST',
                'controller' => \PLCTech\Presentation\Controllers\CartController::class,
                'action' => 'update',
                'role' => 'Customer'
        ],
        '/cart/remove' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\CartController::class,
                'action' => 'remove',
                'role' => 'Customer'
        ],
        '/cart/clear' => [
                'method' => 'GET',
                'controller' => \PLCTech\Presentation\Controllers\CartController::class,
                'action' => 'clear',
                'role' => 'Customer'
        ],
];
