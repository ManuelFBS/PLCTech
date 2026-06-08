<div class="card mt-4">
        <div class="card-header p-4" style="background-color: #f5f5f5;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-user-plus"></i> Nuevo Empleado
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item">
                                        <a href="<?php echo $_ENV['APP_URL']; ?>/employees" class="button is-light">
                                                <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
    
        <div class="card-content">
                <form action="<?php echo $_ENV['APP_URL']; ?>/employees/store" method="POST" id="employeeForm">
                        <div class="columns is-multiline">
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-id-card"></i> DNI <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="text" 
                                                                name="dni" 
                                                                required 
                                                                pattern="[0-9]{8,10}" 
                                                                title="DNI debe tener 8-10 dígitos"
                                                                placeholder="Ej: 12345678"
                                                        >
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-envelope"></i> Email <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="email" 
                                                                name="email" 
                                                                required 
                                                                placeholder="ejemplo@empresa.com"
                                                        >
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-user"></i> Nombres <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="text" 
                                                                name="names" 
                                                                required 
                                                                placeholder="Nombres completos"
                                                        >
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-user-tag"></i> Apellidos <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="text" 
                                                                name="surnames" 
                                                                required 
                                                                placeholder="Apellidos completos"
                                                        >
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-calendar-alt"></i> Fecha de Nacimiento <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="date" 
                                                                name="birthdate" 
                                                                required 
                                                                max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>"
                                                        >
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
                                                        <input 
                                                                class="input" 
                                                                type="tel" 
                                                                name="phone_number" 
                                                                placeholder="Ej: 999999999"
                                                        >
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
                                                                required 
                                                                placeholder="Dirección completa" 
                                                                rows="3"
                                                        ></textarea>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-12">
                                        <hr>
                                        <div class="field is-grouped">
                                                <div class="control">
                                                        <button type="submit" class="button is-success">
                                                                <i class="fas fa-save"></i> Guardar Empleado
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
        // * Validación adicional...
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