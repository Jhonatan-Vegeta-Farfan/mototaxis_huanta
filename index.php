<?php
// Configuración inicial
session_start();

// Verificar si el usuario está logueado
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
    
    include_once 'views/layouts/header.php';
    ?>
    
    <!-- Dashboard Principal - Diseño Moderno e Interactivo -->
    <div class="dashboard-modern">
        <!-- Header Hero -->
        <div class="hero-section">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="gradient-text">MUNICIPALIDAD PROVINCIAL DE HUANTA</span>
                    </h1>
                    <p class="hero-subtitle">Sistema Inteligente de Gestión de Mototaxis</p>
                    <div class="user-welcome">
                        <i class="fas fa-user-circle"></i>
                        <span>Bienvenido, <strong><?php echo $_SESSION['username']; ?></strong></span>
                    </div>
                </div>
                <div class="hero-actions">
                    <button class="btn-logout" onclick="location.href='logout.php'">
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar Sesión
                    </button>
                </div>
            </div>
            <div class="hero-background">
                <div class="floating-shapes">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                </div>
            </div>
        </div>

        <!-- Stats Grid Mejorado -->
        <div class="modern-stats-grid">
            <!-- Tarjeta Empresas -->
            <div class="modern-stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="card-inner">
                    <div class="card-front">
                        <div class="stat-header">
                            <div class="stat-icon-wrapper enterprise">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-trend up">
                                <i class="fas fa-arrow-up"></i>
                                <span>12%</span>
                            </div>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value"><?php echo $totalEmpresas; ?></h3>
                            <p class="stat-label">Empresas Registradas</p>
                        </div>
                        <div class="stat-footer">
                            <span class="view-more">Ver detalles <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                    <div class="card-back">
                        <h4>Gestión de Empresas</h4>
                        <p>Administra las empresas de mototaxis registradas en el sistema</p>
                        <a href="index.php?controller=empresas&action=index" class="btn-action">
                            <i class="fas fa-external-link-alt"></i>
                            Acceder
                        </a>
                    </div>
                </div>
                <div class="card-glow"></div>
            </div>

            <!-- Tarjeta Mototaxis -->
            <div class="modern-stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="card-inner">
                    <div class="card-front">
                        <div class="stat-header">
                            <div class="stat-icon-wrapper bike">
                                <i class="fas fa-motorcycle"></i>
                            </div>
                            <div class="stat-trend up">
                                <i class="fas fa-arrow-up"></i>
                                <span>8%</span>
                            </div>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value"><?php echo $totalMototaxis; ?></h3>
                            <p class="stat-label">Mototaxis Activos</p>
                        </div>
                        <div class="stat-footer">
                            <span class="view-more">Ver detalles <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                    <div class="card-back">
                        <h4>Gestión de Mototaxis</h4>
                        <p>Control y seguimiento de mototaxis en operación</p>
                        <a href="index.php?controller=mototaxis&action=index" class="btn-action">
                            <i class="fas fa-external-link-alt"></i>
                            Acceder
                        </a>
                    </div>
                </div>
                <div class="card-glow"></div>
            </div>

            <!-- Tarjeta Clientes API -->
            <div class="modern-stat-card" data-aos="fade-up" data-aos-delay="300">
                <div class="card-inner">
                    <div class="card-front">
                        <div class="stat-header">
                            <div class="stat-icon-wrapper client">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-trend up">
                                <i class="fas fa-arrow-up"></i>
                                <span>15%</span>
                            </div>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value"><?php echo $totalClientesApi; ?></h3>
                            <p class="stat-label">Clientes API</p>
                        </div>
                        <div class="stat-footer">
                            <span class="view-more">Ver detalles <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                    <div class="card-back">
                        <h4>Clientes API</h4>
                        <p>Gestión de clientes conectados al sistema</p>
                        <a href="index.php?controller=client_api&action=index" class="btn-action">
                            <i class="fas fa-external-link-alt"></i>
                            Acceder
                        </a>
                    </div>
                </div>
                <div class="card-glow"></div>
            </div>

            <!-- Tarjeta Tokens API -->
            <div class="modern-stat-card" data-aos="fade-up" data-aos-delay="400">
                <div class="card-inner">
                    <div class="card-front">
                        <div class="stat-header">
                            <div class="stat-icon-wrapper token">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="stat-trend stable">
                                <i class="fas fa-minus"></i>
                                <span>0%</span>
                            </div>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value"><?php echo $totalTokens; ?></h3>
                            <p class="stat-label">Tokens Activos</p>
                        </div>
                        <div class="stat-footer">
                            <span class="view-more">Ver detalles <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                    <div class="card-back">
                        <h4>Tokens API</h4>
                        <p>Administración de tokens de autenticación</p>
                        <a href="index.php?controller=tokens_api&action=index" class="btn-action">
                            <i class="fas fa-external-link-alt"></i>
                            Acceder
                        </a>
                    </div>
                </div>
                <div class="card-glow"></div>
            </div>
        </div>

        <!-- Quick Actions Mejorado -->
        <div class="quick-actions-modern" data-aos="fade-up">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-bolt"></i>
                    <h2>Acciones Rápidas</h2>
                </div>
                <p class="section-subtitle">Accesos directos a las funciones principales</p>
            </div>
            
            <div class="actions-grid-modern">
                <a href="index.php?controller=empresas&action=create" class="action-card new-enterprise" data-aos="zoom-in" data-aos-delay="100">
                    <div class="action-icon">
                        <i class="fas fa-building"></i>
                        <div class="pulse-effect"></div>
                    </div>
                    <div class="action-content">
                        <h3>Nueva Empresa</h3>
                        <p>Registrar nueva empresa de mototaxis</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="action-hover-effect"></div>
                </a>

                <a href="index.php?controller=mototaxis&action=create" class="action-card new-bike" data-aos="zoom-in" data-aos-delay="200">
                    <div class="action-icon">
                        <i class="fas fa-motorcycle"></i>
                        <div class="pulse-effect"></div>
                    </div>
                    <div class="action-content">
                        <h3>Nuevo Mototaxi</h3>
                        <p>Agregar nuevo vehículo al sistema</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="action-hover-effect"></div>
                </a>

                <a href="index.php?controller=client_api&action=create" class="action-card new-client" data-aos="zoom-in" data-aos-delay="300">
                    <div class="action-icon">
                        <i class="fas fa-user-plus"></i>
                        <div class="pulse-effect"></div>
                    </div>
                    <div class="action-content">
                        <h3>Nuevo Cliente</h3>
                        <p>Crear nuevo cliente API</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="action-hover-effect"></div>
                </a>

                <a href="index.php?controller=tokens_api&action=create" class="action-card new-token" data-aos="zoom-in" data-aos-delay="400">
                    <div class="action-icon">
                        <i class="fas fa-key"></i>
                        <div class="pulse-effect"></div>
                    </div>
                    <div class="action-content">
                        <h3>Nuevo Token</h3>
                        <p>Generar nuevo token de acceso</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="action-hover-effect"></div>
                </a>
            </div>
        </div>

        <!-- System Status -->
        <div class="system-status" data-aos="fade-up">
            <div class="status-header">
                <h3>Estado del Sistema</h3>
                <div class="status-indicator online">
                    <div class="pulse-dot"></div>
                    <span>En Línea</span>
                </div>
            </div>
            <div class="status-grid">
                <div class="status-item">
                    <div class="status-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="status-info">
                        <span class="status-label">Servidor</span>
                        <span class="status-value active">Operativo</span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="status-info">
                        <span class="status-label">Base de Datos</span>
                        <span class="status-value active">Conectada</span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-icon">
                        <i class="fas fa-plug"></i>
                    </div>
                    <div class="status-info">
                        <span class="status-label">API REST</span>
                        <span class="status-value active">Activa</span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="status-info">
                        <span class="status-label">Seguridad</span>
                        <span class="status-value active">Protegido</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
    .dashboard-modern {
        background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 50%, #16213e 100%);
        min-height: 100vh;
        padding: 0;
        position: relative;
        overflow-x: hidden;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        padding: 60px 40px;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .gradient-text {
        background: linear-gradient(45deg, #fff, #e0e0e0, #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-size: 200% 200%;
        animation: gradientShift 3s ease infinite;
    }

    .hero-subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .user-welcome {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255,255,255,0.8);
    }

    .user-welcome i {
        font-size: 1.2rem;
    }

    .btn-logout {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .btn-logout:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }

    .floating-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        animation: float 6s ease-in-out infinite;
    }

    .shape-1 {
        width: 100px;
        height: 100px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 10%;
        animation-delay: 2s;
    }

    .shape-3 {
        width: 80px;
        height: 80px;
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }

    /* Modern Stats Grid */
    .modern-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        padding: 40px;
    }

    .modern-stat-card {
        background: rgba(255,255,255,0.05);
        border-radius: 20px;
        padding: 0;
        position: relative;
        cursor: pointer;
        height: 200px;
        perspective: 1000px;
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.8s;
        transform-style: preserve-3d;
    }

    .modern-stat-card:hover .card-inner {
        transform: rotateY(180deg);
    }

    .card-front, .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-back {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: rotateY(180deg);
        border-radius: 20px;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon-wrapper.enterprise { background: rgba(255,107,107,0.2); color: #ff6b6b; }
    .stat-icon-wrapper.bike { background: rgba(78,205,196,0.2); color: #4ecdc4; }
    .stat-icon-wrapper.client { background: rgba(69,183,209,0.2); color: #45b7d1; }
    .stat-icon-wrapper.token { background: rgba(150,206,180,0.2); color: #96ceb4; }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .stat-trend.up { background: rgba(76,175,80,0.2); color: #4caf50; }
    .stat-trend.stable { background: rgba(255,193,7,0.2); color: #ffc107; }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin: 10px 0;
    }

    .stat-label {
        color: rgba(255,255,255,0.7);
        margin: 0;
    }

    .stat-footer {
        text-align: left;
    }

    .view-more {
        color: rgba(255,255,255,0.6);
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .modern-stat-card:hover .view-more {
        color: rgba(255,255,255,0.9);
    }

    .btn-action {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 10px 20px;
        border-radius: 20px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }

    .card-glow {
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
        border-radius: 22px;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modern-stat-card:hover .card-glow {
        opacity: 0.5;
        animation: glowRotate 2s linear infinite;
    }

    /* Quick Actions Modern */
    .quick-actions-modern {
        padding: 40px;
        background: rgba(255,255,255,0.02);
        margin: 0 40px 40px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 10px;
    }

    .section-title i {
        font-size: 2rem;
        color: #ff6b6b;
    }

    .section-title h2 {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .section-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
    }

    .actions-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }

    .action-card {
        background: rgba(255,255,255,0.05);
        border-radius: 15px;
        padding: 25px;
        text-decoration: none;
        color: white;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .action-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        color: white;
        text-decoration: none;
    }

    .action-icon {
        position: relative;
        width: 70px;
        height: 70px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .new-enterprise .action-icon { background: rgba(255,107,107,0.2); color: #ff6b6b; }
    .new-bike .action-icon { background: rgba(78,205,196,0.2); color: #4ecdc4; }
    .new-client .action-icon { background: rgba(69,183,209,0.2); color: #45b7d1; }
    .new-token .action-icon { background: rgba(150,206,180,0.2); color: #96ceb4; }

    .pulse-effect {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 15px;
        animation: pulse 2s infinite;
    }

    .new-enterprise .pulse-effect { background: rgba(255,107,107,0.3); }
    .new-bike .pulse-effect { background: rgba(78,205,196,0.3); }
    .new-client .pulse-effect { background: rgba(69,183,209,0.3); }
    .new-token .pulse-effect { background: rgba(150,206,180,0.3); }

    .action-content {
        flex: 1;
        text-align: left;
    }

    .action-content h3 {
        margin: 0 0 5px 0;
        font-weight: 700;
    }

    .action-content p {
        margin: 0;
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
    }

    .action-arrow {
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }

    .action-card:hover .action-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    .action-hover-effect {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.5s ease;
    }

    .action-card:hover .action-hover-effect {
        left: 100%;
    }

    /* System Status */
    .system-status {
        background: rgba(255,255,255,0.05);
        border-radius: 20px;
        padding: 30px;
        margin: 0 40px 40px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .status-header h3 {
        color: white;
        margin: 0;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #4caf50;
        font-weight: 600;
    }

    .pulse-dot {
        width: 12px;
        height: 12px;
        background: #4caf50;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: rgba(255,255,255,0.03);
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .status-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(78,205,196,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4ecdc4;
    }

    .status-info {
        display: flex;
        flex-direction: column;
    }

    .status-label {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
    }

    .status-value {
        color: #4caf50;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Animations */
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes glowRotate {
        0% { filter: hue-rotate(0deg); }
        100% { filter: hue-rotate(360deg); }
    }

    @keyframes pulse {
        0% { transform: scale(0.95); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
        100% { transform: scale(0.95); opacity: 1; }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-content {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .modern-stats-grid {
            grid-template-columns: 1fr;
            padding: 20px;
        }
        
        .quick-actions-modern {
            margin: 0 20px 20px;
            padding: 25px;
        }
        
        .system-status {
            margin: 0 20px 20px;
        }
        
        .actions-grid-modern {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <script>
    // Inicializar AOS
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    });

    // Efectos interactivos adicionales
    document.querySelectorAll('.modern-stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    </script>

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