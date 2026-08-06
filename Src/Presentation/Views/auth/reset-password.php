<?php

use \PLCTech\Helpers\UrlHelper;

?>

<!DOCTYPE html>
<html lang="es">
        <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Restablecer Contraseña - <?php echo $_ENV['APP_NAME']; ?></title>
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
                                                <p class="subtitle is-6">Nueva contraseña</p>
                                        </div>
                                        
                                        <?php if (isset($_SESSION['error_message'])): ?>
                                                <div class="notification is-danger is-light">
                                                        <button class="delete" onclick="this.parentElement.remove()"></button>
                                                        <?php echo $_SESSION['error_message'];
                                                        unset($_SESSION['error_message']); ?>
                                                </div>
                                        <?php endif; ?>
                                        
                                        <form action="<?= UrlHelper::url('/reset-password') ?>" method="POST">
                                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token ?? ''); ?>">
                                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId ?? ''); ?>">
                                                
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-lock"></i> Nueva contraseña <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="password" name="new_password" id="new_password" 
                                                                        placeholder="Mínimo 8 caracteres" required minlength="8">
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-lock"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                                
                                                <div class="field">
                                                        <label class="label">
                                                                <i class="fas fa-check-circle"></i> Confirmar contraseña <span class="has-text-danger">*</span>
                                                        </label>
                                                        <div class="control has-icons-left">
                                                                <input class="input" type="password" name="confirm_password" id="confirm_password" 
                                                                        placeholder="Repite la nueva contraseña" required>
                                                                <span class="icon is-small is-left">
                                                                        <i class="fas fa-check-circle"></i>
                                                                </span>
                                                        </div>
                                                </div>
                                                
                                                <div id="passwordStrength" class="mt-2" style="display: none;">
                                                        <progress id="strengthBar" value="0" max="100" style="width: 100%; height: 8px;"></progress>
                                                        <p id="strengthText" class="is-size-7"></p>
                                                </div>
                                                
                                                <div class="field mt-3">
                                                        <div class="control">
                                                                <button type="submit" class="button is-primary is-fullwidth">
                                                                        <i class="fas fa-key"></i> Restablecer contraseña
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
                                const newPass = document.getElementById('new_password').value;
                                const confirmPass = document.getElementById('confirm_password').value;
                                
                                if (newPass !== confirmPass) {
                                        e.preventDefault();
                                        alert('Las contraseñas no coinciden');
                                        return false;
                                }
                                
                                if (newPass.length < 8) {
                                        e.preventDefault();
                                        alert('La contraseña debe tener al menos 8 caracteres');
                                        return false;
                                }
                        });
                        
                        // * Medidor de fortaleza de contraseña...
                        document.getElementById('new_password').addEventListener('input', function() {
                                const password = this.value;
                                const strengthDiv = document.getElementById('passwordStrength');
                                const bar = document.getElementById('strengthBar');
                                const text = document.getElementById('strengthText');
                                
                                if (password.length === 0) {
                                        strengthDiv.style.display = 'none';
                                        return;
                                }
                                
                                strengthDiv.style.display = 'block';
                        
                                let score = 0;
                                if (password.length >= 8) score += 25;
                                if (password.match(/[a-z]/)) score += 15;
                                if (password.match(/[A-Z]/)) score += 20;
                                if (password.match(/[0-9]/)) score += 20;
                                if (password.match(/[^a-zA-Z0-9]/)) score += 20;
                                
                                bar.value = score;
                        
                                if (score < 40) {
                                        text.textContent = '🔴 Contraseña débil';
                                        text.style.color = 'red';
                                } else if (score < 70) {
                                        text.textContent = '🟡 Contraseña media';
                                        text.style.color = 'orange';
                                } else {
                                        text.textContent = '🟢 Contraseña fuerte';
                                        text.style.color = 'green';
                                }
                        });
                </script>
        </body>
</html>