<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Usuario</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $this->model->id; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($this->model->nombre); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="usuario" class="form-label">Nombre de Usuario *</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" 
                                   value="<?php echo htmlspecialchars($this->model->usuario); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Dejar vacío para mantener actual">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Dejar vacío si no desea cambiar la contraseña</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                   placeholder="Confirmar nueva contraseña">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado del Usuario</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="1" <?php echo $this->model->estado == 1 ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo $this->model->estado == 0 ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6><i class="fas fa-info-circle me-2"></i>Información del Usuario</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>ID:</strong> <?php echo $this->model->id; ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Fecha Registro:</strong> <?php echo date('d/m/Y H:i', strtotime($this->model->fecha_registro)); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                        <a href="index.php?controller=usuarios&action=view&id=<?php echo $this->model->id; ?>" 
                           class="btn btn-info me-md-2">
                            <i class="fas fa-eye me-1"></i> Ver Detalles
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        confirmPassword.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // Validar que las contraseñas coincidan si se cambian
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const passwordValue = password.value;
        const confirmValue = confirmPassword.value;
        
        if (passwordValue !== '' && confirmValue !== '' && passwordValue !== confirmValue) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            password.focus();
        }
        
        if (passwordValue !== '' && passwordValue.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres');
            password.focus();
        }
    });
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>