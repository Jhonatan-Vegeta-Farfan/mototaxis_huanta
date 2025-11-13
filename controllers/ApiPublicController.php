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

    // VISTA PÚBLICA DE DOCUMENTACIÓN
    public function index() {
        // Configurar headers para evitar caché
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        
        include __DIR__ . '/../views/api_public/index.php';
    }

    // LISTAR MOTOTAXIS (JSON) - MEJORADO
    public function listarMototaxis() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValidation = $this->validarTokenRequest();
        if (!$tokenValidation['valid']) {
            echo json_encode([
                'success' => false,
                'message' => $tokenValidation['message'],
                'error_code' => 'TOKEN_INVALID'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        try {
            $pagina = max(1, intval($_GET['pagina'] ?? 1));
            $porPagina = min(100, max(1, intval($_GET['por_pagina'] ?? 10)));
            
            // Calcular offset
            $offset = ($pagina - 1) * $porPagina;
            
            // Consulta optimizada con paginación
            $query = "SELECT SQL_CALC_FOUND_ROWS m.*, e.razon_social as empresa, 
                             e.ruc as ruc_empresa, e.representante_legal as representante_empresa
                     FROM mototaxis m 
                     LEFT JOIN empresas e ON m.id_empresa = e.id 
                     ORDER BY m.id DESC
                     LIMIT :offset, :limit";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
            $stmt->execute();
            
            $mototaxis = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mototaxis[] = $this->formatearDatosMototaxi($row);
            }
            
            // Obtener total de registros
            $totalStmt = $this->db->query("SELECT FOUND_ROWS() as total");
            $totalRegistros = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo json_encode([
                'success' => true,
                'message' => 'Lista de mototaxis obtenida exitosamente',
                'data' => $mototaxis,
                'paginacion' => [
                    'pagina_actual' => $pagina,
                    'por_pagina' => $porPagina,
                    'total_registros' => $totalRegistros,
                    'total_paginas' => ceil($totalRegistros / $porPagina)
                ],
                'metadata' => [
                    'fecha_consulta' => date('c'),
                    'version_api' => '1.0.0'
                ]
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("Error en listarMototaxis: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error_code' => 'INTERNAL_ERROR'
            ]);
        }
    }

    // BUSCAR MOTOTAXI POR NÚMERO ASIGNADO (JSON) - MEJORADO
    public function buscarMototaxi() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValidation = $this->validarTokenRequest();
        if (!$tokenValidation['valid']) {
            echo json_encode([
                'success' => false,
                'message' => $tokenValidation['message'],
                'error_code' => 'TOKEN_INVALID'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        try {
            $numero = trim($_GET['numero'] ?? '');
            
            if (empty($numero)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Parámetro "numero" requerido para la búsqueda',
                    'error_code' => 'MISSING_PARAMETER'
                ]);
                return;
            }
            
            // Buscar por número asignado
            $query = "SELECT m.*, e.razon_social as empresa, e.ruc as ruc_empresa,
                             e.representante_legal as representante_empresa
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
                    'message' => 'Mototaxi no encontrado con el número: ' . $numero,
                    'error_code' => 'NOT_FOUND'
                ]);
                return;
            }
            
            // Formatear datos para respuesta completa
            $mototaxiFormateado = $this->formatearDatosMototaxi($mototaxi);
            
            echo json_encode([
                'success' => true,
                'message' => 'Mototaxi encontrado exitosamente',
                'data' => $mototaxiFormateado,
                'metadata' => [
                    'fecha_consulta' => date('c'),
                    'numero_buscado' => $numero,
                    'total_resultados' => 1
                ]
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("Error en buscarMototaxi: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error_code' => 'INTERNAL_ERROR'
            ]);
        }
    }

    // BUSCAR MOTOTAXIS POR DNI (JSON) - MEJORADO
    public function buscarPorDNI() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValidation = $this->validarTokenRequest();
        if (!$tokenValidation['valid']) {
            echo json_encode([
                'success' => false,
                'message' => $tokenValidation['message'],
                'error_code' => 'TOKEN_INVALID'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        try {
            $dni = trim($_GET['dni'] ?? '');
            
            if (empty($dni)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Parámetro "dni" requerido para la búsqueda',
                    'error_code' => 'MISSING_PARAMETER'
                ]);
                return;
            }
            
            if (!preg_match('/^\d{8}$/', $dni)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Formato de DNI inválido. Debe tener 8 dígitos.',
                    'error_code' => 'INVALID_DNI_FORMAT'
                ]);
                return;
            }
            
            // Buscar por DNI exacto
            $query = "SELECT m.*, e.razon_social as empresa, e.ruc as ruc_empresa
                     FROM mototaxis m 
                     LEFT JOIN empresas e ON m.id_empresa = e.id 
                     WHERE m.dni = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $dni);
            $stmt->execute();
            
            $mototaxis = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Formatear datos
            $mototaxisFormateados = array_map([$this, 'formatearDatosMototaxi'], $mototaxis);
            
            echo json_encode([
                'success' => true,
                'message' => count($mototaxisFormateados) > 0 ? 
                            'Búsqueda por DNI completada' : 
                            'No se encontraron mototaxis con el DNI proporcionado',
                'data' => $mototaxisFormateados,
                'total' => count($mototaxisFormateados),
                'metadata' => [
                    'fecha_consulta' => date('c'),
                    'dni_buscado' => $dni
                ]
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("Error en buscarPorDNI: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error_code' => 'INTERNAL_ERROR'
            ]);
        }
    }

    // VALIDAR TOKEN (JSON) - ENDPOINT PÚBLICO MEJORADO
    public function validarTokenEndpoint() {
        $this->configurarHeadersJSON();
        
        $tokenValidation = $this->validarTokenRequest();
        if (!$tokenValidation['valid']) {
            echo json_encode([
                'success' => false,
                'message' => $tokenValidation['message'],
                'error_code' => 'TOKEN_INVALID'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // Si llegó aquí, el token es válido
        $tokenData = $tokenValidation['token_data'];
        
        // Obtener información completa del cliente
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
            'message' => '✅ Token válido y activo',
            'data' => [
                'token' => [
                    'id' => $tokenData['id'] ?? null,
                    'token' => $tokenData['token'] ?? null,
                    'fecha_registro' => $tokenData['fecha_registro'] ?? null,
                    'estado' => (bool)($tokenData['estado'] ?? false)
                ],
                'cliente' => $clientData
            ],
            'metadata' => [
                'fecha_validacion' => date('c'),
                'valido_hasta' => date('c', strtotime('+1 hour'))
            ]
        ], JSON_UNESCAPED_UNICODE);
    }

    // NUEVO ENDPOINT: ESTADÍSTICAS DEL SISTEMA
    public function estadisticas() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValidation = $this->validarTokenRequest();
        if (!$tokenValidation['valid']) {
            echo json_encode([
                'success' => false,
                'message' => $tokenValidation['message'],
                'error_code' => 'TOKEN_INVALID'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        try {
            // Obtener estadísticas
            $stats = [];
            
            // Total mototaxis
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM mototaxis");
            $stats['total_mototaxis'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total empresas
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM empresas");
            $stats['total_empresas'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Requests hoy
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM count_request WHERE DATE(fecha) = CURDATE()");
            $stats['requests_hoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo json_encode([
                'success' => true,
                'message' => 'Estadísticas obtenidas exitosamente',
                'data' => $stats,
                'metadata' => [
                    'fecha_consulta' => date('c')
                ]
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("Error en estadisticas: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error_code' => 'INTERNAL_ERROR'
            ]);
        }
    }

    // MÉTODOS PRIVADOS MEJORADOS
    private function configurarHeadersJSON() {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Authorization, Content-Type, X-API-Key");
        header("Access-Control-Max-Age: 3600");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }

    private function validarTokenRequest() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        $token = str_replace('Bearer ', '', $token);
        
        // Si no hay token en el header, intentar obtenerlo de los parámetros GET
        if (empty($token)) {
            $token = $_GET['token'] ?? $_GET['api_key'] ?? '';
        }
        
        if (empty($token)) {
            return [
                'valid' => false,
                'message' => '❌ Token de acceso requerido. Incluya el token en el header Authorization o parámetro token.'
            ];
        }
        
        // Validar token usando el modelo mejorado
        $validation = $this->tokenApiModel->validateToken($token);
        
        if (!$validation['valid']) {
            return [
                'valid' => false,
                'message' => '❌ ' . $validation['message']
            ];
        }
        
        // Registrar request
        $this->registrarRequest($validation['data']['id'], 'consulta_api');
        
        return [
            'valid' => true,
            'token_data' => $validation['data'],
            'message' => 'Token válido'
        ];
    }

    private function registrarRequest($tokenId, $tipo) {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
            $endpoint = $_SERVER['REQUEST_URI'] ?? 'Desconocido';
            
            $query = "INSERT INTO count_request (id_token_api, tipo, fecha, ip, user_agent, endpoint) 
                     VALUES (?, ?, NOW(), ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $tokenId);
            $stmt->bindParam(2, $tipo);
            $stmt->bindParam(3, $ip);
            $stmt->bindParam(4, $userAgent);
            $stmt->bindParam(5, $endpoint);
            $stmt->execute();
        } catch (Exception $e) {
            // Silenciar errores de registro para no afectar la respuesta principal
            error_log("Error registrando request: " . $e->getMessage());
        }
    }

    // Formatear datos del mototaxi para respuesta completa
    private function formatearDatosMototaxi($mototaxi) {
        return [
            'id' => $mototaxi['id'] ?? null,
            'numero_asignado' => $mototaxi['numero_asignado'] ?? '',
            'nombre_completo' => $mototaxi['nombre_completo'] ?? '',
            'dni' => $mototaxi['dni'] ?? '',
            'direccion' => $mototaxi['direccion'] ?? '',
            'placa_rodaje' => $mototaxi['placa_rodaje'] ?? '',
            'anio_fabricacion' => $mototaxi['anio_fabricacion'] ?? '',
            'marca' => $mototaxi['marca'] ?? '',
            'numero_motor' => $mototaxi['numero_motor'] ?? '',
            'tipo_motor' => $mototaxi['tipo_motor'] ?? '',
            'serie' => $mototaxi['serie'] ?? '',
            'color' => $mototaxi['color'] ?? '',
            'fecha_registro' => $mototaxi['fecha_registro'] ?? '',
            'id_empresa' => $mototaxi['id_empresa'] ?? null,
            'empresa' => [
                'razon_social' => $mototaxi['empresa'] ?? '',
                'ruc' => $mototaxi['ruc_empresa'] ?? '',
                'representante_legal' => $mototaxi['representante_empresa'] ?? ''
            ],
            'estado_registro' => 'ACTIVO',
            'fecha_actualizacion' => date('c')
        ];
    }
}
?>