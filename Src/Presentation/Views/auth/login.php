<?php

use \PLCTech\Helpers\UrlHelper;

?>

<!DOCTYPE html>
<html lang="es">
        <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Login - <?php echo $_ENV['APP_NAME']; ?></title>
                <link rel="stylesheet" href="<?= UrlHelper::url('/CSS/bulma.min.css') ?>">
                <link rel="stylesheet" href="<?= UrlHelper::url('/CSS/styles.css') ?>">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                <style>
                        .login-container {
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        }
                        .login-card {
                        max-width: 400px;
                        width: 100%;
                        margin: 20px;
                        border-radius: 10px;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                        }
                </style>
        </head>
        <body>
                <div class="login-container">
                        <div class="card login-card">
                                <div class="card-content">
                                        <div class="has-text-centered mb-5">
                                                <i class="fas fa-microchip fa-3x has-text-primary"></i>
                                                        <h1 class="title is-3 mt-3"><?php echo $_ENV['APP_NAME']; ?></h1>
                                                <p class="subtitle is-6">Sistema de Gestión</p>
                                        </div>
                                        
                                        <!-- Mensajes flash -->
                                        <?php if (isset($_SESSION['error_message'])): ?>
                                        <div class="notification is-danger is-light">
                                                <button class="delete" onclick="this.parentElement.remove()"></button>
                                                <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['error_message'];
        unset($_SESSION['error_message']); ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($_SESSION['success_message'])): ?>
                                        <div class="notification is-success is-light">
                                                <button class="delete" onclick="this.parentElement.remove()"></button>
                                                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message'];
        unset($_SESSION['success_message']); ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <form action="<?= UrlHelper::url('/do-login') ?>" method="POST">
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-user"></i> Usuario o Email
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="text" name="username" placeholder="Ingrese su usuario o email" required autofocus>
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-envelope"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                                
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-lock"></i> Contraseña
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="password" name="password" placeholder="Ingrese su contraseña" required>
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-key"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                                
                                                <div class="field">
                                                        <div class="control">
                                                                <button type="submit" class="button is-primary is-fullwidth">
                                                                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                                                                </button>
                                                        </div>
                                                </div>
                                        </form>
                                        
                                        <div class="has-text-centered mt-4">
                                                <p class="is-size-7 has-text-grey">
                                                        <i class="fas fa-shield-alt"></i> Sistema seguro con autenticación JWT
                                                </p>
                                                <p class="mt-3">
                                                        ¿No tienes cuenta?
                                                        <a href="<?= UrlHelper::url('/register') ?>" class="has-text-primary">
                                                                <i class="fas fa-user-plus"></i> Regístrate aquí
                                                        </a>
                                                </p>
                                        </div>
                                </div>
                        </div>
                </div>
        </body>
</html>