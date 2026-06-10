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
                        method="POST" id="userForm"
                >
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
                        </div>
                </form>
        </div>

</div>