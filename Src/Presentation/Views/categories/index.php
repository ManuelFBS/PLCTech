<div class="card mt-4">
    <div class="card-header p-4" style="background-color: #f5f5f5;">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h2 class="title is-4">
                        <i class="fas fa-tags"></i> Listado de Categorías
                    </h2>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a href="<?= \PLCTech\Helpers\UrlHelper::url(
                            '/categories/create',
                    ) ?>" class="button is-success">
                        <i class="fas fa-plus"></i> Nueva Categoría
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-content">
        <?php if (empty($categories)): ?>
            <div class="notification is-warning is-light has-text-centered">
                <i class="fas fa-info-circle"></i> No hay categorías registradas
                <br>
                <a href="<?= \PLCTech\Helpers\UrlHelper::url(
                        '/categories/create',
                ) ?>" class="button is-primary mt-3">
                    <i class="fas fa-plus"></i> Crear primera categoría
                </a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table is-fullwidth is-hoverable is-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $category->id ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($category->name) ?></strong>
                                </td>
                                <td>
                                    <?= htmlspecialchars(
                                            $category->description ?? 'Sin descripción',
                                    ) ?>
                                </td>
                                <td>
                                    <?= $category->created_at
                                            ? date('d/m/Y H:i', strtotime($category->created_at))
                                            : '-' ?>
                                </td>
                                <td>
                                    <div class="buttons are-small">
                                        <a href="<?= \PLCTech\Helpers\UrlHelper::url(
                                                '/categories/edit',
                                                ['id' => $category->id],
                                        ) ?>" 
                                           class="button is-info" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button 
                                            class="button is-danger"
                                           data-id="<?= (int) $category->id ?>"
                                           data-name="<?= htmlspecialchars(
                                                   $category->name,
                                                   ENT_QUOTES,
                                                   'UTF-8',
                                           ) ?>"
                                           onclick="confirmDelete(this.dataset.id, this.dataset.name)"
                                           title="Eliminar">
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
            <p>¿Está seguro que desea eliminar la categoría <strong id="categoryName"></strong>?</p>
            <p class="has-text-danger mt-2">
                <i class="fas fa-info-circle"></i> Nota: No se podrá eliminar si tiene productos asociados.
            </p>
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
    let deleteId = null;
    
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('categoryName').textContent = name;
        document.getElementById('deleteModal').classList.add('is-active');
    }
    
    function closeModal() {
        deleteId = null;
        document.getElementById('deleteModal').classList.remove('is-active');
    }
    
    function executeDelete() {
        if (deleteId) {
            window.location.href = 
            '<?= \PLCTech\Helpers\UrlHelper::url('/categories/delete') ?>?id=' + deleteId;
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
