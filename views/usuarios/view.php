<?php
// views/usuarios/view.php
$pageTitle = "Detalles del Usuario - Sistema Mototaxis Huanta";
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
                                <i class="fas fa-user me-2"></i>Detalles del Usuario
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
                    <?php if (isset($usuario)): ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>ID:</strong>
                            <p class="text-muted"><?php echo $usuario['id']; ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Estado:</strong>
                            <p>
                                <?php if ($usuario['estado'] == 1): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactivo</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <strong>Nombre Completo:</strong>
                            <p class="text-muted"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Nombre de Usuario:</strong>
                            <p class="text-muted"><?php echo htmlspecialchars($usuario['usuario']); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de Registro:</strong>
                            <p class="text-muted"><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?controller=usuarios&action=edit&id=<?php echo $usuario['id']; ?>" 
                                   class="btn btn-warning me-md-2">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <a href="index.php?controller=usuarios&action=index" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-list me-1"></i>Volver al Listado
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Usuario no encontrado.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>