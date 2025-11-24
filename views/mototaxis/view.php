<?php include_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-eye me-2 text-info"></i>Detalles del Mototaxi
        </h2>
        <div>
            <a href="index.php?controller=mototaxis&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
            </a>
            <a href="index.php?controller=mototaxis&action=edit&id=<?php echo $this->model->id; ?>" 
               class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                <i class="fas fa-motorcycle me-2"></i>
                Mototaxi N° <?php echo $this->model->numero_asignado; ?>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información Personal -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>Información Personal
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Número Asignado:</th>
                                    <td>
                                        <span class="badge bg-warning text-dark fs-6">
                                            <?php echo $this->model->numero_asignado; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nombre Completo:</th>
                                    <td class="fw-bold"><?php echo $this->model->nombre_completo; ?></td>
                                </tr>
                                <tr>
                                    <th>DNI:</th>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?php echo $this->model->dni; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dirección:</th>
                                    <td><?php echo $this->model->direccion ?: '<span class="text-muted">No especificado</span>'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Información del Vehículo -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-motorcycle me-2"></i>Información del Vehículo
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Placa de Rodaje:</th>
                                    <td>
                                        <span class="badge bg-secondary fs-6">
                                            <?php echo $this->model->placa_rodaje; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Año Fabricación:</th>
                                    <td><?php echo $this->model->anio_fabricacion ?: '<span class="text-muted">No especificado</span>'; ?></td>
                                </tr>
                                <tr>
                                    <th>Marca:</th>
                                    <td><?php echo $this->model->marca ?: '<span class="text-muted">No especificado</span>'; ?></td>
                                </tr>
                                <tr>
                                    <th>Color:</th>
                                    <td>
                                        <?php if ($this->model->color): ?>
                                            <span class="badge" style="background-color: <?php echo strtolower($this->model->color); ?>; color: white;">
                                                <?php echo $this->model->color; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Especificaciones Técnicas -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>Especificaciones Técnicas
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Número de Motor:</th>
                                    <td><?php echo $this->model->numero_motor ?: '<span class="text-muted">No especificado</span>'; ?></td>
                                </tr>
                                <tr>
                                    <th>Tipo de Motor:</th>
                                    <td><?php echo $this->model->tipo_motor ?: '<span class="text-muted">No especificado</span>'; ?></td>
                                </tr>
                                <tr>
                                    <th>Serie:</th>
                                    <td><?php echo $this->model->serie ?: '<span class="text-muted">No especificado</span>'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información Adicional
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-nowrap" style="width: 40%;">Fecha de Registro:</th>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?php echo date('d/m/Y', strtotime($this->model->fecha_registro)); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Empresa:</th>
                                    <td>
                                        <?php 
                                        $empresa_nombre = 'No asignada';
                                        if ($empresas) {
                                            while ($empresa = $empresas->fetch(PDO::FETCH_ASSOC)) {
                                                if ($empresa['id'] == $this->model->id_empresa) {
                                                    $empresa_nombre = $empresa['razon_social'];
                                                    break;
                                                }
                                            }
                                            // Resetear el puntero del resultset
                                            $empresas->execute();
                                        }
                                        echo $empresa_nombre;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>ID en Sistema:</th>
                                    <td>
                                        <span class="badge bg-dark">#<?php echo $this->model->id; ?></span>
                                    </td>
                                </tr>
                            </table>
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
                <a href="index.php?controller=mototaxis&action=edit&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-warning btn-lg">
                    <i class="fas fa-edit me-2"></i>Editar Mototaxi
                </a>
                <a href="index.php?controller=mototaxis&action=delete&id=<?php echo $this->model->id; ?>" 
                   class="btn btn-danger btn-lg"
                   onclick="return confirm('¿Está seguro de eliminar este mototaxi? Esta acción no se puede deshacer.')">
                    <i class="fas fa-trash me-2"></i>Eliminar Mototaxi
                </a>
                <a href="index.php?controller=mototaxis&action=index" 
                   class="btn btn-secondary btn-lg">
                    <i class="fas fa-list me-2"></i>Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>