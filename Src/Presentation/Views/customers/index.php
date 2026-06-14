<div class="card mt-4">
        <div class="card-header p-4" style="background-color: #f5f5f5;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-user-friends"></i> Listado de Clientes
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item">
                                        <a href="<?php echo $_ENV['APP_URL']; ?>/customers/create" class="button is-success">
                                                <i class="fas fa-plus"></i> Nuevo Cliente
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
        
        <div class="card-content">
                <?php if (empty($customers)): ?>
                        <div class="notification is-warning is-light has-text-centered">
                                <i class="fas fa-info-circle"></i> No hay clientes registrados
                        </div>
                <?php else: ?>
                    <div class="table-container">
                            <table class="table is-fullwidth is-hoverable is-striped">
                                    <thead>
                                            <tr>
                                                    <th>ID</th>
                                                    <th>DNI</th>
                                                    <th>Nombre Completo</th>
                                                    <th>Email</th>
                                                    <th>Teléfono</th>
                                                    <th>Fecha Nacimiento</th>
                                                    <th>Fecha Registro</th>
                                                    <th>Acciones</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                            <?php foreach ($customers as $customer): ?>
                                                    <tr>
                                                            <td><?php echo $customer->id; ?></td>
                                                            <td><?php echo htmlspecialchars($customer->dni); ?></td>
                                                            <td><?php echo htmlspecialchars($customer->full_name); ?></td>
                                                            <td><?php echo htmlspecialchars($customer->email); ?></td>
                                                            <td><?php echo htmlspecialchars($customer->phone_number ?? '-'); ?></td>
                                                            <td><?php echo date('d/m/Y', strtotime($customer->birthdate)); ?></td>
                                                            <td><?php echo date('d/m/Y H:i', strtotime($customer->created_at)); ?></td>
                                                            <td>
                                                                    <div class="buttons are-small">
                                                                            <a 
                                                                                    href="<?php echo $_ENV['APP_URL']; ?>/customers/edit?id=<?php echo $customer->id; ?>" 
                                                                                    class="button is-info" title="Editar"
                                                                            >
                                                                                    <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <button 
                                                                                    onclick="confirmDelete(<?php echo $customer->id; ?>)" 
                                                                                    class="button is-danger" 
                                                                                    title="Eliminar"
                                                                            >
                                                                                    <i class="fas fa-trash"></i>
                                                                            </button>
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

<script>
        function confirmDelete(id) {
                if (
                            confirm(
                                    '¿Está seguro que desea eliminar este cliente?\n\nNota: No se podrá eliminar si tiene un usuario asociado.'
                            )
                    ) {
                            window.location.href = '<?php echo $_ENV['APP_URL']; ?>/customers/delete?id=' + id;
                }
        }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>