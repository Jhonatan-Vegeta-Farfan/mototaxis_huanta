<?php
$pageTitle = "Gestión de Usuarios - Sistema Mototaxis";
include_once 'layouts/header.php';
?>

<style>
/* Estilos específicos para la gestión de usuarios */
.usuarios-container {
    background: #f8f9fa;
    min-height: calc(100vh - 80px);
    padding: 20px 0;
}

.usuarios-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 30px 0;
    margin-bottom: 30px;
    border-radius: 0 0 20px 20px;
}

.usuarios-title {
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.usuarios-subtitle {
    opacity: 0.9;
    font-weight: 300;
}

.btn-usuario-primary {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    border-radius: 10px;
    padding: 12px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
}

.btn-usuario-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(30, 60, 114, 0.4);
    background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
}

.usuarios-card {
    background: #fff;
    border-radius: 15px;
    border: none;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    margin-bottom: 25px;
    overflow: hidden;
}

.usuarios-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.usuarios-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #1e3c72;
    padding: 20px 25px;
    font-weight: 600;
    color: #1e3c72;
    font-size: 1.1rem;
}

.usuarios-card-header i {
    margin-right: 10px;
    font-size: 1.2rem;
}

.usuarios-table {
    width: 100%;
    margin-bottom: 0;
}

.usuarios-table thead {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
}

.usuarios-table thead th {
    border: none;
    padding: 15px 12px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.usuarios-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
}

.usuarios-table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    transform: scale(1.01);
}

.usuarios-table tbody td {
    padding: 15px 12px;
    vertical-align: middle;
    border: none;
    font-size: 0.95rem;
}

