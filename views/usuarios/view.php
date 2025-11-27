<?php
$pageTitle = "Ver Usuario - Sistema Mototaxis";
include_once 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: #1e3c72;">
                <i class="fas fa-user fa-fw"></i> Detalles del Usuario
            </h1>
            <p class="text-muted">Información completa del usuario</p>
        </div>
        <div>
            <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary me-2"
               style="border-radius: 8px; padding: 8px 16px;">
                <i class="fas fa-arrow-left fa-fw me-1"></i> Volver
            </a>
            <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning" style="border-radius: 8px; padding: 8px 16px;">
                <i class="fas fa-edit fa-fw me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-circle fa-fw"></i> Información del Usuario
                    </h6>
                </div>
                <div class="card-body" style="padding: 2rem;">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e9ecef;">
                                <strong style="color: #1e3c72; font-weight: 600;">
                                    <i class="fas fa-id-card me-2"></i>ID:
                                </strong>
                                <span>
                                    <span class="badge" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; padding: 8px 12px; border-radius: 20px;">#<?php echo htmlspecialchars($this->model->id); ?></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e9ecef;">
                                <strong style="color: #1e3c72; font-weight: 600;">
                                    <i class="fas fa-toggle-on me-2"></i>Estado:
                                </strong>
                                <span>
                                    <?php if ($this->model->estado == 1): ?>
                                        <span class="badge" style="background: linear-gradient(135deg, #28a745, #1e7e34); color: white; padding: 8px 12px; border-radius: 20px;">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 8px 12px; border-radius: 20px;">
                                            <i class="fas fa-times-circle me-1"></i>Inactivo
                                        </span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e9ecef;">
                                <strong style="color: #1e3c72; font-weight: 600;">
                                    <i class="fas fa-user me-2"></i>Nombre Completo:
                                </strong>
                                <span style="color: #374151; font-weight: 500;"><?php echo htmlspecialchars($this->model->nombre); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e9ecef;">
                                <strong style="color: #1e3c72; font-weight: 600;">
                                    <i class="fas fa-at me-2"></i>Usuario:
                                </strong>
                                <span style="color: #374151; font-weight: 500;"><?php echo htmlspecialchars($this->model->usuario); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e9ecef;">
                                <strong style="color: #1e3c72; font-weight: 600;">
                                    <i class="fas fa-calendar-alt me-2"></i>Fecha de Registro:
                                </strong>
                                <span style="color: #374151; font-weight: 500;">
                                    <?php 
                                    $fecha = new DateTime($this->model->fecha_registro);
                                    echo $fecha->format('d/m/Y H:i:s');
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e9ecef;">
                                <strong style="color: #1e3c72; font-weight: 600;">
                                    <i class="fas fa-clock me-2"></i>Hace:
                                </strong>
                                <span style="color: #374151; font-weight: 500;">
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
                <div class="card-header py-3" style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt fa-fw"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if ($this->model->estado == 1): ?>
                            <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-warning btn-sm"
                               onclick="return confirm('¿Está seguro de desactivar este usuario?')"
                               style="border-radius: 8px; padding: 10px; margin-bottom: 8px;">
                                <i class="fas fa-toggle-on me-1"></i> Desactivar Usuario
                            </a>
                        <?php else: ?>
                            <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('¿Está seguro de activar este usuario?')"
                               style="border-radius: 8px; padding: 10px; margin-bottom: 8px;">
                                <i class="fas fa-toggle-off me-1"></i> Activar Usuario
                            </a>
                        <?php endif; ?>
                        
                        <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
                           class="btn btn-primary btn-sm"
                           style="border-radius: 8px; padding: 10px; margin-bottom: 8px; background: linear-gradient(135deg, #1e3c72, #2a5298); border: none;">
                            <i class="fas fa-edit me-1"></i> Editar Información
                        </a>
                        
                        <a href="index.php?controller=usuarios&action=delete&id=<?php echo $this->model->id; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')"
                           style="border-radius: 8px; padding: 10px; background: linear-gradient(135deg, #dc3545, #c82333); border: none;">
                            <i class="fas fa-trash me-1"></i> Eliminar Usuario
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de información del sistema -->
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #28a745, #1e7e34); color: white; border-radius: 10px 10px 0 0;">
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
                <div class="card-header py-3" style="background: linear-gradient(135deg, #6c757d, #545b62); color: white; border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar fa-fw"></i> Resumen del Usuario
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="transition: all 0.3s ease;">
                                <i class="fas fa-user-clock fa-2x mb-2" style="color: #1e3c72;"></i>
                                <h5 class="mb-1" style="color: #1e3c72;">Antigüedad</h5>
                                <?php
                                $fechaRegistro = new DateTime($this->model->fecha_registro);
                                $hoy = new DateTime();
                                $diferencia = $hoy->diff($fechaRegistro);
                                $dias = $diferencia->days;
                                ?>
                                <p class="mb-0 h4" style="color: #1e3c72;"><?php echo $dias; ?> días</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="transition: all 0.3s ease;">
                                <i class="fas fa-toggle-on fa-2x mb-2" style="color: #28a745;"></i>
                                <h5 class="mb-1" style="color: #1e3c72;">Estado Actual</h5>
                                <p class="mb-0">
                                    <?php if ($this->model->estado == 1): ?>
                                        <span class="badge" style="background: linear-gradient(135deg, #28a745, #1e7e34); color: white; padding: 8px 12px; border-radius: 20px;">ACTIVO</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 8px 12px; border-radius: 20px;">INACTIVO</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="transition: all 0.3s ease;">
                                <i class="fas fa-calendar-check fa-2x mb-2" style="color: #17a2b8;"></i>
                                <h5 class="mb-1" style="color: #1e3c72;">Registrado el</h5>
                                <p class="mb-0 text-muted">
                                    <?php 
                                    $fecha = new DateTime($this->model->fecha_registro);
                                    echo $fecha->format('d/m/Y');
                                    ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3" style="transition: all 0.3s ease;">
                                <i class="fas fa-clock fa-2x mb-2" style="color: #fd7e14;"></i>
                                <h5 class="mb-1" style="color: #1e3c72;">Hora de Registro</h5>
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
});
</script>

<?php include_once 'layouts/footer.php'; ?>