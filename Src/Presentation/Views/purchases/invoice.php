<?php
if (!isset($purchase) || !isset($customer) || !isset($items) || !isset($company)) {
        $_SESSION['error_message'] = 'No se encontraron datos de la factura';
        header('Location: ' . $_ENV['APP_URL'] . '/purchases');
        exit;
}
?>

<div class="card mt-4" style="max-width: 800px; margin: 0 auto;">
    <div class="card-content">
        <!-- Encabezado de la factura -->
        <div class="columns">
            <div class="column is-7">
                <div class="content">
                    <h1 class="title is-3" style="color: #00d1b2;"><?php echo $company['name']; ?></h1>
                    <p class="is-size-7">
                        <i class="fas fa-building"></i> RUC: <?php echo $company['ruc']; ?><br>
                        <i class="fas fa-map-marker-alt"></i> <?php echo $company['address']; ?><br>
                        <i class="fas fa-phone"></i> <?php echo $company['phone']; ?><br>
                        <i class="fas fa-envelope"></i> <?php echo $company['email']; ?>
                    </p>
                </div>
            </div>
            <div class="column is-5 has-text-right">
                <div class="box" style="background-color: #f5f5f5;">
                    <p class="title is-5">FACTURA</p>
                    <p class="is-size-7">
                        <strong>Nº:</strong> <?php echo htmlspecialchars($purchase['invoice_number']); ?><br>
                        <strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($purchase['date'])); ?><br>
                        <strong>Estado:</strong> 
                        <?php if ($purchase['status'] === 'cancelled'): ?>
                            <span class="tag is-danger">ANULADA</span>
                        <?php else: ?>
                            <span class="tag is-success">VÁLIDA</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        
        <hr>
        
        <!-- Datos del cliente -->
        <div class="box" style="background-color: #fafafa;">
            <h4 class="title is-6">
                <i class="fas fa-user"></i> Datos del Cliente
            </h4>
            <table class="table is-fullwidth is-size-7">
                <tr>
                    <td><strong>Nombre:</strong></td>
                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                </tr>
                <tr>
                    <td><strong>DNI:</strong></td>
                    <td><?php echo htmlspecialchars($customer['dni']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                </tr>
                <tr>
                    <td><strong>Teléfono:</strong></td>
                    <td><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                </tr>
            </table>
        </div>
        
        <!-- Detalle de productos -->
        <h4 class="title is-6 mt-3">
            <i class="fas fa-boxes"></i> Detalle de Productos
        </h4>
        
        <table class="table is-fullwidth is-hoverable is-size-7">
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Descripción</th>
                    <th class="has-text-right">Precio Unit.</th>
                    <th class="has-text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td class="has-text-right">$<?php echo number_format($item['unit_price'], 2); ?></td>
                        <td class="has-text-right">$<?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="has-text-right"><strong>Subtotal:</strong></td>
                    <td class="has-text-right">$<?php echo number_format($purchase['subtotal'], 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="has-text-right"><strong>IVA (16%):</strong></td>
                    <td class="has-text-right">$<?php echo number_format($purchase['tax'], 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="has-text-right"><strong>Total:</strong></td>
                    <td class="has-text-right has-text-primary">
                        <strong>$<?php echo number_format($purchase['total'], 2); ?></strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="has-text-right"><strong>Método de Pago:</strong></td>
                    <td class="has-text-right">
                        <?php
                        $methodLabels = [
                                'cash' => 'Efectivo',
                                'card' => 'Tarjeta',
                                'transfer' => 'Transferencia',
                                'online' => 'Online'
                        ];
                        echo $methodLabels[$purchase['payment_method']] ?? $purchase['payment_method'];
                        ?>
                    </td>
                </tr>
            </tfoot>
        </table>
        
        <!-- Notas -->
        <?php if (!empty($purchase['notes'])): ?>
            <div class="box" style="background-color: #fafafa;">
                <h4 class="title is-6">
                    <i class="fas fa-sticky-note"></i> Observaciones
                </h4>
                <p class="is-size-7"><?php echo nl2br(htmlspecialchars($purchase['notes'])); ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Pie de página -->
        <hr>
        <div class="has-text-centered is-size-7 has-text-grey">
            <p>
                <i class="fas fa-check-circle has-text-success"></i> 
                Documento generado por <?php echo $company['name']; ?>
            </p>
            <p>
                <?php echo date('d/m/Y H:i:s'); ?> - Factura Nº <?php echo $purchase['invoice_number']; ?>
            </p>
            <?php if ($purchase['status'] === 'cancelled'): ?>
                <p class="has-text-danger">
                    <i class="fas fa-ban"></i> 
                    <strong>DOCUMENTO ANULADO</strong>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="has-text-centered mt-3">
    <button class="button is-light" onclick="window.print()">
        <i class="fas fa-print"></i> Imprimir / PDF
    </button>
    <a href="<?php echo $_ENV['APP_URL']; ?>/purchases" class="button is-light">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>