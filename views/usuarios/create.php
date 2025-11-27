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
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-circle fa-fw"></i> Datos del Usuario
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?controller=usuarios&action=create" id="formUsuario">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user me-1"></i> Nombre Completo *
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" 
                                       required placeholder="Ingrese el nombre completo"
                                       minlength="3" maxlength="100">
                                <div class="form-text text-muted">
                                    <small>Ej: Juan Pérez García (Mínimo 3 caracteres)</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">
                                    <i class="fas fa-at me-1"></i> Usuario *
                                </label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" 
                                       required placeholder="Ingrese el nombre de usuario"
                                       minlength="3" maxlength="50" pattern="[a-zA-Z0-9_]+">
                                <div class="form-text text-muted">
                                    <small>Solo letras, números y guiones bajos (Mínimo 3 caracteres)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> Contraseña *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           minlength="4" required placeholder="Ingrese la contraseña"
                                           pattern=".{4,}">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text text-muted">
                                    <small>Mínimo 4 caracteres</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> Confirmar Contraseña *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           minlength="4" required placeholder="Confirme la contraseña">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text text-muted">
                                    <small>Debe coincidir con la contraseña anterior</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i> Estado *
                                </label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="1" selected>Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                                <div class="form-text text-muted">
                                    <small>El usuario inactivo no podrá iniciar sesión</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-text">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Los campos marcados con * son obligatorios
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times fa-fw me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">
                                <i class="fas fa-save fa-fw me-1"></i> Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle fa-fw"></i> Información Importante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-shield-alt text-primary me-2"></i>Seguridad</h6>
                            <ul class="list-unstyled small text-muted">
                                <li><i class="fas fa-check text-success me-2"></i>Contraseñas seguras</li>
                                <li><i class="fas fa-check text-success me-2"></i>Usuarios únicos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Validación de datos</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-cogs text-primary me-2"></i>Requisitos</h6>
                            <ul class="list-unstyled small text-muted">
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

    // Validación en tiempo real para las contraseñas
    confirmPasswordField.addEventListener('input', function() {
        const password = passwordField.value;
        const confirmPassword = this.value;

        if (confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (confirmPassword) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-invalid', 'is-valid');
        }
    });

    passwordField.addEventListener('input', function() {
        const password = this.value;
        const confirmPassword = confirmPasswordField.value;

        if (password.length >= 4) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            if (password) {
                this.classList.add('is-invalid');
            }
        }

        // Actualizar validación de confirmación si ya hay valor
        if (confirmPassword) {
            if (password !== confirmPassword) {
                confirmPasswordField.classList.add('is-invalid');
                confirmPasswordField.classList.remove('is-valid');
            } else {
                confirmPasswordField.classList.remove('is-invalid');
                confirmPasswordField.classList.add('is-valid');
            }
        }
    });

    // Auto-focus en el primer campo
    document.getElementById('nombre').focus();
});
</script>

<?php include_once 'layouts/footer.php'; ?>