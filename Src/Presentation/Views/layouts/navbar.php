<?php

use \PLCTech\Helpers\UrlHelper;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_ENV['APP_NAME']; ?> - Sistema de Gestión</title>
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL']; ?>/CSS/bulma.min.css">
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL']; ?>/CSS/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body style="margin: 0; padding: 0;">
    <!-- Navbar -->
    <nav class="navbar is-primary has-shadow" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="<?php echo UrlHelper::url('/dashboard'); ?>">
                    <i class="fas fa-microchip"></i>
                    <strong style="margin-left: 10px;"><?php echo $_ENV['APP_NAME']; ?></strong>
                </a>
                
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            
            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-start">
                    <!-- ========================================================== -->
                    <!-- INICIO / DASHBOARD (Visible para usuarios logueados)                               -->
                    <!-- ========================================================== -->
                    <div class="navbar-item">
                        <a class="navbar-item" href="<?php echo UrlHelper::url('/dashboard'); ?>">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </div>

                    <!-- ========================================================== -->
                    <!-- CATÁLOGO (Visible para TODOS los roles)                    -->
                    <!-- ========================================================== -->
                    <div class="navbar-item">
                        <a class="navbar-item has-text-weight-bold" href="<?php echo UrlHelper::url('/products/catalog'); ?>" 
                           style="color: #00d1b2; border-bottom: 2px solid #00d1b2;">
                            <i class="fas fa-store"></i> Catálogo
                        </a>
                    </div>
                    
                    <!-- ========================================================== -->
                    <!-- EMPLEADOS (SOLO ADMIN)                                     -->
                    <!-- ========================================================== -->
                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <i class="fas fa-users"></i> Empleados
                            </a>
                            <div class="navbar-dropdown">
                                <a class="navbar-item" href="<?php echo UrlHelper::url('/employees/create'); ?>">
                                    <i class="fas fa-plus"></i> Nuevo
                                </a>
                                <a class="navbar-item" href="<?php echo UrlHelper::url('/employees'); ?>">
                                    <i class="fas fa-list"></i> Listado de empleados
                                </a>
                                <hr class="navbar-divider">
                                <div class="navbar-item">
                                    <div class="field has-addons" style="width: 100%;">
                                        <div class="control is-expanded">
                                            <input class="input is-small" type="text" placeholder="Buscar empleado..." id="searchEmployee">
                                        </div>
                                        <div class="control">
                                            <button class="button is-small is-info" onclick="searchEmployee()">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ========================================================== -->
                    <!-- CLIENTES (SOLO ADMIN y EMPLOYEE) - NO para Customer        -->
                    <!-- ========================================================== -->
                    <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Employee'): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <i class="fas fa-user-friends"></i> Clientes
                            </a>
                            <div class="navbar-dropdown">
                                
                                    <a class="navbar-item" href="<?php echo UrlHelper::url('/customers/create'); ?>">
                                        <i class="fas fa-plus"></i> Nuevo
                                    </a>
                                
                                <a class="navbar-item" href="<?php echo UrlHelper::url('/customers'); ?>">
                                    <i class="fas fa-list"></i> Listado de clientes
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ========================================================== -->
                    <!-- USUARIOS (SOLO ADMIN)                                      -->
                    <!-- ========================================================== -->
                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <i class="fas fa-user-lock"></i> Usuarios
                            </a>
                            <div class="navbar-dropdown">
                                <a class="navbar-item" href="<?php echo UrlHelper::url('/users/create'); ?>">
                                    <i class="fas fa-plus"></i> Nuevo
                                </a>
                                <a class="navbar-item" href="<?php echo UrlHelper::url('/users'); ?>">
                                    <i class="fas fa-list"></i> Listado de usuarios
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ========================================================== -->
                    <!-- PRODUCTOS (ADMIN y EMPLOYEE) - NO para Customer            -->
                    <!-- ========================================================== -->
                    <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Employee'): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <i class="fas fa-boxes"></i> Productos
                            </a>
                            <div class="navbar-dropdown">
                                <?php if ($_SESSION['role'] === 'Admin'): ?>
                                    <a class="navbar-item" href="<?php echo UrlHelper::url('/products/create'); ?>">
                                        <i class="fas fa-plus"></i> Nuevo
                                    </a>
                                <?php endif; ?>
                                <a class="navbar-item" href="<?php echo UrlHelper::url('/products'); ?>">
                                    <i class="fas fa-list"></i> Listado de productos
                                </a>
                                <hr class="navbar-divider">
                                <div class="navbar-item">
                                    <div class="field has-addons" style="width: 100%;">
                                        <div class="control is-expanded">
                                            <input class="input is-small" type="text" placeholder="Buscar producto..." id="searchProduct">
                                        </div>
                                        <div class="control">
                                            <button class="button is-small is-info" onclick="searchProduct()">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ========================================================== -->
                    <!-- VENTAS (ADMIN y EMPLOYEE) - NO para Customer               -->
                    <!-- ========================================================== -->
                    <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Employee'): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <i class="fas fa-shopping-cart"></i> Ventas
                            </a>
                            <div class="navbar-dropdown">
                                <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Employee'): ?>
                                    <a class="navbar-item" href="<?php echo UrlHelper::url('/purchases/create'); ?>">
                                        <i class="fas fa-plus"></i> Nueva venta
                                    </a>
                                <?php endif; ?>
                                <a class="navbar-item" href="<?php echo UrlHelper::url('/purchases'); ?>">
                                    <i class="fas fa-list"></i> Listado de ventas
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ========================================================== -->
                    <!-- MIS COMPRAS (SOLO CUSTOMER)                                -->
                    <!-- ========================================================== -->
                    <?php if ($_SESSION['role'] === 'Customer'): ?>
                        <div class="navbar-item">
                            <a class="navbar-item" href="<?php echo UrlHelper::url('/purchases/customer'); ?>">
                                <i class="fas fa-history"></i> Mis compras
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- ========================================================== -->
                <!-- MENÚ DE USUARIO (SIEMPRE VISIBLE)                          -->
                <!-- ========================================================== -->
                <div class="navbar-end">
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            <i class="fas fa-user-circle"></i>
                            <?php
                            $displayName = $_SESSION['full_name'] ?? $_SESSION['username'];
                            echo htmlspecialchars($displayName);
                            ?>
                        </a>
                        <div class="navbar-dropdown is-right">
                            <a class="navbar-item" href="<?php echo UrlHelper::url('/profile'); ?>">
                                <i class="fas fa-id-card"></i> Mi cuenta
                            </a>
                            
                            <!-- Carrito (solo para clientes) -->
                            <?php if ($_SESSION['role'] === 'Customer'): ?>
                                <hr class="navbar-divider">
                                <a class="navbar-item" href="<?php echo UrlHelper::url('/cart'); ?>">
                                    <i class="fas fa-shopping-cart"></i> Mi carrito
                                    <span class="tag is-info is-small ml-2" id="cartBadge">0</span>
                                </a>
                            <?php endif; ?>
                            
                            <hr class="navbar-divider">
                            <a class="navbar-item" href="<?php echo UrlHelper::url('/logout'); ?>">
                                <i class="fas fa-sign-out-alt"></i> Salir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <main class="main-content">
        <div style="min-height: auto; padding: 1rem 0;">
            <div class="container">
                <!-- Mensajes flash -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="notification is-success is-light">
                        <button class="delete" onclick="this.parentElement.remove()"></button>
                        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message'];
    unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="notification is-danger is-light">
                        <button class="delete" onclick="this.parentElement.remove()"></button>
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['error_message'];
    unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['info_message'])): ?>
                    <div class="notification is-info is-light">
                        <button class="delete" onclick="this.parentElement.remove()"></button>
                        <i class="fas fa-info-circle"></i> <?php echo $_SESSION['info_message'];
    unset($_SESSION['info_message']); ?>
                    </div>
                <?php endif; ?>