<?php
// Verificar si es una página pública o administrativa
$isPublicPage = (isset($_GET['controller']) && $_GET['controller'] === 'api_public') || 
                (basename($_SERVER['PHP_SELF']) === 'api.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Sistema Mototaxis Huanta'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <link href="../assets/css/styles.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
            --accent-color: #0f3a4a;
            --highlight-color: #2a5298;
            --highlight-hover: #1e3c72;
            --text-light: #ffffff;
            --text-muted: #e9ecef;
            --gradient-primary: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --gradient-secondary: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        
        /* Header Corporativo */
        .navbar {
            background: var(--gradient-primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        
        .navbar-brand-section {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }
        
        .logo-img {
            height: 45px;
            width: auto;
            border-radius: 6px;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .logo-img:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.4);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--text-light) !important;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.5rem;
        }
        
        .navbar-nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .nav-item {
            margin: 0 2px;
        }
        
        .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            padding: 0.6rem 1rem !important;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }
        
        .nav-link i {
            margin-right: 8px;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }
        
        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.3rem 0.6rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.25);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Responsive Design */
        @media (max-width: 1199px) {
            .nav-link {
                padding: 0.5rem 0.8rem !important;
                font-size: 0.9rem;
            }
            
            .navbar-brand {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 991px) {
            .navbar-collapse {
                background: var(--gradient-primary);
                border-radius: 0 0 8px 8px;
                padding: 1rem;
                margin-top: 0.5rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
            
            .nav-item {
                margin: 2px 0;
                width: 100%;
            }
            
            .nav-link {
                padding: 0.8rem 1rem !important;
                margin: 2px 0;
                justify-content: flex-start;
            }
            
            .navbar-nav {
                width: 100%;
            }
            
            body {
                padding-top: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .logo-img {
                height: 40px;
            }
            
            .nav-link {
                font-size: 0.9rem;
            }
            
            .nav-link i {
                font-size: 0.9rem;
                width: 18px;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand span {
                display: none;
            }
            
            .navbar-brand i {
                margin-right: 0;
                font-size: 1.3rem;
            }
            
            .logo-container {
                margin-right: 10px;
            }
            
            .logo-img {
                height: 35px;
            }
            
            .navbar-brand-section {
                flex: 1;
            }
        }
        
        @media (max-width: 400px) {
            .container {
                padding: 0 10px;
            }
            
            .nav-link {
                padding: 0.7rem 0.8rem !important;
                font-size: 0.85rem;
            }
            
            .nav-link i {
                margin-right: 6px;
                font-size: 0.85rem;
            }
        }
        
        /* Asegurar que el contenido no se solape con el header fijo */
        .main-content {
            min-height: calc(100vh - 80px);
        }
    </style>
</head>
<body>
    <?php if (!$isPublicPage): ?>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <div class="navbar-container">
                <div class="navbar-brand-section">
                    <div class="logo-container">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGxPXJ3rHcxwz-FWTO6nqLKSvMwogHlgdZIQ&s" 
                             alt="Logo Municipalidad Huanta" class="logo-img">
                    </div>
                    <a class="navbar-brand" href="index.php">
                        <i class="fas fa-motorcycle"></i>
                        <span>Mototaxis Huanta</span>
                    </a>
                </div>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=empresas&action=index">
                                <i class="fas fa-building"></i> Empresas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=mototaxis&action=index">
                                <i class="fas fa-motorcycle"></i> Mototaxis
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=client_api&action=index">
                                <i class="fas fa-users"></i> Clientes API
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=tokens_api&action=index">
                                <i class="fas fa-key"></i> Tokens API
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=usuarios&action=index">
                                <i class="fas fa-user-shield"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=count_request&action=index">
                                <i class="fas fa-chart-bar"></i> Requests
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <div class="main-content">