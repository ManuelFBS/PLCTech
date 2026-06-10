<div class="card mt-4">
        <div class="card-header p-4" style="background-color: #f5f5f5;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-user-lock"></i> Listado de Usuarios
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item">
                                        <a href="<?php echo $_ENV['APP_URL']; ?>/users/create" class="button is-success">
                                                <i class="fas fa-plus"></i> Nuevo Usuario
                                        </a>
                                </div>
                        </div>
                </div>
        </div>
    
        <div class="card-content">
                <?php if (empty($users)): ?>
                        <div class="notification is-warning is-light has-text-centered">
                                <i class="fas fa-info-circle"></i> No hay usuarios registrados
                        </div>
                <?php else: ?>
                        <div class="table-container">
                                <table class="table is-fullwidth is-hoverable is-striped">
                                        <thead>
                                                <tr>
                                                        <th>ID</th>
                                                        <th>DNI</th>
                                                        <th>Usuario</th>
                                                        <th>Email</th>
                                                        <th>Rol</th>
                                                        <th>Estado</th>
                                                        <th>Último Login</th>
                                                        <th>Acciones</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <?php foreach ($users as $user): ?>
                                                        <tr>
                                                                <td><?php echo $user->id; ?></td>
                                                                <td><?php echo htmlspecialchars($user->dni); ?></td>
                                                                <td><?php echo htmlspecialchars($user->user); ?></td>
                                                                <td><?php echo htmlspecialchars($user->email); ?></td>
                                                                <td>
                                                                        <?php
                                                                        $badgeColor = match ($user->role) {
                                                                                'Admin' => 'is-danger',
                                                                                'Employee' => 'is-warning',
                                                                                'Customer' => 'is-info',
                                                                                default => 'is-light'
                                                                        };
                                                                        ?>
                                                                        <span class="tag <?php echo $badgeColor; ?>">
                                                                                <?php echo $user->role; ?>
                                                                        </span>
                                                                </td>
                                                                <td>
                                                                        <?php if ($user->is_active): ?>
                                                                                <span class="tag is-success">Activo</span>
                                                                        <?php else: ?>
                                                                                <span class="tag is-danger">Bloqueado</span>
                                                                        <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                        <?php echo $user->last_login ? date('d/m/Y H:i', strtotime($user->last_login)) : 'Nunca'; ?>
                                                                </td>
                                                                <td>
                                                                        <div class="buttons are-small">
                                                                                <a 
                                                                                        href="<?php echo $_ENV['APP_URL']; ?>/users/edit?id=<?php echo $user->id; ?>" 
                                                                                        class="button is-info" 
                                                                                        title="Editar"
                                                                                >
                                                                                        <i class="fas fa-edit"></i>
                                                                                </a>
                                                                                <button 
                                                                                        onclick="confirmDelete(<?php echo $user->id; ?>)" 
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
                if (confirm('¿Está seguro que desea eliminar este usuario?')) {
                        window.location.href = '<?php echo $_ENV['APP_URL']; ?>/users/delete?id=' + id;
                }
        }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>