<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Clientes API</h2>
    <a href="index.php?controller=client_api&action=create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Nuevo Cliente
    </a>
</div>

<!-- Sistema de Búsqueda Mejorado -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-search me-2"></i>Sistema de Búsqueda</h4>
    </div>
    <div class="card-body">
        <!-- Búsqueda Simple -->
        <form method="GET" action="" class="mb-4">
            <input type="hidden" name="controller" value="client_api">
            <input type="hidden" name="action" value="index">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Buscar por RUC o Razón Social..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-warning" type="submit">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="index.php?controller=client_api&action=index" class="btn btn-secondary w-100">
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
                            <input type="hidden" name="controller" value="client_api">
                            <input type="hidden" name="action" value="index">
                            <input type="hidden" name="advanced_search" value="true">
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">RUC</label>
                                    <input type="text" class="form-control" name="ruc" 
                                           placeholder="Ej: 20123456789"
                                           value="<?php echo isset($_GET['ruc']) ? htmlspecialchars($_GET['ruc']) : ''; ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Razón Social</label>
                                    <input type="text" class="form-control" name="razon_social" 
                                           placeholder="Ej: Transportes Rápidos SAC"
                                           value="<?php echo isset($_GET['razon_social']) ? htmlspecialchars($_GET['razon_social']) : ''; ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Estado</label>
                                    <select class="form-control" name="estado">
                                        <option value="">Todos los estados</option>
                                        <option value="1" <?php echo (isset($_GET['estado']) && $_GET['estado'] == '1') ? 'selected' : ''; ?>>Activo</option>
                                        <option value="0" <?php echo (isset($_GET['estado']) && $_GET['estado'] == '0') ? 'selected' : ''; ?>>Inactivo</option>
                                    </select>
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
        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Clientes API</h4>
        <span class="badge bg-primary">
            <?php 
            $rowCount = $stmt->rowCount();
            echo $rowCount . ' cliente' . ($rowCount != 1 ? 's' : '') . ' encontrado' . ($rowCount != 1 ? 's' : '');
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
                        <th>RUC</th>
                        <th>Razón Social</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Fecha Registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                        $clientStats = $this->model->getStats($row['id']);
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo $row['ruc']; ?></span>
                        </td>
                        <td>
                            <strong class="text-warning"><?php echo $row['razon_social']; ?></strong>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-key me-1"></i><?php echo $clientStats['total_tokens']; ?> tokens
                                <i class="fas fa-chart-bar ms-2 me-1"></i><?php echo $clientStats['total_requests']; ?> requests
                            </small>
                        </td>
                        <td><?php echo $row['telefono'] ?: '<span class="text-muted">No especificado</span>'; ?></td>
                        <td><?php echo $row['correo'] ?: '<span class="text-muted">No especificado</span>'; ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $row['fecha_registro']; ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $row['estado'] == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $row['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=client_api&action=edit&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar Cliente">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=client_api&action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Está seguro de eliminar este cliente?')"
                                   data-bs-toggle="tooltip" title="Eliminar Cliente">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <button type="button" class="btn btn-info btn-sm view-details" 
                                        data-bs-toggle="modal" data-bs-target="#detailsModal"
                                        data-id="<?php echo $row['id']; ?>"
                                        data-ruc="<?php echo $row['ruc']; ?>"
                                        data-razon-social="<?php echo $row['razon_social']; ?>"
                                        data-telefono="<?php echo $row['telefono']; ?>"
                                        data-correo="<?php echo $row['correo']; ?>"
                                        data-fecha="<?php echo $row['fecha_registro']; ?>"
                                        data-estado="<?php echo $row['estado']; ?>"
                                        data-tokens="<?php echo $clientStats['total_tokens']; ?>"
                                        data-requests="<?php echo $clientStats['total_requests']; ?>"
                                        data-bs-toggle="tooltip" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="index.php?controller=tokens_api&action=index&client_id=<?php echo $row['id']; ?>" 
                                   class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Ver Tokens">
                                    <i class="fas fa-key"></i>
                                </a>
                                <a href="index.php?controller=count_request&action=index&client_id=<?php echo $row['id']; ?>" 
                                   class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" title="Ver Requests">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
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
            <a href="index.php?controller=client_api&action=index" class="btn btn-primary">
                <i class="fas fa-list me-1"></i> Ver Todos los Clientes
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para Detalles Mejorado -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Detalles del Cliente API
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-warning">Información Principal</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>RUC:</th>
                                <td id="modal-ruc"></td>
                            </tr>
                            <tr>
                                <th>Razón Social:</th>
                                <td id="modal-razon-social"></td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td id="modal-estado"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Información de Contacto</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Teléfono:</th>
                                <td id="modal-telefono"></td>
                            </tr>
                            <tr>
                                <th>Correo:</th>
                                <td id="modal-correo"></td>
                            </tr>
                            <tr>
                                <th>Fecha Registro:</th>
                                <td id="modal-fecha"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-warning">Estadísticas</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Total Tokens:</th>
                                <td id="modal-tokens" class="fw-bold text-primary"></td>
                            </tr>
                            <tr>
                                <th>Total Requests:</th>
                                <td id="modal-requests" class="fw-bold text-success"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-warning">Acciones Disponibles</h6>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="#" class="btn btn-warning me-2" id="modal-edit-link">
                                <i class="fas fa-edit me-1"></i> Editar Cliente
                            </a>
                            <a href="#" class="btn btn-primary me-2" id="modal-tokens-link">
                                <i class="fas fa-key me-1"></i> Ver Tokens
                            </a>
                            <a href="#" class="btn btn-info me-2" id="modal-requests-link">
                                <i class="fas fa-chart-bar me-1"></i> Ver Requests
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-details');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.getAttribute('data-id');
            
            document.getElementById('modal-ruc').textContent = this.getAttribute('data-ruc');
            document.getElementById('modal-razon-social').textContent = this.getAttribute('data-razon-social');
            document.getElementById('modal-telefono').textContent = this.getAttribute('data-telefono') || 'No especificado';
            document.getElementById('modal-correo').textContent = this.getAttribute('data-correo') || 'No especificado';
            document.getElementById('modal-fecha').textContent = this.getAttribute('data-fecha');
            document.getElementById('modal-tokens').textContent = this.getAttribute('data-tokens');
            document.getElementById('modal-requests').textContent = this.getAttribute('data-requests');
            
            const estado = this.getAttribute('data-estado');
            const estadoText = estado == '1' ? 'Activo' : 'Inactivo';
            const estadoClass = estado == '1' ? 'success' : 'danger';
            document.getElementById('modal-estado').innerHTML = `<span class="badge bg-${estadoClass}">${estadoText}</span>`;
            
            document.getElementById('modal-edit-link').href = `index.php?controller=client_api&action=edit&id=${clientId}`;
            document.getElementById('modal-tokens-link').href = `index.php?controller=tokens_api&action=index&client_id=${clientId}`;
            document.getElementById('modal-requests-link').href = `index.php?controller=count_request&action=index&client_id=${clientId}`;
            
            document.getElementById('detailsModalLabel').textContent = 
                'Detalles - ' + this.getAttribute('data-razon-social');
        });
    });
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.focus();
    }
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>