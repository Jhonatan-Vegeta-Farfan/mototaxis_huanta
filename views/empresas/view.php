<?php include_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-eye me-2 text-info"></i>Detalles de la Empresa
        </h2>
        <div>
            <a href="index.php?controller=empresas&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
            </a>
            <a href="index.php?controller=empresas&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                <i class="fas fa-building me-2"></i>
                <?php echo $this->model->razon_social; ?>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información Principal -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información Principal
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">RUC:</th>
                                    <td>
                                        <span class="badge bg-info text-dark fs-6">
                                            <?php echo $this->model->ruc; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Razón Social:</th>
                                    <td class="fw-bold text-warning"><?php echo $this->model->razon_social; ?></td>
                                </tr>
                                <tr>
                                    <th>Representante Legal:</th>
                                    <td><?php echo $this->model->representante_legal; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>Información Adicional
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Fecha de Registro:</th>
                                    <td>
                                        <span class="badge bg-secondary fs-6">
                                            <?php echo date('d/m/Y', strtotime($this->model->fecha_registro)); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>ID de Empresa:</th>
                                    <td>
                                        <span class="badge bg-dark">#<?php echo $this->model->id; ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mototaxis de la Empresa -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-motorcycle me-2"></i>Mototaxis Asociados
                                <span class="badge bg-dark ms-2">
                                    <?php echo $mototaxis->rowCount(); ?> mototaxis
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($mototaxis->rowCount() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° Asignado</th>
                                            <th>Nombre Completo</th>
                                            <th>DNI</th>
                                            <th>Placa</th>
                                            <th>Marca</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Resetear el puntero del resultset
                                        $mototaxis->execute();
                                        while ($mototaxi = $mototaxis->fetch(PDO::FETCH_ASSOC)): 
                                        ?>
                                        <tr>
                                            <td>
                                                <strong class="text-primary"><?php echo $mototaxi['numero_asignado']; ?></strong>
                                            </td>
                                            <td><?php echo $mototaxi['nombre_completo']; ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo $mototaxi['dni']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo $mototaxi['placa_rodaje']; ?></span>
                                            </td>
                                            <td><?php echo $mototaxi['marca']; ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="index.php?controller=mototaxis&action=view&id=<?php echo $mototaxi['id']; ?>" 
                                                       class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="index.php?controller=mototaxis&action=edit&id=<?php echo $mototaxi['id']; ?>" 
                                                       class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-motorcycle fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay mototaxis registrados</h5>
                                <p class="text-muted">Esta empresa no tiene mototaxis asociados actualmente.</p>
                                <a href="index.php?controller=mototaxis&action=create" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Registrar Mototaxi
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="card mt-4">
        <div class="card-body text-center">
            <div class="btn-group" role="group">
                <a href="index.php?controller=empresas&action=edit&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-warning btn-lg">
                    <i class="fas fa-edit me-2"></i>Editar Empresa
                </a>
                <a href="index.php?controller=mototaxis&action=index&empresa_id=<?php echo $this->model->id; ?>" 
                   class="btn btn-info btn-lg">
                    <i class="fas fa-motorcycle me-2"></i>Ver Todos los Mototaxis
                </a>
                <a href="index.php?controller=empresas&action=delete&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-danger btn-lg"
                   onclick="return confirm('¿Está seguro de eliminar esta empresa? Esta acción no se puede deshacer.')">
                    <i class="fas fa-trash me-2"></i>Eliminar Empresa
                </a>
                <a href="index.php?controller=empresas&action=index" 
                   class="btn btn-secondary btn-lg">
                    <i class="fas fa-list me-2"></i>Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>