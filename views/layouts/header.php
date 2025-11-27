<?php
// Verificar si es una página pública o administrativa
$isPublicPage = (isset($_GET['controller']) && $_GET['controller'] === 'api_public') || 
                (basename($_SERVER['PHP_SELF']) === 'api.php');

// Determinar la ruta correcta para los assets
$basePath = '';
if (isset($_SERVER['SCRIPT_NAME'])) {
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    if ($scriptPath !== '/') {
        $basePath = $scriptPath;
    }
}
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
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Estilos personalizados -->
    <link href="<?php echo $basePath; ?>/assets/css/styles.css" rel="stylesheet">
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
        <div class="container-fluid">