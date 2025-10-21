<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Nuevo Cliente API</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ruc" class="form-label">RUC *</label>
                            <input type="text" class="form-control" id="ruc" name="ruc" required 
                                   maxlength="11" pattern="[0-9]{11}" 
                                   placeholder="11 dígitos del RUC"
                                   value="<?php echo isset($_POST['ruc']) ? htmlspecialchars($_POST['ruc']) : ''; ?>">
                            <div class="form-text">Ingrese 11 dígitos del RUC</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="razon_social" class="form-label">Razón Social *</label>
                            <input type="text" class="form-control" id="razon_social" name="razon_social" required
                                   placeholder="Nombre legal de la empresa"
                                   value="<?php echo isset($_POST['razon_social']) ? htmlspecialchars($_POST['razon_social']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                                   placeholder="Número de contacto"
                                   value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo"
                                   placeholder="correo@empresa.com"
                                   value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha_registro" class="form-label">Fecha de Registro *</label>
                        <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" required
                               value="<?php echo isset($_POST['fecha_registro']) ? htmlspecialchars($_POST['fecha_registro']) : date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=client_api&action=index" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de RUC en tiempo real
    const rucInput = document.getElementById('ruc');
    if (rucInput) {
        rucInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });
    }

    // Auto-focus en el primer campo
    document.getElementById('ruc').focus();
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>