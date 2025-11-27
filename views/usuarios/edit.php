<?php
$pageTitle = "Editar Usuario - Sistema Mototaxis";
include_once 'layouts/header.php';
?>

<style>
/* Estilos específicos para editar usuario */
.editar-usuario-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: calc(100vh - 80px);
    padding: 30px 0;
}

.editar-usuario-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 40px 0;
    margin-bottom: 30px;
    border-radius: 0 0 25px 25px;
    text-align: center;
}

.editar-usuario-title {
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.editar-usuario-subtitle {
    opacity: 0.9;
    font-weight: 300;
    font-size: 1.1rem;
}

.editar-usuario-card {
    background: #fff;
    border-radius: 20px;
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    overflow: hidden;
    animation: slideIn 0.6s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.editar-usuario-card-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 25px 30px;
    font-weight: 600;
    font-size: 1.2rem;
}

.editar-usuario-card-header i {
    margin-right: 12px;
    font-size: 1.3rem;
}

.editar-usuario-card-body {
    padding: 40px;
}

.info-usuario-actual {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    border-left: 5px solid #1e3c72;
}

.info-usuario-actual h6 {
    color: #1e3c72;
    margin-bottom: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.info-usuario-actual h6 i {
    margin-right: 10px;
}

.datos-usuario {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.dato-item {
    background: rgba(255, 255, 255, 0.7);
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid rgba(30, 60, 114, 0.1);
}

.dato-label {
    font-weight: 600;
    color: #1e3c72;
    font-size: 0.85rem;
    margin-bottom: 5px;
}

.dato-valor {
    color: #495057;
    font-weight: 500;
}

.btn-usuario-update {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    border: none;
    border-radius: 12px;
    padding: 15px 30px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.3);
    width: 100%;
    margin-top: 10px;
}

.btn-usuario-update:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 193, 7, 0.4);
    background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
}

.password-optional {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 20px;
    margin: 20px 0;
    border: 2px dashed #6c757d;
}

.password-optional h6 {
    color: #6c757d;
    margin-bottom: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.password-optional h6 i {
    margin-right: 8px;
}

/* Estados del formulario */
.campo-modificado {
    border-left: 4px solid #ffc107 !important;
    background: #fffbf0 !important;
}

.campo-inicial {
    border-left: 4px solid #17a2b8 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .editar-usuario-header {
        padding: 30px 0;
    }
    
    .editar-usuario-title {
        font-size: 1.5rem;
    }
    
    .editar-usuario-card-body {
        padding: 25px;
    }
    
    .datos-usuario {
        grid-template-columns: 1fr;
    }
    
    .info-usuario-actual {
        padding: 20px;
    }
}

/* Indicadores de cambio */
.indicador-cambio {
    position: relative;
}

