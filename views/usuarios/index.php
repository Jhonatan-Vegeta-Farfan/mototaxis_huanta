<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Gestión de Usuarios</h2>
    <a href="index.php?controller=usuarios&action=create" class="btn btn-success">
        <i class="fas fa-user-plus me-1"></i> Nuevo Usuario
    </a>
</div>

<!-- Sistema de Búsqueda -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-search me-2"></i>Buscar Usuarios</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="">
            <input type="hidden" name="controller" value="usuarios">
            <input type="hidden" name="action" value="index">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Buscar por nombre o usuario..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-warning" type="submit">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary w-100">
                        <i class="fas fa-sync-alt me-1"></i> Mostrar Todos
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Usuarios -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Usuarios</h4>
        <span class="badge bg-primary">
            <?php 
            $rowCount = $stmt->rowCount();
            echo $rowCount . ' usuario' . ($rowCount != 1 ? 's' : '') . ' encontrado' . ($rowCount != 1 ? 's' : '');
            ?>
        </span>
    </div>
    <div class="card-body">
        <?php if ($stmt->rowCount() > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Fecha Registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nombre']); ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo htmlspecialchars($row['usuario']); ?></span>
                        </td>
                        <td>
                            <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($row['fecha_registro'])); ?></small>
                        </td>
                        <td>
                            <?php if ($row['estado'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=usuarios&action=view&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="index.php?controller=usuarios&action=edit&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($row['estado'] == 1): ?>
                                    <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $row['id']; ?>" 
                                       class="btn btn-secondary btn-sm" 
                                       onclick="return confirm('¿Desactivar este usuario?')"
                                       data-bs-toggle="tooltip" title="Desactivar">
                                        <i class="fas fa-toggle-on"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $row['id']; ?>" 
                                       class="btn btn-success btn-sm" 
                                       onclick="return confirm('¿Activar este usuario?')"
                                       data-bs-toggle="tooltip" title="Activar">
                                        <i class="fas fa-toggle-off"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="index.php?controller=usuarios&action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Está seguro de eliminar este usuario?')"
                                   data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No se encontraron usuarios</h4>
            <p class="text-muted">No hay usuarios registrados en el sistema.</p>
            <a href="index.php?controller=usuarios&action=create" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> Crear Primer Usuario
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>