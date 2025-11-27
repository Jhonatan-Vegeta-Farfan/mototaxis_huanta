<?php
$pageTitle = ($action == 'create') ? "Crear Usuario - Sistema Mototaxis Huanta" : "Editar Usuario - Sistema Mototaxis Huanta";
include_once 'views/layouts/header.php';

// Si es edición, cargar datos del usuario
$usuario = [];
if ($action == 'edit' && isset($_GET['id'])) {
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$_GET['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        <?php echo ($action == 'create') ? 'Crear Nuevo Usuario' : 'Editar Usuario'; ?>
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="index.php?controller=usuarios&action=<?php echo ($action == 'create') ? 'store' : 'update'; ?>" method="POST">
                        <?php if ($action == 'edit'): ?>
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nombre" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo isset($usuario['nombre']) ? htmlspecialchars($usuario['nombre']) : ''; ?>" 
                                       required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="usuario" class="form-label">Usuario *</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?php echo isset($usuario['usuario']) ? htmlspecialchars($usuario['usuario']) : ''; ?>" 
                                       required>
                                <div class="form-text">El nombre de usuario debe ser único.</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="password" class="form-label">
                                    Contraseña <?php echo ($action == 'create') ? '*' : ''; ?>
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       <?php echo ($action == 'create') ? 'required' : ''; ?>>
                                <div class="form-text">
                                    <?php if ($action == 'edit'): ?>
                                        Dejar en blanco para mantener la contraseña actual.
                                    <?php else: ?>
                                        La contraseña debe tener al menos 6 caracteres.
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if ($action == 'edit'): ?>
                            <div class="col-md-12 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="1" <?php echo ($usuario['estado'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo ($usuario['estado'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        <?php echo ($action == 'create') ? 'Crear Usuario' : 'Actualizar Usuario'; ?>
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

<?php include_once 'views/layouts/footer.php'; ?>