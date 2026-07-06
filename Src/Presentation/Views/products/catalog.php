<?php

// * Si alguien abre catalog.php directamente, evitamos el error...
if (!isset($categories)) {
        $categories = [];
}
if (!isset($products)) {
        $products = [];
}

?>

<div class="columns is-multiline mt-4">
        <!-- Sidebar con categorías -->
        <div class="column is-3">
                <div class="card">
                        <header class="card-header">
                                <p class="card-header-title">
                                        <i class="fas fa-tags"></i> Categorías
                                </p>
                        </header>
                        <div class="card-content">
                                <aside class="menu">
                                        <ul class="menu-list">
                                                <li>
                                                        <a href="<?php echo $_ENV['APP_URL']; ?>/products/catalog" 
                                                        class="<?php echo !isset($_GET['category_id']) ? 'is-active' : ''; ?>">
                                                                <i class="fas fa-th-list"></i> Todos los productos
                                                        </a>
                                                </li>
                                                <?php foreach ($categories as $category): ?>
                                                        <li>
                                                                <a href="<?php echo $_ENV['APP_URL']; ?>/products/catalog?category_id=<?php echo $category->getId(); ?>"
                                                                        class="<?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $category->getId()) ? 'is-active' : ''; ?>">
                                                                        <i class="fas fa-folder"></i> <?php echo htmlspecialchars($category->getName()); ?>
                                                                </a>
                                                        </li>
                                                <?php endforeach; ?>
                                        </ul>
                                </aside>
                        </div>
                </div>
                
                <!-- Filtros adicionales -->
                <div class="card mt-4">
                        <header class="card-header">
                                <p class="card-header-title">
                                        <i class="fas fa-filter"></i> Filtros
                                </p>
                        </header>
                        <div class="card-content">
                                <div class="field">
                                        <label class="label">Precio máximo</label>
                                        <div class="control">
                                                <input 
                                                        class="input" 
                                                        type="number" 
                                                        id="maxPrice" 
                                                        placeholder="Ej: 1000" 
                                                        onchange="filterByPrice()"
                                                >
                                        </div>
                                </div>
                                <div class="field">
                                        <label class="checkbox">
                                                <input 
                                                        type="checkbox" 
                                                        id="inStock" 
                                                        onchange="filterByStock()"
                                                >
                                                Solo productos en stock
                                        </label>
                                </div>
                        </div>
                </div>
        </div>
    
        <!-- Listado de productos -->
        <div class="column is-9">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-boxes"></i> Catálogo de Productos
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item">
                                        <div class="field has-addons">
                                                <div class="control">
                                                        <input 
                                                                class="input" 
                                                                type="text" 
                                                                id="searchProduct" 
                                                                placeholder="Buscar producto..."
                                                        >
                                                </div>
                                                <div class="control">
                                                        <button class="button is-info" onclick="searchProducts()">
                                                                <i class="fas fa-search"></i>
                                                        </button>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                
                <?php if (empty($products)): ?>
                <div class="notification is-warning is-light has-text-centered">
                        <i class="fas fa-info-circle"></i> 
                        No hay productos disponibles en esta categoría
                </div>
                <?php else: ?>
                        <div class="columns is-multiline">
                                <?php foreach ($products as $product): ?>
                                        <div class="column is-4">
                                                <div 
                                                        class="card product-card" 
                                                        data-price="<?php echo $product->price; ?>" 
                                                        data-stock="<?php echo $product->stock; ?>"
                                                >
                                                <div class="card-image">
                                                        <figure class="image is-4by3">
                                                                <?php if ($product->image_prod): ?>
                                                                        <img src="<?php echo $_ENV['APP_URL']; ?>/uploads/products/<?php echo $product->image_prod; ?>" 
                                                                                alt="<?php echo htmlspecialchars($product->name); ?>"
                                                                                style="object-fit: cover; width: 100%; height: 100%;">
                                                                <?php else: ?>
                                                                        <div style="width: 100%; height: 100%; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                                                                <i class="fas fa-image fa-3x has-text-grey-light"></i>
                                                                                <p class="has-text-grey-light">Sin imagen</p>
                                                                        </div>
                                                                <?php endif; ?>
                                                        
                                                                <?php if ($product->stock <= 0): ?>
                                                                        <span class="tag is-danger" style="position: absolute; top: 10px; right: 10px; font-size: 0.9rem;">
                                                                                Agotado
                                                                        </span>
                                                                <?php elseif ($product->stock <= 5): ?>
                                                                        <span class="tag is-warning" style="position: absolute; top: 10px; right: 10px; font-size: 0.9rem; background-color: #ffeaa7; color: #d63031;">
                                                                                Stock bajo
                                                                        </span>
                                                                <?php endif; ?>
                                                        </figure>
                                                </div>
                                                
                                                <div class="card-content">
                                                        <div class="media">
                                                                <div class="media-content">
                                                                        <p class="title is-6"><?php echo htmlspecialchars($product->name); ?></p>
                                                                                <?php if ($product->category_name): ?>
                                                                        <p class="subtitle is-7 has-text-grey">
                                                                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($product->category_name); ?>
                                                                        </p>
                                                                        <?php endif; ?>
                                                                </div>
                                                        </div>
                                                        
                                                        <div class="content">
                                                                <p class="has-text-primary has-text-weight-bold is-size-5">
                                                                        $<?php echo number_format($product->price, 2); ?>
                                                                </p>
                                                                <p class="is-size-7 has-text-grey">
                                                                        <i class="fas fa-boxes"></i> Stock: <?php echo $product->stock; ?> unidades
                                                                </p>
                                                        </div>
                                                        
                                                        <div class="buttons">
                                                                <a href="<?php echo $_ENV['APP_URL']; ?>/products/show?id=<?php echo $product->id; ?>" 
                                                                        class="button is-info is-fullwidth"
                                                                >
                                                                        <i class="fas fa-eye"></i> Ver detalles
                                                                </a>
                                                                <div class="buttons">
                                                                        <a 
                                                                                href="<?php echo $_ENV['APP_URL']; ?>/products/show?id=<?php echo $product->id; ?>" 
                                                                                class="button is-info is-fullwidth"
                                                                        >
                                                                                <i class="fas fa-eye"></i> Ver detalles
                                                                        </a>
                                                                        
                                                                        <?php if ($product->stock > 0): ?>
                                                                                <form 
                                                                                        action="<?php echo $_ENV['APP_URL']; ?>/cart/add" 
                                                                                        method="POST" 
                                                                                        style="display: inline; width: 100%;"
                                                                                >
                                                                                        <input 
                                                                                                type="hidden" 
                                                                                                name="product_id" 
                                                                                                value="<?php echo $product->id; ?>"
                                                                                        >
                                                                                        <input 
                                                                                                type="hidden" 
                                                                                                name="quantity" 
                                                                                                value="1"
                                                                                        >
                                                                                        <button type="submit" class="button is-success is-fullwidth">
                                                                                                <i class="fas fa-shopping-cart"></i> Agregar al carrito
                                                                                        </button>
                                                                                </form>
                                                                        <?php else: ?>
                                                                                <button class="button is-light is-fullwidth" disabled>
                                                                                        <i class="fas fa-times"></i> No disponible
                                                                                </button>
                                                                        <?php endif; ?>
                                                                </div>
                                                        </div>
                                                </div>
                                                </div>
                                        </div>
                                <?php endforeach; ?>
                        </div>
                <?php endif; ?>
        </div>
