<?php

use \PLCTech\Helpers\UrlHelper;

// * Verificar que la variable $purchases existe y tiene datos...
$hasPurchases = isset($purchases) && !empty($purchases);

// * Verificación de seguridad...
if (!isset($purchases)) {
    // > Si la variable no existe, mostrar error controlado...
    $purchases = [];
    $_SESSION['error_message'] = 'No se pudieron cargar las compras';
}
?>

<div class="card mt-4">
    <div class="card-header p-4" style="background-color: #f5f5f5;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-history"></i> Mis Compras
                    </h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <?php if (!$hasPurchases): ?>
            <div class="notification is-warning is-light has-text-centered">
                <i class="fas fa-info-circle"></i> No has realizado compras aún
                <br>
                <a href="<?php echo UrlHelper::url('/products/catalog'); ?>" class="button is-primary mt-3">
                    <i class="fas fa-store"></i> Ir al catálogo
                </a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table is-fullwidth is-hoverable is-striped">
                    <thead>
                        <tr>
                            <th># Factura</th>
                            <th>Fecha</th>
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
                                    <?php echo date('d/m/Y H:i', strtotime($purchase->purchase_date)); ?>
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
                                </td>
                                <td>
                                    <div class="buttons are-small">
                                        <a href="<?php echo UrlHelper::url('/purchases/invoice', ['id' => $purchase->id]); ?>" 
                                           class="button is-primary" title="Ver factura">
                                            <i class="fas fa-file-invoice"></i> Ver factura
                                        </a>
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>