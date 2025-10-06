<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Count Request</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $this->model->id; ?>">
                    
                    <div class="mb-3">
                        <label for="id_token_api" class="form-label">Token API</label>
                        <select class="form-control" id="id_token_api" name="id_token_api" required>
                            <option value="">Seleccionar Token</option>
                            <?php while ($token = $tokens->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $token['id']; ?>" 
                                    <?php echo $token['id'] == $this->model->id_token_api ? 'selected' : ''; ?>>
                                <?php echo $token['razon_social'] . ' - ' . substr($token['token'], 0, 20) . '...'; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="consulta" <?php echo $this->model->tipo == 'consulta' ? 'selected' : ''; ?>>Consulta</option>
                            <option value="registro" <?php echo $this->model->tipo == 'registro' ? 'selected' : ''; ?>>Registro</option>
                            <option value="actualizacion" <?php echo $this->model->tipo == 'actualizacion' ? 'selected' : ''; ?>>Actualización</option>
                            <option value="eliminacion" <?php echo $this->model->tipo == 'eliminacion' ? 'selected' : ''; ?>>Eliminación</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" 
                               value="<?php echo $this->model->fecha; ?>" required>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=count_request&action=index" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Request
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
    document.getElementById('id_token_api').focus();
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>