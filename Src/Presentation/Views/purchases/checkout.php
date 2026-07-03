<?php
if (!isset($cart) || empty($cart)) {
        $_SESSION['error_message'] = 'El carrito está vacío';
        header('Location: ' . $_ENV['APP_URL'] . '/products/catalog');
        exit;
}

if (!isset($customer)) {
        $_SESSION['error_message'] = 'Debe iniciar sesión para realizar una compra';
        header('Location: ' . $_ENV['APP_URL'] . '/login');
        exit;
}
?>

<div class="columns is-multiline mt-4">
    <!-- Resumen del pedido -->
    <div class="column is-8">
        <div class="card">
            <div class="card-header" style="background-color: #f5f5f5;">
                <div class="card-header-title">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="ml-2">Resumen de tu pedido</span>
                </div>
            </div>
            <div class="card-content">
                <div class="table-container">
                    <table class="table is-fullwidth is-hoverable">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="has-text-right">Cantidad</th>
                                <th class="has-text-right">Precio</th>
                                <th class="has-text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$subtotal = 0;
foreach ($cart as $item):
        $itemSubtotal = $item['price'] * $item['quantity'];
        $subtotal += $itemSubtotal;
        ?>
                                <tr>
                                    <td>
                                        <?php if ($item['image_prod']): ?>
                                            <img src="<?php echo $_ENV['APP_URL']; ?>/uploads/products/<?php echo $item['image_prod']; ?>" 
                                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; vertical-align: middle;">
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </td>
                                    <td class="has-text-right"><?php echo $item['quantity']; ?></td>
                                    <td class="has-text-right">$<?php echo number_format($item['price'], 2); ?></td>
                                    <td class="has-text-right">$<?php echo number_format($itemSubtotal, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="has-text-right"><strong>Subtotal:</strong></td>
                                <td class="has-text-right">$<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="has-text-right"><strong>IVA (16%):</strong></td>
                                <td class="has-text-right">$<?php echo number_format($subtotal * 0.16, 2); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="has-text-right"><strong>Total:</strong></td>
                                <td class="has-text-right has-text-primary">
                                    <strong>$<?php echo number_format($subtotal * 1.16, 2); ?></strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Formulario de checkout -->
    <div class="column is-4">
        <div class="card">
            <div class="card-header" style="background-color: #f5f5f5;">
                <div class="card-header-title">
                    <i class="fas fa-credit-card"></i>
                    <span class="ml-2">Datos de pago</span>
                </div>
            </div>
            <div class="card-content">
                <form action="<?php echo $_ENV['APP_URL']; ?>/purchases/checkout" method="POST">
                    <!-- Datos del cliente -->
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-user"></i> Cliente
                        </label>
                        <div class="control">
                            <input class="input" type="text" value="<?php echo htmlspecialchars($customer->getFullName()); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <div class="control">
                            <input class="input" type="text" value="<?php echo htmlspecialchars($customer->getEmail()); ?>" readonly>
                        </div>
                    </div>
                    
                    <!-- Método de pago -->
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-credit-card"></i> Método de pago <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="payment_method" required>
                                    <option value="online">Pago Online</option>
                                    <option value="card">Tarjeta de Crédito/Débito</option>
                                    <option value="transfer">Transferencia Bancaria</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Observaciones -->
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-sticky-note"></i> Observaciones
                        </label>
                        <div class="control">
                            <textarea class="textarea" name="notes" rows="2" 
                                      placeholder="Instrucciones adicionales..."></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Total -->
                    <div class="has-text-centered">
                        <p class="title is-4 has-text-primary">
                            Total: $<?php echo number_format($subtotal * 1.16, 2); ?>
                        </p>
                    </div>
                    
                    <!-- Botones -->
                    <div class="buttons">
                        <button type="submit" class="button is-success is-fullwidth">
                            <i class="fas fa-check"></i> Confirmar compra
                        </button>
                        <a href="<?php echo $_ENV['APP_URL']; ?>/cart" class="button is-light is-fullwidth">
                            <i class="fas fa-arrow-left"></i> Volver al carrito
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>