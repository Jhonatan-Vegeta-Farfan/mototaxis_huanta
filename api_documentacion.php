<?php
$pageTitle = 'Documentación API - Mototaxis Huanta';
include __DIR__ . '/views/layouts/header_public.php';
?>

<div class="container-fluid min-vh-100 py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="glass-card p-5">
                    <h1 class="display-4 text-primary text-center mb-5">
                        <i class="bi bi-file-text"></i> Documentación del API
                    </h1>
                    
                    <!-- Introducción -->
                    <div class="mb-5">
                        <h2 class="text-primary mb-3">Introducción</h2>
                        <p class="lead">
                            El API público de Mototaxis Huanta permite consultar información de mototaxis registrados 
                            en el sistema mediante tokens de autenticación.
                        </p>
                    </div>

                    <!-- Autenticación -->
                    <div class="mb-5">
                        <h2 class="text-primary mb-3">Autenticación</h2>
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-key"></i> Token de Acceso
                                </h5>
                                <p class="card-text">
                                    Todas las solicitudes requieren un token de acceso válido en el parámetro <code>token</code>.
                                </p>
                                <pre class="bg-light text-dark p-3 rounded">GET /api.php?action=validar&token=TU_TOKEN_DE_ACCESO</pre>
                            </div>
                        </div>
                    </div>

                    <!-- Endpoints -->
                    <div class="mb-5">
                        <h2 class="text-primary mb-3">Endpoints Disponibles</h2>
                        
                        <!-- Validar Token -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-shield-check"></i> Validar Token
                                </h5>
                            </div>
                            <div class="card-body">
                                <p><strong>URL:</strong> <code>/api.php?action=validar</code></p>
                                <p><strong>Método:</strong> GET</p>
                                <p><strong>Parámetros:</strong></p>
                                <ul>
                                    <li><code>token</code> (requerido) - Token de acceso</li>
                                </ul>
                                <p><strong>Ejemplo:</strong></p>
                                <pre class="bg-light p-3 rounded">GET /api.php?action=validar&token=abc123def456-TRA-1</pre>
                            </div>
                        </div>

                        <!-- Buscar Mototaxi -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-search"></i> Buscar Mototaxi
                                </h5>
                            </div>
                            <div class="card-body">
                                <p><strong>URL:</strong> <code>/api.php?action=buscar</code></p>
                                <p><strong>Método:</strong> GET</p>
                                <p><strong>Parámetros:</strong></p>
                                <ul>
                                    <li><code>token</code> (requerido) - Token de acceso</li>
                                    <li><code>numero</code> - Número asignado del mototaxi</li>
                                    <li><code>nombre</code> - Nombre del conductor</li>
                                    <li><code>dni</code> - DNI del conductor</li>
                                    <li><code>placa</code> - Placa de rodaje</li>
                                </ul>
                                <p><strong>Ejemplos:</strong></p>
                                <pre class="bg-light p-3 rounded">
// Por número
GET /api.php?action=buscar&token=TU_TOKEN&numero=01

// Por nombre
GET /api.php?action=buscar&token=TU_TOKEN&nombre=Juan

// Por DNI
GET /api.php?action=buscar&token=TU_TOKEN&dni=72358506

