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
        <div>
            <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left fa-fw me-1"></i> Volver
            </a>
            <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit fa-fw me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-circle fa-fw"></i> Información del Usuario
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-id-card me-2"></i>ID:
                                </strong>
                                <span class="detail-value">
                                    <span class="badge bg-primary">#<?php echo htmlspecialchars($this->model->id); ?></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-toggle-on me-2"></i>Estado:
                                </strong>
                                <span class="detail-value">
                                    <?php if ($this->model->estado == 1): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Inactivo
                                        </span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-user me-2"></i>Nombre Completo:
                                </strong>
                                <span class="detail-value"><?php echo htmlspecialchars($this->model->nombre); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-at me-2"></i>Usuario:
                                </strong>
                                <span class="detail-value"><?php echo htmlspecialchars($this->model->usuario); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-calendar-alt me-2"></i>Fecha de Registro:
                                </strong>
                                <span class="detail-value">
                                    <?php 
                                    $fecha = new DateTime($this->model->fecha_registro);
                                    echo $fecha->format('d/m/Y H:i:s');
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-clock me-2"></i>Hace:
                                </strong>
                                <span class="detail-value">
                                    <?php
                                    $fechaRegistro = new DateTime($this->model->fecha_registro);
                                    $hoy = new DateTime();
                                    $diferencia = $hoy->diff($fechaRegistro);
                                    
                                    if ($diferencia->y > 0) {
                                        echo $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '');
                                    } elseif ($diferencia->m > 0) {
                                        echo $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '');
                                    } elseif ($diferencia->d > 0) {
                                        echo $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
                                    } elseif ($diferencia->h > 0) {
                                        echo $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
                                    } else {
                                        echo 'Menos de 1 hora';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Tarjeta de acciones rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt fa-fw"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if ($this->model->estado == 1): ?>
                            <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-warning btn-sm"
                               onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                                <i class="fas fa-toggle-on me-1"></i> Desactivar Usuario
                            </a>
                        <?php else: ?>
                            <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('¿Está seguro de activar este usuario?')">
                                <i class="fas fa-toggle-off me-1"></i> Activar Usuario
                            </a>
                        <?php endif; ?>
                        
                        <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Editar Información
                        </a>
                        
                        <a href="index.php?controller=usuarios&action=delete&id=<?php echo $this->model->id; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                            <i class="fas fa-trash me-1"></i> Eliminar Usuario
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de información del sistema -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle fa-fw"></i> Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">
                            <i class="fas fa-database me-2"></i>ID en Base de Datos:
                        </small>
                        <strong>#<?php echo htmlspecialchars($this->model->id); ?></strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">
                            <i class="fas fa-shield-alt me-2"></i>Nivel de Acceso:
                        </small>
                        <strong>Usuario del Sistema</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">
                            <i class="fas fa-history me-2"></i>Última Actualización:
                        </small>
                        <strong>Al crear el usuario</strong>
                    </div>
                    
                    <div>
                        <small class="text-muted d-block">
                            <i class="fas fa-user-check me-2"></i>Estado de Sesión:
                        </small>
                        <?php if ($this->model->estado == 1): ?>
                            <span class="text-success">
                                <i class="fas fa-check-circle me-1"></i>Puede iniciar sesión
                            </span>
                        <?php else: ?>
                            <span class="text-danger">
                                <i class="fas fa-times-circle me-1"></i>No puede iniciar sesión
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de estadísticas adicionales -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar fa-fw"></i> Resumen del Usuario
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-user-clock fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">Antigüedad</h5>
                                <?php
                                $fechaRegistro = new DateTime($this->model->fecha_registro);
                                $hoy = new DateTime();
                                $diferencia = $hoy->diff($fechaRegistro);
                                $dias = $diferencia->days;
                                ?>
                                <p class="mb-0 h4 text-primary"><?php echo $dias; ?> días</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-toggle-on fa-2x text-success mb-2"></i>
                                <h5 class="mb-1">Estado Actual</h5>
                                <p class="mb-0">
                                    <?php if ($this->model->estado == 1): ?>
                                        <span class="badge bg-success">ACTIVO</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">INACTIVO</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-calendar-check fa-2x text-info mb-2"></i>
                                <h5 class="mb-1">Registrado el</h5>
                                <p class="mb-0 text-muted">
                                    <?php 
                                    $fecha = new DateTime($this->model->fecha_registro);
                                    echo $fecha->format('d/m/Y');
                                    ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h5 class="mb-1">Hora de Registro</h5>
                                <p class="mb-0 text-muted">
                                    <?php 
                                    $fecha = new DateTime($this->model->fecha_registro);
                                    echo $fecha->format('H:i:s');
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.2s ease;
}

.detail-item:hover {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding-left: 10px;
    padding-right: 10px;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    color: #1e3c72;
    font-weight: 600;
    font-size: 0.95rem;
    flex: 1;
}

.detail-value {
    color: #374151;
    font-weight: 500;
    text-align: right;
    flex: 1;
}

.card {
    border: none;
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Efectos de hover para las tarjetas de estadísticas
    const statCards = document.querySelectorAll('.border.rounded');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Confirmación para acciones importantes
    const deleteBtn = document.querySelector('.btn-danger');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            if (!confirm('⚠️ ¿ESTÁ SEGURO DE ELIMINAR ESTE USUARIO?\n\nEsta acción es irreversible y el usuario perderá el acceso al sistema.')) {
                e.preventDefault();
            }
        });
    }

    // Tooltips para íconos informativos
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include_once 'layouts/footer.php'; ?>