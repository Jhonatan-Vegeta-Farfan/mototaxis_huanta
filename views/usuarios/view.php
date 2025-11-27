<?php
$pageTitle = "Detalles de Usuario - Sistema Mototaxis Huanta";
include_once 'views/layouts/header.php';

// Cargar datos del usuario
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$_GET['id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: index.php?controller=usuarios&action=index");
    exit();
}
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">
                                <i class="fas fa-user me-2"></i>Detalles del Usuario
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="index.php?controller=usuarios&action=index" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="detail-card">
                                <div class="detail-item">
                                    <span class="detail-label">ID:</span>
                                    <span class="detail-value"><?php echo $usuario['id']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Nombre:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($usuario['nombre']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Usuario:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($usuario['usuario']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Fecha de Registro:</span>
                                    <span class="detail-value"><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Estado:</span>
                                    <span class="detail-value">
                                        <?php if ($usuario['estado'] == 1): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="index.php?controller=usuarios&action=edit&id=<?php echo $usuario['id']; ?>" 
                                   class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <a href="index.php?controller=usuarios&action=index" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-list me-1"></i>Lista de Usuarios
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>