// Por placa
GET /api.php?action=buscar&token=TU_TOKEN&placa=4250-4I</pre>
                            </div>
                        </div>

                        <!-- Listar Mototaxis -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="bi bi-list-ul"></i> Listar Mototaxis
                                </h5>
                            </div>
                            <div class="card-body">
                                <p><strong>URL:</strong> <code>/api.php?action=listar</code></p>
                                <p><strong>Método:</strong> GET</p>
                                <p><strong>Parámetros:</strong></p>
                                <ul>
                                    <li><code>token</code> (requerido) - Token de acceso</li>
                                    <li><code>pagina</code> (opcional) - Número de página (default: 1)</li>
                                    <li><code>por_pagina</code> (opcional) - Resultados por página (default: 10)</li>
                                </ul>
                                <p><strong>Ejemplo:</strong></p>
                                <pre class="bg-light p-3 rounded">GET /api.php?action=listar&token=TU_TOKEN&pagina=1&por_pagina=20</pre>
                            </div>
                        </div>
                    </div>

                    <!-- Respuestas -->
                    <div class="mb-5">
                        <h2 class="text-primary mb-3">Formato de Respuestas</h2>
                        
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-code-slash"></i> Estructura JSON
                                </h5>
                            </div>
                            <div class="card-body">
                                <p>Todas las respuestas siguen el siguiente formato:</p>
                                <pre class="bg-dark text-light p-3 rounded">
{
    "success": true,
    "message": "Mensaje descriptivo",
    "timestamp": "2024-01-15 10:30:00",
    "version": "1.0.0",
    "data": {
        // Datos específicos del endpoint
    }
}</pre>
                                
                                <p class="mt-3"><strong>Ejemplo de respuesta exitosa:</strong></p>
                                <pre class="bg-dark text-light p-3 rounded">
{
    "success": true,
    "message": "Mototaxi encontrado",
    "timestamp": "2024-01-15 10:35:00",
    "version": "1.0.0",
    "data": {
        "mototaxi": {
            "id": 1,
            "numero_asignado": "01",
            "nombre_completo": "LAURA CCORAHUA SULCA",
            "dni": "72358506",
            "placa_rodaje": "4250-4I",
            "empresa": "E.T. HUANTA ETH"
        }
    }
}</pre>

                                <p class="mt-3"><strong>Ejemplo de error:</strong></p>
                                <pre class="bg-dark text-light p-3 rounded">
{
    "success": false,
    "message": "Token inválido o expirado",
    "timestamp": "2024-01-15 10:40:00",
    "version": "1.0.0"
}</pre>
                            </div>
                        </div>
                    </div>

                    <!-- Códigos de Estado -->
                    <div class="mb-5">
                        <h2 class="text-primary mb-3">Códigos de Estado HTTP</h2>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>200</code></td>
                                        <td>Solicitud exitosa</td>
                                    </tr>
                                    <tr>
                                        <td><code>400</code></td>
                                        <td>Solicitud incorrecta (parámetros faltantes)</td>
                                    </tr>
                                    <tr>
                                        <td><code>401</code></td>
                                        <td>Token inválido o no autorizado</td>
                                    </tr>
                                    <tr>
                                        <td><code>404</code></td>
                                        <td>Recurso no encontrado</td>
                                    </tr>
                                    <tr>
                                        <td><code>500</code></td>
                                        <td>Error interno del servidor</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Ejemplos de Uso -->
                    <div class="mb-5">
                        <h2 class="text-primary mb-3">Ejemplos de Uso</h2>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">
                                            <i class="bi bi-browser-chrome"></i> JavaScript
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <pre class="bg-light p-3 rounded" style="font-size: 0.8rem;">
// Buscar mototaxi por número
async function buscarMototaxi(token, numero) {
    const response = await fetch(
        `/api.php?action=buscar&token=${token}&numero=${numero}`
    );
    const data = await response.json();
    
    if (data.success) {
        console.log('Mototaxi encontrado:', data.data.mototaxi);
    } else {
        console.error('Error:', data.message);
    }
}</pre>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">
                                            <i class="bi bi-terminal"></i> cURL
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <pre class="bg-light p-3 rounded" style="font-size: 0.8rem;">
# Validar token
curl "https://tudominio.com/api.php?action=validar&token=TU_TOKEN"

# Buscar por número
curl "https://tudominio.com/api.php?action=buscar&token=TU_TOKEN&numero=01"

# Listar todos
curl "https://tudominio.com/api.php?action=listar&token=TU_TOKEN"</pre>
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

<?php include __DIR__ . '/views/layouts/footer_public.php'; ?>