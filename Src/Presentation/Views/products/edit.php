<?php
if (!isset($product)) {
        $_SESSION['error_message'] = 'No se encontraron datos del producto';
        header('Location: ' . $_ENV['APP_URL'] . '/products');
        exit;
}
?>

<div class="card mt-4">
        <div class="card-header p-4" style="background-color: dark">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-edit"></i> Editar Producto
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item" style="margin-left: 300px;">
                                        <a href="<?php echo $_ENV['APP_URL']; ?>/products" class="customLink-a">
                                                <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
    
        <div class="card-content">
                <form 
                        action="<?php echo $_ENV['APP_URL']; ?>/products/update" 
                        method="POST" 
                        enctype="multipart/form-data" 
                        id="productForm"
                >
                        <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                        
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
                                                                value="<?php echo htmlspecialchars($product->name); ?>"
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
                                                        <textarea class="textarea" name="description" rows="4"><?php echo htmlspecialchars($product->description ?? ''); ?></textarea>
                                                </div>
                                        </div>
                                </div>
                                
                                <!-- Categoría -->
                                <div class="column is-12">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-tags"></i> Categoría <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <div class="select is-fullwidth">
                                                                <select name="category_id" required>
                                                                        <option value="">Seleccione una categoría...</option>
                                                                        <?php if (isset($categories) && count($categories) > 0): ?>
                                                                                <?php foreach ($categories as $category): ?>
                                                                                        <option value="<?php echo $category->getId(); ?>"
                                                                                                <?php echo ($product->category_id == $category->getId()) ? 'selected' : ''; ?>>
                                                                                                <?php echo htmlspecialchars($category->getName()); ?>
                                                                                        </option>
                                                                                <?php endforeach; ?>
                                                                        <?php else: ?>
                                                                                <option value="">No hay categorías disponibles</option>
                                                                        <?php endif; ?>
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
                                                                value="<?php echo $product->price; ?>"
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
                                                        <i class="fas fa-boxes"></i> Stock <span class="has-text-danger">*</span>
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="number" 
                                                                name="stock" 
                                                                required 
                                                                value="<?php echo $product->stock; ?>">
                                                </div>
                                                <?php if ($product->isLowStock() && $product->is_active): ?>
                                                        <p class="help has-text-warning">
                                                                <i class="fas fa-exclamation-triangle"></i> Stock bajo (<?php echo $product->stock; ?> unidades)
                                                        </p>
                                                <?php endif; ?>
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
                                                                <input 
                                                                        type="radio" 
                                                                        name="is_active" 
                                                                        value="1" <?php echo $product->is_active ? 'checked' : ''; ?>
                                                                > 
                                                                Activo
                                                        </label>
                                                        <label class="radio">
                                                                <input 
                                                                        type="radio" 
                                                                        name="is_active" 
                                                                        value="0" <?php echo !$product->is_active ? 'checked' : ''; ?>
                                                                > 
                                                                Descontinuado
                                                        </label>
                                                </div>
                                        </div>
                                </div>
                                
                                <!-- Imagen actual -->
                                <div class="column is-6">
                                        <div class="field">
                                                <label class="label">
                                                    <i class="fas fa-image"></i> Imagen Actual
                                                </label>
                                                <div class="box has-text-centered" style="background-color: #fafafa;">
                                                        <?php if ($product->image_prod): ?>
                                                                <img 
                                                                        src="<?php echo $_ENV['APP_URL']; ?>/uploads/products/<?php echo $product->image_prod; ?>" 
                                                                        alt="<?php echo htmlspecialchars($product->name); ?>"
                                                                        style="max-width: 150px; max-height: 150px; border-radius: 8px;"
                                                                >
                                                                <p class="help">
                                                                        <a 
                                                                                href="#" 
                                                                                onclick="return false;" 
                                                                                style="color: red;"
                                                                        >
                                                                                Para cambiar la imagen, seleccione una nueva abajo
                                                                        </a>
                                                                </p>
                                                        <?php else: ?>
                                                                <div style="width: 100%; padding: 20px; background-color: #e0e0e0; border-radius: 8px;">
                                                                        <i class="fas fa-image fa-2x has-text-grey-light"></i>
                                                                        <p>Sin imagen asignada</p>
                                                                </div>
                                                        <?php endif; ?>
                                                </div>
                                        </div>
                                </div>
                                
                                <!-- Nueva imagen -->
                                <div class="column is-12">
                                        <div class="field">
                                                <label class="label">
                                                        <i class="fas fa-upload"></i> Cambiar Imagen
                                                </label>
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="file" 
                                                                name="image" 
                                                                accept="image/*"
                                                        >
                                                </div>
                                                <p class="help">Dejar en blanco para mantener la imagen actual</p>
                                        </div>
                                </div>
                                
                                <!-- Previsualización -->
                                <div class="column is-12" id="previewContainer" style="display: none;">
                                        <div class="box has-text-centered">
                                                <h4 class="title is-6">Nueva imagen:</h4>
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
                                                                <i class="fas fa-save"></i> Actualizar Producto
                                                        </button>
                                                </div>
                                                <div class="control">
                                                        <a href="<?php echo $_ENV['APP_URL']; ?>/products" class="button is-light">
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
        // * Previsualización de imagen nueva...
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
    
        // * Validaciones...
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