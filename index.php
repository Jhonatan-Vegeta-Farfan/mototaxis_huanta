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
    
    <!-- Dashboard Interactivo - Tema Turquesa -->
    <div class="dashboard-turquesa">
        <!-- Header con efecto partículas -->
        <div class="hero-section">
            <div class="particles-container" id="particles-js"></div>
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="gradient-text">MUNICIPALIDAD PROVINCIAL DE HUANTA</span>
                    </h1>
                    <p class="hero-subtitle">Sistema Inteligente de Gestión de Mototaxis</p>
                </div>
                <div class="user-welcome">
                    <div class="user-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="user-info">
                        <span class="welcome-text">Bienvenido</span>
                        <strong class="username"><?php echo $_SESSION['username']; ?></strong>
                    </div>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
            <div class="hero-waves">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="currentColor"></path>
                    <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="currentColor"></path>
                    <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="currentColor"></path>
                </svg>
            </div>
        </div>

        <!-- Stats Cards con Animaciones -->
        <div class="stats-container">
            <div class="stats-grid">
                <!-- Tarjeta Empresas -->
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                                <div class="icon-pulse"></div>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-title">EMPRESAS</h3>
                                <div class="stat-number" data-count="<?php echo $totalEmpresas; ?>">0</div>
                                <p class="stat-desc">Empresas registradas</p>
                            </div>
                            <div class="stat-badge">ACTIVAS</div>
                        </div>
                        <div class="card-back">
                            <div class="action-btn">
                                <a href="index.php?controller=empresas&action=index" class="btn-turquesa">
                                    <span>Gestionar</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-glow"></div>
                </div>

                <!-- Tarjeta Mototaxis -->
                <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="stat-icon">
                                <i class="fas fa-motorcycle"></i>
                                <div class="icon-pulse"></div>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-title">MOTOTAXIS</h3>
                                <div class="stat-number" data-count="<?php echo $totalMototaxis; ?>">0</div>
                                <p class="stat-desc">Mototaxis activos</p>
                            </div>
                            <div class="stat-badge">ONLINE</div>
                        </div>
                        <div class="card-back">
                            <div class="action-btn">
                                <a href="index.php?controller=mototaxis&action=index" class="btn-turquesa">
                                    <span>Gestionar</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-glow"></div>
                </div>

                <!-- Tarjeta Clientes API -->
                <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                                <div class="icon-pulse"></div>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-title">CLIENTES API</h3>
                                <div class="stat-number" data-count="<?php echo $totalClientesApi; ?>">0</div>
                                <p class="stat-desc">Clientes conectados</p>
                            </div>
                            <div class="stat-badge">CONECTADOS</div>
                        </div>
                        <div class="card-back">
                            <div class="action-btn">
                                <a href="index.php?controller=client_api&action=index" class="btn-turquesa">
                                    <span>Gestionar</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-glow"></div>
                </div>

                <!-- Tarjeta Tokens API -->
                <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="stat-icon">
                                <i class="fas fa-key"></i>
                                <div class="icon-pulse"></div>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-title">TOKENS API</h3>
                                <div class="stat-number" data-count="<?php echo $totalTokens; ?>">0</div>
                                <p class="stat-desc">Tokens activos</p>
                            </div>
                            <div class="stat-badge">ACTIVOS</div>
                        </div>
                        <div class="card-back">
                            <div class="action-btn">
                                <a href="index.php?controller=tokens_api&action=index" class="btn-turquesa">
                                    <span>Gestionar</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-glow"></div>
                </div>

                <!-- Tarjeta Requests -->
                <div class="stat-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="stat-icon">
                                <i class="fas fa-chart-bar"></i>
                                <div class="icon-pulse"></div>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-title">REQUESTS</h3>
                                <div class="stat-number" data-count="<?php echo $totalRequests; ?>">0</div>
                                <p class="stat-desc">Solicitudes procesadas</p>
                            </div>
                            <div class="stat-badge">PROCESADAS</div>
                        </div>
                        <div class="card-back">
                            <div class="action-btn">
                                <a href="index.php?controller=count_request&action=index" class="btn-turquesa">
                                    <span>Gestionar</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-glow"></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Mejorado -->
        <div class="quick-actions-modern">
            <div class="section-header">
                <div class="section-title">
                    <div class="title-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h2>ACCIONES RÁPIDAS</h2>
                </div>
                <div class="section-subtitle">Gestiona tu sistema de forma eficiente</div>
            </div>
            
            <div class="actions-grid-modern">
                <a href="index.php?controller=empresas&action=create" class="action-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="action-content">
                        <div class="action-icon-wrapper">
                            <i class="fas fa-building"></i>
                            <div class="action-shine"></div>
                        </div>
                        <h3>Nueva Empresa</h3>
                        <p>Registrar nueva empresa de mototaxis</p>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="action-hover-effect"></div>
                </a>

                <a href="index.php?controller=mototaxis&action=create" class="action-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="action-content">
                        <div class="action-icon-wrapper">
                            <i class="fas fa-motorcycle"></i>
                            <div class="action-shine"></div>
                        </div>
                        <h3>Nuevo Mototaxi</h3>
                        <p>Agregar nuevo vehículo al sistema</p>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="action-hover-effect"></div>
                </a>

                <a href="index.php?controller=client_api&action=create" class="action-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="action-content">
                        <div class="action-icon-wrapper">
                            <i class="fas fa-user-plus"></i>
                            <div class="action-shine"></div>
                        </div>
                        <h3>Nuevo Cliente</h3>
                        <p>Crear nuevo cliente API</p>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="action-hover-effect"></div>
                </a>

                <a href="index.php?controller=tokens_api&action=create" class="action-card" data-aos="zoom-in" data-aos-delay="400">
                    <div class="action-content">
                        <div class="action-icon-wrapper">
                            <i class="fas fa-key"></i>
                            <div class="action-shine"></div>
                        </div>
                        <h3>Nuevo Token</h3>
                        <p>Generar nuevo token de acceso</p>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="action-hover-effect"></div>
                </a>
            </div>
        </div>

        <!-- Live Stats Mejorado -->
        <div class="live-stats-modern">
            <div class="live-stat-item" data-aos="fade-right">
                <div class="live-stat-icon">
                    <i class="fas fa-sync-alt fa-spin"></i>
                </div>
                <div class="live-stat-content">
                    <span class="live-stat-title">Sistema Activo</span>
                    <span class="live-stat-value">Operacional</span>
                </div>
            </div>
            
            <div class="live-stat-item" data-aos="fade-right" data-aos-delay="100">
                <div class="live-stat-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="live-stat-content">
                    <span class="live-stat-title">Mototaxis Online</span>
                    <span class="live-stat-value"><?php echo $totalMototaxis; ?> Activos</span>
                </div>
            </div>
            
            <div class="live-stat-item" data-aos="fade-right" data-aos-delay="200">
                <div class="live-stat-icon">
                    <i class="fas fa-signal"></i>
                </div>
                <div class="live-stat-content">
                    <span class="live-stat-title">API Status</span>
                    <span class="live-stat-value live-status-online">Operativa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para Interactividad -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
    // Inicializar AOS (Animate On Scroll)
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });

    // Inicializar Partículas
    particlesJS('particles-js', {
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: "#40E0D0" },
            shape: { type: "circle" },
            opacity: { value: 0.5, random: true },
            size: { value: 3, random: true },
            line_linked: {
                enable: true,
                distance: 150,
                color: "#40E0D0",
                opacity: 0.4,
                width: 1
            },
            move: {
                enable: true,
                speed: 2,
                direction: "none",
                random: true,
                straight: false,
                out_mode: "out",
                bounce: false
            }
        },
        interactivity: {
            detect_on: "canvas",
            events: {
                onhover: { enable: true, mode: "repulse" },
                onclick: { enable: true, mode: "push" },
                resize: true
            }
        }
    });

    // Animación de contadores
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-count');
            const duration = 2000;
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

    // Ejecutar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        animateCounters();
        
        // Efecto hover para tarjetas
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
    </script>

    <style>
    .dashboard-turquesa {
        background: linear-gradient(135deg, #0c1a27 0%, #0d2b3a 50%, #0f3a4a 100%);
        min-height: 100vh;
        padding: 0;
        position: relative;
        overflow-x: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
        padding: 60px 40px 120px;
        position: relative;
        overflow: hidden;
    }

    .particles-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 10;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .gradient-text {
        background: linear-gradient(45deg, #fff 30%, #e0f7fa 70%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 1.2rem;
        font-weight: 300;
    }

    .user-welcome {
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        padding: 15px 20px;
        border-radius: 15px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .user-avatar i {
        font-size: 1.5rem;
        color: #fff;
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .welcome-text {
        color: rgba(255,255,255,0.8);
        font-size: 0.9rem;
    }

    .username {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .logout-btn {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        margin-left: 15px;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }

    .hero-waves {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
    }

    .hero-waves svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 80px;
        transform: rotateY(180deg);
    }

    .hero-waves path {
        fill: #0c1a27;
    }

    /* Stats Container */
    .stats-container {
        padding: 40px;
        margin-top: -80px;
        position: relative;
        z-index: 20;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 5px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(64, 224, 208, 0.2);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        height: 160px;
    }

    .stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: rgba(64, 224, 208, 0.5);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .stat-card:hover .card-inner {
        transform: rotateY(180deg);
    }

    .card-front, .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 15px;
        padding: 25px;
        display: flex;
        align-items: center;
    }

    .card-front {
        background: rgba(255,255,255,0.05);
    }

    .card-back {
        background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
        transform: rotateY(180deg);
        justify-content: center;
        align-items: center;
    }

    .stat-icon {
        position: relative;
        font-size: 2.5rem;
        margin-right: 20px;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: rgba(64, 224, 208, 0.1);
        color: #40E0D0;
    }

    .icon-pulse {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 15px;
        background: rgba(64, 224, 208, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    .stat-content {
        flex: 1;
    }

    .stat-title {
        color: #40E0D0;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-number {
        color: #fff;
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .stat-desc {
        color: rgba(255,255,255,0.7);
        font-size: 0.85rem;
        margin: 0;
    }

    .stat-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(64, 224, 208, 0.2);
        color: #40E0D0;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .btn-turquesa {
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.2);
        color: #fff;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-turquesa:hover {
        background: rgba(255,255,255,0.3);
        color: #fff;
        text-decoration: none;
        transform: translateX(5px);
    }

    .btn-turquesa i {
        margin-left: 8px;
    }

    .card-glow {
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(64, 224, 208, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover .card-glow {
        opacity: 1;
    }

    /* Quick Actions Modern */
    .quick-actions-modern {
        padding: 0 40px 40px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }

    .title-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .title-icon i {
        font-size: 1.5rem;
        color: #fff;
    }

    .section-title h2 {
        color: #fff;
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .section-subtitle {
        color: rgba(255,255,255,0.7);
        font-size: 1.1rem;
    }

    .actions-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .action-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 30px;
        text-decoration: none;
        color: #fff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(64, 224, 208, 0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .action-card:hover {
        transform: translateY(-8px);
        border-color: rgba(64, 224, 208, 0.4);
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        color: #fff;
        text-decoration: none;
    }

    .action-content {
        position: relative;
        z-index: 2;
    }

    .action-icon-wrapper {
        position: relative;
        width: 70px;
        height: 70px;
        background: rgba(64, 224, 208, 0.1);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .action-icon-wrapper i {
        font-size: 2rem;
        color: #40E0D0;
    }

    .action-shine {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        opacity: 0.3;
        animation: shine 3s infinite;
    }

    @keyframes shine {
        0%, 100% { transform: scale(0.5); opacity: 0.3; }
        50% { transform: scale(1); opacity: 0.6; }
    }

    .action-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .action-card p {
        color: rgba(255,255,255,0.7);
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .action-arrow {
        width: 40px;
        height: 40px;
        background: rgba(64, 224, 208, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .action-card:hover .action-arrow {
        background: rgba(64, 224, 208, 0.4);
        transform: translateX(5px);
    }

    .action-hover-effect {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(64, 224, 208, 0.1), transparent);
        transition: left 0.5s;
    }

    .action-card:hover .action-hover-effect {
        left: 100%;
    }

    /* Live Stats Modern */
    .live-stats-modern {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        padding: 0 40px 40px;
    }

    .live-stat-item {
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        padding: 20px 25px;
        border-radius: 15px;
        border: 1px solid rgba(64, 224, 208, 0.1);
        min-width: 250px;
        transition: all 0.3s ease;
    }

    .live-stat-item:hover {
        border-color: rgba(64, 224, 208, 0.3);
        transform: translateY(-5px);
    }

    .live-stat-icon {
        width: 50px;
        height: 50px;
        background: rgba(64, 224, 208, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .live-stat-icon i {
        font-size: 1.3rem;
        color: #40E0D0;
    }

    .live-stat-content {
        display: flex;
        flex-direction: column;
    }

    .live-stat-title {
        color: rgba(255,255,255,0.8);
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .live-stat-value {
        color: #fff;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .live-status-online {
        color: #40E0D0 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-content {
            flex-direction: column;
            text-align: center;
        }
        
        .user-welcome {
            margin-top: 20px;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .actions-grid-modern {
            grid-template-columns: 1fr;
        }
        
        .stats-container,
        .quick-actions-modern,
        .live-stats-modern {
            padding: 20px;
        }
        
        .live-stats-modern {
            flex-direction: column;
            align-items: center;
        }
        
        .live-stat-item {
            width: 100%;
            max-width: 300px;
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