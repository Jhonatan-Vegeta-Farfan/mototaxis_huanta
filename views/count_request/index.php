<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-bar me-2"></i>Count Requests</h2>
    <a href="index.php?controller=count_request&action=create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Nuevo Request
    </a>
</div>

<div class="card">
    <div class="card-body">
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
                        <td><?php echo $row['razon_social']; ?></td>
                        <td><code><?php echo substr($row['token'], 0, 15) . '...'; ?></code></td>
                        <td>
                            <span class="badge bg-info"><?php echo $row['tipo']; ?></span>
                        </td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td>
                            <a href="index.php?controller=count_request&action=edit&id=<?php echo $row['id']; ?>" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="index.php?controller=count_request&action=delete&id=<?php echo $row['id']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('¿Está seguro de eliminar este registro?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>