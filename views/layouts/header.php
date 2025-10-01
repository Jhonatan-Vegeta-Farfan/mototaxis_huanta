<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Mototaxis Huanta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a1a2e;
            --secondary-color: #16213e;
            --accent-color: #0f3460;
            --highlight-color: #e94560;
            --text-light: #f1f1f1;
            --text-muted: #b8b8b8;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Header moderno y urbano */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 0.8rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-light) !important;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .navbar-brand i {
            color: var(--highlight-color);
            margin-right: 10px;
            font-size: 1.8rem;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover i {
            transform: rotate(-15deg);
        }
        
        .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            padding: 0.8rem 1rem !important;
            margin: 0 2px;
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background-color: var(--highlight-color);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link:hover::before {
            width: 100%;
        }
        
        .nav-link i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px var(--highlight-color);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Logo styles */
        .logo-container {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }
        
        .logo-img {
            height: 40px;
            width: auto;
            border-radius: 6px;
            transition: transform 0.3s ease;
        }
        
        .logo-img:hover {
            transform: scale(1.05);
        }
        
        /* Efectos para dispositivos móviles */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: var(--secondary-color);
                border-radius: 0 0 10px 10px;
                padding: 15px;
                margin-top: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }
            
            .nav-link {
                margin: 5px 0;
            }
            
            .logo-container {
                margin-right: 100px;
            }
            
            .logo-img {
                height: 50px;
            }
        }
        
        /* Contenedor principal */
        .container.mt-4 {
            margin-top: 2rem !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <div class="logo-container">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGxPXJ3rHcxwz-FWTO6nqLKSvMwogHlgdZIQ&s" 
                     alt="Logo Mototaxis Huanta" class="logo-img">
            </div>
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-motorcycle"></i> Mototaxis Huanta
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
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
                        <a class="nav-link" href="index.php?controller=count_request&action=index">
                            <i class="fas fa-chart-bar"></i> Requests
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>