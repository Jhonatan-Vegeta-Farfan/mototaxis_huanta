<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-motorcycle me-2"></i>Mototaxis</h2>
    <a href="index.php?controller=mototaxis&action=create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Nuevo Mototaxi
    </a>
</div>

<!-- Sistema de Búsqueda -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-search me-2"></i>Sistema de Búsqueda</h4>
    </div>
    <div class="card-body">
        <!-- Búsqueda Simple -->
        <form method="GET" action="" class="mb-4">
            <input type="hidden" name="controller" value="mototaxis">
            <input type="hidden" name="action" value="index">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Buscar por número asignado, nombre o DNI..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-warning" type="submit">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="index.php?controller=mototaxis&action=index" class="btn btn-secondary w-100">
                        <i class="fas fa-sync-alt me-1"></i> Mostrar Todos
                    </a>
                </div>
            </div>
        </form>

        <!-- Búsqueda Avanzada -->
        <div class="accordion" id="advancedSearchAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#advancedSearchCollapse" aria-expanded="false" 
                            aria-controls="advancedSearchCollapse">
                        <i class="fas fa-cogs me-2"></i> Búsqueda Avanzada
                    </button>
                </h2>
                <div id="advancedSearchCollapse" class="accordion-collapse collapse" 
                     data-bs-parent="#advancedSearchAccordion">
                    <div class="accordion-body">
                        <form method="GET" action="">
                            <input type="hidden" name="controller" value="mototaxis">
                            <input type="hidden" name="action" value="index">
                            <input type="hidden" name="advanced_search" value="true">
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Número Asignado</label>
                                    <input type="text" class="form-control" name="numero_asignado" 
                                           placeholder="Ej: MT-001"
                                           value="<?php echo isset($_GET['numero_asignado']) ? htmlspecialchars($_GET['numero_asignado']) : ''; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" name="nombre_completo" 
                                           placeholder="Ej: Juan Pérez"
                                           value="<?php echo isset($_GET['nombre_completo']) ? htmlspecialchars($_GET['nombre_completo']) : ''; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">DNI</label>
                                    <input type="text" class="form-control" name="dni" 
                                           placeholder="Ej: 12345678"
                                           value="<?php echo isset($_GET['dni']) ? htmlspecialchars($_GET['dni']) : ''; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Placa de Rodaje</label>
                                    <input type="text" class="form-control" name="placa_rodaje" 
                                           placeholder="Ej: ABC-123"
                                           value="<?php echo isset($_GET['placa_rodaje']) ? htmlspecialchars($_GET['placa_rodaje']) : ''; ?>">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning me-2">
                                    <i class="fas fa-search me-1"></i> Buscar Avanzado
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-eraser me-1"></i> Limpiar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resultados de Búsqueda -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Mototaxis</h4>
        <span class="badge bg-primary">
            <?php 
            $rowCount = $stmt->rowCount();
            echo $rowCount . ' registro' . ($rowCount != 1 ? 's' : '') . ' encontrado' . ($rowCount != 1 ? 's' : '');
            ?>
        </span>
    </div>
    <div class="card-body">
        <?php if ($stmt->rowCount() > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>N° Asignado</th>
                        <th>Nombre Completo</th>
                        <th>DNI</th>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Empresa</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <strong class="text-warning"><?php echo $row['numero_asignado']; ?></strong>
                        </td>
                        <td><?php echo $row['nombre_completo']; ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo $row['dni']; ?></span>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $row['placa_rodaje']; ?></span>
                        </td>
                        <td><?php echo $row['marca']; ?></td>
                        <td>
                            <span class="badge" style="background-color: <?php echo strtolower($row['color']); ?>; color: white;">
                                <?php echo $row['color']; ?>
                            </span>
                        </td>
                        <td><?php echo $row['empresa']; ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=mototaxis&action=edit&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=mototaxis&action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Está seguro de eliminar este mototaxi?')"
                                   data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <button type="button" class="btn btn-info btn-sm view-details" 
                                        data-bs-toggle="modal" data-bs-target="#detailsModal"
                                        data-id="<?php echo $row['id']; ?>"
                                        data-numero="<?php echo $row['numero_asignado']; ?>"
                                        data-nombre="<?php echo $row['nombre_completo']; ?>"
                                        data-dni="<?php echo $row['dni']; ?>"
                                        data-direccion="<?php echo $row['direccion']; ?>"
                                        data-placa="<?php echo $row['placa_rodaje']; ?>"
                                        data-anio="<?php echo $row['anio_fabricacion']; ?>"
                                        data-marca="<?php echo $row['marca']; ?>"
                                        data-motor="<?php echo $row['numero_motor']; ?>"
                                        data-tipo-motor="<?php echo $row['tipo_motor']; ?>"
                                        data-serie="<?php echo $row['serie']; ?>"
                                        data-color="<?php echo $row['color']; ?>"
                                        data-fecha="<?php echo $row['fecha_registro']; ?>"
                                        data-empresa="<?php echo $row['empresa']; ?>"
                                        data-bs-toggle="tooltip" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No se encontraron resultados</h4>
            <p class="text-muted">Intente con otros términos de búsqueda</p>
            <a href="index.php?controller=mototaxis&action=index" class="btn btn-primary">
                <i class="fas fa-list me-1"></i> Ver Todos los Mototaxis
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para Detalles -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Detalles del Mototaxi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-warning">Información Personal</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Número Asignado:</th>
                                <td id="modal-numero"></td>
                            </tr>
                            <tr>
                                <th>Nombre Completo:</th>
                                <td id="modal-nombre"></td>
                            </tr>
                            <tr>
                                <th>DNI:</th>
                                <td id="modal-dni"></td>
                            </tr>
                            <tr>
                                <th>Dirección:</th>
                                <td id="modal-direccion"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Información del Vehículo</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Placa de Rodaje:</th>
                                <td id="modal-placa"></td>
                            </tr>
                            <tr>
                                <th>Año Fabricación:</th>
                                <td id="modal-anio"></td>
                            </tr>
                            <tr>
                                <th>Marca:</th>
                                <td id="modal-marca"></td>
                            </tr>
                            <tr>
                                <th>Color:</th>
                                <td id="modal-color"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-warning">Especificaciones Técnicas</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Número Motor:</th>
                                <td id="modal-motor"></td>
                            </tr>
                            <tr>
                                <th>Tipo Motor:</th>
                                <td id="modal-tipo-motor"></td>
                            </tr>
                            <tr>
                                <th>Serie:</th>
                                <td id="modal-serie"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Información Adicional</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Fecha Registro:</th>
                                <td id="modal-fecha"></td>
                            </tr>
                            <tr>
                                <th>Empresa:</th>
                                <td id="modal-empresa"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Script para el modal de detalles
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-details');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Obtener datos de los atributos data
            document.getElementById('modal-numero').textContent = this.getAttribute('data-numero');
            document.getElementById('modal-nombre').textContent = this.getAttribute('data-nombre');
            document.getElementById('modal-dni').textContent = this.getAttribute('data-dni');
            document.getElementById('modal-direccion').textContent = this.getAttribute('data-direccion') || 'No especificado';
            document.getElementById('modal-placa').textContent = this.getAttribute('data-placa');
            document.getElementById('modal-anio').textContent = this.getAttribute('data-anio') || 'No especificado';
            document.getElementById('modal-marca').textContent = this.getAttribute('data-marca') || 'No especificado';
            document.getElementById('modal-color').textContent = this.getAttribute('data-color') || 'No especificado';
            document.getElementById('modal-motor').textContent = this.getAttribute('data-motor') || 'No especificado';
            document.getElementById('modal-tipo-motor').textContent = this.getAttribute('data-tipo-motor') || 'No especificado';
            document.getElementById('modal-serie').textContent = this.getAttribute('data-serie') || 'No especificado';
            document.getElementById('modal-fecha').textContent = this.getAttribute('data-fecha');
            document.getElementById('modal-empresa').textContent = this.getAttribute('data-empresa');
            
            // Actualizar título del modal
            document.getElementById('detailsModalLabel').textContent = 
                'Detalles - ' + this.getAttribute('data-numero');
        });
    });
    
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>