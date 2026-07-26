<?php

use \PLCTech\Helpers\UrlHelper;

?>

<div class="card mt-4">
        <div class="card-header p-4" style="background-color: dark;">
                <div class="level">
                        <div class="level-left" style="margin-left: 10px;">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-boxes"></i> Listado de Productos
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right" style="margin-left: 100px;">
                                <div class="level-item">
                                        <a href="<?php echo UrlHelper::url('/products/create'); ?>" class="anchor-a">
                                                <i class="fas fa-plus"></i> Nuevo Producto
                                        </a>
                                </div>
                        </div>
                </div>
        </div>

        <hr class="hr-div">
    
        <div class="card-content">
                <?php if (empty($products)): ?>
                        <div class="notification is-warning is-light has-text-centered">
                                <i class="fas fa-info-circle"></i> No hay productos registrados
                        </div>
                <?php else: ?>
                        <div class="table-container">
                                <table class="table is-fullwidth is-hoverable is-striped">
                                        <thead>
                                                <tr>
                                                <th>ID</th>
                                                <th>Imagen</th>
                                                <th>Nombre</th>
                                                <th>Estado</th>
                                                <th class="has-text-left">Precio</th>
                                                <th class="has-text-left">Stock</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                                <?php foreach ($products as $product): ?>
                                                <tr>
                                                        <td style="padding-top: 22px;"><?php echo $product->id; ?></td>
                                                        <td class="has-text-centered">
                                                                <?php if ($product->image_prod): ?>
                                                                        <img src="<?= UrlHelper::url('/uploads/products/') . urlencode($product->image_prod) ?>" 
                                                                        alt="<?= htmlspecialchars($product->name ?? 'Producto', ENT_QUOTES, 'UTF-8') ?>"
                                                                        class="imageFix">
                                                                <?php else: ?>
                                                                        <div class="no-Image">
                                                                                <i class="fas fa-image has-text-grey-light"></i>
                                                                        </div>
                                                                <?php endif; ?>
                                                        </td>
                                                        <td>
                                                                <strong><?php echo htmlspecialchars($product->name); ?></strong>
                                                                <?php if ($product->description): ?>
                                                                        <br><small class="has-text-grey"><?php echo htmlspecialchars(substr($product->description, 0, 50)); ?>...</small>
                                                                <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                <?php if ($product->is_active): ?>
                                                                        <span class="tag is-success centered-row">Activo</span>
                                                                <?php else: ?>
                                                                        <span class="tag is-danger centered-row">Descontinuado</span>
                                                                <?php endif; ?>
                                                        </td>
                                                        <td class="has-text-left">
                                                                <span class="tag is-success price-column">$<?php echo number_format($product->price, 2); ?></span>
                                                        </td>
                                                        <td class="has-text-left">
                                                                <?php if ($product->stock <= 0): ?>
                                                                        <!-- 1. Agotado: Fondo gris oscuro, texto gris claro (Estilo deshabilitado legible) -->
                                                                        <span class="tag out-of-stock-column centered-row">
                                                                        Agotado
                                                                        </span>

                                                                <?php elseif ($product->stock <= 5): ?>
                                                                        <!-- 2. Stock bajo: Fondo amarillo vibrante, texto negro (Máximo contraste y alerta) -->
                                                                        <span class="tag stock-column-low centered-row">
                                                                                <?php echo $product->stock; ?> uds
                                                                        </span>

                                                                <?php else: ?>
                                                                        <!-- 3. Contraste normal: Fondo verde, texto blanco (Estado seguro/informativo) -->
                                                                        <span class="tag stock-column-normal centered-row">
                                                                        <?php echo $product->stock; ?> uds
                                                                        </span>
                                                                <?php endif; ?>
                                                        </td>
                                                        <td>
                                                        <div class="buttons are-small">
                                                                <a href="<?php echo UrlHelper::url('/products/show', ['id' => $product->id]); ?>" 
                                                                class="button is-info centered-row" title="Ver detalles">
                                                                <i class="fas fa-eye"></i>
                                                                </a>
                                                                <?php if ($_SESSION['role'] === 'Admin'): ?>
                                                                <a href="<?php echo UrlHelper::url('/products/edit', ['id' => $product->id]); ?>" 
                                                                class="button is-warning centered-row" title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button onclick="confirmDelete(<?php echo $product->id; ?>)" 
                                                                        class="button is-danger centered-row" title="Eliminar">
                                                                        <i class="fas fa-trash"></i>
                                                                </button>
                                                                <?php endif; ?>
                                                        </div>
                                                        </td>
                                                </tr>
                                                <?php endforeach; ?>
                                        </tbody>
                                </table>
                        </div>
                <?php endif; ?>
        </div>
</div>

<script>
        const baseDeleteUrl = "<?= UrlHelper::url('/products/delete') ?>";

        function confirmDelete(id) {
                if (confirm('¿Está seguro que desea eliminar este producto?\n\nNota: No se podrá eliminar si tiene ventas asociadas.')) {
                        window.location.href = `${baseDeleteUrl}?id=${id}`;
                }
        }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>