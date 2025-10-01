<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Nuevo Count Request</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="id_token_api" class="form-label">Token API</label>
                        <select class="form-control" id="id_token_api" name="id_token_api" required>
                            <option value="">Seleccionar Token</option>
                            <?php while ($token = $tokens->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $token['id']; ?>">
                                <?php echo $token['razon_social'] . ' - ' . substr($token['token'], 0, 20) . '...'; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="">Seleccionar Tipo</option>
                            <option value="consulta">Consulta</option>
                            <option value="registro">Registro</option>
                            <option value="actualizacion">Actualización</option>
                            <option value="eliminacion">Eliminación</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=count_request&action=index" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>