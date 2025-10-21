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

// Limpiar cualquier salida anterior
ob_clean();

try {
    // Configuración - usar rutas absolutas
    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/controllers/ApiPublicController.php';

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

    // Ejecutar acción correspondiente
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
                        'parameters' => ['token'],
                        'description' => 'Valida token y devuelve información del cliente',
                        'example' => '/api.php?action=validar&token=TU_TOKEN'
                    ],
                    'listar' => [
                        'method' => 'GET',
                        'parameters' => ['token', 'pagina', 'por_pagina'],
                        'description' => 'Lista mototaxis con paginación',
                        'example' => '/api.php?action=listar&token=TU_TOKEN&pagina=1&por_pagina=10'
                    ],
                    'buscar' => [
                        'method' => 'GET',
                        'parameters' => ['token', 'numero'],
                        'description' => 'Busca mototaxi por número asignado',
                        'example' => '/api.php?action=buscar&token=TU_TOKEN&numero=01'
                    ],
                    'buscarDni' => [
                        'method' => 'GET',
                        'parameters' => ['token', 'dni'],
                        'description' => 'Busca mototaxis por DNI del conductor',
                        'example' => '/api.php?action=buscarDni&token=TU_TOKEN&dni=72358506'
                    ]
                ]
            ];
            
            echo json_encode($documentacion, JSON_UNESCAPED_UNICODE);
            exit; // IMPORTANTE: Salir después de enviar JSON
            break;
    }

} catch (Exception $e) {
    // Limpiar buffer antes del error
    if (ob_get_length()) ob_clean();
    
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

// Asegurar que no haya salida después
exit;
?>