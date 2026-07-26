<?php

use \PLCTech\Helpers\UrlHelper;

?>

<div class="card mt-4">
        <div class="card-header p-4" style="background-color: dark;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-user-plus"></i> Nuevo Cliente
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item" style="margin-left: 300px;">
                                        <a 
                                                href="<?php UrlHelper::url('/customers'); ?>" 
                                                class="customLink-a"
                                        >
                                                <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
        
        <div class="card-content">
                <form 
                        action="<?php echo UrlHelper::url('/customers/store'); ?>" 
                        method="POST"
                >
                        <div class="columns is-multiline">
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-id-card"></i> DNI <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="text" name="dni" required>
                                                </div>
                                        </div>
                                </div>

                                <!-- *************************************************************** -->
                                <!-- ========================================================== -->
                                <!-- DATOS DEL USUARIO CREADO (PERSISTENTE) - VERSIÓN MEJORADA -->
                                <!-- ========================================================== -->
                                <?php if (isset($_SESSION['customer_created']) && $_SESSION['customer_created'] === true): ?>
                                        <div class="box" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border: 2px solid #4caf50; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2);">
                                                <!-- Encabezado -->
                                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; border-bottom: 2px solid #4caf50; padding-bottom: 10px;">
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                                <i class="fas fa-check-circle" style="font-size: 28px; color: #4caf50;"></i>
                                                                <h4 class="title is-5" style="color: #2e7d32; margin: 0;">
                                                                        ¡Cliente Registrado Exitosamente!
                                                                </h4>
                                                        </div>
                                                        <span class="tag is-success is-medium" style="font-size: 0.9rem;">
                                                                <i class="fas fa-user-check"></i> Completado
                                                        </span>
                                                </div>
                                                
                                                <!-- Datos del cliente y usuario en 2 columnas -->
                                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                                                        <!-- Columna izquierda: Datos del cliente -->
                                                        <div style="background: white; border-radius: 8px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                                                <h5 style="color: #1b5e20; margin-top: 0; margin-bottom: 12px; border-bottom: 2px solid #e8f5e9; padding-bottom: 8px;">
                                                                        <i class="fas fa-user"></i> Datos del Cliente
                                                                </h5>
                                                                <table style="width: 100%; font-size: 0.95rem;">
                                                                        <tr>
                                                                                <td style="padding: 4px 0; font-weight: 600; color: #555; width: 80px;">Nombre:</td>
                                                                                <td style="padding: 4px 0; color: #333;"><?php echo htmlspecialchars($_SESSION['customer_full_name'] ?? ''); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="padding: 4px 0; font-weight: 600; color: #555;">DNI:</td>
                                                                                <td style="padding: 4px 0; color: #333;"><?php echo htmlspecialchars($_SESSION['customer_dni'] ?? ''); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="padding: 4px 0; font-weight: 600; color: #555;">Email:</td>
                                                                                <td style="padding: 4px 0; color: #333;"><?php echo htmlspecialchars($_SESSION['customer_email'] ?? ''); ?></td>
                                                                        </tr>
                                                                </table>
                                                        </div>
                                                        
                                                        <!-- Columna derecha: Credenciales de acceso -->
                                                        <div style="background: #fff3e0; border-radius: 8px; padding: 15px; border: 2px solid #ffb74d; box-shadow: 0 2px 8px rgba(255, 183, 77, 0.2);">
                                                                <h5 style="color: #e65100; margin-top: 0; margin-bottom: 12px; border-bottom: 2px solid #ffe0b2; padding-bottom: 8px;">
                                                                <i class="fas fa-key"></i> Credenciales de Acceso
                                                                </h5>
                                                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                        <span style="font-weight: 600; color: #555; min-width: 85px;">
                                                                        <i class="fas fa-user"></i> Usuario:
                                                                        </span>
                                                                        <span class="tag is-info is-medium" style="font-size: 1rem; padding: 8px 16px;">
                                                                        <?php echo htmlspecialchars($_SESSION['customer_username'] ?? ''); ?>
                                                                        </span>
                                                                        <button onclick="copyToClipboard('<?php echo htmlspecialchars($_SESSION['customer_username'] ?? ''); ?>', 'Usuario')" 
                                                                                class="button is-small is-light" style="border: none; background: transparent; cursor: pointer; color: #1976d2;">
                                                                        <i class="fas fa-copy"></i>
                                                                        </button>
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                        <span style="font-weight: 600; color: #555; min-width: 85px;">
                                                                        <i class="fas fa-lock"></i> Contraseña:
                                                                        </span>
                                                                        <span class="tag is-warning is-medium" style="font-size: 1rem; padding: 8px 16px; background-color: #fff3cd; color: #856404;">
                                                                        <?php echo htmlspecialchars($_SESSION['customer_password'] ?? ''); ?>
                                                                        </span>
                                                                        <button onclick="copyToClipboard('<?php echo htmlspecialchars($_SESSION['customer_password'] ?? ''); ?>', 'Contraseña')" 
                                                                                class="button is-small is-light" style="border: none; background: transparent; cursor: pointer; color: #1976d2;">
                                                                        <i class="fas fa-copy"></i>
                                                                        </button>
                                                                </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                
                                                <!-- Advertencia -->
                                                <div style="background: #fff8e1; border-left: 4px solid #ffa726; padding: 12px 15px; border-radius: 4px; margin-bottom: 15px;">
                                                        <i class="fas fa-exclamation-triangle" style="color: #ffa726;"></i>
                                                        <strong style="color: #e65100;">Importante:</strong>
                                                        <span style="color: #555;">El cliente debe cambiar su contraseña al iniciar sesión por primera vez.</span>
                                                </div>
                                                
                                                <!-- Botones de acción -->
                                                <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 2px solid #c8e6c9; padding-top: 15px;">
                                                        <a href="<?php echo UrlHelper::url('/customers'); ?>" class="button is-success">
                                                                <i class="fas fa-list"></i> Ir al listado
                                                        </a>
                                                        <button onclick="clearCustomerSession()" class="button is-light">
                                                                <i class="fas fa-plus"></i> Registrar otro cliente
                                                        </button>
                                                </div>
                                        </div>

                                        <script>
                                                function copyToClipboard(text, label) {
                                                        if (navigator.clipboard && navigator.clipboard.writeText) {
                                                                navigator.clipboard.writeText(text).then(() => {
                                                                        showNotification('✅ ' + label + ' copiado al portapapeles');
                                                                }).catch(() => {
                                                                        fallbackCopy(text, label);
                                                                });
                                                        } else {
                                                                fallbackCopy(text, label);
                                                        }
                                                }

                                                function fallbackCopy(text, label) {
                                                        const textarea = document.createElement('textarea');
                                                        textarea.value = text;
                                                        textarea.style.position = 'fixed';
                                                        textarea.style.opacity = '0';
                                                        document.body.appendChild(textarea);
                                                        textarea.select();
                                                        try {
                                                                document.execCommand('copy');
                                                                showNotification('✅ ' + label + ' copiado al portapapeles');
                                                        } catch (err) {
                                                                showNotification('⚠️ No se pudo copiar. Selecciona manualmente.');
                                                        }
                                                        document.body.removeChild(textarea);
                                                }

                                                function showNotification(message) {
                                                        const div = document.createElement('div');
                                                        div.style.cssText = `
                                                                position: fixed; bottom: 20px; right: 20px; 
                                                                background: #43a047; color: white; 
                                                                padding: 12px 24px; border-radius: 8px; 
                                                                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                                                z-index: 9999; font-size: 1rem;
                                                                animation: fadeIn 0.3s ease;
                                                        `;
                                                        div.textContent = message;
                                                        document.body.appendChild(div);
                                                        setTimeout(() => {
                                                                div.style.opacity = '0';
                                                                div.style.transition = 'opacity 0.5s';
                                                                setTimeout(() => div.remove(), 500);
                                                        }, 3000);
                                                }

                                                function clearCustomerSession() {
                                                        fetch("<?php echo UrlHelper::url('/customers/clear-user-data'); ?>", {
                                                                method: 'POST',
                                                                headers: { 'Content-Type': 'application/json' }
                                                        }).then(() => {
                                                                // > Limpiar campos del formulario...
                                                                document.querySelector('input[name="dni"]').value = '';
                                                                document.querySelector('input[name="full_name"]').value = '';
                                                                document.querySelector('input[name="birthdate"]').value = '';
                                                                document.querySelector('input[name="email"]').value = '';
                                                                document.querySelector('input[name="phone_number"]').value = '';
                                                                location.reload();
                                                        });
                                                }
                                        </script>

                                        <style>
                                                @keyframes fadeIn {
                                                        from { opacity: 0; transform: translateY(20px); }
                                                        to { opacity: 1; transform: translateY(0); }
                                                }
                                        </style>

                                        <hr>
                                <?php endif; ?>
                                <!-- *************************************************************** -->
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-envelope"></i> Email <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="email" name="email" required>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-12">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-user"></i> Nombre Completo <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="text" name="full_name" required>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-calendar-alt"></i> Fecha de Nacimiento <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="date" name="birthdate" required>
                                                </div>
                                                <p class="help">No puede ser una fecha futura</p>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-phone"></i> Teléfono
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="tel" name="phone_number">
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-12">
                                        <hr>
                                        <div class="field is-grouped">
                                                <div class="control">
                                                        <button type="submit" class="button is-success">
                                                                <i class="fas fa-save"></i> Guardar Cliente
                                                        </button>
                                                </div>
                                                <div class="control">
                                                        <a href="<?php echo UrlHelper::url('/customers'); ?>" class="button is-light">
                                                                Cancelar
                                                        </a>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <?php if (isset($_SESSION['customer_created'])): ?>
                                <div class="notification is-success is-light">
                                        <i class="fas fa-check-circle"></i>
                                        <strong>¡Cliente registrado exitosamente!</strong><br>
                                        Usuario: <strong><?php echo $_SESSION['customer_username']; ?></strong><br>
                                        Contaseña temporal: <strong><?php echo $_SESSION['customer_password'] ?></strong>
                                        <br><small>El cliente debe cambiar su contraseña al iniciar sesión.</small>
                                </div>
                                <?php unset($_SESSION['customer_created']); ?>
                                <?php unset($_SESSION['customer_username']); ?>
                                <?php unset($_SESSION['customer_password']); ?>
                        <?php endif; ?>
                </form>
        </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>