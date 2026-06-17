<div class="card mt-4">
        <div class="card-header p-4" style="background-color: #f5f5f5;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-boxes"></i> Listado de Productos
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item">
                                        <a href="<?php echo $_ENV['APP_URL']; ?>/products/create" class="button is-success">
                                                <i class="fas fa-plus"></i> Nuevo Producto
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
    
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
                                                <th>Precio</th>
                                                <th>Stock</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                                <?php foreach ($products as $product): ?>
                                                <tr>
                                                        <td><?php echo $product->id; ?></td>
                                                        <td class="has-text-centered">
                                                        <?php if ($product->image_prod): ?>
                                                                <img src="<?php echo $_ENV['APP_URL']; ?>/uploads/products/<?php echo $product->image_prod; ?>" 
                                                                alt="<?php echo htmlspecialchars($product->name); ?>"
                                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                        <?php else: ?>
                                                                <div style="width: 50px; height: 50px; background-color: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
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
                                                                <span class="tag is-success">Activo</span>
                                                        <?php else: ?>
                                                                <span class="tag is-danger">Descontinuado</span>
                                                        <?php endif; ?>
                                                        </td>
                                                        <td class="has-text-right">
                                                        <strong class="has-text-primary">$<?php echo number_format($product->price, 2); ?></strong>
                                                        </td>
                                                        <td class="has-text-right">
                                                        <?php if ($product->stock <= 0): ?>
                                                                <span class="tag is-danger">Agotado</span>
                                                        <?php elseif ($product->stock <= 5): ?>
                                                                <span class="tag is-warning" style="color: red; font-weight: bold;">
                                                                <?php echo $product->stock; ?> unidades
                                                                </span>
                                                        <?php else: ?>
                                                                <span class="tag is-light"><?php echo $product->stock; ?> unidades</span>
                                                        <?php endif; ?>
                                                        </td>
                                                        <td>
                                                        <div class="buttons are-small">
                                                                <a href="<?php echo $_ENV['APP_URL']; ?>/products/show?id=<?php echo $product->id; ?>" 
                                                                class="button is-info" title="Ver detalles">
                                                                <i class="fas fa-eye"></i>
                                                                </a>
                                                                <?php if ($_SESSION['role'] === 'Admin'): ?>
                                                                <a href="<?php echo $_ENV['APP_URL']; ?>/products/edit?id=<?php echo $product->id; ?>" 
                                                                class="button is-warning" title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button onclick="confirmDelete(<?php echo $product->id; ?>)" 
                                                                        class="button is-danger" title="Eliminar">
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
        function confirmDelete(id) {
                if (confirm('¿Está seguro que desea eliminar este producto?\n\nNota: No se podrá eliminar si tiene ventas asociadas.')) {
                        window.location.href = '<?php echo $_ENV['APP_URL']; ?>/products/delete?id=' + id;
                }
        }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>