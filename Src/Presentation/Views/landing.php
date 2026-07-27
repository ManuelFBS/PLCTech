<?php

use \PLCTech\Helpers\UrlHelper;

?>

<!DOCTYPE html>
<html lang="en">
        <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PLC Tech Pulse - Bienvenido</title>
                <link rel="stylesheet" href="<?= UrlHelper::url('/CSS/bulma.min.css') ?>">
                <link rel="stylesheet" href="<?= UrlHelper::url('/CSS/styles.css') ?>">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                <style>
                        .hero {
                                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                                min-height: 100vh;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                        }
                        .hero-title {
                                font-size: 4rem;
                                font-weight: 700;
                                background: linear-gradient(45deg, #00d1b2, #00b894, #00a3d6);
                                -webkit-background-clip: text;
                                -webkit-text-fill-color: transparent;
                                background-clip: text;
                                text-shadow: 0 0 40px rgba(0, 209, 178, 0.2);
                        }
                        .hero-subtitle {
                                color: #b2bec3;
                                font-size: 1.5rem;
                                font-weight: 300;
                        }
                        .hero-description {
                                color: #dfe6e9;
                                font-size: 1.1rem;
                                max-width: 600px;
                                margin: 0 auto;
                                line-height: 1.8;
                        }
                        .floating-icons {
                                position: absolute;
                                font-size: 4rem;
                                opacity: 0.1;
                                color: #00d1b2;
                                animation: float 6s ease-in-out infinite;
                        }
                        .floating-icons:nth-child(1) { top: 10%; left: 5%; animation-delay: 0s; }
                        .floating-icons:nth-child(2) { top: 20%; right: 8%; animation-delay: 1.5s; }
                        .floating-icons:nth-child(3) { bottom: 15%; left: 10%; animation-delay: 3s; }
                        .floating-icons:nth-child(4) { bottom: 25%; right: 5%; animation-delay: 4.5s; }
                        .floating-icons:nth-child(5) { top: 50%; left: 2%; animation-delay: 2s; }
                        .floating-icons:nth-child(6) { top: 45%; right: 3%; animation-delay: 3.5s; }
                        
                        @keyframes float {
                                0%, 100% { transform: translateY(0) rotate(0deg); }
                                50% { transform: translateY(-20px) rotate(5deg); }
                        }

                        .btn-primary {
                                background: linear-gradient(45deg, #00d1b2, #00b894);
                                border: none;
                                color: white;
                                padding: 16px 40px;
                                border-radius: 50px;
                                font-size: 1.1rem;
                                font-weight: 600;
                                transition: all 0.3s ease;
                                box-shadow: 0 4px 20px rgba(0, 209, 178, 0.3);
                        }
                        .btn-primary:hover {
                                transform: translateY(-3px);
                                box-shadow: 0 8px 30px rgba(0, 209, 178, 0.5);
                                color: white;
                        }
                        .btn-secondary {
                                background: transparent;
                                border: 2px solid #00d1b2;
                                color: #00d1b2;
                                padding: 14px 35px;
                                border-radius: 50px;
                                font-size: 1rem;
                                font-weight: 600;
                                transition: all 0.3s ease;
                        }
                        .btn-secondary:hover {
                                background: #00d1b2;
                                color: white;
                        }

                        .stats {
                                display: flex;
                                justify-content: center;
                                gap: 60px;
                                margin-top: 40px;
                        }
                        .stat-item {
                                color: #dfe6e9;
                                text-align: center;
                        }
                        .stat-number {
                                font-size: 2rem;
                                font-weight: 700;
                                color: #00d1b2;
                        }
                        .stat-label {
                                font-size: 0.9rem;
                                color: #b2bec3;
                        }
                </style>
        </head>
        <body>
                <div class="hero" style="position: relative; overflow: hidden;">
                        <!-- Iconos flotantes de fondo -->
                        <div class="floating-icons"><i class="fas fa-microchip"></i></div>
                        <div class="floating-icons"><i class="fas fa-laptop"></i></div>
                        <div class="floating-icons"><i class="fas fa-server"></i></div>
                        <div class="floating-icons"><i class="fas fa-database"></i></div>
                        <div class="floating-icons"><i class="fas fa-cogs"></i></div>
                        <div class="floating-icons"><i class="fas fa-cloud"></i></div>
                
                        <div class="container has-text-centered" style="position: relative; z-index: 1;">
                                <!-- Logo -->
                                <div style="margin-bottom: 30px;">
                                        <i class="fas fa-microchip" style="font-size: 5rem; color: #00d1b2;"></i>
                                </div>
                                
                                <!-- Título -->
                                <h1 class="hero-title">
                                        PLC Tech Pulse
                                </h1>
                                <p class="hero-subtitle mt-2">
                                        <span style="color: #00d1b2;">PLCTech</span> · Gestión Inteligente
                                </p>
                                
                                <!-- Descripción -->
                                <p class="hero-description mt-4">
                                        Solución completa para la gestión de inventario, ventas y clientes.
                                        Tecnología al servicio de tu negocio.
                                </p>

                                <!-- Botones -->
                                <div style="display: flex; gap: 20px; justify-content: center; margin-top: 40px; flex-wrap: wrap;">
                                        <a href="<?= UrlHelper::url('/login') ?>" class="btn-primary">
                                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                                        </a>
                                        <a href="<?= UrlHelper::url('/customers/create') ?>" class="btn-secondary">
                                                <i class="fas fa-user-plus"></i> Crear Cuenta
                                        </a>
                                </div>

                                <!-- Estadísticas -->
                                <div class="stats">
                                        <div class="stat-item">
                                                <div class="stat-number">✓</div>
                                                <div class="stat-label">Gestión de Inventario</div>
                                        </div>
                                        <div class="stat-item">
                                                <div class="stat-number">✓</div>
                                                <div class="stat-label">Ventas en Línea</div>
                                        </div>
                                        <div class="stat-item">
                                                <div class="stat-number">✓</div>
                                                <div class="stat-label">Clientes</div>
                                        </div>
                                        <div class="stat-item">
                                                <div class="stat-number">✓</div>
                                                <div class="stat-label">Reportes</div>
                                        </div>
                                </div>

                                <!-- Footer -->
                                <div style="margin-top: 50px; color: #636e72; font-size: 0.85rem;">
                                        <p>&copy; <?php echo date('Y'); ?> PLC Tech Pulse. Todos los derechos reservados.</p>
                                </div>
                        </div>
                </div>
        </body>
</html>