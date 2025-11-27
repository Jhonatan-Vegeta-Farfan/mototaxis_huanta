<?php
$pageTitle = "Crear Usuario - Sistema Mototaxis";
include_once 'layouts/header.php';
?>

<style>
/* Estilos específicos para crear usuario */
.crear-usuario-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: calc(100vh - 80px);
    padding: 30px 0;
}

.crear-usuario-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 40px 0;
    margin-bottom: 30px;
    border-radius: 0 0 25px 25px;
    text-align: center;
}

.crear-usuario-title {
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.crear-usuario-subtitle {
    opacity: 0.9;
    font-weight: 300;
    font-size: 1.1rem;
}

.crear-usuario-card {
    background: #fff;
    border-radius: 20px;
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    overflow: hidden;
    animation: slideUp 0.6s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.crear-usuario-card-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 25px 30px;
    font-weight: 600;
    font-size: 1.2rem;
}

.crear-usuario-card-header i {
    margin-right: 12px;
    font-size: 1.3rem;
}

.crear-usuario-card-body {
    padding: 40px;
}

.form-group-usuario {
    margin-bottom: 25px;
}

.form-label-usuario {
    font-weight: 600;
    color: #1e3c72;
    margin-bottom: 10px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}

.form-label-usuario i {
    margin-right: 8px;
    width: 20px;
    text-align: center;
}

.form-control-usuario {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 14px 18px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control-usuario:focus {
    border-color: #1e3c72;
    box-shadow: 0 0 0 0.3rem rgba(30, 60, 114, 0.15);
    background: #fff;
    transform: translateY(-2px);
}

.form-control-usuario:invalid {
    border-color: #dc3545;
}

.form-control-usuario:valid {
    border-color: #28a745;
}

.form-text-usuario {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 6px;
    display: flex;
    align-items: center;
}

.form-text-usuario i {
    margin-right: 5px;
}

.btn-usuario-submit {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 12px;
    padding: 15px 30px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    width: 100%;
    margin-top: 10px;
}

.btn-usuario-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
}

.btn-usuario-cancel {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    border-radius: 12px;
    padding: 15px 30px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
    width: 100%;
    margin-top: 10px;
}

.btn-usuario-cancel:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(108, 117, 125, 0.4);
    background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
    color: white;
    text-decoration: none;
}

.password-strength {
    margin-top: 8px;
    padding: 10px;
    border-radius: 8px;
    font-size: 0.85rem;
    display: none;
}

.password-weak {
    background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
    color: #dc3545;
    border: 1px solid #dc3545;
}

.password-medium {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border: 1px solid #ffc107;
}

.password-strong {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border: 1px solid #28a745;
}

.requisitos-contraseña {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    border-left: 4px solid #1e3c72;
}

.requisitos-contraseña h6 {
    color: #1e3c72;
    margin-bottom: 15px;
    font-weight: 600;
}

.requisito-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.requisito-item i {
    margin-right: 8px;
    width: 16px;
    text-align: center;
}

.requisito-cumplido {
    color: #28a745;
}

.requisito-incumplido {
    color: #dc3545;
}

/* Responsive */
@media (max-width: 768px) {
    .crear-usuario-header {
        padding: 30px 0;
    }
    
    .crear-usuario-title {
        font-size: 1.5rem;
    }
    
    .crear-usuario-card-body {
        padding: 25px;
    }
    
    .btn-usuario-submit,
    .btn-usuario-cancel {
        padding: 12px 20px;
        font-size: 1rem;
    }
}

/* Animaciones de validación */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.input-error {
    animation: shake 0.5s ease;
    border-color: #dc3545 !important;
}

.input-success {
    border-color: #28a745 !important;
}
</style>

