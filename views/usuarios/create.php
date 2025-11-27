<?php
$pageTitle = "Crear Usuario - Sistema Mototaxis";
include_once 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-plus fa-fw"></i> Crear Nuevo Usuario
            </h1>
            <p class="text-muted">Complete los datos del nuevo usuario</p>
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

                    <form method="POST" action="index.php?controller=usuarios&action=create" id="formUsuario">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-user me-1"></i> Nombre Completo *
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" 
                                       required placeholder="Ingrese el nombre completo"
                                       minlength="3" maxlength="100"
                                       style="border-radius: 8px; padding: 10px; border: 2px solid #dee2e6;">
                                <div class="form-text" style="color: #6c757d; font-size: 0.85rem;">
                                    <small>Ej: Juan Pérez García (Mínimo 3 caracteres)</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-at me-1"></i> Usuario *
                                </label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" 
                                       required placeholder="Ingrese el nombre de usuario"
                                       minlength="3" maxlength="50" pattern="[a-zA-Z0-9_]+"
                                       style="border-radius: 8px; padding: 10px; border: 2px solid #dee2e6;">
                                <div class="form-text" style="color: #6c757d; font-size: 0.85rem;">
                                    <small>Solo letras, números y guiones bajos (Mínimo 3 caracteres)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-lock me-1"></i> Contraseña *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           minlength="4" required placeholder="Ingrese la contraseña"
                                           pattern=".{4,}"
                                           style="border-radius: 8px 0 0 8px; padding: 10px; border: 2px solid #dee2e6;">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                                            style="border-radius: 0 8px 8px 0; border: 2px solid #dee2e6;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text" style="color: #6c757d; font-size: 0.85rem;">
                                    <small>Mínimo 4 caracteres</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-lock me-1"></i> Confirmar Contraseña *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           minlength="4" required placeholder="Confirme la contraseña"
                                           style="border-radius: 8px 0 0 8px; padding: 10px; border: 2px solid #dee2e6;">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword"
                                            style="border-radius: 0 8px 8px 0; border: 2px solid #dee2e6;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text" style="color: #6c757d; font-size: 0.85rem;">
                                    <small>Debe coincidir con la contraseña anterior</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label" style="font-weight: 600; color: #1e3c72;">
                                    <i class="fas fa-toggle-on me-1"></i> Estado *
                                </label>
                                <select class="form-control" id="estado" name="estado" required
                                        style="border-radius: 8px; padding: 10px; border: 2px solid #dee2e6;">
                                    <option value="1" selected>Activo</option>
                                    <option value="0">Inactivo</option>
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
                                    <i class="fas fa-save fa-fw me-1"></i> Crear Usuario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; border-radius: 10px 10px 0 0;">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle fa-fw"></i> Información Importante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 style="color: #1e3c72;"><i class="fas fa-shield-alt text-primary me-2"></i>Seguridad</h6>
                            <ul class="list-unstyled small" style="color: #6c757d;">
                                <li><i class="fas fa-check text-success me-2"></i>Contraseñas seguras</li>
                                <li><i class="fas fa-check text-success me-2"></i>Usuarios únicos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Validación de datos</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 style="color: #1e3c72;"><i class="fas fa-cogs text-primary me-2"></i>Requisitos</h6>
                            <ul class="list-unstyled small" style="color: #6c757d;">
                                <li><i class="fas fa-user text-info me-2"></i>Nombre completo válido</li>
                                <li><i class="fas fa-at text-info me-2"></i>Usuario único en el sistema</li>
                                <li><i class="fas fa-key text-info me-2"></i>Contraseña de 4+ caracteres</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.form-control:focus {
    border-color: #1e3c72;
    box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.25);
}

.btn {
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.alert {
    border-radius: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle para mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');

    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordField.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // Validación de formulario
    const form = document.getElementById('formUsuario');
    const btnSubmit = document.getElementById('btnSubmit');

    form.addEventListener('submit', function(e) {
        let isValid = true;
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;

        // Validar que las contraseñas coincidan
        if (password !== confirmPassword) {
            isValid = false;
            alert('Las contraseñas no coinciden. Por favor, verifique.');
            confirmPasswordField.focus();
        }

        // Validar longitud mínima de contraseña
        if (password.length < 4) {
            isValid = false;
            alert('La contraseña debe tener al menos 4 caracteres.');
            passwordField.focus();
        }

        if (!isValid) {
            e.preventDefault();
        } else {
            // Mostrar loading en el botón
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creando...';
            btnSubmit.disabled = true;
        }
    });

    // Auto-focus en el primer campo
    document.getElementById('nombre').focus();
});
</script>

<?php include_once 'layouts/footer.php'; ?>