</div>

<script>
        function searchProducts() {
                const searchTerm = document.getElementById('searchProduct').value;
                if (searchTerm) {
                        window.location.href = '<?php echo $_ENV['APP_URL']; ?>/products/catalog?search=' + encodeURIComponent(searchTerm);
                } else {
                        window.location.href = '<?php echo $_ENV['APP_URL']; ?>/products/catalog';
                }
        }
        
        function filterByPrice() {
                const maxPrice = document.getElementById('maxPrice').value;
                const products = document.querySelectorAll('.product-card');
                
                products.forEach(product => {
                        const price = parseFloat(product.dataset.price);
                        if (maxPrice && price > maxPrice) {
                                product.parentElement.style.display = 'none';
                        } else {
                                product.parentElement.style.display = 'block';
                        }
                });
        }
    
        function filterByStock() {
                const inStock = document.getElementById('inStock').checked;
                const products = document.querySelectorAll('.product-card');
                
                products.forEach(product => {
                        const stock = parseInt(product.dataset.stock);
                        if (inStock && stock <= 0) {
                                product.parentElement.style.display = 'none';
                        } else {
                                product.parentElement.style.display = 'block';
                        }
                });
        }
        
        function addToCart(productId) {
                // * Aquí implementaremos la funcionalidad del carrito en el siguiente paso...
                alert('Producto agregado al carrito (próximamente)');
        }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>