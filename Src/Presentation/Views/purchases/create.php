<?php
// * Verificar que las variables existan...
if (!isset($customers) || empty($customers)) {
        require_once __DIR__ . '/../../Infrastructure/Database/Repositories/MySQLCustomerRepository.php';
        $customerRepo = new \PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository();
        $customers = $customerRepo->findAll();
}

if (!isset($products) || empty($products)) {
        require_once __DIR__ . '/../../Infrastructure/Database/Repositories/MySQLProductRepository.php';
        $productRepo = new \PLCTech\Infrastructure\Database\Repositories\MySQLProductRepository();
        $products = $productRepo->findActive();
}
?>

<div class="card mt-4">
    <div class="card-header p-4" style="background-color: #f5f5f5;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-plus-circle"></i> Nueva Venta
                    </h2>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a href="<?php echo $_ENV['APP_URL']; ?>/purchases" class="button is-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <form action="<?php echo $_ENV['APP_URL']; ?>/purchases/store" method="POST" id="purchaseForm">
            <!-- Datos de la venta -->
            <div class="columns is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-user"></i> Cliente <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="customer_id" required>
                                    <option value="">Seleccione un cliente...</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?php echo $customer->getId(); ?>">
                                            <?php echo htmlspecialchars($customer->getFullName()); ?> - <?php echo $customer->getDni(); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="column is-6">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-credit-card"></i> Método de Pago <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="payment_method" required>
                                    <option value="cash">Efectivo</option>
                                    <option value="card">Tarjeta de Crédito/Débito</option>
                                    <option value="transfer">Transferencia Bancaria</option>
                                    <option value="online">Pago Online</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="column is-12">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-sticky-note"></i> Observaciones
                        </label>
                        <div class="control">
                            <textarea class="textarea" name="notes" rows="2" 
                                      placeholder="Observaciones adicionales..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <!-- Productos -->
            <h4 class="title is-5">
                <i class="fas fa-boxes"></i> Productos
                <button type="button" class="button is-small is-info" onclick="addProductRow()">
                    <i class="fas fa-plus"></i> Agregar Producto
                </button>
            </h4>
            
            <div id="productsContainer">
                <div class="columns is-multiline product-row">
                    <div class="column is-5">
                        <div class="field">
                            <label class="label">Producto</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="product_id[]" class="product-select" required>
                                        <option value="">Seleccione un producto...</option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?php echo $product->getId(); ?>" 
                                                    data-price="<?php echo $product->getPrice(); ?>"
                                                    data-stock="<?php echo $product->getStock(); ?>">
                                                <?php echo htmlspecialchars($product->getName()); ?> 
                                                (Stock: <?php echo $product->getStock(); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-3">
                        <div class="field">
                            <label class="label">Cantidad</label>
                            <div class="control">
                                <input class="input quantity-input" type="number" name="quantity[]" 
                                       value="1" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="column is-3">
                        <div class="field">
                            <label class="label">Subtotal</label>
                            <div class="control">
                                <input class="input subtotal-input" type="text" value="$0.00" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="column is-1">
                        <div class="field">
                            <label class="label">&nbsp;</label>
                            <div class="control">
                                <button type="button" class="button is-danger" onclick="removeProductRow(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <!-- Totales -->
            <div class="columns">
                <div class="column is-6 is-offset-6">
                    <table class="table is-fullwidth">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="has-text-right">
                                <span id="subtotalDisplay">$0.00</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>IVA (16%):</strong></td>
                            <td class="has-text-right">
                                <span id="taxDisplay">$0.00</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td class="has-text-right has-text-primary">
                                <strong id="totalDisplay">$0.00</strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="field is-grouped">
                <div class="control">
                    <button type="submit" class="button is-success">
                        <i class="fas fa-save"></i> Guardar Venta
                    </button>
                </div>
                <div class="control">
                    <a href="<?php echo $_ENV['APP_URL']; ?>/purchases" class="button is-light">
                        Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Agregar fila de producto
    function addProductRow() {
        const container = document.getElementById('productsContainer');
        const firstRow = container.querySelector('.product-row');
        const newRow = firstRow.cloneNode(true);
        
        // Limpiar valores
        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.quantity-input').value = '1';
        newRow.querySelector('.subtotal-input').value = '$0.00';
        
        container.appendChild(newRow);
        updateTotals();
    }
    
    // Eliminar fila de producto
    function removeProductRow(button) {
        const row = button.closest('.product-row');
        if (document.querySelectorAll('.product-row').length > 1) {
            row.remove();
            updateTotals();
        } else {
            alert('Debe haber al menos un producto en la venta');
        }
    }
    
    // Calcular subtotal de una fila
    function calculateRowSubtotal(row) {
        const select = row.querySelector('.product-select');
        const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(select.options[select.selectedIndex]?.dataset.price) || 0;
        const subtotal = quantity * price;
        
        row.querySelector('.subtotal-input').value = '$' + subtotal.toFixed(2);
        return subtotal;
    }
    
    // Actualizar totales
    function updateTotals() {
        const rows = document.querySelectorAll('.product-row');
        let subtotal = 0;
        
        rows.forEach(row => {
            subtotal += calculateRowSubtotal(row);
        });
        
        const tax = subtotal * 0.16;
        const total = subtotal + tax;
        
        document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('taxDisplay').textContent = '$' + tax.toFixed(2);
        document.getElementById('totalDisplay').textContent = '$' + total.toFixed(2);
    }
    
    // Event listeners
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || 
            e.target.classList.contains('quantity-input')) {
            updateTotals();
        }
    });
    
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            updateTotals();
        }
    });
    
    // Validar antes de enviar
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('.product-row');
        let hasProducts = false;
        
        rows.forEach(row => {
            const select = row.querySelector('.product-select');
            if (select.value) {
                hasProducts = true;
            }
        });
        
        if (!hasProducts) {
            e.preventDefault();
            alert('Debe agregar al menos un producto a la venta');
            return false;
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>