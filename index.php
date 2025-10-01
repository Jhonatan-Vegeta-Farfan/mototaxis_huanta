<?php
// Configuración inicial
session_start();

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
    
    include_once 'views/layouts/header.php';
    ?>
    
    <!-- Dashboard Principal - Estilo Urbano -->
    <div class="dashboard-urban">
        <!-- Header con gradiente -->
        <div class="urban-header">
            <div class="urban-title">
                <h1>MUNICIPALIDAD PROVINCIAL DE HUANTA</h1>
                <p class="urban-subtitle">Sistema de Gestión de Mototaxis Huanta</p>
            </div>
            <div class="urban-waves">
                <div class="wave wave-1"></div>
                <div class="wave wave-2"></div>
                <div class="wave wave-3"></div>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="stats-grid">
            <!-- Tarjeta Empresas -->
            <div class="stat-card enterprise">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">EMPRESAS</h3>
                    <div class="stat-number"><?php echo $totalEmpresas; ?></div>
                    <p class="stat-desc">Empresas registradas</p>
                </div>
                <div class="stat-action">
                    <a href="index.php?controller=empresas&action=index" class="urban-btn">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="stat-glow"></div>
            </div>

            <!-- Tarjeta Mototaxis -->
            <div class="stat-card bike">
                <div class="stat-icon">
                    <i class="fas fa-motorcycle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">MOTOTAXIS</h3>
                    <div class="stat-number"><?php echo $totalMototaxis; ?></div>
                    <p class="stat-desc">Mototaxis activos</p>
                </div>
                <div class="stat-action">
                    <a href="index.php?controller=mototaxis&action=index" class="urban-btn">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="stat-glow"></div>
            </div>

            <!-- Tarjeta Clientes API -->
            <div class="stat-card client">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">CLIENTES API</h3>
                    <div class="stat-number"><?php echo $totalClientesApi; ?></div>
                    <p class="stat-desc">Clientes conectados</p>
                </div>
                <div class="stat-action">
                    <a href="index.php?controller=client_api&action=index" class="urban-btn">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="stat-glow"></div>
            </div>

            <!-- Tarjeta Tokens API -->
            <div class="stat-card token">
                <div class="stat-icon">
                    <i class="fas fa-key"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">TOKENS API</h3>
                    <div class="stat-number"><?php echo $totalTokens; ?></div>
                    <p class="stat-desc">Tokens activos</p>
                </div>
                <div class="stat-action">
                    <a href="index.php?controller=tokens_api&action=index" class="urban-btn">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="stat-glow"></div>
            </div>

            <!-- Tarjeta Requests -->
            <div class="stat-card request">
                <div class="stat-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">REQUESTS</h3>
                    <div class="stat-number"><?php echo $totalRequests; ?></div>
                    <p class="stat-desc">Solicitudes procesadas</p>
                </div>
                <div class="stat-action">
                    <a href="index.php?controller=count_request&action=index" class="urban-btn">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="stat-glow"></div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="section-title">
                <i class="fas fa-rocket"></i>
                <h2>ACCIONES RÁPIDAS</h2>
            </div>
            <div class="actions-grid">
                <a href="index.php?controller=empresas&action=create" class="action-btn new-enterprise">
                    <div class="action-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <span>Nueva Empresa</span>
                    <div class="action-pulse"></div>
                </a>

                <a href="index.php?controller=mototaxis&action=create" class="action-btn new-bike">
                    <div class="action-icon">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <span>Nuevo Mototaxi</span>
                    <div class="action-pulse"></div>
                </a>

                <a href="index.php?controller=client_api&action=create" class="action-btn new-client">
                    <div class="action-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <span>Nuevo Cliente</span>
                    <div class="action-pulse"></div>
                </a>

                <a href="index.php?controller=tokens_api&action=create" class="action-btn new-token">
                    <div class="action-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <span>Nuevo Token</span>
                    <div class="action-pulse"></div>
                </a>
            </div>
        </div>

        <!-- Live Stats Bar -->
        <div class="live-stats">
            <div class="live-stat">
                <i class="fas fa-sync-alt fa-spin"></i>
                <span>Sistema Activo</span>
            </div>
            <div class="live-stat">
                <i class="fas fa-database"></i>
                <span><?php echo $totalMototaxis; ?> Mototaxis Online</span>
            </div>
            <div class="live-stat">
                <i class="fas fa-signal"></i>
                <span>API Operativa</span>
            </div>
        </div>
    </div>

    <style>
    .dashboard-urban {
        background: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 50%, #16213e 100%);
        min-height: 100vh;
        padding: 20px;
        position: relative;
        overflow-x: hidden;
    }

    .urban-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 30px;
        border-radius: 20px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    }

    .urban-title {
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .urban-icon {
        font-size: 3rem;
        color: #fff;
        margin-bottom: 15px;
        text-shadow: 0 0 20px rgba(255,255,255,0.5);
    }

    .urban-title h1 {
        color: #fff;
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .urban-subtitle {
        color: rgba(255,255,255,0.8);
        font-size: 1.1rem;
        font-weight: 300;
    }

    .urban-waves {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60px;
    }

    .wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        animation: wave 6s linear infinite;
    }

    .wave-1 { animation-delay: 0s; }
    .wave-2 { animation-delay: -2s; }
    .wave-3 { animation-delay: -4s; }

    @keyframes wave {
        0% { transform: translateX(0) translateY(0); }
        50% { transform: translateX(-25%) translateY(-10px); }
        100% { transform: translateX(-50%) translateY(0); }
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .stat-card.enterprise { border-left: 4px solid #ff6b6b; }
    .stat-card.bike { border-left: 4px solid #4ecdc4; }
    .stat-card.client { border-left: 4px solid #45b7d1; }
    .stat-card.token { border-left: 4px solid #96ceb4; }
    .stat-card.request { border-left: 4px solid #feca57; }

    .stat-icon {
        font-size: 2.5rem;
        margin-right: 20px;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: rgba(255,255,255,0.1);
    }

    .stat-card.enterprise .stat-icon { color: #ff6b6b; }
    .stat-card.bike .stat-icon { color: #4ecdc4; }
    .stat-card.client .stat-icon { color: #45b7d1; }
    .stat-card.token .stat-icon { color: #96ceb4; }
    .stat-card.request .stat-icon { color: #feca57; }

    .stat-content {
        flex: 1;
    }

    .stat-title {
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-number {
        color: #fff;
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .stat-desc {
        color: rgba(255,255,255,0.7);
        font-size: 0.85rem;
        margin: 0;
    }

    .stat-action .urban-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .stat-action .urban-btn:hover {
        background: rgba(255,255,255,0.2);
        transform: scale(1.1);
    }

    .stat-glow {
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover .stat-glow {
        opacity: 1;
    }

    .quick-actions {
        background: rgba(255,255,255,0.05);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .section-title {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .section-title i {
        font-size: 1.5rem;
        color: #ff6b6b;
        margin-right: 15px;
    }

    .section-title h2 {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .action-btn {
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        border-radius: 15px;
        padding: 25px 20px;
        text-decoration: none;
        color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .action-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        color: #fff;
        text-decoration: none;
    }

    .action-icon {
        font-size: 2rem;
        margin-bottom: 10px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }

    .action-btn span {
        font-weight: 600;
        text-align: center;
    }

    .action-pulse {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.3s ease;
    }

    .action-btn:hover .action-pulse {
        width: 100px;
        height: 100px;
    }

    .new-enterprise .action-icon { color: #ff6b6b; }
    .new-bike .action-icon { color: #4ecdc4; }
    .new-client .action-icon { color: #45b7d1; }
    .new-token .action-icon { color: #96ceb4; }

    .live-stats {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .live-stat {
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.05);
        padding: 15px 25px;
        border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .live-stat i {
        color: #4ecdc4;
        margin-right: 10px;
    }

    .live-stat span {
        color: #fff;
        font-weight: 500;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .actions-grid {
            grid-template-columns: 1fr;
        }
        
        .urban-title h1 {
            font-size: 2rem;
        }
        
        .stat-card {
            padding: 20px;
        }
    }
    </style>

    <?php
    include_once 'views/layouts/footer.php';
} else {
    // Enrutamiento normal para otros controladores
    switch($controller) {
        case 'client_api':
            $controllerObj = new ClientApiController($db);
            break;
        case 'tokens_api':
            $controllerObj = new TokenApiController($db);
            break;
        case 'count_request':
            $controllerObj = new CountRequestController($db);
            break;
        case 'empresas':
            $controllerObj = new EmpresaController($db);
            break;
        case 'mototaxis':
            $controllerObj = new MototaxiController($db);
            break;
        default:
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