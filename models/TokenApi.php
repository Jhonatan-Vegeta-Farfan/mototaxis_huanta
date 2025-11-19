<?php
class TokenApi {
    private $conn;
    private $table_name = "tokens_api";

    public $id;
    public $id_client_api;
    public $token;
    public $fecha_registro;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Leer todos los tokens API con información del cliente
     */
    public function read() {
        $query = "SELECT t.*, c.razon_social, c.ruc, c.estado as cliente_estado
                 FROM " . $this->table_name . " t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 ORDER BY t.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Obtener tokens por cliente específico
     */
    public function getByClient($client_id) {
        $query = "SELECT t.*, c.razon_social, c.ruc
                 FROM " . $this->table_name . " t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.id_client_api = ? AND t.estado = 1
                 ORDER BY t.id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Obtener token por valor
     */
    public function getByToken($token) {
        try {
            $query = "SELECT t.*, c.razon_social, c.ruc, c.estado as cliente_estado
                     FROM " . $this->table_name . " t 
                     LEFT JOIN client_api c ON t.id_client_api = c.id 
                     WHERE t.token = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $token);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verificar que tanto el token como el cliente estén activos
                if ($tokenData['estado'] == 1 && $tokenData['cliente_estado'] == 1) {
                    return $tokenData;
                }
                return null;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error en getByToken: " . $e->getMessage());
            $this->logNotification('ERROR_BUSQUEDA_TOKEN', "Error al buscar token: $token - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Leer un token específico por ID
     */
    public function readOne($id = null) {
        if ($id) {
            $this->id = $id;
        }
        
        $query = "SELECT t.*, c.razon_social, c.ruc
                 FROM " . $this->table_name . " t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id_client_api = $row['id_client_api'];
                $this->token = $row['token'];
                $this->fecha_registro = $row['fecha_registro'];
                $this->estado = $row['estado'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en readOne: " . $e->getMessage());
            $this->logNotification('ERROR_LECTURA_TOKEN', "Error al leer token ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generar token automático único para el cliente
     */
    public function generateToken($client_id) {
        // Primero obtener información del cliente
        $query = "SELECT razon_social, ruc FROM client_api WHERE id = ? AND estado = 1 LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $client = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Contar tokens existentes del cliente
            $countQuery = "SELECT COUNT(*) as total FROM tokens_api WHERE id_client_api = ?";
            $countStmt = $this->conn->prepare($countQuery);
            $countStmt->bindParam(1, $client_id);
            $countStmt->execute();
            $tokenCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'] + 1;
            
            // Generar token base aleatorio seguro
            $base_token = bin2hex(random_bytes(24));
            
            // Crear identificador del cliente (primeras 3 letras de razón social)
            $client_identifier = substr($client['razon_social'], 0, 3);
            $client_identifier = preg_replace('/[^a-zA-Z0-9]/', '', $client_identifier);
            $client_identifier = strtoupper($client_identifier);
            
            // Si el identificador está vacío, usar RUC
            if (empty($client_identifier)) {
                $client_identifier = substr($client['ruc'], 0, 3);
            }
            
            return $base_token . '-' . $client_identifier . '-' . $tokenCount . '-' . time();
        }
        
        return false;
    }

    /**
     * Crear nuevo token automáticamente
     */
    public function create() {
        try {
            $this->conn->beginTransaction();
            
            // Validar que el cliente existe y está activo
            $query = "SELECT razon_social, ruc FROM client_api WHERE id = ? AND estado = 1 LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id_client_api);
            
            if (!$stmt->execute() || $stmt->rowCount() === 0) {
                throw new Exception("Cliente no existe o está inactivo");
            }
            
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            // Generar token automáticamente
            $this->token = $this->generateToken($this->id_client_api);
            $this->fecha_registro = date('Y-m-d H:i:s');
            
            if (!$this->token) {
                throw new Exception("Error generando el token");
            }

            $query = "INSERT INTO " . $this->table_name . " 
                     SET id_client_api=:id_client_api, token=:token, 
                         fecha_registro=:fecha_registro, estado=:estado";
            
            $stmt = $this->conn->prepare($query);
            
            $this->id_client_api = htmlspecialchars(strip_tags($this->id_client_api));
            $this->token = htmlspecialchars(strip_tags($this->token));
            $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
            $this->estado = htmlspecialchars(strip_tags($this->estado));
            
            $stmt->bindParam(":id_client_api", $this->id_client_api);
            $stmt->bindParam(":token", $this->token);
            $stmt->bindParam(":fecha_registro", $this->fecha_registro);
            $stmt->bindParam(":estado", $this->estado);
            
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                
                // Reorganizar IDs después de crear
                $this->reorganizarTokens();
                
                // Registrar notificación
                $detalles = json_encode([
                    'token_id' => $this->id,
                    'cliente_id' => $this->id_client_api,
                    'cliente' => $cliente['razon_social'],
                    'token_generado' => substr($this->token, 0, 20) . '...', // Mostrar solo parte del token por seguridad
                    'fecha_registro' => $this->fecha_registro
                ]);
                
                $this->logNotification('TOKEN_CREADO', "Nuevo token creado para: {$cliente['razon_social']}", null, $detalles);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la inserción");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_CREACION_TOKEN', "Error al crear token: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Actualizar token existente
     */
    public function update() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos antiguos
            $old_data = $this->getOldData();
            
            $query = "UPDATE " . $this->table_name . " 
                     SET id_client_api=:id_client_api, token=:token, 
                         fecha_registro=:fecha_registro, estado=:estado
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $this->id_client_api = htmlspecialchars(strip_tags($this->id_client_api));
            $this->token = htmlspecialchars(strip_tags($this->token));
            $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
            $this->estado = htmlspecialchars(strip_tags($this->estado));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            $stmt->bindParam(":id_client_api", $this->id_client_api);
            $stmt->bindParam(":token", $this->token);
            $stmt->bindParam(":fecha_registro", $this->fecha_registro);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                // Registrar cambios
                $this->logTokenChanges($old_data);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la actualización");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTUALIZACION_TOKEN', "Error al actualizar token ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar token (eliminación lógica) y reorganizar
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos del token antes de eliminar
            $token_data = $this->getTokenData();

            // Verificar si el token tiene requests asociados
            $query = "SELECT COUNT(*) as total FROM count_request WHERE id_token_api = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $requests_count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            if ($requests_count > 0) {
                throw new Exception("No se puede eliminar el token porque tiene $requests_count requests asociados");
            }
            
            // Eliminar lógicamente el token
            $query = "UPDATE " . $this->table_name . " SET estado = 0 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar el token");
            }
            
            // Reorganizar IDs de tokens activos
            $reorganizados = $this->reorganizarTokens();
            
            // Registrar notificación
            $detalles = json_encode([
                'token_id' => $this->id,
                'cliente' => $token_data['razon_social'],
                'requests_afectados' => $requests_count,
                'ids_reorganizados' => $reorganizados
            ]);
            
            $this->logNotification('TOKEN_ELIMINADO', "Token eliminado para: {$token_data['razon_social']}", null, $detalles);
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ELIMINACION_TOKEN', "Error al eliminar token ID: {$this->id} - " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Reorganizar IDs de tokens activos
     */
    private function reorganizarTokens() {
        try {
            // 1. Obtener todos los tokens activos ordenados por ID actual
            $query = "SELECT id FROM " . $this->table_name . " WHERE estado = 1 ORDER BY id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $cambios = [];
            
            // 2. Actualizar IDs secuencialmente
            $new_id = 1;
            foreach ($tokens as $token) {
                if ($token['id'] != $new_id) {
                    $update_query = "UPDATE " . $this->table_name . " SET id = ? WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $new_id);
                    $update_stmt->bindParam(2, $token['id']);
                    $update_stmt->execute();
                    
                    // Actualizar también en count_request
                    $update_requests = "UPDATE count_request SET id_token_api = ? WHERE id_token_api = ?";
                    $update_requests_stmt = $this->conn->prepare($update_requests);
                    $update_requests_stmt->bindParam(1, $new_id);
                    $update_requests_stmt->bindParam(2, $token['id']);
                    $update_requests_stmt->execute();
                    
                    $cambios[] = [
                        'viejo_id' => $token['id'],
                        'nuevo_id' => $new_id
                    ];
                }
                $new_id++;
            }
            
            if (!empty($cambios)) {
                $this->logNotification('REORGANIZACION_TOKENS', "IDs de tokens reorganizados. Total cambios: " . count($cambios), null, json_encode($cambios));
            }
            
            return $cambios;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando tokens: " . $e->getMessage());
            $this->logNotification('ERROR_REORGANIZACION_TOKENS', "Error al reorganizar tokens: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Activar token
     */
    public function activate() {
        try {
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . " SET estado = 1 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if($stmt->execute()) {
                // Obtener datos del token
                $token_data = $this->getTokenData();
                
                // Reorganizar después de activar
                $this->reorganizarTokens();
                
                // Registrar notificación
                $this->logNotification('TOKEN_ACTIVADO', "Token activado para: {$token_data['razon_social']}");
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al activar el token");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTIVACION_TOKEN', "Error al activar token ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener lista de clientes activos para dropdown
     */
    public function getClientes() {
        $query = "SELECT id, razon_social, ruc FROM client_api WHERE estado = 1 ORDER BY razon_social";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Verificar si token ya existe
     */
    public function tokenExists($token, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE token = ?";
        $params = [$token];
        
        if ($exclude_id) {
            $query .= " AND id != ?";
            $params[] = $exclude_id;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Validar token completo (token y cliente activos)
     */
    public function validateToken($token) {
        $tokenData = $this->getByToken($token);
        
        if (!$tokenData) {
            $this->logNotification('VALIDACION_TOKEN_FALLIDA', "Intento de validación fallido - Token no existe: " . substr($token, 0, 20) . '...');
            return [
                'valid' => false,
                'message' => '❌ Token no existe o es inválido'
            ];
        }
        
        if ($tokenData['estado'] == 0) {
            $this->logNotification('VALIDACION_TOKEN_FALLIDA', "Token inactivo: " . substr($token, 0, 20) . '...');
            return [
                'valid' => false,
                'message' => '❌ Token inactivo'
            ];
        }
        
        if ($tokenData['cliente_estado'] == 0) {
            $this->logNotification('VALIDACION_TOKEN_FALLIDA', "Cliente inactivo para token: " . substr($token, 0, 20) . '...');
            return [
                'valid' => false,
                'message' => '❌ Cliente inactivo'
            ];
        }
        
        $this->logNotification('VALIDACION_TOKEN_EXITOSA', "Token validado exitosamente para: {$tokenData['razon_social']}");
        return [
            'valid' => true,
            'message' => '✅ Token válido y activo',
            'data' => $tokenData
        ];
    }

    /**
     * Reorganizar todos los IDs de tokens (método público para llamadas manuales)
     */
    public function reorganizarTodosLosIds() {
        $result = $this->reorganizarTokens();
        $this->logNotification('REORGANIZACION_MANUAL_TOKENS', "Reorganización manual de IDs de tokens completada", null, json_encode($result));
        return $result;
    }

    /**
     * Obtener datos antiguos del token
     */
    private function getOldData() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener datos del token con información del cliente
     */
    private function getTokenData() {
        $query = "SELECT t.*, c.razon_social 
                 FROM " . $this->table_name . " t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registrar cambios en las actualizaciones de tokens
     */
    private function logTokenChanges($old_data) {
        $changes = [];
        
        if ($old_data['id_client_api'] != $this->id_client_api) {
            // Obtener nombres de clientes
            $cliente_old = $this->getClienteName($old_data['id_client_api']);
            $cliente_new = $this->getClienteName($this->id_client_api);
            $changes[] = "Cliente: $cliente_old → $cliente_new";
        }
        if ($old_data['token'] != $this->token) {
            $changes[] = "Token actualizado (se generó uno nuevo)";
        }
        if ($old_data['estado'] != $this->estado) {
            $estado_old = $old_data['estado'] == 1 ? 'Activo' : 'Inactivo';
            $estado_new = $this->estado == 1 ? 'Activo' : 'Inactivo';
            $changes[] = "Estado: $estado_old → $estado_new";
        }
        
        if (!empty($changes)) {
            $detalles = json_encode([
                'token_id' => $this->id,
                'cambios' => $changes,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ]);
            
            $this->logNotification('TOKEN_ACTUALIZADO', "Token actualizado. Cambios: " . count($changes), null, $detalles);
        }
    }

    /**
     * Obtener nombre del cliente
     */
    private function getClienteName($cliente_id) {
        $query = "SELECT razon_social FROM client_api WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cliente_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['razon_social'] : 'Desconocido';
    }

    /**
     * Registrar notificación
     */
    private function logNotification($tipo, $mensaje, $usuario_id = null, $detalles = null) {
        $database = new Database();
        $database->logNotification($tipo, $mensaje, $usuario_id, $detalles);
    }
}
?>