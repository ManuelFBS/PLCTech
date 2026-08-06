<?php

use \PLCTech\Helpers\UrlHelper;

?>
<!DOCTYPE html>
<html lang="es">
        <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Recuperar Contraseña - <?php echo $_ENV['APP_NAME']; ?></title>
                <link rel="stylesheet" href="<?= UrlHelper::url('/CSS/bulma.min.css') ?>">
                <link rel="stylesheet" href="<?= UrlHelper::url('/CSS/styles.css') ?>">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                <style>
                        .reset-container {
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        }
                        .reset-card {
                        max-width: 450px;
                        width: 100%;
                        margin: 20px;
                        border-radius: 10px;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                        }
                </style>
        </head>
        <body>
                <div class="reset-container">
                        <div class="card reset-card">
                                <div class="card-content">
                                        <div class="has-text-centered mb-5">
                                                <i class="fas fa-microchip fa-3x has-text-primary"></i>
                                                <h1 class="title is-3 mt-3"><?php echo $_ENV['APP_NAME']; ?></h1>
                                                <p class="subtitle is-6">Recuperar contraseña</p>
                                        </div>
                                        
                                        <?php if (isset($_SESSION['error_message'])): ?>
                                                <div class="notification is-danger is-light">
                                                        <button class="delete" onclick="this.parentElement.remove()"></button>
                                                        <?php echo $_SESSION['error_message'];
                                                        unset($_SESSION['error_message']); ?>
                                                </div>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($_SESSION['success_message'])): ?>
                                                <div class="notification is-success is-light">
                                                        <button class="delete" onclick="this.parentElement.remove()"></button>
                                                        <?php echo $_SESSION['success_message'];
                                                        unset($_SESSION['success_message']); ?>
                                                </div>
                                        <?php endif; ?>
                                        
                                        <form action="<?= UrlHelper::url('/forgot-password') ?>" method="POST">
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-envelope"></i> Email <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="email" name="email" placeholder="usuario@correo.com" required>
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-envelope"></i>
                                                                </span>
                                                        </div>
                                                        <p class="help">Ingresa el email asociado a tu cuenta</p>
                                                </div>
                                                
                                                <div class="field">
                                                        <div class="control">
                                                                <button type="submit" class="button is-primary is-fullwidth">
                                                                        <i class="fas fa-paper-plane"></i> Enviar enlace de recuperación
                                                                </button>
                                                        </div>
                                                </div>
                                        </form>
                                        
                                        <div class="has-text-centered mt-4">
                                                <p class="is-size-7">
                                                        <i class="fas fa-arrow-left"></i> 
                                                        <a href="<?= UrlHelper::url('/login') ?>">Volver al inicio de sesión</a>
                                                </p>
                                        </div>
                                </div>
                        </div>
                </div>
        </body>
</html>