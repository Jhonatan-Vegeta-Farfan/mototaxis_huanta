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

                    <form method="POST" action="index.php?controller=usuarios&action=create" id="userForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user me-1"></i> Nombre Completo *
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" 
                                       required placeholder="Ingrese el nombre completo"
                                       minlength="3" maxlength="100">
                                <div class="form-text">Ej: Juan Pérez García (Mínimo 3 caracteres)</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">
                                    <i class="fas fa-at me-1"></i> Usuario *
                                </label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" 
                                       required placeholder="Ingrese el nombre de usuario"
                                       minlength="3" maxlength="50" pattern="[a-zA-Z0-9_]+"
                                       title="Solo letras, números y guiones bajos">
                                <div class="form-text">Solo letras, números y _ (Mínimo 3 caracteres)</div>
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
                                           pattern=".{4,}" title="Mínimo 4 caracteres">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 4 caracteres</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> Confirmar Contraseña *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" minlength="4" required 
                                           placeholder="Confirme la contraseña">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Repita la contraseña</div>
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
                                <div class="form-text">El usuario inactivo no podrá iniciar sesión</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-label">
                                    <i class="fas fa-info-circle me-1"></i> Información del Sistema
                                </div>
                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <strong>Importante:</strong> El usuario creado tendrá acceso completo al sistema de gestión de mototaxis.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times fa-fw"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save fa-fw"></i> Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-lightbulb fa-fw"></i> Recomendaciones de Seguridad
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-shield-alt text-success me-2"></i>Contraseñas Seguras</h6>
                            <ul class="small text-muted">
                                <li>Use al menos 8 caracteres</li>
                                <li>Combine letras mayúsculas y minúsculas</li>
                                <li>Incluya números y símbolos</li>
                                <li>No use información personal</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-user-secret text-warning me-2"></i>Buenas Prácticas</h6>
                            <ul class="small text-muted">
                                <li>Asigne permisos según las necesidades</li>
                                <li>Revise periódicamente los usuarios activos</li>
                                <li>Desactive usuarios que ya no se usen</li>
                                <li>Mantenga registros de actividad</li>
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
    // Mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // Validación de formulario
    const form = document.getElementById('userForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            showAlert('Las contraseñas no coinciden', 'error');
            confirmPasswordInput.focus();
            return;
        }

        if (password.length < 4) {
            e.preventDefault();
            showAlert('La contraseña debe tener al menos 4 caracteres', 'error');
            passwordInput.focus();
            return;
        }

        // Mostrar loading
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin fa-fw"></i> Creando...';
        submitBtn.disabled = true;
    });

    // Validación en tiempo real
    passwordInput.addEventListener('input', validatePassword);
    confirmPasswordInput.addEventListener('input', validatePassword);

    function validatePassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword && password !== confirmPassword) {
            confirmPasswordInput.classList.add('is-invalid');
            confirmPasswordInput.classList.remove('is-valid');
        } else if (confirmPassword) {
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.add('is-valid');
        } else {
            confirmPasswordInput.classList.remove('is-invalid', 'is-valid');
        }

        if (password.length >= 4) {
            passwordInput.classList.remove('is-invalid');
            passwordInput.classList.add('is-valid');
        } else if (password.length > 0) {
            passwordInput.classList.add('is-invalid');
            passwordInput.classList.remove('is-valid');
        } else {
            passwordInput.classList.remove('is-invalid', 'is-valid');
        }
    }

    function showAlert(message, type) {
        // Remover alertas existentes
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : 'success'} custom-alert alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        form.parentNode.insertBefore(alertDiv, form);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Validación de usuario único
    const usuarioInput = document.getElementById('usuario');
    let checkTimeout;

    usuarioInput.addEventListener('input', function() {
        clearTimeout(checkTimeout);
        const username = this.value.trim();
        
        if (username.length >= 3) {
            checkTimeout = setTimeout(() => {
                checkUsernameAvailability(username);
            }, 500);
        }
    });

    function checkUsernameAvailability(username) {
        // Simulación de verificación - en un sistema real harías una petición AJAX
        console.log('Verificando disponibilidad de usuario:', username);
        
        // Aquí iría la llamada AJAX real
        /*
        fetch(`check_username.php?username=${username}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    usuarioInput.classList.remove('is-invalid');
                    usuarioInput.classList.add('is-valid');
                } else {
                    usuarioInput.classList.remove('is-valid');
                    usuarioInput.classList.add('is-invalid');
                    showAlert('Este usuario ya está registrado', 'error');
                }
            });
        */
    }
});
</script>

<style>
.form-control.is-valid {
    border-color: #198754;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v1'/%3e%3c/svg%3e");
}

.btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

.custom-alert {
    animation: slideInDown 0.3s ease-out;
}

@keyframes slideInDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<?php include_once 'layouts/footer.php'; ?>