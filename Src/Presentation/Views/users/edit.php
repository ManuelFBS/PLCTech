<?php

use \PLCTech\Helpers\UrlHelper;

// * Verificar que las variables existen...
if (!isset($user)) {
        $_SESSION['error_message'] = 'No se encontraron datos del usuario';
        header('Location: ' . UrlHelper::url('/users'));
        exit;
}
?>

<div class="card mt-4">
        <div class="card-header p-4" style="background-color: dark;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-user-edit"></i> Editar Usuario
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item" style="margin-left: 300px;">
                                        <a 
                                                href="<?php echo UrlHelper::url('/users'); ?>" 
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
                        action="<?php echo UrlHelper::url('/users/update'); ?>" 
                        method="POST"
                >
                        <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                    
                        <div class="columns is-multiline">
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-id-card"></i> DNI <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="text" name="dni" required 
                                                                value="<?php echo htmlspecialchars($user->dni); ?>">
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
                                                                value="<?php echo htmlspecialchars($user->user); ?>">
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
                                                                value="<?php echo htmlspecialchars($user->email); ?>">
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-lock"></i> Contraseña
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="password" name="password" 
                                                                placeholder="Dejar en blanco para no cambiar">
                                                </div>
                                                <p class="help">Solo completar si desea cambiar la contraseña</p>
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
                                                                        <option value="Admin" <?php echo $user->role === 'Admin' ? 'selected' : ''; ?>>Administrador</option>
                                                                        <option value="Employee" <?php echo $user->role === 'Employee' ? 'selected' : ''; ?>>Empleado</option>
                                                                        <option value="Customer" <?php echo $user->role === 'Customer' ? 'selected' : ''; ?>>Cliente</option>
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
                                                                <input type="checkbox" name="is_active" <?php echo $user->is_active ? 'checked' : ''; ?>>
                                                                Activo
                                                        </label>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6" id="employeeField" style="display: <?php echo $user->role === 'Employee' ? 'block' : 'none'; ?>;">
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
                                                                                        <option value="<?php echo $emp->getId(); ?>" 
                                                                                                <?php echo ($user->employee_id == $emp->getId()) ? 'selected' : ''; ?>>
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
                                
                                <div class="column is-6" id="customerField" style="display: <?php echo $user->role === 'Customer' ? 'block' : 'none'; ?>;">
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
                                                                                    <option value="<?php echo $cust->getId(); ?>"
                                                                                            <?php echo ($user->customer_id == $cust->getId()) ? 'selected' : ''; ?>>
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
                                                                <i class="fas fa-save"></i> Actualizar Usuario
                                                        </button>
                                                </div>
                                                <div class="control">
                                                        <a href="<?php echo UrlHelper::url('/users'); ?>" class="button is-light">
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
        // * Mostrar/ocultar campos según el rol seleccionado...
        document.getElementById('role').addEventListener('change', function() {
                const role = this.value;
                const employeeField = document.getElementById('employeeField');
                const customerField = document.getElementById('customerField');
                
                if (employeeField) employeeField.style.display = 'none';
                if (customerField) customerField.style.display = 'none';
                
                if (role === 'Employee' || role === 'Admin') {
                        employeeField.style.display = 'block';
                        if (customerField) customerField.style.display = 'none';
                } else if (role === 'Customer') {
                        customerField.style.display = 'block';
                        if (employeeField) employeeField.style.display = 'none';
                }
        });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>