<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-building me-2"></i>Empresas</h2>
    <a href="index.php?controller=empresas&action=create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Nueva Empresa
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
            <input type="hidden" name="controller" value="empresas">
            <input type="hidden" name="action" value="index">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Buscar por RUC, Razón Social o Representante Legal..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-warning" type="submit">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="index.php?controller=empresas&action=index" class="btn btn-secondary w-100">
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
                            <input type="hidden" name="controller" value="empresas">
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
                                    <label class="form-label">Representante Legal</label>
                                    <input type="text" class="form-control" name="representante_legal" 
                                           placeholder="Ej: Juan Pérez Rodríguez"
                                           value="<?php echo isset($_GET['representante_legal']) ? htmlspecialchars($_GET['representante_legal']) : ''; ?>">
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
        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Empresas</h4>
        <span class="badge bg-primary">
            <?php 
            $rowCount = $stmt->rowCount();
            echo $rowCount . ' empresa' . ($rowCount != 1 ? 's' : '') . ' encontrada' . ($rowCount != 1 ? 's' : '');
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
                        <th>Representante Legal</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo $row['ruc']; ?></span>
                        </td>
                        <td>
                            <strong class="text-warning"><?php echo $row['razon_social']; ?></strong>
                        </td>
                        <td><?php echo $row['representante_legal']; ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $row['fecha_registro']; ?></span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=empresas&action=edit&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=empresas&action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Está seguro de eliminar esta empresa?')"
                                   data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <button type="button" class="btn btn-info btn-sm view-details" 
                                        data-bs-toggle="modal" data-bs-target="#detailsModal"
                                        data-id="<?php echo $row['id']; ?>"
                                        data-ruc="<?php echo $row['ruc']; ?>"
                                        data-razon-social="<?php echo $row['razon_social']; ?>"
                                        data-representante="<?php echo $row['representante_legal']; ?>"
                                        data-fecha="<?php echo $row['fecha_registro']; ?>"
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
            <a href="index.php?controller=empresas&action=index" class="btn btn-primary">
                <i class="fas fa-list me-1"></i> Ver Todas las Empresas
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
                    <i class="fas fa-info-circle me-2"></i>Detalles de la Empresa
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
                                <th>Representante Legal:</th>
                                <td id="modal-representante"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Información Adicional</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Fecha de Registro:</th>
                                <td id="modal-fecha"></td>
                            </tr>
                            <tr>
                                <th>ID de Empresa:</th>
                                <td id="modal-id"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-warning">Acciones Disponibles</h6>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="#" class="btn btn-warning me-2" id="modal-edit-link">
                                <i class="fas fa-edit me-1"></i> Editar Empresa
                            </a>
                            <a href="#" class="btn btn-info me-2" id="modal-mototaxis-link">
                                <i class="fas fa-motorcycle me-1"></i> Ver Mototaxis
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
// Script para el modal de detalles
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-details');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const empresaId = this.getAttribute('data-id');
            
            // Obtener datos de los atributos data
            document.getElementById('modal-id').textContent = empresaId;
            document.getElementById('modal-ruc').textContent = this.getAttribute('data-ruc');
            document.getElementById('modal-razon-social').textContent = this.getAttribute('data-razon-social');
            document.getElementById('modal-representante').textContent = this.getAttribute('data-representante');
            document.getElementById('modal-fecha').textContent = this.getAttribute('data-fecha');
            
            // Actualizar enlaces de acciones
            document.getElementById('modal-edit-link').href = `index.php?controller=empresas&action=edit&id=${empresaId}`;
            document.getElementById('modal-mototaxis-link').href = `index.php?controller=mototaxis&action=index&empresa_id=${empresaId}`;
            
            // Actualizar título del modal
            document.getElementById('detailsModalLabel').textContent = 
                'Detalles - ' + this.getAttribute('data-razon-social');
        });
    });
    
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-focus en el campo de búsqueda
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.focus();
    }
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>