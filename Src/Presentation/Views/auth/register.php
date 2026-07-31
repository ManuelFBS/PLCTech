<?php

use \PLCTech\Helpers\UrlHelper;

?>

<!DOCTYPE html>
<html lang="es">
        <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Registro - <?php echo $_ENV['APP_NAME']; ?></title>
                <link rel="stylesheet" href="<?= UrlHelper::url('/CSS/bulma.min.css') ?>">
                <link rel="stylesheet" href="<?= UrlHelper::url('/CSS/styles.css') ?>">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                <style>
                        .register-container {
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        }
                        .register-card {
                        max-width: 500px;
                        width: 100%;
                        margin: 20px;
                        border-radius: 10px;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                        }
                </style>
        </head>
        <body>
                <div class="register-container">
                        <div class="card register-card">
                                <div class="card-content">
                                        <div class="has-text-centered mb-5">
                                                <i class="fas fa-microchip fa-3x has-text-primary"></i>
                                                <h1 class="title is-3 mt-3"><?php echo $_ENV['APP_NAME']; ?></h1>
                                                <p class="subtitle is-6">Crear nueva cuenta</p>
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
                                        
                                        <form action="<?= UrlHelper::url('/register') ?>" method="POST">
                                                <!-- DNI -->
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-id-card"></i> DNI / Cédula <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="text" name="dni" placeholder="Ingrese su DNI" required>
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-id-card"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                        
                                                <!-- Nombre Completo -->
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-user"></i> Nombre Completo <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="text" name="full_name" placeholder="Nombres y apellidos" required>
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-user"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                                
                                                <!-- Fecha de Nacimiento -->
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-calendar-alt"></i> Fecha de Nacimiento <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="date" name="birthdate" required max="<?php echo date('Y-m-d'); ?>">
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-calendar-alt"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                                
                                                <!-- Email -->
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-envelope"></i> Email <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="email" name="email" placeholder="ejemplo@correo.com" required>
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-envelope"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                        
                                                <!-- Teléfono -->
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-phone"></i> Teléfono
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="tel" name="phone_number" placeholder="Número de contacto">
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-phone"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                        
                                                <!-- Contraseña -->
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-lock"></i> Contraseña <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="password" name="password" placeholder="Mínimo 8 caracteres" required minlength="8">
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-lock"></i>
                                                                </span>
                                                        </div>
                                                        <p class="help">La contraseña debe tener al menos 8 caracteres</p>
                                                </div>
                                                
                                                <!-- Confirmar Contraseña -->
                                                <div class="field">
                                                        <label class="label">
                                                        <i class="fas fa-check-circle"></i> Confirmar Contraseña <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                        <input class="input" type="password" name="confirm_password" placeholder="Repita su contraseña" required>
                                                        <span class="icon is-small is-left">
                                                                <i class="fas fa-check-circle"></i>
                                                        </span>
                                                        </div>
                                                </div>
                                        
                                                <!-- Botón -->
                                                <div class="field">
                                                        <div class="control">
                                                                <button type="submit" class="button is-primary is-fullwidth">
                                                                        <i class="fas fa-user-plus"></i> Registrarse
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
        
                <script>
                        // * Validar que las contraseñas coincidan...
                        document.querySelector('form').addEventListener('submit', function(e) {
                                const password = this.querySelector('input[name="password"]').value;
                                const confirm = this.querySelector('input[name="confirm_password"]').value;
                        
                                if (password !== confirm) {
                                        e.preventDefault();
                                        alert('Las contraseñas no coinciden');
                                        return false;
                                }
                        
                                if (password.length < 8) {
                                        e.preventDefault();
                                        alert('La contraseña debe tener al menos 8 caracteres');
                                        return false;
                                }
                        });
                </script>
        </body>
</html>