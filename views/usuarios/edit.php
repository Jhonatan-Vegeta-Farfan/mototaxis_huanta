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
            <div class="card shadow mb-4 user-form">
                <div class="user-form-header">
                    <h3 class="mb-0">
                        <i class="fas fa-user-circle fa-fw"></i> Datos del Usuario
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>">
                        <input type="hidden" name="id" value="<?php echo $this->model->id; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($this->model->nombre); ?>" 
                                       required placeholder="Ingrese el nombre completo">
                                <div class="form-text">Ej: Juan Pérez García</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">Usuario *</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?php echo htmlspecialchars($this->model->usuario); ?>" 
                                       required placeholder="Ingrese el nombre de usuario">
                                <div class="form-text">Mínimo 3 caracteres</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       minlength="4" placeholder="Dejar vacío para mantener la actual">
                                <div class="form-text">Mínimo 4 caracteres. Dejar vacío para no cambiar.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="1" <?php echo $this->model->estado == 1 ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo $this->model->estado == 0 ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                                <div class="form-text">El usuario inactivo no podrá iniciar sesión</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times fa-fw"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save fa-fw"></i> Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>