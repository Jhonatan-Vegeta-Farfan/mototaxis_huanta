<?php include_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-eye me-2 text-info"></i>Detalles del Request
        </h2>
        <div>
            <a href="index.php?controller=count_request&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
            </a>
            <a href="index.php?controller=count_request&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                <i class="fas fa-chart-bar me-2"></i>
                Request ID: <?php echo $this->model->id; ?>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información del Request -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información del Request
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">ID del Request:</th>
                                    <td>
                                        <span class="badge bg-dark fs-6">
                                            #<?php echo $this->model->id; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>
                                        <span class="badge bg-<?php 
                                            switch($this->model->tipo) {
                                                case 'consulta': echo 'info'; break;
                                                case 'registro': echo 'success'; break;
                                                case 'actualizacion': echo 'warning'; break;
                                                case 'eliminacion': echo 'danger'; break;
                                                default: echo 'secondary';
                                            }
                                        ?>">
                                            <?php echo ucfirst($this->model->tipo); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha:</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo date('d/m/Y H:i:s', strtotime($this->model->fecha)); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Información del Token y Cliente -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-key me-2"></i>Información del Token
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">ID Token:</th>
                                    <td>
                                        <span class="badge bg-info"><?php echo $this->model->id_token_api; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Token:</th>
                                    <td>
                                        <code class="text-truncate d-inline-block" style="max-width: 200px;" 
                                              data-bs-toggle="tooltip" title="<?php 
                                              // Obtener token completo
                                              $token_completo = '';
                                              if ($tokens) {
                                                  while ($token = $tokens->fetch(PDO::FETCH_ASSOC)) {
                                                      if ($token['id'] == $this->model->id_token_api) {
                                                          $token_completo = $token['token'];
                                                          break;
                                                      }
                                                  }
                                                  // Resetear el puntero del resultset
                                                  $tokens->execute();
                                              }
                                              echo $token_completo;
                                              ?>">
                                            <?php echo substr($token_completo, 0, 30) . '...'; ?>
                                        </code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Acciones:</th>
                                    <td>
                                        <a href="index.php?controller=tokens_api&action=view&id=<?php echo $this->model->id_token_api; ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> Ver Token
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>Información Temporal
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h6 class="text-muted">Día de la Semana</h6>
                                        <h4 class="text-primary">
                                            <?php echo date('l', strtotime($this->model->fecha)); ?>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h6 class="text-muted">Hora del Día</h6>
                                        <h4 class="text-success">
                                            <?php echo date('H:i:s', strtotime($this->model->fecha)); ?>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h6 class="text-muted">Hace</h6>
                                        <h4 class="text-info">
                                            <?php
                                            $fecha_request = new DateTime($this->model->fecha);
                                            $fecha_actual = new DateTime();
                                            $diferencia = $fecha_actual->diff($fecha_request);
                                            
                                            if ($diferencia->d > 0) {
                                                echo $diferencia->d . ' días';
                                            } elseif ($diferencia->h > 0) {
                                                echo $diferencia->h . ' horas';
                                            } elseif ($diferencia->i > 0) {
                                                echo $diferencia->i . ' minutos';
                                            } else {
                                                echo $diferencia->s . ' segundos';
                                            }
                                            ?>
                                        </h4>
                                    </div>
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
                <a href="index.php?controller=count_request&action=edit&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-warning btn-lg">
                    <i class="fas fa-edit me-2"></i>Editar Request
                </a>
                <a href="index.php?controller=count_request&action=delete&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-danger btn-lg"
                   onclick="return confirm('¿Está seguro de eliminar este request? Esta acción no se puede deshacer.')">
                    <i class="fas fa-trash me-2"></i>Eliminar Request
                </a>
                <a href="index.php?controller=count_request&action=index&token_id=<?php echo $this->model->id_token_api; ?>" 
                   class="btn btn-primary btn-lg">
                    <i class="fas fa-filter me-2"></i>Ver Todos del Token
                </a>
                <a href="index.php?controller=count_request&action=index" 
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