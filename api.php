<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once 'config/database.php';
require_once 'models/Mototaxi.php';
require_once 'models/TokenApi.php';
require_once 'models/ClientApi.php';
require_once 'models/CountRequest.php';

class ApiPublicController {
    private $mototaxiModel;
    private $tokenApiModel;
    private $clientApiModel;
    private $countRequestModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->mototaxiModel = new Mototaxi($this->db);
        $this->tokenApiModel = new TokenApi($this->db);
        $this->clientApiModel = new ClientApi($this->db);
        $this->countRequestModel = new CountRequest($this->db);
    }

    private function validarToken() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        $token = str_replace('Bearer ', '', $token);
        
        if (empty($token)) {
            $token = $_GET['token'] ?? '';
        }
        
        if (empty($token)) {
            $this->jsonResponse(false, '❌ Token de acceso requerido', null, 401);
            return false;
        }
        
        $tokenData = $this->tokenApiModel->getByToken($token);
        
        if (!$tokenData) {
            $this->jsonResponse(false, '❌ Token no existe o es inválido', null, 401);
            return false;
        }
        
        if (!$tokenData['estado']) {
            $this->jsonResponse(false, '❌ Token inactivo', null, 401);
            return false;
        }
        
        // Registrar request
        $this->registrarRequest($tokenData['id'], 'consulta_api');
        
        return $tokenData;
    }

    private function registrarRequest($tokenId, $tipo) {
        try {
            $query = "INSERT INTO count_request (id_token_api, tipo, fecha) VALUES (?, ?, CURDATE())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $tokenId);
            $stmt->bindParam(2, $tipo);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error registrando request: " . $e->getMessage());
        }
    }

    private function jsonResponse($success, $message, $data = null, $httpCode = 200) {
        http_response_code($httpCode);
        $response = ['success' => $success, 'message' => $message];
        if ($data !== null) $response['data'] = $data;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function listarMototaxis() {
        $tokenValido = $this->validarToken();
        if (!$tokenValido) return;
        
        try {
            $pagina = max(1, intval($_GET['pagina'] ?? 1));
            $porPagina = min(50, max(1, intval($_GET['por_pagina'] ?? 10)));
            
            $stmt = $this->mototaxiModel->read();
            $totalMototaxis = $stmt->rowCount();
            
            $offset = ($pagina - 1) * $porPagina;
            $mototaxisPaginados = [];
            $contador = 0;
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($contador >= $offset && $contador < ($offset + $porPagina)) {
                    $mototaxisPaginados[] = $row;
                }
                $contador++;
                if ($contador >= ($offset + $porPagina)) break;
            }
            
            $this->jsonResponse(true, 'Lista de mototaxis obtenida exitosamente', [
                'data' => $mototaxisPaginados,
                'paginacion' => [
                    'pagina_actual' => $pagina,
                    'por_pagina' => $porPagina,
                    'total_registros' => $totalMototaxis,
                    'total_paginas' => ceil($totalMototaxis / $porPagina)
                ]
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Error al obtener mototaxis: ' . $e->getMessage(), null, 500);
        }
    }

    public function buscarMototaxi() {
        $tokenValido = $this->validarToken();
        if (!$tokenValido) return;
        
        try {
            $numero = $_GET['numero'] ?? '';
            
            if (empty($numero)) {
                $this->jsonResponse(false, 'Parámetro "numero" requerido para la búsqueda', null, 400);
                return;
            }
            
            $query = "SELECT m.*, e.razon_social as empresa 
                     FROM mototaxis m 
                     LEFT JOIN empresas e ON m.id_empresa = e.id 
                     WHERE m.numero_asignado = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $numero);
            $stmt->execute();
            
            $mototaxi = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$mototaxi) {
                $this->jsonResponse(false, 'Mototaxi no encontrado', null, 404);
                return;
            }
            
            $this->jsonResponse(true, 'Mototaxi encontrado exitosamente', $mototaxi);
            
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Error en la búsqueda: ' . $e->getMessage(), null, 500);
        }
    }

    public function buscarPorDNI() {
        $tokenValido = $this->validarToken();
        if (!$tokenValido) return;
        
        try {
            $dni = $_GET['dni'] ?? '';
            
            if (empty($dni)) {
                $this->jsonResponse(false, 'Parámetro "dni" requerido para la búsqueda', null, 400);
                return;
            }
            
            $query = "SELECT m.*, e.razon_social as empresa 
                     FROM mototaxis m 
                     LEFT JOIN empresas e ON m.id_empresa = e.id 
                     WHERE m.dni LIKE ?";
            
            $stmt = $this->db->prepare($query);
            $dniParam = "%$dni%";
            $stmt->bindParam(1, $dniParam);
            $stmt->execute();
            
            $mototaxis = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->jsonResponse(true, 'Búsqueda por DNI completada', [
                'data' => $mototaxis,
                'total' => count($mototaxis)
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Error en la búsqueda: ' . $e->getMessage(), null, 500);
        }
    }

    public function validarTokenEndpoint() {
        $tokenValido = $this->validarToken();
        if (!$tokenValido) return;
        
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        $token = str_replace('Bearer ', '', $token);
        
        $clientQuery = "SELECT c.* FROM client_api c 
                       JOIN tokens_api t ON c.id = t.id_client_api 
                       WHERE t.token = ? AND t.estado = 1 AND c.estado = 1";
        $clientStmt = $this->db->prepare($clientQuery);
        $clientStmt->bindParam(1, $token);
        $clientStmt->execute();
        
        $clientData = $clientStmt->fetch(PDO::FETCH_ASSOC);
        
        $this->jsonResponse(true, '✅ Token válido', [
            'token' => [
                'token' => $token,
                'estado' => true
            ],
            'cliente' => $clientData ? [
                'id' => $clientData['id'],
                'razon_social' => $clientData['razon_social'],
                'ruc' => $clientData['ruc'],
                'telefono' => $clientData['telefono'],
                'correo' => $clientData['correo'],
                'fecha_registro' => $clientData['fecha_registro']
            ] : null
        ]);
    }
}

// Procesar la solicitud API
$api = new ApiPublicController();
$action = $_GET['action'] ?? 'listarMototaxis';

switch($action) {
    case 'listar':
    case 'listarMototaxis':
        $api->listarMototaxis();
        break;
    case 'buscar':
    case 'buscarMototaxi':
        $api->buscarMototaxi();
        break;
    case 'buscarPorDNI':
        $api->buscarPorDNI();
        break;
    case 'validarToken':
        $api->validarTokenEndpoint();
        break;
    default:
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint no encontrado'
        ]);
}
?>