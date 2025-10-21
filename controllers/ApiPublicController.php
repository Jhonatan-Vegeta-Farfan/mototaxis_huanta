<?php
require_once __DIR__ . '/../models/Mototaxi.php';
require_once __DIR__ . '/../models/TokenApi.php';
require_once __DIR__ . '/../models/ClientApi.php';
require_once __DIR__ . '/../models/CountRequest.php';

class ApiPublicController {
    private $mototaxiModel;
    private $tokenApiModel;
    private $clientApiModel;
    private $countRequestModel;
    private $db;

    public function __construct($db = null) {
        $this->db = $db;
        $this->mototaxiModel = new Mototaxi($db);
        $this->tokenApiModel = new TokenApi($db);
        $this->clientApiModel = new ClientApi($db);
        $this->countRequestModel = new CountRequest($db);
    }

    // VISTA PÚBLICA DE BÚSQUEDA
    public function index() {
        include __DIR__ . '/../views/api_public/index.php';
    }

    // LISTAR MOTOTAXIS (JSON)
    public function listarMototaxis() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValido = $this->validarTokenRequest();
        if (!$tokenValido) return;
        
        try {
            $pagina = $_GET['pagina'] ?? 1;
            $porPagina = $_GET['por_pagina'] ?? 10;
            
            // Usar el método read del modelo Mototaxi
            $stmt = $this->mototaxiModel->read();
            $totalMototaxis = $stmt->rowCount();
            
            // Paginación manual
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
            
            echo json_encode([
                'success' => true,
                'message' => 'Lista de mototaxis obtenida exitosamente',
                'data' => $mototaxisPaginados,
                'paginacion' => [
                    'pagina_actual' => (int)$pagina,
                    'por_pagina' => (int)$porPagina,
                    'total_registros' => $totalMototaxis,
                    'total_paginas' => ceil($totalMototaxis / $porPagina)
                ]
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener mototaxis: ' . $e->getMessage()
            ]);
        }
    }

    // BUSCAR MOTOTAXI POR NÚMERO ASIGNADO (JSON)
    public function buscarMototaxi() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValido = $this->validarTokenRequest();
        if (!$tokenValido) return;
        
        try {
            $numero = $_GET['numero'] ?? '';
            
            if (empty($numero)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Parámetro "numero" requerido para la búsqueda'
                ]);
                return;
            }
            
            // Buscar por número asignado usando el modelo
            $query = "SELECT m.*, e.razon_social as empresa 
                     FROM mototaxis m 
                     LEFT JOIN empresas e ON m.id_empresa = e.id 
                     WHERE m.numero_asignado = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $numero);
            $stmt->execute();
            
            $mototaxi = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$mototaxi) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Mototaxi no encontrado'
                ]);
                return;
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Mototaxi encontrado exitosamente',
                'data' => $mototaxi
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage()
            ]);
        }
    }

    // BUSCAR MOTOTAXIS POR DNI (JSON)
    public function buscarPorDNI() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValido = $this->validarTokenRequest();
        if (!$tokenValido) return;
        
        try {
            $dni = $_GET['dni'] ?? '';
            
            if (empty($dni)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Parámetro "dni" requerido para la búsqueda'
                ]);
                return;
            }
            
            // Buscar por DNI usando el modelo
            $query = "SELECT m.*, e.razon_social as empresa 
                     FROM mototaxis m 
                     LEFT JOIN empresas e ON m.id_empresa = e.id 
                     WHERE m.dni LIKE ?";
            
            $stmt = $this->db->prepare($query);
            $dniParam = "%$dni%";
            $stmt->bindParam(1, $dniParam);
            $stmt->execute();
            
            $mototaxis = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'message' => 'Búsqueda por DNI completada',
                'data' => $mototaxis,
                'total' => count($mototaxis)
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage()
            ]);
        }
    }

    // VALIDAR TOKEN (JSON) - ENDPOINT PÚBLICO
    public function validarTokenEndpoint() {
        $this->configurarHeadersJSON();
        
        $tokenValido = $this->validarTokenRequest();
        if (!$tokenValido) return;
        
        // Si llegó aquí, el token es válido
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        $token = str_replace('Bearer ', '', $token);
        
        $tokenData = $this->tokenApiModel->getByToken($token);
        
        // Obtener información del cliente
        $clientData = null;
        if ($tokenData) {
            $clientQuery = "SELECT * FROM client_api WHERE id = ?";
            $clientStmt = $this->db->prepare($clientQuery);
            $clientStmt->bindParam(1, $tokenData['id_client_api']);
            $clientStmt->execute();
            
            if ($clientStmt->rowCount() > 0) {
                $clientRow = $clientStmt->fetch(PDO::FETCH_ASSOC);
                $clientData = [
                    'id' => $clientRow['id'],
                    'razon_social' => $clientRow['razon_social'],
                    'ruc' => $clientRow['ruc'],
                    'telefono' => $clientRow['telefono'],
                    'correo' => $clientRow['correo'],
                    'fecha_registro' => $clientRow['fecha_registro'],
                    'estado' => (bool)$clientRow['estado']
                ];
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => '✅ Token válido',
            'data' => [
                'token' => [
                    'id' => $tokenData['id'],
                    'token' => $tokenData['token'],
                    'fecha_registro' => $tokenData['fecha_registro'],
                    'estado' => (bool)$tokenData['estado']
                ],
                'cliente' => $clientData
            ]
        ], JSON_UNESCAPED_UNICODE);
    }

    // MÉTODOS PRIVADOS - CORREGIDOS
    private function configurarHeadersJSON() {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Authorization, Content-Type");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }
    }

    private function validarTokenRequest() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        $token = str_replace('Bearer ', '', $token);
        
        // Si no hay token en el header, intentar obtenerlo de los parámetros GET
        if (empty($token)) {
            $token = $_GET['token'] ?? '';
        }
        
        if (empty($token)) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => '❌ Token de acceso requerido'
            ]);
            return false;
        }
        
        $tokenData = $this->tokenApiModel->getByToken($token);
        
        if (!$tokenData) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => '❌ Token no existe'
            ]);
            return false;
        }
        
        if (!$tokenData['estado']) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => '❌ Token inactivo'
            ]);
            return false;
        }
        
        // Registrar request
        $this->registrarRequest($tokenData['id'], 'consulta_api');
        
        return true;
    }

    private function registrarRequest($tokenId, $tipo) {
        try {
            $query = "INSERT INTO count_request (id_token_api, tipo, fecha) VALUES (?, ?, CURDATE())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $tokenId);
            $stmt->bindParam(2, $tipo);
            $stmt->execute();
        } catch (Exception $e) {
            // Silenciar errores de registro para no afectar la respuesta principal
            error_log("Error registrando request: " . $e->getMessage());
        }
    }
}
?>