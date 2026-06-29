<div class="card mt-4">
        <div class="card-header p-4" style="background-color: dark;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-user-plus"></i> Nuevo Usuario
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item" style="margin-left: 300px;">
                                        <a 
                                                href="<?php echo $_ENV['APP_URL']; ?>/users" 
                                                class="customLink-a"
                                        >
                                                <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
    
        <div class="card-content">
                <form action="<?php echo $_ENV['APP_URL']; ?>/users/store" method="POST">
                        <div class="columns is-multiline">
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-id-card"></i> DNI <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="text" name="dni" required>
                                                </div>
                                                <p class="help is-info">El DNI debe existir en la tabla de Empleados o Clientes</p>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-user"></i> Nombre de Usuario <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="text" name="user" required>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-envelope"></i> Email <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="email" name="email" required>
                                                </div>
                                                <p class="help is-info">El email debe coincidir con el del Empleado o Cliente</p>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-lock"></i> Contraseña <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="password" name="password" required>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                    <div class="field">
                                            <label class="label">
                                                    <i class="fas fa-tag"></i> Rol <span class="has-text-danger">*</span>
                                            </label>
                                            <div class="control">
                                                    <div class="select is-fullwidth">
                                                            <select name="role" required>
                                                                    <option value="Admin">Administrador</option>
                                                                    <option value="Employee">Empleado</option>
                                                                    <option value="Customer">Cliente</option>
                                                            </select>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-toggle-on"></i> Estado
                                                </label>
                                                <div class="control">
                                                        <label class="checkbox">
                                                                <input type="checkbox" name="is_active" checked>
                                                                Activo
                                                        </label>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-12">
                                        <hr>
                                        <div class="field is-grouped">
                                                <div class="control">
                                                        <button type="submit" class="button is-success">
                                                                <i class="fas fa-save"></i> Guardar Usuario
                                                        </button>
                                                </div>
                                                <div class="control">
                                                        <a 
                                                                href="<?php echo $_ENV['APP_URL']; ?>/users" 
                                                                class="button is-light"
                                                        >
                                                                Cancelar
                                                        </a>
                                                </div>
                                        </div>
                                </div>
                        </div>
                    </form>
        </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>