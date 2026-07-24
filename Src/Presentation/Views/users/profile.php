<div class="card mt-4" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header p-4" style="background-color: dark;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-id-card"></i> Mi Cuenta
                                        </h2>
                                </div>
                        </div>
                </div>
        </div>
        
        <div class="card-content">
                <!-- Datos del usuario -->
                <div class="box" style="background-color: #fafafa;">
                        <h4 class="title is-6">
                                <i class="fas fa-user"></i> Datos del Usuario
                        </h4>
                        <table class="table is-fullwidth is-size-7">
                                <tr>
                                        <td><strong>Usuario:</strong></td>
                                        <td><?php echo htmlspecialchars($_SESSION['username']); ?></td>
                                </tr>
                                <tr>
                                        <td><strong>Nombre:</strong></td>
                                        <td><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'No definido'); ?></td>
                                </tr>
                                <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?php echo htmlspecialchars($_SESSION['user_email']); ?></td>
                                </tr>
                                <tr>
                                        <td><strong>Rol:</strong></td>
                                        <td><span class="tag is-primary"><?php echo htmlspecialchars($_SESSION['role']); ?></span></td>
                                </tr>
                        </table>
                </div>
                
                <!-- Formulario cambiar username -->
                <form action="<?php echo $_ENV['APP_URL']; ?>/users/update-username" method="POST" class="mt-4">
                        <div class="box" style="background-color: #f5f5f5;">
                                <h4 class="title is-6">
                                        <i class="fas fa-user-edit"></i> Cambiar Nombre de Usuario
                                </h4>
                                <div class="field">
                                        <label class="label">Nuevo Usuario</label>
                                        <div class="control">
                                                <input 
                                                        class="input" type="text" 
                                                        name="new_username" 
                                                        placeholder="Ingrese nuevo nombre de usuario" 
                                                        required
                                                >
                                        </div>
                                </div>
                                <div class="field">
                                        <label class="label">Contraseña actual <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                                <input 
                                                        class="input" 
                                                        type="password" 
                                                        name="current_password" 
                                                        placeholder="Ingrese su contraseña actual" 
                                                        required
                                                >
                                        </div>
                                        <p class="help">Requerido para confirmar identidad</p>
                                </div>
                                <div class="control">
                                        <button type="submit" class="button is-info">
                                                <i class="fas fa-save"></i> Cambiar Usuario
                                        </button>
                                </div>
                        </div>
                </form>
                
                <!-- Formulario cambiar contraseña -->
                <form action="<?php echo $_ENV['APP_URL']; ?>/users/update-password" method="POST" class="mt-4">
                        <div class="box" style="background-color: #f5f5f5;">
                                <h4 class="title is-6">
                                        <i class="fas fa-key"></i> Cambiar Contraseña
                                </h4>
                                <div class="field">
                                        <label class="label">Contraseña actual <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                                <input 
                                                        class="input" 
                                                        type="password" 
                                                        name="current_password" 
                                                        placeholder="Ingrese su contraseña actual" 
                                                        required
                                                >
                                        </div>
                                </div>
                                <div class="field">
                                        <label class="label">Nueva contraseña <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                                <input 
                                                        class="input" 
                                                        type="password" 
                                                        name="new_password" 
                                                        placeholder="Mínimo 8 caracteres" 
                                                        required minlength="8"
                                                >
                                        </div>
                                </div>
                                <div class="field">
                                        <label class="label">Confirmar nueva contraseña <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                                <input 
                                                        class="input" 
                                                        type="password" 
                                                        name="confirm_password" 
                                                        placeholder="Repita la nueva contraseña" 
                                                        required
                                                >
                                        </div>
                                </div>
                                <div class="control">
                                        <button type="submit" class="button is-warning">
                                                <i class="fas fa-key"></i> Cambiar Contraseña
                                        </button>
                                </div>
                        </div>
                </form>
        </div>
</div>

<script>
        // * Validar que las contraseñas coincidan...
        document.querySelector('form[action*="update-password"]')
                .addEventListener('submit', function(e) {
                const newPass = this.querySelector('input[name="new_password"]').value;
                const confirmPass = this.querySelector('input[name="confirm_password"]').value;
                
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
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>