<?php
// views/usuarios/form.php
$pageTitle = isset($usuario['id']) ? "Editar Usuario" : "Crear Usuario";
include_once 'views/layouts/header.php';
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0">
                                <i class="fas fa-user me-2"></i>
                                <?php echo isset($usuario['id']) ? 'Editar Usuario' : 'Crear Nuevo Usuario'; ?>
                            </h4>
                        </div>
                        <div class="col-auto">
                            <a href="index.php?controller=usuarios&action=index" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Mostrar mensajes de error -->
                    <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <form action="index.php?controller=usuarios&action=<?php echo isset($usuario['id']) ? 'update&id=' . $usuario['id'] : 'store'; ?>" 
                          method="POST" id="usuarioForm">
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nombre" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" 
                                       required>
                                <div class="form-text">Ingrese el nombre completo del usuario.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">Nombre de Usuario *</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?php echo htmlspecialchars($usuario['usuario'] ?? ''); ?>" 
                                       required>
                                <div class="form-text">El nombre de usuario debe ser único.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Contraseña <?php echo !isset($usuario['id']) ? '*' : ''; ?>
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       <?php echo !isset($usuario['id']) ? 'required' : ''; ?>>
                                <div class="form-text">
                                    <?php echo isset($usuario['id']) ? 
                                        'Dejar en blanco para mantener la contraseña actual.' : 
                                        'La contraseña se almacena en texto plano.'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="1" <?php echo (isset($usuario['estado']) && $usuario['estado'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo (isset($usuario['estado']) && $usuario['estado'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                                <div class="form-text">Estado actual del usuario en el sistema.</div>
                            </div>
                            
                            <?php if (isset($usuario['id'])): ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Registro</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?>" 
                                       readonly>
                                <div class="form-text">Fecha en que se registró el usuario.</div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        <?php echo isset($usuario['id']) ? 'Actualizar Usuario' : 'Crear Usuario'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Validación del formulario
    $('#usuarioForm').on('submit', function(e) {
        let isValid = true;
        const nombre = $('#nombre').val().trim();
        const usuario = $('#usuario').val().trim();
        const password = $('#password').val();

        // Validar campos requeridos
        if (nombre === '') {
            showValidationError('nombre', 'El nombre es obligatorio');
            isValid = false;
        } else {
            clearValidationError('nombre');
        }

        if (usuario === '') {
            showValidationError('usuario', 'El nombre de usuario es obligatorio');
            isValid = false;
        } else {
            clearValidationError('usuario');
        }

        <?php if (!isset($usuario['id'])): ?>
        if (password === '') {
            showValidationError('password', 'La contraseña es obligatoria');
            isValid = false;
        } else {
            clearValidationError('password');
        }
        <?php endif; ?>

        if (!isValid) {
            e.preventDefault();
            showNotification('error', 'Por favor, complete todos los campos obligatorios.');
        }
    });

    function showValidationError(fieldId, message) {
        const field = $('#' + fieldId);
        field.addClass('is-invalid');
        field.next('.form-text').after(`<div class="invalid-feedback">${message}</div>`);
    }

    function clearValidationError(fieldId) {
        const field = $('#' + fieldId);
        field.removeClass('is-invalid');
        field.next('.form-text').next('.invalid-feedback').remove();
    }
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>