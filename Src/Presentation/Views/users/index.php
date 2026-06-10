<div class="card mt-4">
        <div class="card-header p-4" style="background-color: #f5f5f5;">
                <div class="level">
                        <div class="level-left">
                                <div class="level-item">
                                        <h2 class="title is-4">
                                                <i class="fas fa-users"></i> Listado de Usuarios
                                        </h2>
                                </div>
                        </div>
                        <div class="level-right">
                                <div class="level-item">
                                        <a 
                                                href="<?php echo $_ENV['APP_URL']; ?>/users/create" 
                                                class="button is-success"
                                        >
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
                                                <th>Activo</th>
                                                <th>Fecha Registro</th>
                                                <th>Acciones</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php foreach ($users as $user): ?>
                                                <tr>
                                                        <td><?php echo htmlspecialchars((string) ($user->id ?? '')); ?></td>
                                                        <td><?php echo htmlspecialchars($user->dni ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($user->user ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($user->email ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($user->role ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($user->is_active ?? ''); ?></td>
                                                        <td>
                                                                <?php
                                                                echo $user->created_at
                                                                        ? date('d/m/Y H:i', strtotime($user->created_at))
                                                                        : '-';
                                                                ?>
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

<!-- Modal de confirmación para eliminar -->
 <div id="deleteModal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
                <header class="modal-card-head">
                        <p class="modal-card-title">
                                <i class="fas fa-exclamation-triangle has-text-warning"></i> Confirmar Eliminación
                        </p>
                        <button class="delete" aria-label="close" onclick="closeModal()"></button>
                </header>
                <section class="modal-card-body">
                        <p>¿Está seguro que desea eliminar este usuario?</p>
                        <!-- <p class="has-text-danger mt-2">
                                <i class="fas fa-info-circle"></i> Nota: No se podrá eliminar si tiene un usuario asociado.
                        </p> -->
                </section>
                <footer class="modal-card-foot">
                        <button onclick="executeDelete()" class="button is-danger">
                                <i class="fas fa-trash"></i> Sí, eliminar
                        </button>
                        <button onclick="closeModal()" class="button">
                                Cancelar
                        </button>
                </footer>
        </div>
 </div>

 <script>
        let deletedId = null;

        function confirmDelete(id) {
                deletedId = id;
                document.getElementById('deleteModal').classList.add('is-active');
        }

        function closeModal() {
                deleteId = null;
                document.getElementById('deleteModal').classList.remove('is-active');
        }
    
        function executeDelete() {
                if (deleteId) {
                        window.location.href = '<?php echo $_ENV['APP_URL']; ?>/users/delete?id=' + deleteId;
                }
        }
 </script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>