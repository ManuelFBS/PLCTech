<?php
if (!isset($cart) || empty($cart)) {
        ?>
                <div class="card mt-4">
                        <div class="card-content has-text-centered">
                                <i class="fas fa-shopping-cart fa-4x has-text-grey-light"></i>
                                <h3 class="title is-4 mt-3">Tu carrito está vacío</h3>
                                <p class="subtitle is-6">Explora nuestro catálogo y agrega productos</p>
                                <a 
                                        href="<?php echo $_ENV['APP_URL']; ?>/products/catalog" 
                                        class="button is-primary"
                                >
                                        <i class="fas fa-store"></i> Ir al catálogo
                                </a>
                        </div>
                </div>
        <?php
        require_once __DIR__ . '/../layouts/footer.php';
        return;
}

$subtotal = $subtotal ?? 0;
$tax = $tax ?? 0;
$total = $total ?? 0;
?>

<div class="columns is-multiline mt-4">
        <div class="column is-8">
                <div class="card">
                        <div class="card-header" style="background-color: #f5f5f5;">
                                <div class="card-header-title">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="ml-2">Mi Carrito</span>
                                        <span class="tag is-info ml-2"><?php echo count($cart); ?> productos</span>
                                </div>
                                <div class="card-header-icon">
                                        <button onclick="clearCart()" class="button is-danger is-small">
                                                <i class="fas fa-trash"></i> Vaciar carrito
                                        </button>
                                </div>
                        </div>
                        <div class="card-content">
                                <div class="table-container">
                                        <table class="table is-fullwidth is-hoverable">
                                                <thead>
                                                        <tr>
                                                                <th>Producto</th>
                                                                <th class="has-text-right">Precio</th>
                                                                <th class="has-text-right">Cantidad</th>
                                                                <th class="has-text-right">Subtotal</th>
                                                                <th></th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        <?php foreach ($cart as $item): ?>
                                                                <tr>
                                                                        <td>
                                                                                <?php if ($item['image_prod']): ?>
                                                                                        <img src="<?php echo $_ENV['APP_URL']; ?>/uploads/products/<?php echo $item['image_prod']; ?>" 
                                                                                                alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; vertical-align: middle; margin-right: 8px;">
                                                                                <?php endif; ?>
                                                                                <?php echo htmlspecialchars($item['name']); ?>
                                                                        </td>
                                                                        <td class="has-text-right">$<?php echo number_format($item['price'], 2); ?></td>
                                                                        <td class="has-text-right">
                                                                                <form action="<?php echo $_ENV['APP_URL']; ?>/cart/update" method="POST" style="display: inline;">
                                                                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                                                                        <input 
                                                                                                type="number" 
                                                                                                name="quantity" 
                                                                                                value="<?php echo $item['quantity']; ?>" 
                                                                                                min="1" 
                                                                                                max="<?php echo $item['stock']; ?>" 
                                                                                                class="input is-small" style="width: 70px; display: inline; text-align: center;"
                                                                                                onchange="this.form.submit()">
                                                                                </form>
                                                                        </td>
                                                                        <td class="has-text-right">$<?php echo number_format($item['subtotal'], 2); ?></td>
                                                                        <td>
                                                                                <a 
                                                                                        href="<?php echo $_ENV['APP_URL']; ?>/cart/remove?id=<?php echo $item['id']; ?>" 
                                                                                        class="button is-danger is-small" 
                                                                                        onclick="return confirm('¿Eliminar este producto del carrito?')"
                                                                                >
                                                                                        <i class="fas fa-times"></i>
                                                                                </a>
                                                                        </td>
                                                                </tr>
                                                        <?php endforeach; ?>
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                </div>
        </div>
    
        <div class="column is-4">
                <div class="card">
                        <div class="card-header" style="background-color: #f5f5f5;">
                                <div class="card-header-title">
                                        <i class="fas fa-receipt"></i> Resumen
                                </div>
                        </div>
                        <div class="card-content">
                                <table class="table is-fullwidth is-size-7">
                                        <tr>
                                                <td><strong>Subtotal:</strong></td>
                                                <td class="has-text-right">$<?php echo number_format($subtotal, 2); ?></td>
                                        </tr>
                                        <tr>
                                                <td><strong>IVA (16%):</strong></td>
                                                <td class="has-text-right">$<?php echo number_format($tax, 2); ?></td>
                                        </tr>
                                        <tr>
                                                <td><strong>Total:</strong></td>
                                                <td class="has-text-right has-text-primary">
                                                        <strong>$<?php echo number_format($total, 2); ?></strong>
                                                </td>
                                        </tr>
                                </table>
                                
                                <hr>
                                
                                <form action="<?php echo $_ENV['APP_URL']; ?>/purchases/checkout" method="POST">
                                        <div class="field">
                                                <label class="label">Método de pago</label>
                                                <div class="control">
                                                        <div class="select is-fullwidth">
                                                                <select name="payment_method" required>
                                                                        <option value="online">Pago Online</option>
                                                                        <option value="card">Tarjeta de Crédito</option>
                                                                        <option value="transfer">Transferencia</option>
                                                                </select>
                                                        </div>
                                                </div>
                                        </div>
                                        
                                        <div class="field">
                                                <label class="label">Observaciones</label>
                                                <div class="control">
                                                        <textarea 
                                                                class="textarea" 
                                                                name="notes" 
                                                                rows="2" 
                                                                placeholder="Instrucciones adicionales..."></textarea>
                                                </div>
                                        </div>
                                        
                                        <button type="submit" class="button is-success is-fullwidth">
                                                <i class="fas fa-check"></i> Finalizar compra
                                        </button>
                                </form>
                                
                                <a href="<?php echo $_ENV['APP_URL']; ?>/products/catalog" class="button is-light is-fullwidth mt-2">
                                        <i class="fas fa-store"></i> Seguir comprando
                                </a>
                        </div>
                </div>
        </div>
</div>

<script>
        function clearCart() {
                if (confirm('¿Está seguro que desea vaciar el carrito?')) {
                        window.location.href = '<?php echo $_ENV['APP_URL']; ?>/cart/clear';
                }
        }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>