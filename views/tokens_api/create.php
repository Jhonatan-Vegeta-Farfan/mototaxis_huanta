<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Nuevo Token API</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Información:</strong> El token se generará automáticamente con un identificador único del cliente. La fecha de registro será la fecha actual.
                </div>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="id_client_api" class="form-label">Cliente *</label>
                        <select class="form-control" id="id_client_api" name="id_client_api" required>
                            <option value="">Seleccionar Cliente</option>
                            <?php while ($cliente = $clientes->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $cliente['id']; ?>" 
                                    <?php echo (isset($_POST['id_client_api']) && $_POST['id_client_api'] == $cliente['id']) ? 'selected' : ''; ?>>
                                <?php echo $cliente['razon_social'] . ' (RUC: ' . $cliente['ruc'] . ')'; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <div class="form-text">Seleccione el cliente para el cual se generará el token</div>
                    </div>

                    <!-- Información del token generado automáticamente -->
                    <div class="card bg-dark text-light mb-4">
                        <div class="card-body">
                            <h6 class="text-warning mb-3">
                                <i class="fas fa-key me-2"></i>Información del Token a Generar
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Formato del Token:</strong><br>
                                    <small>Base64Random-ClienteID-Número</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>Fecha de Registro:</strong><br>
                                    <small><?php echo date('Y-m-d'); ?></small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <strong>Ejemplo:</strong><br>
                                <code class="text-light">a1b2c3d4e5f6-TRA-1</code>
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
    // Auto-focus en el campo de selección
    document.getElementById('id_client_api').focus();
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>