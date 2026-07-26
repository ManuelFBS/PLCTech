<?php

use \PLCTech\Helpers\UrlHelper;

?>

<div class="card mt-4">
    <div class="card-header p-4" style="background-color: dark;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-shopping-cart"></i> Listado de Ventas
                    </h2>
                </div>
            </div>
            <div class="level-right" style="margin-left: 300px;">
                <div class="level-item">
                    <a href="<?php echo UrlHelper::url('/purchases/create'); ?>" class="anchor-a">
                        <i class="fas fa-plus"></i> Nueva Venta
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <?php if (empty($purchases)): ?>
            <div class="notification is-warning is-light has-text-centered">
                <i class="fas fa-info-circle"></i> No hay ventas registradas
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table is-fullwidth is-hoverable is-striped">
                    <thead>
                        <tr>
                            <th># Factura</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Subtotal</th>
                            <th>IVA</th>
                            <th>Total</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchases as $purchase): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($purchase->invoice_number); ?></strong>
                                </td>
                                <td>
                                    <?php
                                    // > Obtener nombre del cliente (opcional - se puede mejorar con JOIN)...
                                    echo 'Cliente ID: ' . $purchase->customer_id;
                                    ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y H:i', strtotime($purchase->purchase_date)); ?>
                                </td>
                                <td class="has-text-right">
                                    $<?php echo number_format($purchase->subtotal, 2); ?>
                                </td>
                                <td class="has-text-right">
                                    $<?php echo number_format($purchase->tax, 2); ?>
                                </td>
                                <td class="has-text-right">
                                    <strong>$<?php echo number_format($purchase->total_amount, 2); ?></strong>
                                </td>
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
                                <td>
                                    <?php if ($purchase->status === 'cancelled'): ?>
                                        <span class="tag is-danger">Anulada</span>
                                    <?php elseif ($purchase->payment_status === 'paid'): ?>
                                        <span class="tag is-success">Pagada</span>
                                    <?php elseif ($purchase->payment_status === 'pending'): ?>
                                        <span class="tag is-warning">Pendiente</span>
                                    <?php else: ?>
                                        <span class="tag is-light"><?php echo $purchase->status; ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if ($purchase->is_online): ?>
                                        <span class="tag is-info is-small">Online</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="buttons are-small">
                                        <a href="<?php echo UrlHelper::url('/purchases/show', ['id' => $purchase->id]); ?>" 
                                           class="button is-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo UrlHelper::url('/purchases/invoice', ['id' => $purchase->id]); ?>" 
                                           class="button is-primary" title="Ver factura">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <?php if ($_SESSION['role'] === 'Admin' && $purchase->status !== 'cancelled'): ?>
                                            <button onclick="confirmCancel(<?php echo $purchase->id; ?>)" 
                                                    class="button is-danger" title="Anular venta">
                                                <i class="fas fa-ban"></i>
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

<!-- Modal para anular venta -->
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
    let cancelId = null;
    
    function confirmCancel(id) {
        cancelId = id;
        document.getElementById('cancelModal').classList.add('is-active');
    }
    
    function closeCancelModal() {
        cancelId = null;
        document.getElementById('cancelModal').classList.remove('is-active');
        document.getElementById('cancelReason').value = '';
    }
    
    function executeCancel() {
        if (cancelId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo UrlHelper::url(''); ?>/purchases/cancel?id=' + cancelId;
            
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