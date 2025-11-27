<?php
$pageTitle = "Editar Usuario - Sistema Mototaxis";
include_once 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit fa-fw"></i> Editar Usuario
            </h1>
            <p class="text-muted">Actualice los datos del usuario</p>
        </div>
        <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-fw"></i> Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-circle fa-fw"></i> Datos del Usuario
                    </h6>
                </div>
                <div class="card-body" style="padding: 2rem;">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>">
                        <input type="hidden" name="id" value="<?php echo $this->model->id; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-user me-1"></i> Nombre Completo *
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($this->model->nombre); ?>" 
                                       required placeholder="Ingrese el nombre completo"
                                       style="border-radius: 8px; padding: 10px; border: 2px solid #dee2e6;">
                                <div class="form-text" style="color: #6c757d; font-size: 0.85rem;">
                                    <small>Ej: Juan Pérez García</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-at me-1"></i> Usuario *
                                </label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?php echo htmlspecialchars($this->model->usuario); ?>" 
                                       required placeholder="Ingrese el nombre de usuario"
                                       style="border-radius: 8px; padding: 10px; border: 2px solid #dee2e6;">
                                <div class="form-text" style="color: #6c757d; font-size: 0.85rem;">
                                    <small>Mínimo 3 caracteres</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-lock me-1"></i> Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           minlength="4" placeholder="Dejar vacío para mantener la actual"
                                           style="border-radius: 8px 0 0 8px; padding: 10px; border: 2px solid #dee2e6;">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                                            style="border-radius: 0 8px 8px 0; border: 2px solid #dee2e6;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text" style="color: #6c757d; font-size: 0.85rem;">
                                    <small>Mínimo 4 caracteres. Dejar vacío para no cambiar.</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-toggle-on me-1"></i> Estado *
                                </label>
                                <select class="form-control" id="estado" name="estado" required
                                        style="border-radius: 8px; padding: 10px; border: 2px solid #dee2e6;">
                                    <option value="1" <?php echo $this->model->estado == 1 ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo $this->model->estado == 0 ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                                <div class="form-text" style="color: #6c757d; font-size: 0.85rem;">
                                    <small>El usuario inactivo no podrá iniciar sesión</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Los campos marcados con * son obligatorios
                            </small>
                            <div>
                                <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary me-2"
                                   style="border-radius: 8px; padding: 10px 20px;">
                                    <i class="fas fa-times fa-fw me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary" id="btnSubmit"
                                        style="border-radius: 8px; padding: 10px 20px; background: linear-gradient(135deg, #1e3c72, #2a5298); border: none;">
                                    <i class="fas fa-save fa-fw me-1"></i> Actualizar Usuario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle para mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // Auto-focus en el primer campo
    document.getElementById('nombre').focus();
});
</script>

<?php include_once 'layouts/footer.php'; ?>