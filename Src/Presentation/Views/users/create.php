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
                <form 
                        action="<?php echo $_ENV['APP_URL']; ?>/users/store" 
                        method="POST" 
                        id="userForm"
                >
                        <div class="columns is-multiline">
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-id-card"></i> DNI / Cédula <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="text" 
                                                                name="dni" 
                                                                id="dni" 
                                                                required 
                                                                placeholder="Número de identificación"
                                                        >
                                                </div>
                                                <div id="dniStatus" class="help"></div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-user"></i> Nombre de Usuario <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="text" 
                                                                name="user" 
                                                                required 
                                                                placeholder="Ej: jperez"
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
                                                                id="email" 
                                                                required 
                                                                placeholder="usuario@empresa.com"
                                                        >
                                                </div>
                                                <div id="emailStatus" class="help"></div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-lock"></i> Contraseña <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="password" 
                                                                name="password" 
                                                                required 
                                                                placeholder="Mínimo 8 caracteres"
                                                        >
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
                                                                <input 
                                                                        type="checkbox" 
                                                                        name="is_active" 
                                                                        checked
                                                                >
                                                                Activo
                                                        </label>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-12">
                                        <div 
                                                id="personInfo" 
                                                class="box" 
                                                style="display: none; background-color: #f5f5f5;"
                                        >
                                                <h4 class="title is-6">
                                                        <i class="fas fa-check-circle has-text-success"></i> Datos encontrados:
                                                </h4>
                                                <p id="personData"></p>
                                        </div>
                                        <div id="personError" class="notification is-danger is-light" style="display: none;">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                <span id="personErrorMessage"></span>
                                        </div>
                                </div>
                                
                                <div class="column is-12">
                                        <hr>
                                        <div class="field is-grouped">
                                                <div class="control">
                                                        <button 
                                                                type="submit" class="button is-success" 
                                                                id="submitBtn" 
                                                                disabled
                                                        >
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

<script>
        let personFound = false;
        let personId = null;
        let personType = null;
    
        // * Buscar por DNI...
        document.getElementById('dni').addEventListener('blur', function() {
                const dni = this.value;
                if (dni.length >= 5) {
                        checkPerson(dni, 'dni');
                }
        });
    
        // * Buscar por Email...
        document.getElementById('email').addEventListener('blur', function() {
                const email = this.value;
                if (email.includes('@')) {
                        checkPerson(email, 'email');
                }
        });
    
        function checkPerson(value, type) {
                fetch('<?php echo $_ENV['APP_URL']; ?>/users/search?type=' + type + '&value=' + encodeURIComponent(value))
                .then(response => response.json())
                .then(data => {
                        if (data.found) {
                                personFound = true;
                                personId = data.id;
                                personType = data.type;
                                document.getElementById('personInfo').style.display = 'block';
                                document.getElementById('personError').style.display = 'none';
                                document.getElementById('personData').innerHTML = `
                                        <strong>Nombre:</strong> ${data.name}<br>
                                        <strong>Tipo:</strong> ${data.type === 'employee' ? 'Empleado' : 'Cliente'}<br>
                                        <strong>DNI:</strong> ${data.dni}<br>
                                        <strong>Email:</strong> ${data.email}
                                `;
                                document.getElementById('submitBtn').disabled = false;
                                
                                // > Llenar campos automáticamente
                                document.getElementById('dni').value = data.dni;
                                document.getElementById('email').value = data.email;
                        } else {
                                personFound = false;
                                personId = null;
                                personType = null;
                                document.getElementById('personInfo').style.display = 'none';
                                document.getElementById('personError').style.display = 'block';
                                document.getElementById('personErrorMessage').innerHTML = data.message;
                                document.getElementById('submitBtn').disabled = true;
                        }
                })
                .catch(error => {
                        console.error('Error:', error);
                        personFound = false;
                        document.getElementById('submitBtn').disabled = true;
                });
        }
    
        // * Validar antes de enviar...
        document.getElementById('userForm').addEventListener('submit', function(e) {
                if (!personFound) {
                        e.preventDefault();
                        alert('Debe buscar y encontrar un empleado o cliente válido antes de guardar');
                        return false;
                }
                
                // > Agregar campos ocultos con los IDs encontrados...
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = personType === 'employee' ? 'employee_id' : 'customer_id';
                input.value = personId;
                this.appendChild(input);
        });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>