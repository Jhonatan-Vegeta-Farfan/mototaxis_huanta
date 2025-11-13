<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-eye me-2"></i>Detalles del Cliente API</h2>
    <a href="index.php?controller=client_api&action=index" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Cliente</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">ID:</th>
                                <td><?php echo $this->model->id; ?></td>
                            </tr>
                            <tr>
                                <th>RUC:</th>
                                <td><span class="badge bg-info"><?php echo $this->model->ruc; ?></span></td>
                            </tr>
                            <tr>
                                <th>Razón Social:</th>
                                <td><strong><?php echo $this->model->razon_social; ?></strong></td>
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
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Teléfono:</th>
                                <td><?php echo $this->model->telefono ?: '<span class="text-muted">No especificado</span>'; ?></td>
                            </tr>
                            <tr>
                                <th>Correo:</th>
                                <td><?php echo $this->model->correo ?: '<span class="text-muted">No especificado</span>'; ?></td>
                            </tr>
                            <tr>
                                <th>Fecha Registro:</th>
                                <td><span class="badge bg-secondary"><?php echo $this->model->fecha_registro; ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tokens del Cliente -->
        <div class="card mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-key me-2"></i>Tokens del Cliente</h4>
            </div>
            <div class="card-body">
                <?php if ($tokens->rowCount() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Token</th>
                                <th>Fecha Registro</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($token = $tokens->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><code><?php echo substr($token['token'], 0, 30) . '...'; ?></code></td>
                                <td><?php echo $token['fecha_registro']; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $token['estado'] == 1 ? 'success' : 'danger'; ?>">
                                        <?php echo $token['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?controller=tokens_api&action=edit&id=<?php echo $token['id']; ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center">Este cliente no tiene tokens registrados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Estadísticas -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h4>
            </div>
            <div class="card-body">
                <?php
                $stats = $this->model->getStats($this->model->id);
                ?>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Total Tokens</span>
                        <span class="badge bg-primary"><?php echo $stats['total_tokens']; ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Tokens Activos</span>
                        <span class="badge bg-success"><?php echo $stats['tokens_activos']; ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Total Requests</span>
                        <span class="badge bg-secondary"><?php echo $stats['total_requests']; ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Requests Hoy</span>
                        <span class="badge bg-warning"><?php echo $stats['requests_hoy']; ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Requests Este Mes</span>
                        <span class="badge bg-info"><?php echo $stats['requests_este_mes']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?controller=tokens_api&action=create&client_id=<?php echo $this->model->id; ?>" 
                       class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Token
                    </a>
                    <a href="index.php?controller=client_api&action=edit&id=<?php echo $this->model->id; ?>" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Editar Cliente
                    </a>
                    <a href="index.php?controller=count_request&action=index&client_id=<?php echo $this->model->id; ?>" 
                       class="btn btn-info">
                        <i class="fas fa-chart-bar me-1"></i> Ver Requests
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>