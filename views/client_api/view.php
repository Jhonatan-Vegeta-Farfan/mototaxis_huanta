<?php include_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-eye me-2 text-info"></i>Detalles del Cliente API
        </h2>
        <div>
            <a href="index.php?controller=client_api&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
            </a>
            <a href="index.php?controller=client_api&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                <i class="fas fa-users me-2"></i>
                <?php echo $this->model->razon_social; ?>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información Principal -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información Principal
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">RUC:</th>
                                    <td>
                                        <span class="badge bg-info text-dark fs-6">
                                            <?php echo $this->model->ruc; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Razón Social:</th>
                                    <td class="fw-bold text-warning"><?php echo $this->model->razon_social; ?></td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <span class="badge bg-<?php echo $this->model->estado == 1 ? 'success' : 'danger'; ?>">
                                            <?php echo $this->model->estado == 1 ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-address-book me-2"></i>Información de Contacto
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Teléfono:</th>
                                    <td>
                                        <?php if ($this->model->telefono): ?>
                                            <span class="text-dark"><?php echo $this->model->telefono; ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Correo:</th>
                                    <td>
                                        <?php if ($this->model->correo): ?>
                                            <a href="mailto:<?php echo $this->model->correo; ?>" class="text-decoration-none">
                                                <?php echo $this->model->correo; ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha Registro:</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo date('d/m/Y', strtotime($this->model->fecha_registro)); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Estadísticas -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Total Tokens</span>
                                    <span class="badge bg-primary"><?php echo $stats['total_tokens'] ?? 0; ?></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Tokens Activos</span>
                                    <span class="badge bg-success"><?php echo $stats['tokens_activos'] ?? 0; ?></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Total Requests</span>
                                    <span class="badge bg-secondary"><?php echo $stats['total_requests'] ?? 0; ?></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Requests Hoy</span>
                                    <span class="badge bg-info"><?php echo $stats['requests_hoy'] ?? 0; ?></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Requests Este Mes</span>
                                    <span class="badge bg-dark"><?php echo $stats['requests_este_mes'] ?? 0; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="index.php?controller=tokens_api&action=create&client_id=<?php echo $this->model->id; ?>" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Nuevo Token
                                </a>
                                <a href="index.php?controller=tokens_api&action=index&client_id=<?php echo $this->model->id; ?>" 
                                   class="btn btn-info">
                                    <i class="fas fa-key me-1"></i> Ver Todos los Tokens
                                </a>
                                <a href="index.php?controller=count_request&action=index&client_id=<?php echo $this->model->id; ?>" 
                                   class="btn btn-warning">
                                    <i class="fas fa-chart-bar me-1"></i> Ver Requests
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tokens del Cliente -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-key me-2"></i>Tokens del Cliente
                                <span class="badge bg-warning ms-2">
                                    <?php echo $tokens->rowCount(); ?> tokens
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($tokens->rowCount() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Token</th>
                                            <th>Fecha Registro</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Resetear el puntero del resultset
                                        $tokens->execute();
                                        while ($token = $tokens->fetch(PDO::FETCH_ASSOC)): 
                                        ?>
                                        <tr>
                                            <td>
                                                <code class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      data-bs-toggle="tooltip" title="<?php echo $token['token']; ?>">
                                                    <?php echo substr($token['token'], 0, 30) . '...'; ?>
                                                </code>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo date('d/m/Y', strtotime($token['fecha_registro'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $token['estado'] == 1 ? 'success' : 'danger'; ?>">
                                                    <?php echo $token['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="index.php?controller=tokens_api&action=edit&id=<?php echo $token['id']; ?>" 
                                                       class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" title="Editar Token">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="index.php?controller=tokens_api&action=view&id=<?php echo $token['id']; ?>" 
                                                       class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-key fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay tokens registrados</h5>
                                <p class="text-muted">Este cliente no tiene tokens asociados actualmente.</p>
                                <a href="index.php?controller=tokens_api&action=create&client_id=<?php echo $this->model->id; ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Crear Primer Token
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="card mt-4">
        <div class="card-body text-center">
            <div class="btn-group" role="group">
                <a href="index.php?controller=client_api&action=edit&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-warning btn-lg">
                    <i class="fas fa-edit me-2"></i>Editar Cliente
                </a>
                <a href="index.php?controller=tokens_api&action=index&client_id=<?php echo $this->model->id; ?>" 
                   class="btn btn-primary btn-lg">
                    <i class="fas fa-key me-2"></i>Gestionar Tokens
                </a>
                <a href="index.php?controller=client_api&action=delete&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-danger btn-lg"
                   onclick="return confirm('¿Está seguro de eliminar este cliente? Esta acción no se puede deshacer.')">
                    <i class="fas fa-trash me-2"></i>Eliminar Cliente
                </a>
                <a href="index.php?controller=client_api&action=index" 
                   class="btn btn-secondary btn-lg">
                    <i class="fas fa-list me-2"></i>Volver al Listado
                </a>
            </div>
        </div>
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