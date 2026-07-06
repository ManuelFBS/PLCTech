<?php
// ~ Continuación del layout...
?>

<div class="hero is-light mt-5" style="border-radius: 10px;">
        <div class="hero-body">
                <div class="container has-text-centered">
                        <h1 class="title">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                        </h1>
                        <h2 class="subtitle">Bienvenido, <strong>
                                    <?php
$displayName = $_SESSION['full_name'] ?? $_SESSION['username'];
echo htmlspecialchars($displayName);
?>
                                </strong>
                        </h2>
                        <p class="mt-3">
                                Rol: <span class="tag is-primary is-medium"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
                        </p>
                </div>
        </div>
</div>

<div class="columns is-multiline mt-5">
        <!-- Tarjetas de resumen según el rol -->
        <?php if ($_SESSION['role'] === 'Admin'): ?>
        <div class="column is-3">
                <div class="card has-shadow">
                        <div class="card-content has-text-centered">
                                <i class="fas fa-users fa-3x has-text-primary"></i>
                                <h3 class="title is-5 mt-3">Empleados</h3>
                                <p class="subtitle is-6">Gestión completa</p>
                                <a href="<?php echo $_ENV['APP_URL']; ?>/employees" class="button is-primary is-small">
                                        Ver empleados
                                </a>
                        </div>
                </div>
        </div>
        <?php endif; ?>
        
        <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Employee'): ?>
                <div class="column is-3">
                        <div class="card has-shadow">
                                <div class="card-content has-text-centered">
                                        <i class="fas fa-user-friends fa-3x has-text-info"></i>
                                        <h3 class="title is-5 mt-3">Clientes</h3>
                                        <p class="subtitle is-6">Base de datos</p>
                                        <a href="<?php echo $_ENV['APP_URL']; ?>/customers" class="button is-info is-small">
                                                Ver clientes
                                        </a>
                                </div>
                        </div>
                </div>
        <?php endif; ?>
    
        <div class="column is-3">
                <div class="card has-shadow">
                        <div class="card-content has-text-centered">
                            <i class="fas fa-boxes fa-3x has-text-success"></i>
                            <h3 class="title is-5 mt-3">Productos</h3>
                            <p class="subtitle is-6">Catálogo</p>
                            <a href="<?php echo $_ENV['APP_URL']; ?>/products/catalog" class="button is-success is-small">
                                    Ver productos
                            </a>
                        </div>
                </div>
        </div>
        
        <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Employee'): ?>
                <div class="column is-3">
                        <div class="card has-shadow">
                                <div class="card-content has-text-centered">
                                        <i class="fas fa-shopping-cart fa-3x has-text-warning"></i>
                                        <h3 class="title is-5 mt-3">Ventas</h3>
                                        <p class="subtitle is-6">Registros</p>
                                        <a href="<?php echo $_ENV['APP_URL']; ?>/purchases" class="button is-warning is-small">
                                                Ver ventas
                                        </a>
                                </div>
                        </div>
                </div>
        <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>