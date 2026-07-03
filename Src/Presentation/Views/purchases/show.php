<?php
if (!isset($purchase)) {
        $_SESSION['error_message'] = 'No se encontraron datos de la venta';
        header('Location: ' . $_ENV['APP_URL'] . '/purchases');
        exit;
}
?>

<div class="card mt-4">
    <div class="card-header p-4" style="background-color: #f5f5f5;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-file-invoice"></i> Detalle de Venta
                    </h2>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a href="<?php echo $_ENV['APP_URL']; ?>/purchases" class="button is-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <a href="<?php echo $_ENV['APP_URL']; ?>/purchases/invoice?id=<?php echo $purchase->id; ?>" 
                       class="button is-primary ml-2">
                        <i class="fas fa-file-pdf"></i> Factura
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <!-- Información de la venta -->
        <div class="columns">
            <div class="column is-6">
                <div class="box">
                    <h4 class="title is-6">
                        <i class="fas fa-info-circle"></i> Información de la Venta
                    </h4>
                    <table class="table is-fullwidth is-size-7">
                        <tr>
                            <td><strong>Factura:</strong></td>
                            <td><?php echo htmlspecialchars($purchase->invoice_number); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($purchase->purchase_date)); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>
                                <?php if ($purchase->status === 'cancelled'): ?>
                                    <span class="tag is-danger">Anulada</span>
                                <?php elseif ($purchase->payment_status === 'paid'): ?>
                                    <span class="tag is-success">Pagada</span>
                                <?php elseif ($purchase->payment_status === 'pending'): ?>
                                    <span class="tag is-warning">Pendiente</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Método de pago:</strong></td>
                            <td>
                                <?php
                                $methodLabels = [
                                        'cash' => 'Efectivo',
                                        'card' => 'Tarjeta',
                                        'transfer' => 'Transferencia',
                                        'online' => 'Online'
                                ];
                                echo $methodLabels[$purchase->payment_method] ?? $purchase->payment_method;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tipo:</strong></td>
                            <td><?php echo $purchase->is_online ? 'Online' : 'Presencial'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Usuario:</strong></td>
                            <td><?php echo $purchase->user_id ? 'ID: ' . $purchase->user_id : 'N/A'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="column is-6">
                <div class="box">
                    <h4 class="title is-6">
                        <i class="fas fa-user"></i> Cliente
                    </h4>
                    <table class="table is-fullwidth is-size-7">
                        <tr>
                            <td><strong>ID Cliente:</strong></td>
                            <td><?php echo $purchase->customer_id; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Observaciones:</strong></td>
                            <td><?php echo nl2br(htmlspecialchars($purchase->notes ?? 'Sin observaciones')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Productos -->
        <div class="box mt-3">
            <h4 class="title is-6">
                <i class="fas fa-boxes"></i> Productos
            </h4>
            
            <?php if (empty($purchase->items)): ?>
                <div class="notification is-warning is-light">
                    <i class="fas fa-info-circle"></i> No hay productos en esta venta
                </div>
            <?php else: ?>
                <table class="table is-fullwidth is-hoverable is-size-7">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="has-text-right">Cantidad</th>
                            <th class="has-text-right">Precio Unit.</th>
                            <th class="has-text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchase->items as $item): ?>
                            <tr>
                                <td>
                                    <?php
                // Intentar obtener nombre del producto
                echo 'Producto ID: ' . $item['product_id'];
                ?>
                                </td>
                                <td class="has-text-right"><?php echo $item['quantity']; ?></td>
                                <td class="has-text-right">$<?php echo number_format($item['unit_price'], 2); ?></td>
                                <td class="has-text-right">$<?php echo number_format($item['subtotal'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="has-text-right">Subtotal:</th>
                            <th class="has-text-right">$<?php echo number_format($purchase->subtotal, 2); ?></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="has-text-right">IVA (16%):</th>
                            <th class="has-text-right">$<?php echo number_format($purchase->tax, 2); ?></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="has-text-right">Total:</th>
                            <th class="has-text-right has-text-primary">
                                <strong>$<?php echo number_format($purchase->total_amount, 2); ?></strong>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Botones de acción -->
        <div class="buttons mt-3">
            <?php if ($_SESSION['role'] === 'Admin' && $purchase->status !== 'cancelled'): ?>
                <button onclick="confirmCancel(<?php echo $purchase->id; ?>)" class="button is-danger">
                    <i class="fas fa-ban"></i> Anular Venta
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para anular venta (mismo que en index.php) -->
<div id="cancelModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">
                <i class="fas fa-exclamation-triangle has-text-danger"></i> Anular Venta
            </p>
            <button class="delete" aria-label="close" onclick="closeCancelModal()"></button>
        </header>
        <section class="modal-card-body">
            <p>¿Está seguro que desea anular esta venta?</p>
            <p class="has-text-danger mt-2">
                <i class="fas fa-info-circle"></i> Esta acción restaurará el stock de los productos.
            </p>
            <div class="field mt-3">
                <label class="label">Motivo de anulación</label>
                <div class="control">
                    <textarea class="textarea" id="cancelReason" placeholder="Ingrese el motivo..."></textarea>
                </div>
            </div>
        </section>
        <footer class="modal-card-foot">
            <button onclick="executeCancel()" class="button is-danger">
                <i class="fas fa-ban"></i> Sí, anular venta
            </button>
            <button onclick="closeCancelModal()" class="button">
                Cancelar
            </button>
        </footer>
    </div>
</div>

<script>
    let cancelId = <?php echo $purchase->id; ?>;
    
    function confirmCancel(id) {
        cancelId = id;
        document.getElementById('cancelModal').classList.add('is-active');
    }
    
    function closeCancelModal() {
        document.getElementById('cancelModal').classList.remove('is-active');
        document.getElementById('cancelReason').value = '';
    }
    
    function executeCancel() {
        if (cancelId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo $_ENV['APP_URL']; ?>/purchases/cancel?id=' + cancelId;
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'reason';
            input.value = document.getElementById('cancelReason').value;
            form.appendChild(input);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>