.estado-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.estado-activo {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.estado-inactivo {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    color: white;
}

.acciones-usuario {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-accion {
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.btn-ver {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    color: white;
}

.btn-editar {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
}

.btn-activar {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-desactivar {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.btn-eliminar {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    color: white;
}

.btn-accion:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    color: white;
    text-decoration: none;
}

.btn-accion i {
    margin-right: 5px;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .usuarios-header {
        padding: 20px 0;
        margin-bottom: 20px;
    }
    
    .usuarios-title {
        font-size: 1.5rem;
    }
    
    .usuarios-table thead {
        display: none;
    }
    
    .usuarios-table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
    }
    
    .usuarios-table tbody td {
        display: block;
        text-align: right;
        padding: 8px 10px;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .usuarios-table tbody td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: #1e3c72;
    }
    
    .acciones-usuario {
        justify-content: center;
        margin-top: 10px;
    }
    
    .btn-usuario-primary {
        width: 100%;
        margin-bottom: 15px;
    }
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.usuario-item {
    animation: fadeInUp 0.5s ease forwards;
}

.usuario-item:nth-child(1) { animation-delay: 0.1s; }
.usuario-item:nth-child(2) { animation-delay: 0.2s; }
.usuario-item:nth-child(3) { animation-delay: 0.3s; }
.usuario-item:nth-child(4) { animation-delay: 0.4s; }
.usuario-item:nth-child(5) { animation-delay: 0.5s; }

/* Estados vacíos */
.usuarios-vacio {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.usuarios-vacio i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.usuarios-vacio h4 {
    color: #495057;
    margin-bottom: 10px;
}
</style>

<div class="usuarios-container">
    <!-- Header -->
    <div class="usuarios-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="usuarios-title">
                        <i class="fas fa-users fa-fw"></i> GESTIÓN DE USUARIOS
                    </h1>
                    <p class="usuarios-subtitle mb-0">Administre los usuarios del sistema de manera eficiente</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="index.php?controller=usuarios&action=create" class="btn btn-usuario-primary">
                        <i class="fas fa-plus-circle fa-fw"></i> NUEVO USUARIO
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-3 fa-lg"></i>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">¡Éxito!</h6>
                        <p class="mb-0"><?php echo $_SESSION['success_message']; ?></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">¡Error!</h6>
                        <p class="mb-0"><?php echo $_SESSION['error_message']; ?></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Users Table -->
        <div class="usuarios-card">
            <div class="usuarios-card-header">
                <i class="fas fa-list fa-fw"></i> LISTA DE USUARIOS REGISTRADOS
            </div>
            <div class="card-body">
                <?php if ($stmt->rowCount() > 0): ?>
                <div class="table-responsive">
                    <table class="table usuarios-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE COMPLETO</th>
                                <th>USUARIO</th>
                                <th>FECHA REGISTRO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="usuario-item">
                                <td data-label="ID">
                                    <strong>#<?php echo htmlspecialchars($row['id']); ?></strong>
                                </td>
                                <td data-label="NOMBRE COMPLETO">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-usuario me-3">
                                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($row['nombre']); ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="USUARIO">
                                    <code><?php echo htmlspecialchars($row['usuario']); ?></code>
                                </td>
                                <td data-label="FECHA REGISTRO">
                                    <?php echo date('d/m/Y H:i', strtotime($row['fecha_registro'])); ?>
                                </td>
                                <td data-label="ESTADO">
                                    <?php if ($row['estado'] == 1): ?>
                                        <span class="estado-badge estado-activo">
                                            <i class="fas fa-check-circle me-1"></i> ACTIVO
                                        </span>
                                    <?php else: ?>
                                        <span class="estado-badge estado-inactivo">
                                            <i class="fas fa-times-circle me-1"></i> INACTIVO
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="ACCIONES">
                                    <div class="acciones-usuario">
                                        <a href="index.php?controller=usuarios&action=view&id=<?php echo $row['id']; ?>" 
                                           class="btn-accion btn-ver" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="index.php?controller=usuarios&action=edit&id=<?php echo $row['id']; ?>" 
                                           class="btn-accion btn-editar" title="Editar usuario">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($row['estado'] == 1): ?>
                                            <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $row['id']; ?>" 
                                               class="btn-accion btn-desactivar" title="Desactivar usuario">
                                                <i class="fas fa-toggle-on"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $row['id']; ?>" 
                                               class="btn-accion btn-activar" title="Activar usuario">
                                                <i class="fas fa-toggle-off"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?controller=usuarios&action=delete&id=<?php echo $row['id']; ?>" 
                                           class="btn-accion btn-eliminar" 
                                           onclick="return confirm('¿Está seguro de eliminar al usuario <?php echo htmlspecialchars($row['nombre']); ?>?')"
                                           title="Eliminar usuario">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="usuarios-vacio">
                    <i class="fas fa-users-slash"></i>
                    <h4>No hay usuarios registrados</h4>
                    <p class="text-muted">Comience agregando el primer usuario al sistema.</p>
                    <a href="index.php?controller=usuarios&action=create" class="btn btn-usuario-primary mt-3">
                        <i class="fas fa-plus-circle fa-fw"></i> CREAR PRIMER USUARIO
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="usuarios-card text-center">
                    <div class="card-body py-4">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h3 class="text-primary"><?php echo $stmt->rowCount(); ?></h3>
                        <p class="text-muted mb-0">Total Usuarios</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="usuarios-card text-center">
                    <div class="card-body py-4">
                        <i class="fas fa-user-check fa-3x text-success mb-3"></i>
                        <h3 class="text-success">
                            <?php
                            $activos = 0;
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                if ($row['estado'] == 1) $activos++;
                            }
                            echo $activos;
                            ?>
                        </h3>
                        <p class="text-muted mb-0">Usuarios Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="usuarios-card text-center">
                    <div class="card-body py-4">
                        <i class="fas fa-user-times fa-3x text-danger mb-3"></i>
                        <h3 class="text-danger">
                            <?php
                            $inactivos = 0;
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                if ($row['estado'] == 0) $inactivos++;
                            }
                            echo $inactivos;
                            ?>
                        </h3>
                        <p class="text-muted mb-0">Usuarios Inactivos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="usuarios-card text-center">
                    <div class="card-body py-4">
                        <i class="fas fa-calendar-alt fa-3x text-info mb-3"></i>
                        <h3 class="text-info"><?php echo date('Y'); ?></h3>
                        <p class="text-muted mb-0">Año Actual</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Scripts específicos para la página de usuarios
document.addEventListener('DOMContentLoaded', function() {
    // Efectos hover mejorados
    const usuarioItems = document.querySelectorAll('.usuario-item');
    usuarioItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.1)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });

    // Confirmación para acciones importantes
    const deleteButtons = document.querySelectorAll('.btn-eliminar');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('⚠️ ¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });

    // Animación de números en estadísticas
    function animateStats() {
        const stats = document.querySelectorAll('.usuarios-card.text-center h3');
        stats.forEach(stat => {
            const target = parseInt(stat.textContent);
            let current = 0;
            const duration = 1500;
            const increment = target / (duration / 16);
            
            const updateStat = () => {
                current += increment;
                if (current < target) {
                    stat.textContent = Math.floor(current);
                    requestAnimationFrame(updateStat);
                } else {
                    stat.textContent = target;
                }
            };
            updateStat();
        });
    }

    // Ejecutar animación después de un breve delay
    setTimeout(animateStats, 500);
});
</script>

<?php include_once 'layouts/footer.php'; ?>