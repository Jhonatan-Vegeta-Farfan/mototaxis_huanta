<?php
$pageTitle = "Ver Usuario - Sistema Mototaxis";
include_once 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user fa-fw"></i> Detalles del Usuario
            </h1>
            <p class="text-muted">Información completa del usuario</p>
        </div>
        <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-fw"></i> Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle fa-fw"></i> Información del Usuario
                    </h6>
                    <div>
                        <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit fa-fw"></i> Editar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>ID:</strong>
                            <p class="text-muted"><?php echo htmlspecialchars($this->model->id); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Estado:</strong>
                            <p>
                                <?php if ($this->model->estado == 1): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactivo</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Nombre Completo:</strong>
                            <p class="text-muted"><?php echo htmlspecialchars($this->model->nombre); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Usuario:</strong>
                            <p class="text-muted"><?php echo htmlspecialchars($this->model->usuario); ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de Registro:</strong>
                            <p class="text-muted">
                                <?php 
                                if (!empty($this->model->fecha_registro)) {
                                    echo date('d/m/Y H:i', strtotime($this->model->fecha_registro));
                                } else {
                                    echo 'No registrada';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Última Actualización:</strong>
                            <p class="text-muted"><?php echo date('d/m/Y H:i'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle fa-fw"></i> Información Adicional
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Rol:</strong>
                            <p class="text-muted">Administrador del Sistema</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Permisos:</strong>
                            <p class="text-muted">Acceso completo al sistema</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <strong>Descripción:</strong>
                            <p class="text-muted">Usuario con permisos administrativos para gestionar empresas, mototaxis, clientes API y otros usuarios del sistema.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs fa-fw"></i> Acciones
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-warning btn-block">
                                <i class="fas fa-edit fa-fw"></i> Editar Usuario
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <?php if ($this->model->estado == 1): ?>
                                <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                                   class="btn btn-secondary btn-block"
                                   onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                                    <i class="fas fa-toggle-on fa-fw"></i> Desactivar
                                </a>
                            <?php else: ?>
                                <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                                   class="btn btn-success btn-block"
                                   onclick="return confirm('¿Está seguro de activar este usuario?')">
                                    <i class="fas fa-toggle-off fa-fw"></i> Activar
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="index.php?controller=usuarios&action=delete&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-danger btn-block"
                               onclick="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                <i class="fas fa-trash fa-fw"></i> Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>