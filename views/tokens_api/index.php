<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-key me-2"></i>Tokens API</h2>
    <a href="index.php?controller=tokens_api&action=create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Nuevo Token
    </a>
</div>

<!-- Indicador de Filtro por Cliente -->
<?php if (isset($_GET['client_id']) && !empty($_GET['client_id'])): ?>
<?php
    // Obtener información del cliente para mostrar
    $clientModel = new ClientApi($db_connection);
    $clientModel->id = $_GET['client_id'];
    $clientInfo = '';
    if ($clientModel->readOne()) {
        $clientInfo = $clientModel->razon_social . ' (RUC: ' . $clientModel->ruc . ')';
    }
?>
<div class="alert alert-info mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-filter me-2"></i>
            <strong>Filtrado por cliente:</strong> <?php echo $clientInfo; ?>
        </div>
        <a href="index.php?controller=tokens_api&action=index" class="btn btn-sm btn-outline-info">
            <i class="fas fa-times me-1"></i> Quitar Filtro
        </a>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Tokens API</h4>
        <span class="badge bg-primary">
            <?php 
            $rowCount = $stmt->rowCount();
            echo $rowCount . ' token' . ($rowCount != 1 ? 's' : '') . ' encontrado' . ($rowCount != 1 ? 's' : '');
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
                        <th>Cliente</th>
                        <th>Token</th>
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
                            <strong class="text-warning"><?php echo $row['razon_social']; ?></strong>
                            <br>
                            <small class="text-muted">RUC: <?php echo $row['ruc']; ?></small>
                            <br>
                            <small>
                                <a href="index.php?controller=tokens_api&action=index&client_id=<?php echo $row['id_client_api']; ?>" 
                                   class="text-info">
                                    <i class="fas fa-filter me-1"></i>Filtrar por este cliente
                                </a>
                            </small>
                        </td>
                        <td>
                            <code class="text-light bg-dark p-1 rounded d-block" style="font-size: 0.8rem;">
                                <?php echo substr($row['token'], 0, 30) . '...'; ?>
                            </code>
                            <small class="text-muted d-block mt-1">
                                ID Cliente: <?php echo $row['id_client_api']; ?>
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $row['estado'] == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $row['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=tokens_api&action=edit&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar Token">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=tokens_api&action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Está seguro de eliminar este token?')"
                                   data-bs-toggle="tooltip" title="Eliminar Token">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="index.php?controller=tokens_api&action=view&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Ver Detalles Completos">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="index.php?controller=count_request&action=index&token_id=<?php echo $row['id']; ?>" 
                                   class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Ver Requests">
                                    <i class="fas fa-chart-bar"></i>
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
            <i class="fas fa-key fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No se encontraron tokens</h4>
            <p class="text-muted">
                <?php if (isset($_GET['client_id'])): ?>
                Este cliente no tiene tokens registrados.
                <?php else: ?>
                No hay tokens registrados en el sistema.
                <?php endif; ?>
            </p>
            <a href="index.php?controller=tokens_api&action=create" class="btn btn-success me-2">
                <i class="fas fa-plus me-1"></i> Crear Nuevo Token
            </a>
            <a href="index.php?controller=tokens_api&action=index" class="btn btn-primary">
                <i class="fas fa-list me-1"></i> Ver Todos los Tokens
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>