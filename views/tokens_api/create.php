<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Nuevo Token API</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Información:</strong> El token se generará automáticamente con un formato único que incluye un identificador del cliente y un número secuencial.
                </div>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="id_client_api" class="form-label">Cliente *</label>
                        <select class="form-control" id="id_client_api" name="id_client_api" required>
                            <option value="">Seleccionar Cliente</option>
                            <?php while ($cliente = $clientes->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $cliente['id']; ?>">
                                <?php echo $cliente['razon_social']; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <div class="form-text">Seleccione el cliente para el cual generará el token</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Información del Token</label>
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <small>
                                    <strong>Formato del token:</strong> [hash_aleatorio]-[ID_CLIENTE]-[NÚMERO]<br>
                                    <strong>Ejemplo:</strong> a1b2c3d4e5f6g7h8-TRA-1<br>
                                    <strong>Fecha de registro:</strong> Se asignará automáticamente la fecha actual
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=tokens_api&action=index" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-1"></i> Generar Token
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus en el primer campo
    document.getElementById('id_client_api').focus();
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>