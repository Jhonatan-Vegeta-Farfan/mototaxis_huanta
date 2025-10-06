<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Nuevo Cliente API</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ruc" class="form-label">RUC</label>
                            <input type="text" class="form-control" id="ruc" name="ruc" required 
                                   maxlength="11" pattern="[0-9]{11}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="razon_social" class="form-label">Razón Social</label>
                            <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                        <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" required
                               value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=client_api&action=index" class="btn btn-secondary me-md-2">
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