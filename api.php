<?php
// Evitar mostrar errores al cliente
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// API Público para consulta de mototaxis
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // Configuración
    require_once 'config/database.php';
    require_once 'controllers/ApiPublicController.php';

    // Conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Error de conexión a la base de datos');
    }

    // Crear controlador del API
    $apiController = new ApiPublicController($db);

    // Obtener acción
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    // Ejecutar acción correspondiente - CORREGIDO
    switch($action) {
        case 'validar':
            $apiController->validarTokenEndpoint();
            break;
        case 'listar':
            $apiController->listarMototaxis();
            break;
        case 'buscar':
            $apiController->buscarMototaxi();
            break;
        case 'buscarDni':
            $apiController->buscarPorDNI();
            break;
        case 'ver':
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            // Este método no está implementado, mostrar error
            http_response_code(501);
            echo json_encode([
                'success' => false,
                'message' => 'Endpoint no implementado',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            break;
        default:
            // Documentación del API
            $documentacion = [
                'success' => true,
                'message' => 'API Mototaxis Huanta - Endpoints disponibles',
                'timestamp' => date('Y-m-d H:i:s'),
                'version' => '1.0.0',
                'endpoints' => [
                    'validar' => [
                        'method' => 'GET',
                        'parameters' => [],
                        'description' => 'Valida token y devuelve información del cliente',
                        'headers' => ['Authorization: Bearer [TOKEN]'],
                        'example' => '/api.php?action=validar'
                    ],
                    'listar' => [
                        'method' => 'GET',
                        'parameters' => ['pagina', 'por_pagina'],
                        'description' => 'Lista mototaxis con paginación',
                        'headers' => ['Authorization: Bearer [TOKEN]'],
                        'example' => '/api.php?action=listar&pagina=1&por_pagina=10'
                    ],
                    'buscar' => [
                        'method' => 'GET',
                        'parameters' => ['numero'],
                        'description' => 'Busca mototaxi por número asignado',
                        'headers' => ['Authorization: Bearer [TOKEN]'],
                        'example' => '/api.php?action=buscar&numero=01'
                    ],
                    'buscarDni' => [
                        'method' => 'GET',
                        'parameters' => ['dni'],
                        'description' => 'Busca mototaxis por DNI del conductor',
                        'headers' => ['Authorization: Bearer [TOKEN]'],
                        'example' => '/api.php?action=buscarDni&dni=72358506'
                    ]
                ],
                'autenticacion' => [
                    'tipo' => 'Bearer Token',
                    'header' => 'Authorization: Bearer [token]',
                    'obtener_token' => 'Contactar con administración'
                ]
            ];
            
            echo json_encode($documentacion, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
    }

} catch (Exception $e) {
    // Manejar errores de manera limpia
    http_response_code(500);
    $errorResponse = [
        'success' => false,
        'message' => 'Error interno del servidor',
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // En desarrollo, puedes mostrar el error real
    if (ini_get('display_errors')) {
        $errorResponse['debug'] = $e->getMessage();
    }
    
    echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE);
    exit;
}
?>