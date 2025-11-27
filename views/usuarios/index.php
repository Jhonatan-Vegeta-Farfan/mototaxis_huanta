<?php
$pageTitle = "Gestión de Usuarios - Sistema Mototaxis Huanta";
include_once 'views/layouts/header.php';

// Obtener lista de usuarios
$query = "SELECT * FROM usuarios ORDER BY id ASC";
$stmt = $conn->prepare($query);
$stmt->execute();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">
                                <i class="fas fa-users me-2"></i>Gestión de Usuarios
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="index.php?controller=usuarios&action=create" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>Nuevo Usuario
                            </a>
                            <a href="index.php" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Mostrar mensajes de sesión -->
                    <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success_message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success_message']); endif; ?>

                    <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $_SESSION['error_message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error_message']); endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tablaUsuarios">
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
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_registro'])); ?></td>
                                    <td>
                                        <?php if ($row['estado'] == 1): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="index.php?controller=usuarios&action=view&id=<?php echo $row['id']; ?>" 
                                               class="btn btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?controller=usuarios&action=edit&id=<?php echo $row['id']; ?>" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($row['estado'] == 1): ?>
                                                <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-secondary" title="Desactivar"
                                                   onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                                                    <i class="fas fa-toggle-on"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-success" title="Activar"
                                                   onclick="return confirm('¿Está seguro de activar este usuario?')">
                                                    <i class="fas fa-toggle-off"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="index.php?controller=usuarios&action=delete&id=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger" title="Eliminar"
                                               onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($stmt->rowCount() == 0): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay usuarios registrados</h5>
                        <p class="text-muted">Comience creando el primer usuario del sistema.</p>
                        <a href="index.php?controller=usuarios&action=create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Crear Primer Usuario
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaUsuarios').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true
    });
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>