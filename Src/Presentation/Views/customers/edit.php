<?php
if (!isset($customer)) {
        $_SESSION['error_message'] = 'No se encontraron datos del cliente';
        header('Location: ' . $_ENV['APP_URL'] . '/customers');
        exit;
}
?>

<div class="card mt-4">
    <div class="card-header p-4" style="background-color: #f5f5f5;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-user-edit"></i> Editar Cliente
                    </h2>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a href="<?php echo $_ENV['APP_URL']; ?>/customers" class="button is-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <form action="<?php echo $_ENV['APP_URL']; ?>/customers/update" method="POST">
            <input type="hidden" name="id" value="<?php echo $customer->id; ?>">
            
            <div class="columns is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-id-card"></i> DNI <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <input class="input" type="text" name="dni" required 
                                   value="<?php echo htmlspecialchars($customer->dni); ?>">
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
                                   value="<?php echo htmlspecialchars($customer->email); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="column is-12">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-user"></i> Nombre Completo <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <input class="input" type="text" name="full_name" required 
                                   value="<?php echo htmlspecialchars($customer->full_name); ?>">
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
                                   value="<?php echo $customer->birthdate; ?>">
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
                            <input class="input" type="tel" name="phone_number" 
                                   value="<?php echo htmlspecialchars($customer->phone_number ?? ''); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="column is-12">
                    <hr>
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-success">
                                <i class="fas fa-save"></i> Actualizar Cliente
                            </button>
                        </div>
                        <div class="control">
                            <a href="<?php echo $_ENV['APP_URL']; ?>/customers" class="button is-light">
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