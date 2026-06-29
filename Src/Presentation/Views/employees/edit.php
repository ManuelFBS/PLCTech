<?php

// * Verificar que la variable $employee existe...
if (!isset($employee) || !$employee) {
        $_SESSION['error_message'] = 'No se encontraron datos del empleado';
        header('Location: ' . $_ENV['APP_URL'] . '/employees');
        exit;
}

?>

<div class="card mt-4">
        <div class="card-header p-4" style="background-color: dark;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-user-edit"></i> Editar Empleado
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item">
                                        <a href="<?php echo $_ENV['APP_URL']; ?>/employees" class="customLink-a">
                                                <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
    
        <div class="card-content">
                <form action="<?php echo $_ENV['APP_URL']; ?>/employees/update" method="POST" id="employeeForm">
                        <input type="hidden" name="id" value="<?php echo $employee->id; ?>">
                
                        <div class="columns is-multiline">
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-id-card"></i> DNI <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="text" name="dni" required 
                                                                value="<?php echo htmlspecialchars($employee->dni); ?>"
                                                                pattern="[0-9]{8,10}" title="DNI debe tener 8-10 dígitos">
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
                                                                value="<?php echo htmlspecialchars($employee->email); ?>">
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-user"></i> Nombres <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="text" name="names" required 
                                                                value="<?php echo htmlspecialchars($employee->names); ?>">
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-user-tag"></i> Apellidos <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="text" name="surnames" required 
                                                                value="<?php echo htmlspecialchars($employee->surnames); ?>">
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-calendar-alt"></i> Fecha de Nacimiento <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="date" name="birthdate" required 
                                                                value="<?php echo $employee->birthdate; ?>"
                                                                max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                                                </div>
                                                <p class="help">Debe ser mayor de 18 años</p>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-phone"></i> Teléfono
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="tel" name="phone_number" 
                                                                value="<?php echo htmlspecialchars($employee->phone_number ?? ''); ?>">
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-12">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-map-marker-alt"></i> Dirección <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <textarea 
                                                                class="textarea" 
                                                                name="address" 
                                                                required rows="3"
                                                        >
                                                                <?php echo htmlspecialchars($employee->address); ?>
                                                        </textarea>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-12">
                                        <hr>
                                        <div class="field is-grouped">
                                                <div class="control">
                                                        <button type="submit" class="button is-success">
                                                                <i class="fas fa-save"></i> Actualizar Empleado
                                                        </button>
                                                </div>
                                                <div class="control">
                                                        <a href="<?php echo $_ENV['APP_URL']; ?>/employees" class="button is-light">
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
        document.getElementById('employeeForm').addEventListener('submit', function(e) {
                const birthdate = new Date(this.birthdate.value);
                const today = new Date();
                let age = today.getFullYear() - birthdate.getFullYear();
                const m = today.getMonth() - birthdate.getMonth();
                
                if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                        age--;
                }
        
                if (age < 18) {
                        e.preventDefault();
                        alert('El empleado debe ser mayor de 18 años');
                        return false;
                }
        });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>