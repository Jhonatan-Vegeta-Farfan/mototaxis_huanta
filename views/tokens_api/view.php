<?php include_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-eye me-2 text-info"></i>Detalles del Token API
        </h2>
        <div>
            <a href="index.php?controller=tokens_api&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
            </a>
            <a href="index.php?controller=tokens_api&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                <i class="fas fa-key me-2"></i>
                Token ID: <?php echo $this->model->id; ?>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información del Token -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información del Token
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">ID del Token:</th>
                                    <td>
                                        <span class="badge bg-dark fs-6">#<?php echo $this->model->id; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Token:</th>
                                    <td>
                                        <code class="bg-dark text-light p-2 rounded d-block" style="word-break: break-all; font-size: 0.9rem;">
                                            <?php echo $this->model->token; ?>
                                        </code>
                                        <button class="btn btn-sm btn-outline-light mt-2" onclick="copyToClipboard('<?php echo $this->model->token; ?>')">
                                            <i class="fas fa-copy me-1"></i> Copiar Token
                                        </button>
                                    </td>
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

                <!-- Información del Cliente -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2"></i>Información del Cliente
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($clientInfo): ?>
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Cliente:</th>
                                    <td class="fw-bold text-warning"><?php echo $clientInfo->razon_social; ?></td>
                                </tr>
                                <tr>
                                    <th>RUC:</th>
                                    <td>
                                        <span class="badge bg-info text-dark"><?php echo $clientInfo->ruc; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado Cliente:</th>
                                    <td>
                                        <span class="badge bg-<?php echo $clientInfo->estado == 1 ? 'success' : 'danger'; ?>">
                                            <?php echo $clientInfo->estado == 1 ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Contacto:</th>
                                    <td>
                                        <?php if ($clientInfo->telefono || $clientInfo->correo): ?>
                                            <?php if ($clientInfo->telefono): ?>
                                                <div><i class="fas fa-phone me-2"></i><?php echo $clientInfo->telefono; ?></div>
                                            <?php endif; ?>
                                            <?php if ($clientInfo->correo): ?>
                                                <div><i class="fas fa-envelope me-2"></i><?php echo $clientInfo->correo; ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                            <?php else: ?>
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <p>Cliente no encontrado o eliminado</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Estadísticas de Uso -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas de Uso
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Total Requests</span>
                                    <span class="badge bg-primary"><?php echo $stats['total_requests'] ?? 0; ?></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Requests Hoy</span>
                                    <span class="badge bg-success"><?php echo $stats['requests_hoy'] ?? 0; ?></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Requests Este Mes</span>
                                    <span class="badge bg-info"><?php echo $stats['requests_este_mes'] ?? 0; ?></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Último Request</span>
                                    <span class="badge bg-secondary">
                                        <?php echo $stats['ultimo_request'] ? date('d/m/Y H:i', strtotime($stats['ultimo_request'])) : 'Nunca'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Técnica -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>Información Técnica
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Fecha de Registro:</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo date('d/m/Y H:i', strtotime($this->model->fecha_registro)); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>ID Cliente API:</th>
                                    <td>
                                        <span class="badge bg-dark">#<?php echo $this->model->id_client_api; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Longitud del Token:</th>
                                    <td>
                                        <span class="badge bg-info"><?php echo strlen($this->model->token); ?> caracteres</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="index.php?controller=tokens_api&action=edit&id=<?php echo $this->model->id; ?>" 
                                       class="btn btn-warning w-100">
                                        <i class="fas fa-edit me-1"></i> Editar Token
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="index.php?controller=count_request&action=index&token_id=<?php echo $this->model->id; ?>" 
                                       class="btn btn-info w-100">
                                        <i class="fas fa-chart-bar me-1"></i> Ver Requests
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <?php if ($clientInfo): ?>
                                    <a href="index.php?controller=client_api&action=view&id=<?php echo $this->model->id_client_api; ?>" 
                                       class="btn btn-success w-100">
                                        <i class="fas fa-building me-1"></i> Ver Cliente
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="index.php?controller=tokens_api&action=validate&id=<?php echo $this->model->id; ?>" 
                                       class="btn btn-outline-dark w-100"
                                       onclick="return confirm('¿Validar este token?')">
                                        <i class="fas fa-check me-1"></i> Validar Token
                                    </a>
                                </div>
                            </div>
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
                <a href="index.php?controller=tokens_api&action=edit&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-warning btn-lg">
                    <i class="fas fa-edit me-2"></i>Editar Token
                </a>
                <a href="index.php?controller=count_request&action=index&token_id=<?php echo $this->model->id; ?>" 
                   class="btn btn-info btn-lg">
                    <i class="fas fa-chart-bar me-2"></i>Ver Requests
                </a>
                <a href="index.php?controller=tokens_api&action=delete&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-danger btn-lg"
                   onclick="return confirm('¿Está seguro de eliminar este token? Esta acción no se puede deshacer.')">
                    <i class="fas fa-trash me-2"></i>Eliminar Token
                </a>
                <a href="index.php?controller=tokens_api&action=index" 
                   class="btn btn-secondary btn-lg">
                    <i class="fas fa-list me-2"></i>Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Mostrar mensaje de éxito
        const originalText = event.target.innerHTML;
        event.target.innerHTML = '<i class="fas fa-check me-1"></i> Copiado!';
        event.target.classList.remove('btn-outline-light');
        event.target.classList.add('btn-success');
        
        setTimeout(function() {
            event.target.innerHTML = originalText;
            event.target.classList.remove('btn-success');
            event.target.classList.add('btn-outline-light');
        }, 2000);
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
        alert('Error al copiar el token');
    });
}

// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>