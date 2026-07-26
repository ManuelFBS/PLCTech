<?php

use \PLCTech\Helpers\UrlHelper;

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
    <div class="card-header p-4" style="background-color: dark;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-plus-circle"></i> Nueva Venta
                    </h2>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item" style="margin-left: 300px;">
                    <a href="<?php echo UrlHelper::url('/purchases'); ?>" class="customLink-a">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <form action="<?php echo UrlHelper::url('/purchases/store'); ?>" method="POST" id="purchaseForm">
            
            <!-- ========================================================== -->
            <!-- SECCIÓN: BUSCAR CLIENTE POR DNI                            -->
            <!-- ========================================================== -->
            <div class="box" style="background-color: dark;">
                <h4 class="title is-6">
                    <i class="fas fa-user"></i> Buscar Cliente
                </h4>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input class="input" type="text" id="dniSearch" 
                                       placeholder="Ingrese DNI del cliente..." 
                                       onkeyup="searchCustomer()">
                            </div>
                            <div class="control">
                                <button type="button" class="button is-info is-medium" onclick="searchCustomer()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-8">
                        <!-- Contenedor de resultados (VACÍO) -->
                        <div id="customerResultContainer" class="column is-8" style="margin-top: -12px">
                            <!-- Los resultados se mostrarán aquí mediante JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ========================================================== -->
            <!-- SECCIÓN: DATOS DE LA VENTA                                 -->
            <!-- ========================================================== -->
            <div class="columns is-multiline mt-3">
                <div class="column is-6">
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-credit-card"></i> Método de Pago <span class="has-text-danger">*</span>
                        </label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="payment_method" id="paymentMethod" required>
                                    <option value="cash">Efectivo</option>
                                    <option value="card">Tarjeta de Crédito/Débito</option>
                                    <option value="transfer">Transferencia Bancaria</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="column is-6">
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
            
            <!-- ========================================================== -->
            <!-- SECCIÓN: PRODUCTOS                                         -->
            <!-- ========================================================== -->
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
            
            <!-- ========================================================== -->
            <!-- SECCIÓN: TOTALES                                           -->
            <!-- ========================================================== -->
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
            
            <!-- ========================================================== -->
            <!-- SECCIÓN: BOTONES                                           -->
            <!-- ========================================================== -->
            <div class="field is-grouped">
                <div class="control">
                    <button type="submit" class="button is-success" id="submitBtn" disabled>
                        <i class="fas fa-save"></i> Guardar Venta
                    </button>
                </div>
                <div class="control">
                    <a href="<?php echo UrlHelper::url('/purchases'); ?>" class="button is-light">
                        Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // * ============================================================
    // * VARIABLES GLOBALES
    // * ============================================================
    var customerFound = false;
    var customerId = null;
    
    // * ============================================================
    // * BUSCAR CLIENTE POR DNI
    // * ============================================================
    function searchCustomer() {
        const dni = document.getElementById('dniSearch').value.trim();
        const container = document.getElementById('customerResultContainer');
        const submitBtn = document.getElementById('submitBtn');
        
        // > Limpiar contenedor...
        container.innerHTML = '';
        submitBtn.disabled = true;
        
        if (dni.length < 5) {
            return;
        }
        
        // > Mostrar "Buscando..."
        container.innerHTML = `
            <div class="notification is-info is-light" style="padding: 10px 10px;">
                <i class="fas fa-spinner fa-pulse"></i> Buscando cliente...
            </div>
        `;
        
        const url = `<?= UrlHelper::url('/customers/search') ?>?dni=${encodeURIComponent(dni)}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.found) {
                    // CLIENTE ENCONTRADO
                    customerFound = true;
                    customerId = data.id;
                    
                    container.innerHTML = `
                        <div class="notification is-success is-light" style="padding: 10px 15px;">
                            <i class="fas fa-check-circle has-text-success"></i>
                            <strong>${data.full_name}</strong> 
                            (DNI: <span class="tag is-light">${data.dni}</span>)
                            <span class="tag is-success is-small">Registrado</span>
                            <input type="hidden" name="customer_id" id="customerId" value="${data.id}">
                        </div>
                    `;
                    
                    submitBtn.disabled = false;
                    
                } else {
                    // CLIENTE NO ENCONTRADO
                    customerFound = false;
                    customerId = null;
                    
                    container.innerHTML = `
                        <div class="notification is-warning is-light" style="padding: 10px 15px;">
                            <i class="fas fa-exclamation-triangle has-text-warning"></i>
                            No se encontró cliente con DNI: <strong>${dni}</strong>
                            <a href="<?php echo UrlHelper::url('/customers/create'); ?>" 
                               class="button is-primary is-small ml-2">
                                <i class="fas fa-plus"></i> Registrar Cliente
                            </a>
                        </div>
                    `;
                    
                    submitBtn.disabled = true;
                }
            })
            .catch(error => {
                container.innerHTML = `
                    <div class="notification is-danger is-light" style="padding: 10px 15px;">
                        <i class="fas fa-exclamation-triangle has-text-danger"></i>
                        Error: ${error.message}
                    </div>
                `;
                submitBtn.disabled = true;
            });
    }
    
    // Buscar al presionar Enter
    document.getElementById('dniSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchCustomer();
        }
    });
    
    // ============================================================
    // VALIDAR ANTES DE ENVIAR
    // ============================================================
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        if (!customerFound || !customerId) {
            e.preventDefault();
            alert('Debe buscar y seleccionar un cliente válido');
            return false;
        }
        
        // Verificar productos
        const rows = document.querySelectorAll('.product-row');
        let hasProducts = false;
        
        rows.forEach(row => {
            const select = row.querySelector('.product-select');
            if (select && select.value) {
                hasProducts = true;
            }
        });
        
        if (!hasProducts) {
            e.preventDefault();
            alert('Debe agregar al menos un producto a la venta');
            return false;
        }
    });
    
    // ============================================================
    // AGREGAR FILA DE PRODUCTO
    // ============================================================
    function addProductRow() {
        const container = document.getElementById('productsContainer');
        const firstRow = container.querySelector('.product-row');
        const newRow = firstRow.cloneNode(true);
        
        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.quantity-input').value = '1';
        newRow.querySelector('.subtotal-input').value = '$0.00';
        
        container.appendChild(newRow);
        updateTotals();
    }
    
    // ============================================================
    // ELIMINAR FILA DE PRODUCTO
    // ============================================================
    function removeProductRow(button) {
        const row = button.closest('.product-row');
        if (document.querySelectorAll('.product-row').length > 1) {
            row.remove();
            updateTotals();
        } else {
            alert('Debe haber al menos un producto en la venta');
        }
    }
    
    // ============================================================
    // CALCULAR SUBTOTAL
    // ============================================================
    function calculateRowSubtotal(row) {
        const select = row.querySelector('.product-select');
        const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(select.options[select.selectedIndex]?.dataset.price) || 0;
        const subtotal = quantity * price;
        
        row.querySelector('.subtotal-input').value = '$' + subtotal.toFixed(2);
        return subtotal;
    }
    
    // ============================================================
    // ACTUALIZAR TOTALES
    // ============================================================
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
    
    // ============================================================
    // EVENT LISTENERS
    // ============================================================
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
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>