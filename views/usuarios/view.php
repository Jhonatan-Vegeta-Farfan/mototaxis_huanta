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
                <i class="fas fa-arrow-left fa-fw"></i> Volver
            </a>
            <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit fa-fw"></i> Editar
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
                                    <i class="fas fa-id-card me-2"></i> ID:
                                </strong>
                                <span class="detail-value">#<?php echo htmlspecialchars($this->model->id); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-toggle-on me-2"></i> Estado:
                                </strong>
                                <span class="detail-value">
                                    <?php if ($this->model->estado == 1): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i> Inactivo
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
                                    <i class="fas fa-user me-2"></i> Nombre Completo:
                                </strong>
                                <span class="detail-value"><?php echo htmlspecialchars($this->model->nombre); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-at me-2"></i> Usuario:
                                </strong>
                                <span class="detail-value"><?php echo htmlspecialchars($this->model->usuario); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-calendar-alt me-2"></i> Fecha de Registro:
                                </strong>
                                <span class="detail-value">
                                    <?php 
                                        $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $this->model->fecha_registro);
                                        echo $fecha ? $fecha->format('d/m/Y H:i') : htmlspecialchars($this->model->fecha_registro);
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <strong class="detail-label">
                                    <i class="fas fa-clock me-2"></i> Última Actualización:
                                </strong>
                                <span class="detail-value">
                                    <?php
                                        // En un sistema real, tendrías un campo de última actualización
                                        echo "No disponible";
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar fa-fw"></i> Estadísticas del Usuario
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="stat-number">0</div>
                                <div class="stat-label">Inicios de Sesión</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-number">0</div>
                                <div class="stat-label">Días Activo</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="stat-number">-</div>
                                <div class="stat-label">Último Acceso</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Acciones Rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt fa-fw"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if ($this->model->estado == 1): ?>
                            <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                                <i class="fas fa-toggle-off me-1"></i> Desactivar Usuario
                            </a>
                        <?php else: ?>
                            <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-outline-success btn-sm"
                               onclick="return confirm('¿Está seguro de activar este usuario?')">
                                <i class="fas fa-toggle-on me-1"></i> Activar Usuario
                            </a>
                        <?php endif; ?>
                        
                        <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Editar Información
                        </a>
                        
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#passwordModal">
                            <i class="fas fa-key me-1"></i> Cambiar Contraseña
                        </button>
                        
                        <a href="index.php?controller=usuarios&action=delete&id=<?php echo $this->model->id; ?>" 
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                            <i class="fas fa-trash me-1"></i> Eliminar Usuario
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle fa-fw"></i> Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="system-info">
                        <div class="info-item mb-3">
                            <small class="text-muted">Creado el:</small>
                            <div class="fw-bold">
                                <?php 
                                    $fechaCreacion = DateTime::createFromFormat('Y-m-d H:i:s', $this->model->fecha_registro);
                                    echo $fechaCreacion ? $fechaCreacion->format('d/m/Y') : 'N/A';
                                ?>
                            </div>
                        </div>
                        <div class="info-item mb-3">
                            <small class="text-muted">ID del Sistema:</small>
                            <div class="fw-bold">USR-<?php echo str_pad($this->model->id, 4, '0', STR_PAD_LEFT); ?></div>
                        </div>
                        <div class="info-item">
                            <small class="text-muted">Estado Actual:</small>
                            <div>
                                <?php if ($this->model->estado == 1): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-circle me-1"></i> Operativo
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-circle me-1"></i> Suspendido
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Actividad (Placeholder) -->
            <div class="card shadow">
                <div class="card-header py-3 bg-dark text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-history fa-fw"></i> Actividad Reciente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                        <p class="text-muted small">El historial de actividad estará disponible en futuras actualizaciones del sistema.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cambiar Contraseña -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="passwordModalLabel">
                    <i class="fas fa-key me-2"></i> Cambiar Contraseña
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordForm">
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" 
                               minlength="4" required placeholder="Ingrese nueva contraseña">
                        <div class="form-text">Mínimo 4 caracteres</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmNewPassword" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirmNewPassword" 
                               minlength="4" required placeholder="Confirme nueva contraseña">
                    </div>
                </form>
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        La contraseña se cambiará inmediatamente después de confirmar.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="changePassword()">
                    <i class="fas fa-save me-1"></i> Cambiar Contraseña
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function changePassword() {
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmNewPassword').value;
    
    if (newPassword.length < 4) {
        alert('La contraseña debe tener al menos 4 caracteres');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        return;
    }
    
    // Aquí iría la llamada AJAX para cambiar la contraseña
    // Por ahora solo mostramos un mensaje
    alert('Función de cambio de contraseña en desarrollo. En una implementación real, esto actualizaría la contraseña en la base de datos.');
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('passwordModal'));
    modal.hide();
}

// Validación en tiempo real para el modal
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmNewPassword');
    
    function validatePasswords() {
        if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
            confirmPassword.classList.add('is-invalid');
            confirmPassword.classList.remove('is-valid');
        } else if (confirmPassword.value) {
            confirmPassword.classList.remove('is-invalid');
            confirmPassword.classList.add('is-valid');
        }
        
        if (newPassword.value.length >= 4) {
            newPassword.classList.remove('is-invalid');
            newPassword.classList.add('is-valid');
        } else if (newPassword.value.length > 0) {
            newPassword.classList.add('is-invalid');
            newPassword.classList.remove('is-valid');
        }
    }
    
    newPassword.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
    
    // Limpiar formulario cuando se cierre el modal
    document.getElementById('passwordModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('passwordForm').reset();
        newPassword.classList.remove('is-invalid', 'is-valid');
        confirmPassword.classList.remove('is-invalid', 'is-valid');
    });
});
</script>

<style>
.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid #e9ecef;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    color: #1e3c72;
    font-weight: 600;
    font-size: 0.95rem;
}

.detail-value {
    color: #374151;
    font-weight: 500;
}

.stat-card {
    padding: 1rem;
    text-align: center;
    border-radius: 8px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.2rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1e3c72;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.system-info .info-item {
    border-left: 3px solid #1e3c72;
    padding-left: 1rem;
}

.btn-group-vertical .btn {
    text-align: left;
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .detail-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .detail-value {
        margin-top: 0.3rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>

<?php include_once 'layouts/footer.php'; ?>