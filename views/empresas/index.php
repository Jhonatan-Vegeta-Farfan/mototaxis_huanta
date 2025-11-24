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
                            <span class="badge bg-secondary"><?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?></span>
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
                                <a href="index.php?controller=empresas&action=view&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Ver Detalles Completos">
                                    <i class="fas fa-eye"></i>
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
            <a href="index.php?controller=empresas&action=index" class="btn btn-primary">
                <i class="fas fa-list me-1"></i> Ver Todas las Empresas
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
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