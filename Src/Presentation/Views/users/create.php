<div class="card mt-4">
    <div class="card-header p-4" style="background-color: #f5f5f5;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-user-plus"></i> Nuevo Usuario
                    </h2>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a href="<?php echo $_ENV['APP_URL']; ?>/users" class="button is-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <form action="<?php echo $_ENV['APP_URL']; ?>/users/store" method="POST" id="userForm">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-id-card"></i> DNI <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <input class="input" type="text" name="dni" required 
                                   placeholder="Número de identificación">
                        </div>
                    </div>
                </div>
                
                <div class="column is-6">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-user"></i> Nombre de Usuario <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <input class="input" type="text" name="user" required 
                                   placeholder="Ej: jperez">
                        </div>
                    </div>
                </div>
                
                <div class="column is-6">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-envelope"></i> Email <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <input class="input" type="email" name="email" required 
                                   placeholder="usuario@empresa.com">
                        </div>
                    </div>
                </div>
                
                <div class="column is-6">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-lock"></i> Contraseña <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <input class="input" type="password" name="password" required 
                                   placeholder="Mínimo 8 caracteres">
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
                                <select name="role" id="role" required>
                                    <option value="">Seleccione un rol...</option>
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
                
                <div class="column is-6" id="employeeField" style="display: none;">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-user-tie"></i> Empleado <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="employee_id">
                                    <option value="">Seleccione un empleado...</option>
                                    <?php if (!empty($employees) && count($employees) > 0): ?>
                                        <?php foreach ($employees as $emp): ?>
                                            <option value="<?php echo $emp->getId(); ?>">
                                                <?php echo htmlspecialchars($emp->getFullName()); ?> - <?php echo $emp->getDni(); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No hay empleados disponibles</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="column is-6" id="customerField" style="display: none;">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-user-friends"></i> Cliente <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="customer_id">
                                    <option value="">Seleccione un cliente...</option>
                                    <?php if (!empty($customers) && count($customers) > 0): ?>
                                        <?php foreach ($customers as $cust): ?>
                                            <option value="<?php echo $cust->getId(); ?>">
                                                <?php echo htmlspecialchars($cust->getFullName()); ?> - <?php echo $cust->getDni(); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No hay clientes disponibles</option>
                                    <?php endif; ?>
                                </select>
                            </div>
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
                            <a href="<?php echo $_ENV['APP_URL']; ?>/users" class="button is-light">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Mostrar/ocultar campos según el rol seleccionado
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        const employeeField = document.getElementById('employeeField');
        const customerField = document.getElementById('customerField');
        
        // Ocultar ambos campos primero
        if (employeeField) employeeField.style.display = 'none';
        if (customerField) customerField.style.display = 'none';
        
        // Mostrar el campo correspondiente según el rol
        if (role === 'Employee') {
            if (employeeField) employeeField.style.display = 'block';
        } else if (role === 'Customer') {
            if (customerField) customerField.style.display = 'block';
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>