<div class="crear-usuario-container">
    <!-- Header -->
    <div class="crear-usuario-header">
        <div class="container">
            <h1 class="crear-usuario-title">
                <i class="fas fa-user-plus fa-fw"></i> CREAR NUEVO USUARIO
            </h1>
            <p class="crear-usuario-subtitle">Complete los datos para registrar un nuevo usuario en el sistema</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="crear-usuario-card">
                    <div class="crear-usuario-card-header">
                        <i class="fas fa-user-circle fa-fw"></i> INFORMACIÓN DEL USUARIO
                    </div>
                    <div class="crear-usuario-card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="alert-heading mb-1">Error de Validación</h6>
                                        <p class="mb-0"><?php echo $error; ?></p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?controller=usuarios&action=create" id="formCrearUsuario">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-usuario">
                                        <label for="nombre" class="form-label-usuario">
                                            <i class="fas fa-user"></i> Nombre Completo *
                                        </label>
                                        <input type="text" class="form-control form-control-usuario" id="nombre" name="nombre" 
                                               value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" 
                                               required maxlength="255" placeholder="Ingrese el nombre completo">
                                        <div class="form-text-usuario">
                                            <i class="fas fa-info-circle"></i> Nombre real del usuario
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-usuario">
                                        <label for="usuario" class="form-label-usuario">
                                            <i class="fas fa-at"></i> Usuario *
                                        </label>
                                        <input type="text" class="form-control form-control-usuario" id="usuario" name="usuario" 
                                               value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" 
                                               required maxlength="100" placeholder="Ingrese el nombre de usuario">
                                        <div class="form-text-usuario">
                                            <i class="fas fa-info-circle"></i> Nombre único para iniciar sesión
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-usuario">
                                        <label for="password" class="form-label-usuario">
                                            <i class="fas fa-lock"></i> Contraseña *
                                        </label>
                                        <input type="password" class="form-control form-control-usuario" id="password" name="password" 
                                               required minlength="4" placeholder="Ingrese la contraseña">
                                        <div id="passwordStrength" class="password-strength"></div>
                                        <div class="form-text-usuario">
                                            <i class="fas fa-shield-alt"></i> Mínimo 4 caracteres
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-usuario">
                                        <label for="confirm_password" class="form-label-usuario">
                                            <i class="fas fa-lock"></i> Confirmar Contraseña *
                                        </label>
                                        <input type="password" class="form-control form-control-usuario" id="confirm_password" 
                                               required placeholder="Confirme la contraseña">
                                        <div class="form-text-usuario">
                                            <i class="fas fa-redo"></i> Repita la contraseña
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-usuario">
                                        <label for="estado" class="form-label-usuario">
                                            <i class="fas fa-toggle-on"></i> Estado *
                                        </label>
                                        <select class="form-control form-control-usuario" id="estado" name="estado" required>
                                            <option value="1" selected>Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                        <div class="form-text-usuario">
                                            <i class="fas fa-info-circle"></i> Estado inicial del usuario
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Requisitos de Contraseña -->
                            <div class="requisitos-contraseña">
                                <h6><i class="fas fa-list-check me-2"></i>Requisitos de Seguridad</h6>
                                <div class="requisito-item">
                                    <i class="fas fa-check requisito-cumplido" id="reqLongitud"></i>
                                    <span id="reqLongitudText">Mínimo 4 caracteres</span>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <a href="index.php?controller=usuarios&action=index" class="btn btn-usuario-cancel">
                                        <i class="fas fa-times fa-fw"></i> CANCELAR
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-usuario-submit" id="btnSubmit">
                                        <i class="fas fa-save fa-fw"></i> CREAR USUARIO
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordStrength = document.getElementById('passwordStrength');
    const form = document.getElementById('formCrearUsuario');
    const btnSubmit = document.getElementById('btnSubmit');

    // Validar fortaleza de contraseña
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = '';
        let strengthClass = '';
        
        if (password.length === 0) {
            passwordStrength.style.display = 'none';
            return;
        }
        
        if (password.length < 4) {
            strength = 'Débil - Mínimo 4 caracteres requeridos';
            strengthClass = 'password-weak';
        } else if (password.length < 6) {
            strength = 'Media - Considere una contraseña más larga';
            strengthClass = 'password-medium';
        } else {
            strength = 'Fuerte - Contraseña segura';
            strengthClass = 'password-strong';
        }
        
        passwordStrength.textContent = strength;
        passwordStrength.className = `password-strength ${strengthClass}`;
        passwordStrength.style.display = 'block';
        
        // Actualizar requisito de longitud
        const reqLongitud = document.getElementById('reqLongitud');
        const reqLongitudText = document.getElementById('reqLongitudText');
        
        if (password.length >= 4) {
            reqLongitud.className = 'fas fa-check requisito-cumplido';
            reqLongitudText.style.color = '#28a745';
        } else {
            reqLongitud.className = 'fas fa-times requisito-incumplido';
            reqLongitudText.style.color = '#dc3545';
        }
    });

    // Validar coincidencia de contraseñas
    confirmPasswordInput.addEventListener('input', function() {
        if (this.value !== passwordInput.value) {
            this.classList.add('input-error');
            this.classList.remove('input-success');
        } else {
            this.classList.remove('input-error');
            this.classList.add('input-success');
        }
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validar contraseñas coincidentes
        if (passwordInput.value !== confirmPasswordInput.value) {
            isValid = false;
            confirmPasswordInput.classList.add('input-error');
            showToast('Las contraseñas no coinciden', 'error');
        }
        
        // Validar longitud mínima
        if (passwordInput.value.length < 4) {
            isValid = false;
            passwordInput.classList.add('input-error');
            showToast('La contraseña debe tener al menos 4 caracteres', 'error');
        }
        
        if (!isValid) {
            e.preventDefault();
            // Agitar el botón de submit
            btnSubmit.style.animation = 'shake 0.5s ease';
            setTimeout(() => {
                btnSubmit.style.animation = '';
            }, 500);
        } else {
            // Cambiar texto del botón durante el envío
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin fa-fw"></i> CREANDO USUARIO...';
            btnSubmit.disabled = true;
        }
    });

    // Función para mostrar notificaciones
    function showToast(message, type = 'info') {
        // Implementación básica de toast
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : 'success'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    // Efectos de focus en los inputs
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>

<?php include_once 'layouts/footer.php'; ?>