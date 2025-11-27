<?php
$pageTitle = "Ver Usuario - Sistema Mototaxis";
include_once 'layouts/header.php';
?>

<style>
/* Estilos específicos para ver usuario */
.ver-usuario-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: calc(100vh - 80px);
    padding: 30px 0;
}

.ver-usuario-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 40px 0;
    margin-bottom: 30px;
    border-radius: 0 0 25px 25px;
    text-align: center;
}

.ver-usuario-title {
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.ver-usuario-subtitle {
    opacity: 0.9;
    font-weight: 300;
    font-size: 1.1rem;
}

.ver-usuario-card {
    background: #fff;
    border-radius: 20px;
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    overflow: hidden;
    animation: fadeIn 0.8s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.ver-usuario-card-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 25px 30px;
    font-weight: 600;
    font-size: 1.2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ver-usuario-card-header i {
    margin-right: 12px;
    font-size: 1.3rem;
}

.ver-usuario-card-body {
    padding: 40px;
}

.perfil-usuario {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    border-radius: 20px;
    border: 2px solid #e3f2fd;
}

.avatar-perfil {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border: 5px solid white;
    box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
}

.avatar-perfil i {
    font-size: 3rem;
    color: white;
}

.nombre-usuario {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e3c72;
    margin-bottom: 5px;
}

.usuario-login {
    font-size: 1.2rem;
    color: #6c757d;
    margin-bottom: 15px;
    font-family: 'Courier New', monospace;
}

.estado-usuario {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.estado-activo-perfil {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.estado-inactivo-perfil {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    color: white;
}

.info-detallada {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.grupo-info {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    border-left: 4px solid #1e3c72;
}

.grupo-info h5 {
    color: #1e3c72;
    margin-bottom: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.grupo-info h5 i {
    margin-right: 10px;
    font-size: 1.1rem;
}

.item-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.item-info:last-child {
    border-bottom: none;
}

.item-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.item-valor {
    color: #1e3c72;
    font-weight: 500;
    text-align: right;
}

.acciones-perfil {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    border: 2px dashed #ffc107;
}

.acciones-perfil h5 {
    color: #856404;
    margin-bottom: 20px;
    font-weight: 600;
}

.btn-accion-perfil {
    margin: 5px;
    border: none;
    border-radius: 10px;
    padding: 12px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-editar-perfil {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
}

.btn-cambiar-estado {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    color: white;
}

.btn-volver-perfil {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.btn-accion-perfil:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    color: white;
    text-decoration: none;
}

.btn-accion-perfil i {
    margin-right: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .ver-usuario-header {
        padding: 30px 0;
    }
    
    .ver-usuario-title {
        font-size: 1.5rem;
    }
    
    .ver-usuario-card-body {
        padding: 25px;
    }
    
    .info-detallada {
        grid-template-columns: 1fr;
    }
    
    .perfil-usuario {
        padding: 20px;
    }
    
    .avatar-perfil {
        width: 100px;
        height: 100px;
    }
    
    .avatar-perfil i {
        font-size: 2.5rem;
    }
    
    .nombre-usuario {
        font-size: 1.5rem;
    }
}

/* Efectos especiales */
.stats-usuario {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.stat-item {
    background: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.stat-numero {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e3c72;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<div class="ver-usuario-container">
    <!-- Header -->
    <div class="ver-usuario-header">
        <div class="container">
            <h1 class="ver-usuario-title">
                <i class="fas fa-user fa-fw"></i> DETALLES DEL USUARIO
            </h1>
            <p class="ver-usuario-subtitle">Información completa y detallada del usuario</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ver-usuario-card">
                    <div class="ver-usuario-card-header">
                        <div>
                            <i class="fas fa-id-card fa-fw"></i> PERFIL DEL USUARIO
                        </div>
                        <div>
                            <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
                               class="btn btn-editar-perfil btn-accion-perfil">
                                <i class="fas fa-edit fa-fw"></i> EDITAR
                            </a>
                        </div>
                    </div>
                    <div class="ver-usuario-card-body">
                        <!-- Perfil del Usuario -->
                        <div class="perfil-usuario">
                            <div class="avatar-perfil">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h2 class="nombre-usuario"><?php echo htmlspecialchars($this->model->nombre); ?></h2>
                            <div class="usuario-login">@<?php echo htmlspecialchars($this->model->usuario); ?></div>
                            <span class="estado-usuario <?php echo $this->model->estado == 1 ? 'estado-activo-perfil' : 'estado-inactivo-perfil'; ?>">
                                <i class="fas fa-<?php echo $this->model->estado == 1 ? 'check-circle' : 'times-circle'; ?> me-1"></i>
                                <?php echo $this->model->estado == 1 ? 'ACTIVO' : 'INACTIVO'; ?>
                            </span>
                            
                            <!-- Estadísticas Simbólicas -->
                            <div class="stats-usuario">
                                <div class="stat-item">
                                    <div class="stat-numero">#<?php echo $this->model->id; ?></div>
                                    <div class="stat-label">ID Usuario</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-numero">
                                        <i class="fas fa-<?php echo $this->model->estado == 1 ? 'check text-success' : 'times text-danger'; ?>"></i>
                                    </div>
                                    <div class="stat-label">Estado</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-numero">
                                        <i class="fas fa-user-clock text-info"></i>
                                    </div>
                                    <div class="stat-label">Registrado</div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Detallada -->
                        <div class="info-detallada">
                            <!-- Información Personal -->
                            <div class="grupo-info">
                                <h5><i class="fas fa-user-circle"></i> INFORMACIÓN PERSONAL</h5>
                                <div class="item-info">
                                    <span class="item-label">Nombre Completo</span>
                                    <span class="item-valor"><?php echo htmlspecialchars($this->model->nombre); ?></span>
                                </div>
                                <div class="item-info">
                                    <span class="item-label">Usuario</span>
                                    <span class="item-valor">
                                        <code><?php echo htmlspecialchars($this->model->usuario); ?></code>
                                    </span>
                                </div>
                                <div class="item-info">
                                    <span class="item-label">Tipo de Cuenta</span>
                                    <span class="item-valor">
                                        <span class="badge bg-primary">USUARIO SISTEMA</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Información del Sistema -->
                            <div class="grupo-info">
                                <h5><i class="fas fa-cog"></i> INFORMACIÓN DEL SISTEMA</h5>
                                <div class="item-info">
                                    <span class="item-label">ID de Registro</span>
                                    <span class="item-valor">#<?php echo $this->model->id; ?></span>
                                </div>
                                <div class="item-info">
                                    <span class="item-label">Fecha de Registro</span>
                                    <span class="item-valor">
                                        <?php echo date('d/m/Y H:i', strtotime($this->model->fecha_registro)); ?>
                                    </span>
                                </div>
                                <div class="item-info">
                                    <span class="item-label">Estado Actual</span>
                                    <span class="item-valor">
                                        <?php if ($this->model->estado == 1): ?>
                                            <span class="badge bg-success">ACTIVO</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">INACTIVO</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="acciones-perfil">
                            <h5><i class="fas fa-bolt"></i> ACCIONES DISPONIBLES</h5>
                            <div class="d-flex flex-wrap justify-content-center gap-3">
                                <a href="index.php?controller=usuarios&action=edit&id=<?php echo $this->model->id; ?>" 
                                   class="btn btn-editar-perfil btn-accion-perfil">
                                    <i class="fas fa-edit fa-fw"></i> EDITAR USUARIO
                                </a>
                                
                                <?php if ($this->model->estado == 1): ?>
                                    <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                                       class="btn btn-cambiar-estado btn-accion-perfil">
                                        <i class="fas fa-toggle-on fa-fw"></i> DESACTIVAR
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?controller=usuarios&action=toggleStatus&id=<?php echo $this->model->id; ?>" 
                                       class="btn btn-cambiar-estado btn-accion-perfil">
                                        <i class="fas fa-toggle-off fa-fw"></i> ACTIVAR
                                    </a>
                                <?php endif; ?>
                                
                                <a href="index.php?controller=usuarios&action=index" 
                                   class="btn btn-volver-perfil btn-accion-perfil">
                                    <i class="fas fa-arrow-left fa-fw"></i> VOLVER
                                </a>
                            </div>
                            
                            <!-- Información Adicional -->
                            <div class="mt-4 pt-3 border-top">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            Registrado el <?php echo date('d/m/Y', strtotime($this->model->fecha_registro)); ?>
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo date('H:i', strtotime($this->model->fecha_registro)); ?>
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">
                                            <i class="fas fa-database me-1"></i>
                                            ID: <?php echo $this->model->id; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Efectos de animación para las tarjetas
    const gruposInfo = document.querySelectorAll('.grupo-info');
    gruposInfo.forEach((grupo, index) => {
        grupo.style.animationDelay = `${index * 0.2}s`;
        grupo.classList.add('animate__animated', 'animate__fadeInUp');
    });

    // Efecto de hover en las acciones
    const botonesAccion = document.querySelectorAll('.btn-accion-perfil');
    botonesAccion.forEach(boton => {
        boton.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });
        
        boton.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Confirmación para cambiar estado
    const botonesEstado = document.querySelectorAll('.btn-cambiar-estado');
    botonesEstado.forEach(boton => {
        boton.addEventListener('click', function(e) {
            const accion = this.textContent.includes('ACTIVAR') ? 'activar' : 'desactivar';
            const mensaje = `¿Está seguro de que desea ${accion} este usuario?`;
            
            if (!confirm(mensaje)) {
                e.preventDefault();
            }
        });
    });

    // Efecto de parpadeo para el estado
    const estadoBadge = document.querySelector('.estado-usuario');
    if (estadoBadge.classList.contains('estado-inactivo-perfil')) {
        setInterval(() => {
            estadoBadge.style.opacity = estadoBadge.style.opacity === '0.7' ? '1' : '0.7';
        }, 1000);
    }
});
</script>

<?php include_once 'layouts/footer.php'; ?>