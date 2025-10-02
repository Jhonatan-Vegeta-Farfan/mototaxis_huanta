<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-bar me-2"></i>Count Requests</h2>
    <a href="index.php?controller=count_request&action=create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Nuevo Request
    </a>
</div>

<!-- Indicador de Filtro -->
<?php if (isset($_GET['client_id']) && !empty($_GET['client_id'])): ?>
<?php
    // Obtener información del cliente para mostrar
    $clientModel = new ClientApi($db_connection);
    $clientModel->id = $_GET['client_id'];
    $clientInfo = '';
    if ($clientModel->readOne()) {
        $clientInfo = $clientModel->razon_social . ' (RUC: ' . $clientModel->ruc . ')';
    }
?>
<div class="alert alert-info mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-filter me-2"></i>
            <strong>Filtrado por cliente:</strong> <?php echo $clientInfo; ?>
        </div>
        <a href="index.php?controller=count_request&action=index" class="btn btn-sm btn-outline-info">
            <i class="fas fa-times me-1"></i> Quitar Filtro
        </a>
    </div>
</div>
<?php elseif (isset($_GET['token_id']) && !empty($_GET['token_id'])): ?>
<?php
    // Obtener información del token para mostrar
    $tokenModel = new TokenApi($db_connection);
    $tokenModel->id = $_GET['token_id'];
    $tokenInfo = '';
    if ($tokenModel->readOne()) {
        $clientModel = new ClientApi($db_connection);
        $clientModel->id = $tokenModel->id_client_api;
        if ($clientModel->readOne()) {
            $tokenInfo = 'Token: ' . substr($tokenModel->token, 0, 20) . '... - Cliente: ' . $clientModel->razon_social;
        }
    }
?>
<div class="alert alert-info mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-filter me-2"></i>
            <strong>Filtrado por token:</strong> <?php echo $tokenInfo; ?>
        </div>
        <a href="index.php?controller=count_request&action=index" class="btn btn-sm btn-outline-info">
            <i class="fas fa-times me-1"></i> Quitar Filtro
        </a>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Count Requests</h4>
        <span class="badge bg-primary">
            <?php 
            $rowCount = $stmt->rowCount();
            echo $rowCount . ' request' . ($rowCount != 1 ? 's' : '') . ' encontrado' . ($rowCount != 1 ? 's' : '');
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
                        <th>Cliente</th>
                        <th>Token</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <strong class="text-warning"><?php echo $row['razon_social']; ?></strong>
                            <br>
                            <small>
                                <?php
                                // Obtener client_id desde token_id
                                $tokenModel = new TokenApi($db_connection);
                                $tokenModel->id = $row['id_token_api'];
                                $client_id_from_token = '';
                                if ($tokenModel->readOne()) {
                                    $client_id_from_token = $tokenModel->id_client_api;
                                }
                                ?>
                                <a href="index.php?controller=count_request&action=index&client_id=<?php echo $client_id_from_token; ?>" 
                                   class="text-info">
                                    <i class="fas fa-filter me-1"></i>Filtrar por este cliente
                                </a>
                            </small>
                        </td>
                        <td>
                            <code class="text-light bg-dark p-1 rounded"><?php echo substr($row['token'], 0, 15) . '...'; ?></code>
                            <br>
                            <small>
                                <a href="index.php?controller=count_request&action=index&token_id=<?php echo $row['id_token_api']; ?>" 
                                   class="text-info">
                                    <i class="fas fa-filter me-1"></i>Filtrar por este token
                                </a>
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-<?php 
                                switch($row['tipo']) {
                                    case 'consulta': echo 'info'; break;
                                    case 'registro': echo 'success'; break;
                                    case 'actualizacion': echo 'warning'; break;
                                    case 'eliminacion': echo 'danger'; break;
                                    default: echo 'secondary';
                                }
                            ?>">
                                <?php echo ucfirst($row['tipo']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $row['fecha']; ?></span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=count_request&action=edit&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar Request">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=count_request&action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Está seguro de eliminar este registro?')"
                                   data-bs-toggle="tooltip" title="Eliminar Request">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <button type="button" class="btn btn-info btn-sm view-details" 
                                        data-bs-toggle="modal" data-bs-target="#detailsModal"
                                        data-id="<?php echo $row['id']; ?>"
                                        data-cliente="<?php echo $row['razon_social']; ?>"
                                        data-token="<?php echo $row['token']; ?>"
                                        data-tipo="<?php echo $row['tipo']; ?>"
                                        data-fecha="<?php echo $row['fecha']; ?>"
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
            <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No se encontraron requests</h4>
            <p class="text-muted">
                <?php if (isset($_GET['client_id'])): ?>
                Este cliente no tiene requests registrados.
                <?php elseif (isset($_GET['token_id'])): ?>
                Este token no tiene requests registrados.
                <?php else: ?>
                No hay requests registrados en el sistema.
                <?php endif; ?>
            </p>
            <a href="index.php?controller=count_request&action=create" class="btn btn-success me-2">
                <i class="fas fa-plus me-1"></i> Crear Nuevo Request
            </a>
            <a href="index.php?controller=count_request&action=index" class="btn btn-primary">
                <i class="fas fa-list me-1"></i> Ver Todos los Requests
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
                    <i class="fas fa-info-circle me-2"></i>Detalles del Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-warning">Información del Request</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>ID del Request:</th>
                                <td id="modal-id"></td>
                            </tr>
                            <tr>
                                <th>Cliente:</th>
                                <td id="modal-cliente"></td>
                            </tr>
                            <tr>
                                <th>Tipo:</th>
                                <td id="modal-tipo"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Información Técnica</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Fecha:</th>
                                <td id="modal-fecha"></td>
                            </tr>
                            <tr>
                                <th>Token:</th>
                                <td>
                                    <code id="modal-token" class="bg-dark text-light p-1 rounded d-block"></code>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-warning">Acciones Disponibles</h6>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="#" class="btn btn-warning me-2" id="modal-edit-link">
                                <i class="fas fa-edit me-1"></i> Editar Request
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
            const requestId = this.getAttribute('data-id');
            
            // Obtener datos de los atributos data
            document.getElementById('modal-id').textContent = requestId;
            document.getElementById('modal-cliente').textContent = this.getAttribute('data-cliente');
            document.getElementById('modal-token').textContent = this.getAttribute('data-token');
            document.getElementById('modal-fecha').textContent = this.getAttribute('data-fecha');
            
            // Tipo con badge
            const tipo = this.getAttribute('data-tipo');
            const tipoClass = 
                tipo === 'consulta' ? 'info' :
                tipo === 'registro' ? 'success' :
                tipo === 'actualizacion' ? 'warning' :
                tipo === 'eliminacion' ? 'danger' : 'secondary';
            document.getElementById('modal-tipo').innerHTML = `<span class="badge bg-${tipoClass}">${tipo}</span>`;
            
            // Actualizar enlaces de acciones
            document.getElementById('modal-edit-link').href = `index.php?controller=count_request&action=edit&id=${requestId}`;
            
            // Actualizar título del modal
            document.getElementById('detailsModalLabel').textContent = 
                'Detalles del Request - ID: ' + requestId;
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