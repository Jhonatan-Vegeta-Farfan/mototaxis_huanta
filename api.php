<?php
// api.php - Punto de entrada para la API Pública
require_once 'config/database.php';
require_once 'controllers/ApiPublicController.php';

// Configurar conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear instancia del controlador
$apiController = new ApiPublicController($db);

// Obtener la acción desde la URL
$action = $_GET['action'] ?? 'index';

// Enrutar las solicitudes
switch ($action) {
    case 'listar':
        $apiController->listarMototaxis();
        break;
    case 'buscar':
        $apiController->buscarMototaxi();
        break;
    case 'buscar_dni':
        $apiController->buscarPorDNI();
        break;
    case 'validar_token':
        $apiController->validarTokenEndpoint();
        break;
    case 'index':
    default:
        $apiController->index();
        break;
}
?>