<?php
// * Verificar que la variable $product existe...
if (!isset($product)) {
        $_SESSION['error_message'] = 'No se encontraron datos del producto';
        header('Location: ' . $_ENV['APP_URL'] . '/products');
        exit;
}

// * Determinar si el producto está disponible...
$isAvailable = $product->is_active && $product->stock > 0;
$isDiscontinued = !$product->is_active;
$isOutOfStock = $product->stock <= 0;
$isLowStock = $product->is_active && $product->stock > 0 && $product->stock <= 5;
?>

<div class="columns is-centered mt-4">
    <div class="column is-8">
        <div class="card">
            <!-- Cabecera de la card -->
            <div class="card-header" style="background-color: #f5f5f5;">
                <div class="card-header-title">
                    <i class="fas fa-box"></i> 
                    <span class="ml-2">Detalles del Producto</span>
                </div>
                <div class="card-header-icon">
                    <a href="<?php echo $_ENV['APP_URL']; ?>/products" class="button is-light is-small">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            
            <!-- Mensajes de advertencia según el estado -->
            <?php if ($isDiscontinued): ?>
                <div class="notification is-danger is-light">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Producto Descontinuado:</strong> Este producto ya no está disponible para la venta.
                </div>
            <?php elseif ($isOutOfStock): ?>
                <div class="notification is-danger is-light">
                    <i class="fas fa-times-circle"></i>
                    <strong>Producto Agotado:</strong> No hay unidades disponibles en inventario.
                </div>
            <?php elseif ($isLowStock): ?>
                <div class="notification is-warning is-light">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>¡Inventario Bajo!</strong> Solo quedan <strong><?php echo $product->stock; ?></strong> unidades. ¡Reabastecer pronto!
                </div>
            <?php endif; ?>
            
            <!-- Contenido de la card -->
            <div class="card-content">
                <div class="columns is-multiline">
                    <!-- Columna de la imagen -->
                    <div class="column is-5">
                        <div class="box has-text-centered" style="background-color: #fafafa;">
                            <?php if ($product->image_prod): ?>
                                <img src="<?php echo $_ENV['APP_URL']; ?>/uploads/products/<?php echo $product->image_prod; ?>" 
                                     alt="<?php echo htmlspecialchars($product->name); ?>"
                                     style="width: 100%; max-height: 300px; object-fit: contain; border-radius: 8px;">
                            <?php else: ?>
                                <div style="width: 100%; height: 250px; background-color: #e0e0e0; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                    <i class="fas fa-image fa-4x has-text-grey-light"></i>
                                    <p class="has-text-grey-light mt-2">Sin imagen</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Columna de la información -->
                    <div class="column is-7">
                        <h1 class="title is-3"><?php echo htmlspecialchars($product->name); ?></h1>
                        
                        <!-- Estado -->
                        <div class="tags">
                            <?php if ($product->is_active): ?>
                                <span class="tag is-success is-medium">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            <?php else: ?>
                                <span class="tag is-danger is-medium">
                                    <i class="fas fa-ban"></i> Descontinuado
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($isLowStock && !$isOutOfStock && $product->is_active): ?>
                                <span class="tag is-warning is-medium">
                                    <i class="fas fa-exclamation-triangle"></i> Stock Bajo
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <hr>
                        
                        <!-- Información del producto -->
                        <div class="content">
                            <p>
                                <strong><i class="fas fa-info-circle"></i> Descripción:</strong><br>
                                <?php echo nl2br(htmlspecialchars($product->description ?? 'Sin descripción')); ?>
                            </p>
                            
                            <div class="columns">
                                <div class="column is-6">
                                    <p>
                                        <strong><i class="fas fa-tag"></i> Precio:</strong><br>
                                        <span class="title is-4 has-text-primary">$<?php echo number_format($product->price, 2); ?></span>
                                    </p>
                                </div>
                                <div class="column is-6">
                                    <p>
                                        <strong><i class="fas fa-boxes"></i> Stock:</strong><br>
                                        <span class="title is-4 <?php echo ($isLowStock && !$isOutOfStock) ? 'has-text-warning' : ($isOutOfStock ? 'has-text-danger' : 'has-text-dark'); ?>">
                                            <?php echo $product->stock; ?> unidades
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <p class="is-size-7 has-text-grey">
                                <i class="fas fa-calendar-alt"></i> Registrado: <?php echo date('d/m/Y H:i', strtotime($product->created_at)); ?>
                                <?php if ($product->updated_at && $product->updated_at !== $product->created_at): ?>
                                    <br><i class="fas fa-edit"></i> Última actualización: <?php echo date('d/m/Y H:i', strtotime($product->updated_at)); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <hr>
                        
                        <!-- Botones de acción (solo para Admin) -->
                        <?php if ($_SESSION['role'] === 'Admin'): ?>
                            <div class="buttons">
                                <a href="<?php echo $_ENV['APP_URL']; ?>/products/edit?id=<?php echo $product->id; ?>" 
                                   class="button is-warning">
                                    <i class="fas fa-edit"></i> Actualizar Producto
                                </a>
                                <button onclick="confirmDelete(<?php echo $product->id; ?>)" 
                                        class="button is-danger">
                                    <i class="fas fa-trash"></i> Eliminar Producto
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="buttons">
                                <a href="<?php echo $_ENV['APP_URL']; ?>/products" class="button is-light">
                                    <i class="fas fa-arrow-left"></i> Volver al listado
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('¿Está seguro que desea eliminar este producto?\n\nNota: No se podrá eliminar si tiene ventas asociadas.')) {
        window.location.href = '<?php echo $_ENV['APP_URL']; ?>/products/delete?id=' + id;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>