.indicador-cambio::after {
    content: '●';
    position: absolute;
    top: 10px;
    right: 15px;
    color: #ffc107;
    font-size: 0.8rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>

<div class="editar-usuario-container">
    <!-- Header -->
    <div class="editar-usuario-header">
        <div class="container">
            <h1 class="editar-usuario-title">
                <i class="fas fa-user-edit fa-fw"></i> EDITAR USUARIO
            </h1>
            <p class="editar-usuario-subtitle">Modifique la información del usuario seleccionado</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="editar-usuario-card">
                    <div class="editar-usuario-card-header">
                        <i class="fas fa-user-circle fa-fw"></i> MODIFICAR INFORMACIÓN DEL USUARIO
                    </div>
                    <div class="editar-usuario-card-body">
                        <!-- Información Actual -->
                        <div class="info-usuario-actual">
                            <h6><i class="fas fa-info-circle"></i> INFORMACIÓN ACTUAL</h6>
                            <div class="datos-usuario">
                                <div class="dato-item">
                                    <div class="dato-label">ID Usuario</div>
                                    <div class="dato-valor">#<?php echo $this->model->id; ?></div>
                                </div>
                                <div class="dato-item">
                                    <div class="dato-label">Fecha Registro</div>
                                    <div class="dato-valor"><?php echo date('d/m/Y H:i', strtotime($this->model->fecha_registro)); ?></div>
                                </div>
                                <div class="dato-item">
                                    <div class="dato-label">Estado Actual</div>
                                    <div class="dato-valor">
                                        <?php if ($this->model->estado == 1): ?>
                                            <span class="badge bg-success">ACTIVO</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">INACTIVO</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

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

                        <form method="POST" action="index.php?controller=usuarios&action=edit" id="formEditarUsuario">
                            <input type="hidden" name="id" value="<?php echo $this->model->id; ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-usuario">
                                        <label for="nombre" class="form-label-usuario">
                                            <i class="fas fa-user"></i> Nombre Completo *
                                        </label>
                                        <input type="text" class="form-control form-control-usuario campo-inicial" id="nombre" name="nombre" 
                                               value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : htmlspecialchars($this->model->nombre); ?>" 
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
                                        <input type="text" class="form-control form-control-usuario campo-inicial" id="usuario" name="usuario" 
                                               value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : htmlspecialchars($this->model->usuario); ?>" 
                                               required maxlength="100" placeholder="Ingrese el nombre de usuario">
                                        <div class="form-text-usuario">
                                            <i class="fas fa-info-circle"></i> Nombre único para iniciar sesión
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contraseña (Opcional) -->
                            <div class="password-optional">
                                <h6><i class="fas fa-key"></i> CAMBIO DE CONTRASEÑA (OPCIONAL)</h6>
                                <p class="text-muted small mb-3">Complete solo si desea cambiar la contraseña actual</p>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-usuario">
                                            <label for="password" class="form-label-usuario">
                                                <i class="fas fa-lock"></i> Nueva Contraseña
                                            </label>
                                            <input type="password" class="form-control form-control-usuario" id="password" name="password" 
                                                   minlength="4" placeholder="Dejar en blanco para mantener actual">
                                            <div class="form-text-usuario">
                                                <i class="fas fa-shield-alt"></i> Mínimo 4 caracteres
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-usuario">
                                            <label for="confirm_password" class="form-label-usuario">
                                                <i class="fas fa-lock"></i> Confirmar Contraseña
                                            </label>
                                            <input type="password" class="form-control form-control-usuario" id="confirm_password" 
                                                   placeholder="Confirmar nueva contraseña">
                                            <div class="form-text-usuario">
                                                <i class="fas fa-redo"></i> Repita la nueva contraseña
                                            </div>
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
                                        <select class="form-control form-control-usuario campo-inicial" id="estado" name="estado" required>
                                            <option value="1" <?php echo ($this->model->estado == 1) ? 'selected' : ''; ?>>Activo</option>
                                            <option value="0" <?php echo ($this->model->estado == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                        <div class="form-text-usuario">
                                            <i class="fas fa-info-circle"></i> Estado del usuario en el sistema
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <a href="index.php?controller=usuarios&action=index" class="btn btn-usuario-cancel">
                                        <i class="fas fa-times fa-fw"></i> CANCELAR
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-usuario-update" id="btnUpdate">
                                        <i class="fas fa-save fa-fw"></i> ACTUALIZAR USUARIO
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
    const form = document.getElementById('formEditarUsuario');
    const btnUpdate = document.getElementById('btnUpdate');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const initialValues = {};

    // Guardar valores iniciales
    document.querySelectorAll('.campo-inicial').forEach(input => {
        initialValues[input.name] = input.value;
    });

    // Detectar cambios en los campos
    document.querySelectorAll('.campo-inicial').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value !== initialValues[this.name]) {
                this.classList.add('campo-modificado');
                this.classList.add('indicador-cambio');
            } else {
                this.classList.remove('campo-modificado');
                this.classList.remove('indicador-cambio');
            }
        });
    });

    // Validar contraseñas coincidentes
    function validatePasswords() {
        if (passwordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('input-error');
            confirmPasswordInput.classList.remove('input-success');
            return false;
        } else if (passwordInput.value) {
            confirmPasswordInput.classList.remove('input-error');
            confirmPasswordInput.classList.add('input-success');
        }
        return true;
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validar contraseñas si se proporcionaron
        if (passwordInput.value) {
            if (!validatePasswords()) {
                isValid = false;
                showToast('Las contraseñas no coinciden', 'error');
            }
            
            if (passwordInput.value.length < 4) {
                isValid = false;
                passwordInput.classList.add('input-error');
                showToast('La contraseña debe tener al menos 4 caracteres', 'error');
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            btnUpdate.style.animation = 'shake 0.5s ease';
            setTimeout(() => {
                btnUpdate.style.animation = '';
            }, 500);
        } else {
            // Cambiar texto del botón durante el envío
            btnUpdate.innerHTML = '<i class="fas fa-spinner fa-spin fa-fw"></i> ACTUALIZANDO...';
            btnUpdate.disabled = true;
        }
    });

    // Función para mostrar notificaciones
    function showToast(message, type = 'info') {
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
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    // Mostrar advertencia si se desactiva el usuario
    const estadoSelect = document.getElementById('estado');
    estadoSelect.addEventListener('change', function() {
        if (this.value === '0') {
            showToast('⚠️ El usuario no podrá acceder al sistema cuando esté inactivo', 'warning');
        }
    });
});
</script>

<?php include_once 'layouts/footer.php'; ?>