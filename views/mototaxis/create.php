<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Nuevo Mototaxi</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_asignado" class="form-label">Número Asignado</label>
                            <input type="text" class="form-control" id="numero_asignado" name="numero_asignado" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre_completo" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" required maxlength="8">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="placa_rodaje" class="form-label">Placa de Rodaje</label>
                            <input type="text" class="form-control" id="placa_rodaje" name="placa_rodaje" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="anio_fabricacion" class="form-label">Año Fabricación</label>
                            <input type="number" class="form-control" id="anio_fabricacion" name="anio_fabricacion">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" name="marca">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="text" class="form-control" id="color" name="color">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="numero_motor" class="form-label">Número Motor</label>
                            <input type="text" class="form-control" id="numero_motor" name="numero_motor">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tipo_motor" class="form-label">Tipo Motor</label>
                            <input type="text" class="form-control" id="tipo_motor" name="tipo_motor">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="serie" class="form-label">Serie</label>
                            <input type="text" class="form-control" id="serie" name="serie">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_registro" class="form-label">Fecha Registro</label>
                            <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_empresa" class="form-label">Empresa</label>
                            <select class="form-control" id="id_empresa" name="id_empresa" required>
                                <option value="">Seleccionar Empresa</option>
                                <?php while ($empresa = $empresas->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $empresa['id']; ?>">
                                    <?php echo $empresa['razon_social']; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=mototaxis&action=index" class="btn btn-secondary me-md-2">
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