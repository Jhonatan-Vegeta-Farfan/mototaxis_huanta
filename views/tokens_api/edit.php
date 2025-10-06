<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Token API</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $this->model->id; ?>">
                    
                    <div class="mb-3">
                        <label for="id_client_api" class="form-label">Cliente</label>
                        <select class="form-control" id="id_client_api" name="id_client_api" required>
                            <option value="">Seleccionar Cliente</option>
                            <?php while ($cliente = $clientes->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $cliente['id']; ?>" 
                                    <?php echo $cliente['id'] == $this->model->id_client_api ? 'selected' : ''; ?>>
                                <?php echo $cliente['razon_social']; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="token" class="form-label">Token</label>
                        <input type="text" class="form-control" id="token" name="token" 
                               value="<?php echo $this->model->token; ?>" required>
                        <small class="form-text text-muted">
                            Formato: [Código único]-[Identificador cliente]-[Número secuencial]
                        </small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                            <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" 
                                   value="<?php echo $this->model->fecha_registro; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="1" <?php echo $this->model->estado == 1 ? 'selected' : ''; ?>>Activo</option>
                                <option value="0" <?php echo $this->model->estado == 0 ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=tokens_api&action=index" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>