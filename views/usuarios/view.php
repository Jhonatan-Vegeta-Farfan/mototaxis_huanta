<?php include_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-user-circle me-2 text-primary"></i>Detalles del Usuario
        </h2>
        <div>
            <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
            </a>
            <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-user me-2"></i>
                <?php echo htmlspecialchars($this->model->nombre); ?>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información Principal -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-id-card me-2"></i>Información Principal
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Nombre Completo:</th>
                                    <td class="fw-bold"><?php echo htmlspecialchars($this->model->nombre); ?></td>
                                </tr>
                                <tr>
                                    <th>Usuario:</th>
                                    <td>
                                        <span class="badge bg-info text-dark fs-6">
                                            <?php echo htmlspecialchars($this->model->usuario); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <?php if ($this->model->estado == 1): ?>
                                            <span class="badge bg-success fs-6">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger fs-6">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>Información del Sistema
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">ID de Usuario:</th>
                                    <td>
                                        <span class="badge bg-dark fs-6">#<?php echo $this->model->id; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha de Registro:</th>
                                    <td>
                                        <span class="badge bg-secondary fs-6">
                                            <?php echo date('d/m/Y H:i', strtotime($this->model->fecha_registro)); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Última Actualización:</th>
                                    <td>
                                        <span class="badge bg-warning text-dark fs-6">
                                            <?php echo date('d/m/Y H:i'); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas del Usuario -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas del Usuario
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-primary"><?php echo $this->model->estado == 1 ? 'Activo' : 'Inactivo'; ?></h3>
                                            <p class="text-muted mb-0">Estado Actual</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-success">Sistema</h3>
                                            <p class="text-muted mb-0">Tipo de Usuario</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-info">Admin</h3>
                                            <p class="text-muted mb-0">Rol</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-warning">Completo</h3>
                                            <p class="text-muted mb-0">Accesos</p>
                                        </div>
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
                <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-warning btn-lg">
                    <i class="fas fa-edit me-2"></i>Editar Usuario
                </a>
                <?php if ($this->model->estado == 1): ?>
                    <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                       class="btn btn-secondary btn-lg"
                       onclick="return confirm('¿Desactivar este usuario?')">
                        <i class="fas fa-toggle-on me-2"></i>Desactivar
                    </a>
                <?php else: ?>
                    <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                       class="btn btn-success btn-lg"
                       onclick="return confirm('¿Activar este usuario?')">
                        <i class="fas fa-toggle-off me-2"></i>Activar
                    </a>
                <?php endif; ?>
                <a href="index.php?controller=usuarios&action=delete&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-danger btn-lg"
                   onclick="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                    <i class="fas fa-trash me-2"></i>Eliminar Usuario
                </a>
                <a href="index.php?controller=usuarios&action=index" 
                   class="btn btn-info btn-lg">
                    <i class="fas fa-list me-2"></i>Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>