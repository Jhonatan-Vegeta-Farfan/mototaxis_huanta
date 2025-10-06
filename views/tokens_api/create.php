<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Nuevo Token API</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Información:</strong> El token se generará automáticamente al guardar. 
                    La fecha de registro será la fecha actual.
                </div>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="id_client_api" class="form-label">Cliente</label>
                        <select class="form-control" id="id_client_api" name="id_client_api" required>
                            <option value="">Seleccionar Cliente</option>
                            <?php while ($cliente = $clientes->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $cliente['id']; ?>">
                                <?php echo $cliente['razon_social']; ?> (RUC: <?php echo $cliente['ruc']; ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <small class="form-text text-muted">
                            Seleccione el cliente para el cual se generará el token automáticamente.
                        </small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nota:</strong> El token se generará con el formato: 
                        [Código único]-[Identificador cliente]-[Número secuencial]
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

<?php include_once 'views/layouts/footer.php'; ?>