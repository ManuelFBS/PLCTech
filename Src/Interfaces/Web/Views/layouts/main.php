<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title ?? 'PLCTech'; ?></title>
        <link rel="stylesheet" href="/CSS/bulma.min.css">
        <link rel="stylesheet" href="/CSS/styles.css">
        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
</head>

<body>
        <!-- Navbar -->
         <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
                <div class="navbar-brand">
                        <a class="navbar-item" href="/home">
                                <strong>PLCTech</strong>
                        </a>

                        <a 
                                role="button" 
                                class="navbar-burger" 
                                aria-label="menu" 
                                aria-expanded="false" 
                                data-target="navbarBasicExample"
                        >
                                <span aria-hidden="true"></span>
                                <span aria-hidden="true"></span>
                                <span aria-hidden="true"></span>
                        </a>
                </div>

                <div id="navbarBasicExample" class="navbar-menu">
                        <div class="navbar-start">
                                <!-- Menú Empleados (solo visible para Admin y Employee) -->
                                 <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['Admin', 'Employee'])): ?>
                                        <div class="navbar-item has-dropdown is-hoverable">
                                                <a class="navbar-link">Empleados</a>
                                                <div class="navbar-dropdown">
                                                        <a class="navbar-item" href="/employees/create">Nuevo</a>
                                                        <a class="navbar-item" href="/employees">Listado de empleados</a>
                                                        <a class="navbar-item" href="/employees/search">Buscar</a>
                                                </div>
                                        </div>
                                <?php endif; ?>

                                <!-- Menú Clientes (solo visible para Admin y Employee) -->
                                 <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['Admin', 'Employee'])): ?>
                                        <div class="navbar-item has-dropdown is-hoverable">
                                                <a class="navbar-link">Clientes</a>
                                                <div class="navbar-dropdown">
                                                        <a class="navbar-item" href="/customers/create">Nuevo</a>
                                                        <a class="navbar-item" href="/customers">Listado de clientes</a>
                                                        <a class="navbar-item" href="/customers/search">Buscar</a>
                                                </div>
                                        </div>
                                <?php endif; ?>

                                <!-- Menú Usuarios (solo visible para Admin) -->
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Admin'): ?>
                                        <div class="navbar-item has-dropdown is-hoverable">
                                                <a class="navbar-link">Usuarios</a>
                                                <div class="navbar-dropdown">
                                                        <a class="navbar-item" href="/users/create">Nuevo</a>
                                                        <a class="navbar-item" href="/users">Listado de usuarios</a>
                                                        <a class="navbar-item" href="/users/search">Buscar</a>
                                                </div>
                                        </div>
                                <?php endif; ?>

                                <!-- Menú Productos (solo visible para Admin y Employee) -->
                                <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['Admin', 'Employee'])): ?>
                                        <div class="navbar-item has-dropdown is-hoverable">
                                                <a class="navbar-link">Productos</a>
                                                <div class="navbar-dropdown">
                                                        <a class="navbar-item" href="/products/create">Nuevo</a>
                                                        <a class="navbar-item" href="/products">Listado de productos</a>
                                                        <a class="navbar-item" href="/products/search">Buscar</a>
                                                </div>
                                        </div>
                                <?php endif; ?>

                                <!-- Menú Ventas -->
                                <?php if (isset($_SESSION['user_role'])): ?>
                                        <div class="navbar-item has-dropdown is-hoverable">
                                                <a class="navbar-link">Ventas</a>
                                                <div class="navbar-dropdown">
                                                        <?php if (in_array($_SESSION['user_role'], ['Admin', 'Employee', 'Customer'])): ?>
                                                        <a class="navbar-item" href="/sales/create">Nuevo</a>
                                                        <?php endif; ?>
                                                        <a class="navbar-item" href="/sales">Listado de ventas</a>
                                                        <a class="navbar-item" href="/sales/search">Buscar</a>
                                                </div>
                                        </div>
                                <?php endif; ?>

                                <!-- Menú Cliente-Facturas (solo visible para Customer) -->
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Customer'): ?>
                                        <div class="navbar-item has-dropdown is-hoverable">
                                                <a class="navbar-link">Mis Facturas</a>
                                                <div class="navbar-dropdown">
                                                        <a class="navbar-item" href="/invoices">Listado de facturas</a>
                                                        <a class="navbar-item" href="/invoices/search">Buscar</a>
                                                </div>
                                        </div>
                                <?php endif; ?>
                        </div>

                        <div class="navbar-end">
                                <div class="navbar-item">
                                        <div class="buttons">
                                                <?php if (isset($_SESSION['user_id'])): ?>
                                                        <span class="button is-light">
                                                                <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?></strong>
                                                        </span>
                                                        <a class="button is-light" href="/profile">
                                                                Mi cuenta
                                                        </a>
                                                        <a class="button is-light" href="/logout">
                                                                Salir
                                                        </a>
                                                <?php else: ?>
                                                        <a class="button is-light" href="/login">
                                                                Iniciar sesión
                                                        </a>
                                                <?php endif; ?>
                                        </div>
                                </div>
                        </div>
                </div>
         </nav>

         <main class="section">
                <div class="container">
                        <?php if (isset($_SESSION['flash_message'])): ?>
                                <div class="notification is-<?php echo $_SESSION['flash_type'] ?? 'info'; ?>">
                                        <button class="delete"></button>
                                        <?php
                                        echo htmlspecialchars($_SESSION['flash_message']);
                                        unset($_SESSION['flash_message']);
                                        unset($_SESSION['flash_type']);
                                        ?>
                                </div>
                        <? endif; ?>

                        <?php echo $content ?? ''; ?>
                </div>
         </main>

         <script>
                // * Navbar burger menu...
                document.addEventListener("DOMContentLoaded", () => {
                                const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
                                if ($navbarBurgers.length > 0) {
                                        $navbarBurgers.forEach(el => {
                                                el.addEventListener('click', () => {
                                                        const target = el.dataset.target;
                                                        const $target = document.getElementById(target);
                                                        el.classList.toggle('is-active');
                                                        $target.classList.toggle('is-active');
                                                });
                                        });
                                }

                                // * Delete notifications...
                                const deleteButtons = document.querySelectorAll('.notification .delete');
                                deleteButtons.forEach(button => {
                                        button.addEventListener('click', () => {
                                                button.parentElement.remove();
                                        });
                                });
                        });
         </script>
</body>
</html>