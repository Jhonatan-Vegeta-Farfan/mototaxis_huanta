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
                                        <span class="badge bg-dark fs-6">
                                            #<?php echo $this->model->id; ?>
                                        </span>
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
                                <tr>
                                    <th>Fecha de Registro:</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo date('d/m/Y H:i:s', strtotime($this->model->fecha_registro)); ?>
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
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Cliente:</th>
                                    <td>
                                        <?php 
                                        $cliente_nombre = 'No encontrado';
                                        if ($clientes) {
                                            while ($cliente = $clientes->fetch(PDO::FETCH_ASSOC)) {
                                                if ($cliente['id'] == $this->model->id_client_api) {
                                                    $cliente_nombre = $cliente['razon_social'];
                                                    break;
                                                }
                                            }
                                            // Resetear el puntero del resultset
                                            $clientes->execute();
                                        }
                                        echo '<strong class="text-warning">' . $cliente_nombre . '</strong>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>ID Cliente:</th>
                                    <td>
                                        <span class="badge bg-info"><?php echo $this->model->id_client_api; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Acciones:</th>
                                    <td>
                                        <a href="index.php?controller=client_api&action=view&id=<?php echo $this->model->id_client_api; ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> Ver Cliente
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Token Completo -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-key me-2"></i>Token Completo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-dark">
                                <h6 class="alert-heading">
                                    <i class="fas fa-shield-alt me-2"></i>Token de Acceso
                                </h6>
                                <hr>
                                <code class="fs-6 d-block p-3 bg-light rounded" style="word-break: break-all;">
                                    <?php echo $this->model->token; ?>
                                </code>
                                <div class="mt-3">
                                    <button class="btn btn-outline-primary btn-sm" onclick="copyToken()">
                                        <i class="fas fa-copy me-1"></i> Copiar Token
                                    </button>
                                    <a href="index.php?controller=tokens_api&action=validate&id=<?php echo $this->model->id; ?>" 
                                       class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-check me-1"></i> Validar Token
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas y Acciones -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Total Requests</span>
                                    <span class="badge bg-primary">0</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Requests Hoy</span>
                                    <span class="badge bg-success">0</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Requests Este Mes</span>
                                    <span class="badge bg-info">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="index.php?controller=count_request&action=index&token_id=<?php echo $this->model->id; ?>" 
                                   class="btn btn-info">
                                    <i class="fas fa-chart-bar me-1"></i> Ver Requests
                                </a>
                                <a href="index.php?controller=count_request&action=create&token_id=<?php echo $this->model->id; ?>" 
                                   class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i> Nuevo Request
                                </a>
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
                <a href="index.php?controller=tokens_api&action=delete&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-danger btn-lg"
                   onclick="return confirm('¿Está seguro de eliminar este token? Esta acción no se puede deshacer.')">
                    <i class="fas fa-trash me-2"></i>Eliminar Token
                </a>
                <a href="index.php?controller=count_request&action=index&token_id=<?php echo $this->model->id; ?>" 
                   class="btn btn-primary btn-lg">
                    <i class="fas fa-chart-bar me-2"></i>Ver Requests
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
function copyToken() {
    const token = '<?php echo $this->model->token; ?>';
    navigator.clipboard.writeText(token).then(function() {
        alert('Token copiado al portapapeles');
    }, function(err) {
        console.error('Error al copiar: ', err);
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