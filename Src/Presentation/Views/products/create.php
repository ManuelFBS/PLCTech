<?php

use \PLCTech\Helpers\UrlHelper;

// * Verificar que la variable $categories existe...
if (!isset($categories) || empty($categories)) {
        // > Si no existe, obtenerlas directamente usando el repositorio...
        require_once __DIR__ . '/../../Infrastructure/Database/Repositories/MySQLCategoryRepository.php';
        $categoryRepo = new \PLCTech\Infrastructure\Database\Repositories\MySQLCategoryRepository();
        $categories = $categoryRepo->findAll();
}
?>

<div class="card mt-4">
        <div class="card-header p-4" style="background-color: dark;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-plus-circle"></i> Nuevo Producto
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item" style="margin-left: 300px;">
                                        <a href="<?php echo UrlHelper::url('/products'); ?>" class="customLink-a">
                                                <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
        
        <div class="card-content">
                <form 
                        action="<?php echo UrlHelper::url('/products/store'); ?>" 
                        method="POST" 
                        enctype="multipart/form-data" 
                        id="productForm"
                >
                        <div class="columns is-multiline">
                                <!-- Nombre del producto -->
                                <div class="column is-12">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-tag"></i> Nombre del Producto <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="text" 
                                                                name="name" 
                                                                required 
                                                                placeholder="Ej: Laptop Gamer XT-1000"
                                                        >
                                                </div>
                                        </div>
                                </div>
                                
                                <!-- Descripción -->
                                <div class="column is-12">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-align-left"></i> Descripción
                                                </label>
                                                <div class="control">
                                                        <textarea 
                                                                class="textarea" 
                                                                name="description" 
                                                                rows="4" 
                                                                placeholder="Descripción detallada del producto..."
                                                        ></textarea>
                                                </div>
                                        </div>
                                </div>

                                <!-- NUEVO: Categoría -->
                                <div class="column is-12">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-tags"></i> Categoría <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <div class="select is-fullwidth">
                                                                <select name="category_id" required>
                                                                        <option value="">Seleccione una categoría...</option>
                                                                        <?php foreach ($categories as $category): ?>
                                                                                <option value="<?php echo $category->getId(); ?>">
                                                                                        <?php echo htmlspecialchars($category->getName()); ?>
                                                                                </option>
                                                                        <?php endforeach; ?>
                                                                </select>
                                                        </div>
                                                </div>
                                                <p class="help">Seleccione la categoría a la que pertenece el producto</p>
                                        </div>
                                </div>
                                
                                <!-- Precio y Stock -->
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-dollar-sign"></i> Precio <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control has-icons-left">
                                                        <input 
                                                                class="input" 
                                                                type="number" 
                                                                step="0.01" 
                                                                name="price" 
                                                                required 
                                                                placeholder="0.00"
                                                        >
                                                        <span class="icon is-small is-left">
                                                                <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-boxes"></i> Stock Inicial <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="number" name="stock" required value="0">
                                                </div>
                                                <p class="help">Número de unidades disponibles</p>
                                        </div>
                                </div>
                                
                                <!-- Estado -->
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-toggle-on"></i> Estado
                                                </label>
                                                <div class="control">
                                                        <label class="radio">
                                                                <input type="radio" name="is_active" value="1" checked> Activo
                                                        </label>
                                                        <label class="radio">
                                                                <input type="radio" name="is_active" value="0"> Descontinuado
                                                        </label>
                                                </div>
                                        </div>
                                </div>
                                
                                <!-- Imagen -->
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-image"></i> Imagen del Producto
                                                </label>
                                                <div class="control">
                                                        <input class="input" type="file" name="image" accept="image/*">
                                                </div>
                                                <p class="help">Formatos: JPG, PNG, GIF (Máx. 2MB)</p>
                                        </div>
                                </div>
                                
                                <!-- Previsualización de imagen -->
                                <div class="column is-12" id="previewContainer" style="display: none;">
                                        <div class="box has-text-centered">
                                                <h4 class="title is-6">Previsualización:</h4>
                                                <img 
                                                        id="imagePreview" 
                                                        src="#" 
                                                        alt="Vista previa" 
                                                        style="max-width: 200px; max-height: 200px; border-radius: 8px;"
                                                >
                                        </div>
                                </div>
                            
                                <!-- Botones -->
                                <div class="column is-12">
                                        <hr>
                                        <div class="field is-grouped">
                                                <div class="control">
                                                        <button type="submit" class="button is-success">
                                                                <i class="fas fa-save"></i> Guardar Producto
                                                        </button>
                                                </div>
                                                <div class="control">
                                                        <a href="<?php echo UrlHelper::url('/products'); ?>" class="button is-light">
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
        // * Previsualización de imagen...
        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                                document.getElementById('imagePreview').src = event.target.result;
                                document.getElementById('previewContainer').style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                } else {
                        document.getElementById('previewContainer').style.display = 'none';
                }
        });
        
        // * Validar que el precio sea positivo...
        document.getElementById('productForm').addEventListener('submit', function(e) {
                const price = parseFloat(this.price.value);
                if (price < 0) {
                        e.preventDefault();
                        alert('El precio no puede ser negativo');
                        return false;
                }
                
                const stock = parseInt(this.stock.value);
                if (stock < 0) {
                        e.preventDefault();
                        alert('El stock no puede ser negativo');
                        return false;
                }
        });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>