<div class="card mt-4">
    <div class="card-header p-4" style="background-color: #f5f5f5;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-plus-circle"></i> Nueva Categoría
                    </h2>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a href="<?= \PLCTech\Helpers\UrlHelper::url(
                            '/categories',
                    ) ?>" class="button is-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <form action="<?= \PLCTech\Helpers\UrlHelper::url('/categories/store') ?>" method="POST">
            <div class="columns is-multiline">
                <div class="column is-12">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-tag"></i> Nombre <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <input class="input" type="text" name="name" required 
                                   placeholder="Ej: Laptops, Smartphones, Accesorios..." 
                                   maxlength="100" autofocus>
                        </div>
                        <p class="help">Máximo 100 caracteres</p>
                    </div>
                </div>
                
                <div class="column is-12">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-align-left"></i> Descripción
                        </label>
                        <div class="control">
                            <textarea class="textarea" name="description" rows="4" 
                                      placeholder="Descripción de la categoría (opcional)"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="column is-12">
                    <hr>
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-success">
                                <i class="fas fa-save"></i> Guardar Categoría
                            </button>
                        </div>
                        <div class="control">
                            <a href="<?= \PLCTech\Helpers\UrlHelper::url(
                                    '/categories',
                            ) ?>" class="button is-light">
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
