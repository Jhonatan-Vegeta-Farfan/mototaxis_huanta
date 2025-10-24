<?php
session_start();

// Verificar si el usuario está logueado (solo para admin)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Incluir modelos
require_once 'models/Database.php';
require_once 'models/ClientApi.php';
require_once 'models/TokenApi.php';
require_once 'models/CountRequest.php';
require_once 'models/Empresa.php';
require_once 'models/Mototaxi.php';

// Incluir controladores
require_once 'controllers/ClientApiController.php';
require_once 'controllers/TokenApiController.php';
require_once 'controllers/CountRequestController.php';
require_once 'controllers/EmpresaController.php';
require_once 'controllers/MototaxiController.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener controlador y acción
$controller = isset($_GET['controller']) ? $_GET['controller'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Si no hay controlador específico, mostrar dashboard
if (empty($controller)) {
    // Obtener estadísticas para el dashboard
    $empresaModel = new Empresa($db);
    $mototaxiModel = new Mototaxi($db);
    $clientApiModel = new ClientApi($db);
    $tokenApiModel = new TokenApi($db);
    $countRequestModel = new CountRequest($db);
    
    $totalEmpresas = $empresaModel->read()->rowCount();
    $totalMototaxis = $mototaxiModel->read()->rowCount();
    $totalClientesApi = $clientApiModel->read()->rowCount();
    $totalTokens = $tokenApiModel->read()->rowCount();
    $totalRequests = $countRequestModel->read()->rowCount();

    // Incluir header del sistema
    $pageTitle = 'Dashboard - Sistema Mototaxis Huanta';
    include_once 'views/layouts/header.php';
?>

<!-- Dashboard Content -->
<div class="dashboard-content" style="min-height: calc(100vh - 120px);">
    
    <!-- Header Corporativo Compacto -->
    <div class="header-corporativo-compacto bg-primary text-white py-3 mb-0">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="municipal-logo-compacto me-3">
                            <i class="fas fa-landmark fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">MUNICIPALIDAD PROVINCIAL DE HUANTA</h4>
                            <small class="opacity-75">Sistema de Gestión de Mototaxis</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <div class="user-info-compacto bg-dark bg-opacity-25 px-3 py-2 rounded">
                            <small class="d-block opacity-75">Bienvenido</small>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-user-tie"></i>
                                <strong><?php echo $_SESSION['username']; ?></strong>
                                <small class="opacity-75">Administrador del Sistema</small>
                            </div>
                        </div>
                        <a href="logout.php" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Corporativas -->
    <div class="stats-corporativas py-4">
        <div class="container-fluid">
            <div class="section-header-corp text-center mb-4">
                <h2 class="section-title-corp text-dark">PANEL DE CONTROL</h2>
                <p class="text-muted">Resumen general del sistema</p>
            </div>
            
            <div class="row g-3">
                <!-- Empresas -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card-corp" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header-corp">
                            <div class="card-icon-corp bg-primary">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="card-badge">Registradas</div>
                        </div>
                        <div class="card-body-corp">
                            <h3 class="card-title-corp">EMPRESAS</h3>
                            <div class="card-number-corp" data-count="<?php echo $totalEmpresas; ?>">0</div>
                            <p class="card-desc-corp">Empresas de transporte registradas</p>
                        </div>
                        <div class="card-footer-corp">
                            <a href="index.php?controller=empresas&action=index" class="btn-corp-primary">
                                <span>Gestionar</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mototaxis -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card-corp" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header-corp">
                            <div class="card-icon-corp bg-success">
                                <i class="fas fa-motorcycle"></i>
                            </div>
                            <div class="card-badge">Activos</div>
                        </div>
                        <div class="card-body-corp">
                            <h3 class="card-title-corp">MOTOTAXIS</h3>
                            <div class="card-number-corp" data-count="<?php echo $totalMototaxis; ?>">0</div>
                            <p class="card-desc-corp">Vehículos en operación</p>
                        </div>
                        <div class="card-footer-corp">
                            <a href="index.php?controller=mototaxis&action=index" class="btn-corp-primary">
                                <span>Gestionar</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Clientes API -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card-corp" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header-corp">
                            <div class="card-icon-corp bg-info">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-badge">Conectados</div>
                        </div>
                        <div class="card-body-corp">
                            <h3 class="card-title-corp">CLIENTES API</h3>
                            <div class="card-number-corp" data-count="<?php echo $totalClientesApi; ?>">0</div>
                            <p class="card-desc-corp">Clientes del servicio API</p>
                        </div>
                        <div class="card-footer-corp">
                            <a href="index.php?controller=client_api&action=index" class="btn-corp-primary">
                                <span>Gestionar</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tokens API -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card-corp" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-header-corp">
                            <div class="card-icon-corp bg-warning">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="card-badge">Activos</div>
                        </div>
                        <div class="card-body-corp">
                            <h3 class="card-title-corp">TOKENS API</h3>
                            <div class="card-number-corp" data-count="<?php echo $totalTokens; ?>">0</div>
                            <p class="card-desc-corp">Tokens de acceso activos</p>
                        </div>
                        <div class="card-footer-corp">
                            <a href="index.php?controller=tokens_api&action=index" class="btn-corp-primary">
                                <span>Gestionar</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Requests -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card-corp" data-aos="fade-up" data-aos-delay="500">
                        <div class="card-header-corp">
                            <div class="card-icon-corp bg-secondary">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="card-badge">Procesadas</div>
                        </div>
                        <div class="card-body-corp">
                            <h3 class="card-title-corp">SOLICITUDES</h3>
                            <div class="card-number-corp" data-count="<?php echo $totalRequests; ?>">0</div>
                            <p class="card-desc-corp">Solicitudes procesadas</p>
                        </div>
                        <div class="card-footer-corp">
                            <a href="index.php?controller=count_request&action=index" class="btn-corp-primary">
                                <span>Gestionar</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas Corporativas -->
    <div class="acciones-corporativas py-4 bg-light">
        <div class="container-fluid">
            <div class="section-header-corp text-center mb-4">
                <h2 class="section-title-corp text-dark">ACCIONES RÁPIDAS</h2>
                <p class="text-muted">Accesos directos a funciones principales</p>
            </div>
            
            <div class="row g-3">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <a href="index.php?controller=empresas&action=create" class="action-card-corp" data-aos="zoom-in" data-aos-delay="100">
                        <div class="action-icon-corp">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="action-content-corp">
                            <h4>Nueva Empresa</h4>
                            <p>Registrar nueva empresa de transporte</p>
                        </div>
                        <div class="action-arrow-corp">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <a href="index.php?controller=mototaxis&action=create" class="action-card-corp" data-aos="zoom-in" data-aos-delay="200">
                        <div class="action-icon-corp">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <div class="action-content-corp">
                            <h4>Nuevo Mototaxi</h4>
                            <p>Registrar nuevo vehículo</p>
                        </div>
                        <div class="action-arrow-corp">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <a href="index.php?controller=client_api&action=create" class="action-card-corp" data-aos="zoom-in" data-aos-delay="300">
                        <div class="action-icon-corp">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="action-content-corp">
                            <h4>Nuevo Cliente API</h4>
                            <p>Crear nuevo cliente del servicio</p>
                        </div>
                        <div class="action-arrow-corp">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <a href="index.php?controller=tokens_api&action=create" class="action-card-corp" data-aos="zoom-in" data-aos-delay="400">
                        <div class="action-icon-corp">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="action-content-corp">
                            <h4>Nuevo Token</h4>
                            <p>Generar token de acceso</p>
                        </div>
                        <div class="action-arrow-corp">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado del Sistema -->
    <div class="estado-sistema py-4">
        <div class="container-fluid">
            <div class="section-header-corp text-center mb-4">
                <h2 class="section-title-corp text-dark">ESTADO DEL SISTEMA</h2>
                <p class="text-muted">Monitoreo en tiempo real</p>
            </div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="status-card-corp" data-aos="fade-right">
                        <div class="status-icon online">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="status-content">
                            <h5>Servidor Principal</h5>
                            <span class="status-text online">Operativo</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="status-card-corp" data-aos="fade-right" data-aos-delay="100">
                        <div class="status-icon online">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="status-content">
                            <h5>Base de Datos</h5>
                            <span class="status-text online">Conectada</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="status-card-corp" data-aos="fade-right" data-aos-delay="200">
                        <div class="status-icon online">
                            <i class="fas fa-plug"></i>
                        </div>
                        <div class="status-content">
                            <h5>Servicio API</h5>
                            <span class="status-text online">Activo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
/* Estilos específicos para el dashboard */
.dashboard-content {
    background: #f8f9fa;
}

.header-corporativo-compacto {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
    border-bottom: 3px solid #2a5298;
}

.municipal-logo-compacto {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.1);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(255,255,255,0.2);
}

/* Stats Cards */
.stat-card-corp {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.stat-card-corp:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-color: #1e3c72;
}

.card-header-corp {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.card-icon-corp {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.3rem;
}

.card-icon-corp.bg-primary { background: linear-gradient(135deg, #007bff, #0056b3); }
.card-icon-corp.bg-success { background: linear-gradient(135deg, #28a745, #1e7e34); }
.card-icon-corp.bg-info { background: linear-gradient(135deg, #17a2b8, #138496); }
.card-icon-corp.bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800); }
.card-icon-corp.bg-secondary { background: linear-gradient(135deg, #6c757d, #545b62); }

.card-badge {
    background: #e9ecef;
    color: #495057;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
}

.card-body-corp {
    flex: 1;
    margin-bottom: 15px;
}

.card-title-corp {
    color: #495057;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

.card-number-corp {
    color: #1e3c72;
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 6px;
    line-height: 1;
}

.card-desc-corp {
    color: #6c757d;
    font-size: 0.8rem;
    margin: 0;
}

.card-footer-corp {
    border-top: 1px solid #e9ecef;
    padding-top: 15px;
}

.btn-corp-primary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #1e3c72;
    color: #fff;
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-corp-primary:hover {
    background: #2a5298;
    color: #fff;
    text-decoration: none;
    transform: translateX(3px);
}

/* Action Cards */
.action-card-corp {
    display: flex;
    align-items: center;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    text-decoration: none;
    color: #495057;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
    margin-bottom: 20px;
}

.action-card-corp:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    color: #495057;
    text-decoration: none;
    border-color: #1e3c72;
}

.action-icon-corp {
    width: 45px;
    height: 45px;
    background: #e9ecef;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: #1e3c72;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.action-card-corp:hover .action-icon-corp {
    background: #1e3c72;
    color: #fff;
}

.action-content-corp {
    flex: 1;
}

.action-content-corp h4 {
    color: #1e3c72;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.action-content-corp p {
    color: #6c757d;
    font-size: 0.8rem;
    margin: 0;
}

.action-arrow-corp {
    color: #6c757d;
    transition: all 0.3s ease;
}

.action-card-corp:hover .action-arrow-corp {
    color: #1e3c72;
    transform: translateX(5px);
}

/* Status Cards */
.status-card-corp {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    margin-bottom: 15px;
}

.status-card-corp:hover {
    border-color: #1e3c72;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}

.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 1.1rem;
}

.status-icon.online {
    background: #d4edda;
    color: #28a745;
}

.status-content h5 {
    color: #495057;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 3px;
}

.status-text {
    font-size: 0.8rem;
    font-weight: 500;
}

.status-text.online {
    color: #28a745;
}

/* Responsive */
@media (max-width: 768px) {
    .header-corporativo-compacto .col-md-6 {
        text-align: center;
        margin-bottom: 10px;
    }
    
    .user-info-compacto {
        text-align: center;
    }
    
    .card-number-corp {
        font-size: 1.6rem;
    }
}
</style>

<script>
// Animación de contadores
function animateCounters() {
    const counters = document.querySelectorAll('.card-number-corp');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-count');
        const duration = 1500;
        const step = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        updateCounter();
    });
}

// Inicializar AOS
if (typeof AOS !== 'undefined') {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
}

// Ejecutar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
});
</script>

<?php
    // Incluir footer del sistema
    include_once 'views/layouts/footer.php';

} else {
    // Enrutamiento normal para otros controladores
    switch($controller) {
        case 'client_api':
            if (!isset($_SESSION['loggedin'])) {
                header("Location: login.php");
                exit;
            }
            $controllerObj = new ClientApiController($db);
            break;
        case 'tokens_api':
            if (!isset($_SESSION['loggedin'])) {
                header("Location: login.php");
                exit;
            }
            $controllerObj = new TokenApiController($db);
            break;
        case 'count_request':
            if (!isset($_SESSION['loggedin'])) {
                header("Location: login.php");
                exit;
            }
            $controllerObj = new CountRequestController($db);
            break;
        case 'empresas':
            if (!isset($_SESSION['loggedin'])) {
                header("Location: login.php");
                exit;
            }
            $controllerObj = new EmpresaController($db);
            break;
        case 'mototaxis':
            if (!isset($_SESSION['loggedin'])) {
                header("Location: login.php");
                exit;
            }
            $controllerObj = new MototaxiController($db);
            break;
        default:
            if (!isset($_SESSION['loggedin'])) {
                header("Location: login.php");
                exit;
            }
            $controllerObj = new EmpresaController($db);
            $action = 'index';
    }

    // Ejecutar acción
    if(method_exists($controllerObj, $action)) {
        $controllerObj->$action();
    } else {
        echo "Acción no encontrada";
    }
}
?>