<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-motorcycle me-2"></i>Mototaxis</h2>
    <a href="index.php?controller=mototaxis&action=create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Nuevo Mototaxi
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>N° Asignado</th>
                        <th>Nombre Completo</th>
                        <th>DNI</th>
                        <th>Placa</th>
                        <th>Empresa</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><strong><?php echo $row['numero_asignado']; ?></strong></td>
                        <td><?php echo $row['nombre_completo']; ?></td>
                        <td><?php echo $row['dni']; ?></td>
                        <td><?php echo $row['placa_rodaje']; ?></td>
                        <td><?php echo $row['empresa']; ?></td>
                        <td>
                            <a href="index.php?controller=mototaxis&action=edit&id=<?php echo $row['id']; ?>" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="index.php?controller=mototaxis&action=delete&id=<?php echo $row['id']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('¿Está seguro de eliminar este mototaxi?')">
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