<?php
// Configuración inicial
session_start();

// Verificar si el usuario está logueado (solo para admin)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Permitir acceso público solo al API público
    $controller = isset($_GET['controller']) ? $_GET['controller'] : '';
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    if ($controller !== 'api_public') {
        header("Location: login.php");
        exit;
    }
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
require_once 'controllers/ApiPublicController.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener controlador y acción
$controller = isset($_GET['controller']) ? $_GET['controller'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Si no hay controlador específico, mostrar dashboard (solo para admin)
if (empty($controller)) {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php");
        exit;
    }
    
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
    
    // HTML completo del dashboard
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistema de Gestión de Mototaxis - Municipalidad Provincial de Huanta</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        .dashboard-corporativo {
            background: #f8f9fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Corporativo */
        .header-corporativo {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 20px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            border-bottom: 4px solid #2a5298;
        }

        .brand-section {
            display: flex;
            align-items: center;
        }

        .municipal-logo {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .municipal-logo i {
            font-size: 2rem;
            color: #fff;
        }

        .municipal-title {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .system-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            margin: 5px 0 0 0;
            font-weight: 300;
        }

        .user-section {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 20px;
        }

        .user-info-corporate {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.1);
            padding: 12px 20px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .user-avatar-corp {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .user-avatar-corp i {
            font-size: 1.2rem;
            color: #fff;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-welcome-corp {
            color: rgba(255,255,255,0.8);
            font-size: 0.8rem;
        }

        .username-corp {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
        }

        .user-role {
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
        }

        .logout-btn-corp {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }

        .logout-btn-corp:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
            text-decoration: none;
        }

        /* Stats Corporativas */
        .stats-corporativas {
            padding: 40px 0;
            background: #fff;
        }

        .section-header-corp {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title-corp {
            color: #1e3c72;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-card-corp {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
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
            margin-bottom: 20px;
        }

        .card-icon-corp {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
        }

        .card-icon-corp.bg-primary { background: linear-gradient(135deg, #007bff, #0056b3); }
        .card-icon-corp.bg-success { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .card-icon-corp.bg-info { background: linear-gradient(135deg, #17a2b8, #138496); }
        .card-icon-corp.bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800); }
        .card-icon-corp.bg-secondary { background: linear-gradient(135deg, #6c757d, #545b62); }

        .card-badge {
            background: #e9ecef;
            color: #495057;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .card-body-corp {
            flex: 1;
            margin-bottom: 20px;
        }

        .card-title-corp {
            color: #495057;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .card-number-corp {
            color: #1e3c72;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 8px;
            line-height: 1;
        }

        .card-desc-corp {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }

        .card-footer-corp {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
        }

        .btn-corp-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1e3c72;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-corp-primary:hover {
            background: #2a5298;
            color: #fff;
            text-decoration: none;
            transform: translateX(3px);
        }

        /* Acciones Corporativas */
        .acciones-corporativas {
            padding: 40px 0;
            background: #f8f9fa;
        }

        .action-card-corp {
            display: flex;
            align-items: center;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            text-decoration: none;
            color: #495057;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            height: 100%;
        }

        .action-card-corp:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: #495057;
            text-decoration: none;
            border-color: #1e3c72;
        }

        .action-icon-corp {
            width: 50px;
            height: 50px;
            background: #e9ecef;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #1e3c72;
            font-size: 1.3rem;
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
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .action-content-corp p {
            color: #6c757d;
            font-size: 0.85rem;
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

        /* Estado del Sistema */
        .estado-sistema {
            padding: 40px 0;
            background: #fff;
        }

        .status-card-corp {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .status-card-corp:hover {
            border-color: #1e3c72;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }

        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.3rem;
        }

        .status-icon.online {
            background: #d4edda;
            color: #28a745;
        }

        .status-content h5 {
            color: #495057;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .status-text {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-text.online {
            color: #28a745;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-corporativo {
                padding: 15px 0;
            }
            
            .brand-section {
                justify-content: center;
                text-align: center;
                margin-bottom: 15px;
            }
            
            .user-section {
                justify-content: center;
            }
            
            .municipal-title {
                font-size: 1.2rem;
            }
            
            .section-title-corp {
                font-size: 1.5rem;
            }
            
            .card-number-corp {
                font-size: 2rem;
            }
            
            .action-card-corp {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .user-info-corporate {
                flex-direction: column;
                text-align: center;
                padding: 15px;
            }
            
            .user-avatar-corp {
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            .logout-btn-corp span {
                display: none;
            }
        }
        </style>
    </head>
    <body>
        <!-- Dashboard Corporativo -->
        <div class="dashboard-corporativo">
            <!-- Header Corporativo -->
            <div class="header-corporativo">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="brand-section">
                                <div class="municipal-logo">
                                    <i class="fas fa-landmark"></i>
                                </div>
                                <div class="brand-text">
                                    <h1 class="municipal-title">MUNICIPALIDAD PROVINCIAL DE HUANTA</h1>
                                    <p class="system-subtitle">Sistema de Gestión de Mototaxis</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="user-section">
                                <div class="user-info-corporate">
                                    <div class="user-avatar-corp">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="user-details">
                                        <span class="user-welcome-corp">Bienvenido</span>
                                        <strong class="username-corp"><?php echo $_SESSION['username']; ?></strong>
                                        <span class="user-role">Administrador del Sistema</span>
                                    </div>
                                </div>
                                <a href="logout.php" class="logout-btn-corp">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Cerrar Sesión</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards Corporativas -->
            <div class="stats-corporativas">
                <div class="container-fluid">
                    <div class="section-header-corp">
                        <h2 class="section-title-corp">PANEL DE CONTROL</h2>
                    </div>
                    
                    <div class="row g-4">
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
            <div class="acciones-corporativas">
                <div class="container-fluid">
                    <div class="section-header-corp">
                        <h2 class="section-title-corp">ACCIONES RÁPIDAS</h2>
                    </div>
                    
                    <div class="row g-4">
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
            <div class="estado-sistema">
                <div class="container-fluid">
                    <div class="row g-4">
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

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        // Inicializar AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

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

        // Ejecutar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            animateCounters();
            
            // Efectos hover para tarjetas
            const statCards = document.querySelectorAll('.stat-card-corp');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
        </script>
    </body>
    </html>
    <?php

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
        case 'api_public':
            // Acceso público permitido
            $controllerObj = new ApiPublicController($db);
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