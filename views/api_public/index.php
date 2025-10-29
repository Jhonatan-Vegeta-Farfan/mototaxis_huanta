<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Mototaxis Huanta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
            --accent-color: #0f3a4a;
        }
        
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .api-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .endpoint-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #40e0d0;
            transition: all 0.3s ease;
        }
        
        .endpoint-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .method-get {
            border-left-color: #28a745;
        }
        
        .method-post {
            border-left-color: #007bff;
        }
        
        .method-put {
            border-left-color: #ffc107;
        }
        
        .method-delete {
            border-left-color: #dc3545;
        }
        
        .code-block {
            background: #1a1a1a;
            color: #40e0d0;
            padding: 1rem;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="fas fa-motorcycle me-3"></i>
                        API Mototaxis Huanta
                    </h1>
                    <p class="lead">Documentación y acceso a la API pública de consulta de mototaxis</p>
                </div>
                
                <div class="api-container">
                    <h2 class="mb-4"><i class="fas fa-book me-2"></i>Documentación de la API</h2>
                    
                    <!-- Endpoint: Listar Mototaxis -->
                    <div class="endpoint-card method-get">
                        <h4 class="text-warning">
                            <span class="badge bg-success me-2">GET</span>
                            /api.php?action=listar
                        </h4>
                        <p class="mb-3">Obtiene la lista paginada de todos los mototaxis registrados.</p>
                        
                        <h6>Parámetros:</h6>
                        <ul>
                            <li><code>token</code> (requerido) - Token de autenticación</li>
                            <li><code>pagina</code> (opcional) - Número de página (default: 1)</li>
                            <li><code>por_pagina</code> (opcional) - Registros por página (default: 10)</li>
                        </ul>
                        
                        <h6>Ejemplo de uso:</h6>
                        <div class="code-block">
                            GET /api.php?action=listar&token=tu_token&pagina=1&por_pagina=10
                        </div>
                    </div>
                    
                    <!-- Endpoint: Buscar por Número -->
                    <div class="endpoint-card method-get">
                        <h4 class="text-warning">
                            <span class="badge bg-success me-2">GET</span>
                            /api.php?action=buscar
                        </h4>
                        <p class="mb-3">Busca un mototaxi específico por su número asignado.</p>
                        
                        <h6>Parámetros:</h6>
                        <ul>
                            <li><code>token</code> (requerido) - Token de autenticación</li>
                            <li><code>numero</code> (requerido) - Número asignado del mototaxi</li>
                        </ul>
                        
                        <h6>Ejemplo de uso:</h6>
                        <div class="code-block">
                            GET /api.php?action=buscar&token=tu_token&numero=01
                        </div>
                    </div>
                    
                    <!-- Endpoint: Buscar por DNI -->
                    <div class="endpoint-card method-get">
                        <h4 class="text-warning">
                            <span class="badge bg-success me-2">GET</span>
                            /api.php?action=buscar-dni
                        </h4>
                        <p class="mb-3">Busca mototaxis por DNI del conductor.</p>
                        
                        <h6>Parámetros:</h6>
                        <ul>
                            <li><code>token</code> (requerido) - Token de autenticación</li>
                            <li><code>dni</code> (requerido) - DNI del conductor</li>
                        </ul>
                        
                        <h6>Ejemplo de uso:</h6>
                        <div class="code-block">
                            GET /api.php?action=buscar-dni&token=tu_token&dni=72358506
                        </div>
                    </div>
                    
                    <!-- Endpoint: Validar Token -->
                    <div class="endpoint-card method-get">
                        <h4 class="text-warning">
                            <span class="badge bg-success me-2">GET</span>
                            /api.php?action=validar-token
                        </h4>
                        <p class="mb-3">Valida un token de autenticación y devuelve información del cliente.</p>
                        
                        <h6>Parámetros:</h6>
                        <ul>
                            <li><code>token</code> (requerido) - Token a validar</li>
                        </ul>
                        
                        <h6>Ejemplo de uso:</h6>
                        <div class="code-block">
                            GET /api.php?action=validar-token&token=tu_token
                        </div>
                    </div>
                    
                    <!-- Autenticación -->
                    <div class="mt-5 p-4 bg-dark rounded">
                        <h4 class="text-info mb-3">
                            <i class="fas fa-key me-2"></i>Autenticación
                        </h4>
                        <p>Todas las solicitudes a la API requieren autenticación mediante token. El token debe incluirse en el header <code>Authorization</code> o como parámetro GET.</p>
                        
                        <h6>Ejemplo con Header:</h6>
                        <div class="code-block">
                            Authorization: Bearer tu_token_aqui
                        </div>
                        
                        <h6>Ejemplo con parámetro GET:</h6>
                        <div class="code-block">
                            /api.php?action=listar&token=tu_token_aqui
                        </div>
                    </div>
                    
                    <!-- Respuestas -->
                    <div class="mt-4 p-4 bg-dark rounded">
                        <h4 class="text-info mb-3">
                            <i class="fas fa-code me-2"></i>Formato de Respuesta
                        </h4>
                        <p>Todas las respuestas siguen el formato JSON estándar:</p>
                        
                        <h6>Éxito:</h6>
                        <div class="code-block">
                            {
                                "success": true,
                                "message": "Mensaje de éxito",
                                "data": { ... }
                            }
                        </div>
                        
                        <h6>Error:</h6>
                        <div class="code-block">
                            {
                                "success": false,
                                "message": "Mensaje de error"
                            }
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-5">
                    <p class="text-muted">
                        Sistema de Gestión de Mototaxis Huanta &copy; 2025
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>