<?php
class CountRequest {
    private $conn;
    private $table_name = "count_request";

    public $id;
    public $id_token_api;
    public $tipo;
    public $fecha;
    public $ip;
    public $user_agent;
    public $endpoint;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT cr.*, t.token, c.razon_social, c.id as client_id, c.ruc
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 ORDER BY cr.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getByClient($client_id) {
        $query = "SELECT cr.*, t.token, c.razon_social, c.id as client_id, c.ruc
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.id_client_api = ?
                 ORDER BY cr.id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        
        $this->logNotification('CONSULTA_REQUESTS_CLIENTE', "Consultados requests del cliente ID: $client_id. Total: " . $stmt->rowCount());
        return $stmt;
    }

    public function getByToken($token_id) {
        $query = "SELECT cr.*, t.token, c.razon_social, c.id as client_id, c.ruc
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE cr.id_token_api = ?
                 ORDER BY cr.id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $token_id);
        $stmt->execute();
        
        $this->logNotification('CONSULTA_REQUESTS_TOKEN', "Consultados requests del token ID: $token_id. Total: " . $stmt->rowCount());
        return $stmt;
    }

    public function getByDateRange($fecha_inicio, $fecha_fin) {
        $query = "SELECT cr.*, t.token, c.razon_social, c.id as client_id, c.ruc
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE 1=1";
        
        $params = array();
        $criterios = [];
        
        if (!empty($fecha_inicio)) {
            $query .= " AND DATE(cr.fecha) >= ?";
            $params[] = $fecha_inicio;
            $criterios[] = "Desde: $fecha_inicio";
        }
        
        if (!empty($fecha_fin)) {
            $query .= " AND DATE(cr.fecha) <= ?";
            $params[] = $fecha_fin;
            $criterios[] = "Hasta: $fecha_fin";
        }
        
        $query .= " ORDER BY cr.id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        
        $criterios_str = implode(', ', $criterios);
        $this->logNotification('CONSULTA_REQUESTS_FECHAS', "Consultados requests por fecha - $criterios_str. Total: " . $stmt->rowCount());
        return $stmt;
    }

    public function create() {
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO " . $this->table_name . " 
                     SET id_token_api=:id_token_api, tipo=:tipo, fecha=:fecha, 
                         ip=:ip, user_agent=:user_agent, endpoint=:endpoint";
            
            $stmt = $this->conn->prepare($query);
            
            $this->id_token_api = htmlspecialchars(strip_tags($this->id_token_api));
            $this->tipo = htmlspecialchars(strip_tags($this->tipo));
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
            $this->ip = htmlspecialchars(strip_tags($this->ip));
            $this->user_agent = htmlspecialchars(strip_tags($this->user_agent));
            $this->endpoint = htmlspecialchars(strip_tags($this->endpoint));
            
            $stmt->bindParam(":id_token_api", $this->id_token_api);
            $stmt->bindParam(":tipo", $this->tipo);
            $stmt->bindParam(":fecha", $this->fecha);
            $stmt->bindParam(":ip", $this->ip);
            $stmt->bindParam(":user_agent", $this->user_agent);
            $stmt->bindParam(":endpoint", $this->endpoint);
            
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                
                // Reorganizar IDs después de crear
                $this->reorganizarRequests();
                
                // Obtener información del token y cliente
                $info_request = $this->getRequestInfo();
                
                // Registrar notificación
                $detalles = json_encode([
                    'request_id' => $this->id,
                    'token_id' => $this->id_token_api,
                    'cliente' => $info_request['cliente'],
                    'tipo' => $this->tipo,
                    'endpoint' => $this->endpoint,
                    'ip' => $this->ip,
                    'fecha' => $this->fecha
                ]);
                
                $this->logNotification('REQUEST_REGISTRADO', "Nuevo request registrado - Tipo: {$this->tipo}, Cliente: {$info_request['cliente']}", null, $detalles);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la inserción");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_CREACION_REQUEST', "Error al crear request: " . $e->getMessage());
            return false;
        }
    }

    public function update() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos antiguos
            $old_data = $this->getOldData();
            
            $query = "UPDATE " . $this->table_name . " 
                     SET id_token_api=:id_token_api, tipo=:tipo, fecha=:fecha, 
                         ip=:ip, user_agent=:user_agent, endpoint=:endpoint
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $this->id_token_api = htmlspecialchars(strip_tags($this->id_token_api));
            $this->tipo = htmlspecialchars(strip_tags($this->tipo));
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
            $this->ip = htmlspecialchars(strip_tags($this->ip));
            $this->user_agent = htmlspecialchars(strip_tags($this->user_agent));
            $this->endpoint = htmlspecialchars(strip_tags($this->endpoint));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            $stmt->bindParam(":id_token_api", $this->id_token_api);
            $stmt->bindParam(":tipo", $this->tipo);
            $stmt->bindParam(":fecha", $this->fecha);
            $stmt->bindParam(":ip", $this->ip);
            $stmt->bindParam(":user_agent", $this->user_agent);
            $stmt->bindParam(":endpoint", $this->endpoint);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                // Registrar cambios
                $this->logRequestChanges($old_data);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la actualización");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTUALIZACION_REQUEST', "Error al actualizar request ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar request y reorganizar IDs
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener información del request antes de eliminar
            $request_info = $this->getRequestInfo();

            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar el request");
            }
            
            // Reorganizar IDs
            $reorganizados = $this->reorganizarRequests();
            
            // Registrar notificación
            $detalles = json_encode([
                'request_id' => $this->id,
                'cliente' => $request_info['cliente'],
                'tipo' => $request_info['tipo'],
                'endpoint' => $request_info['endpoint'],
                'ids_reorganizados' => $reorganizados
            ]);
            
            $this->logNotification('REQUEST_ELIMINADO', "Request eliminado - Tipo: {$request_info['tipo']}, Cliente: {$request_info['cliente']}", null, $detalles);
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ELIMINACION_REQUEST', "Error al eliminar request ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reorganizar IDs de requests
     */
    private function reorganizarRequests() {
        try {
            // 1. Obtener todos los requests ordenados por ID actual
            $query = "SELECT id FROM " . $this->table_name . " ORDER BY id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $cambios = [];
            
            // 2. Actualizar IDs secuencialmente
            $new_id = 1;
            foreach ($requests as $request) {
                if ($request['id'] != $new_id) {
                    $update_query = "UPDATE " . $this->table_name . " SET id = ? WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $new_id);
                    $update_stmt->bindParam(2, $request['id']);
                    $update_stmt->execute();
                    
                    $cambios[] = [
                        'viejo_id' => $request['id'],
                        'nuevo_id' => $new_id
                    ];
                }
                $new_id++;
            }
            
            // 3. Resetear el auto_increment
            $reset_query = "ALTER TABLE " . $this->table_name . " AUTO_INCREMENT = 1";
            $this->conn->exec($reset_query);
            
            if (!empty($cambios)) {
                $this->logNotification('REORGANIZACION_REQUESTS', "IDs de requests reorganizados. Total cambios: " . count($cambios), null, json_encode($cambios));
            }
            
            return $cambios;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando requests: " . $e->getMessage());
            $this->logNotification('ERROR_REORGANIZACION_REQUESTS', "Error al reorganizar requests: " . $e->getMessage());
            return [];
        }
    }

    public function readOne() {
        $query = "SELECT cr.*, t.token, c.razon_social, c.id as client_id
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE cr.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id_token_api = $row['id_token_api'];
            $this->tipo = $row['tipo'];
            $this->fecha = $row['fecha'];
            $this->ip = $row['ip'];
            $this->user_agent = $row['user_agent'];
            $this->endpoint = $row['endpoint'];
            return true;
        }
        return false;
    }

    public function getTokens() {
        $query = "SELECT t.id, t.token, c.razon_social, c.ruc
                 FROM tokens_api t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.estado = 1
                 ORDER BY c.razon_social, t.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Obtener estadísticas de requests
     */
    public function getStats($fecha_inicio = null, $fecha_fin = null) {
        $stats = [
            'total_requests' => 0,
            'requests_hoy' => 0,
            'requests_este_mes' => 0,
            'por_tipo' => [],
            'por_cliente' => [],
            'por_dia' => []
        ];
        
        // Filtro de fecha
        $where = "1=1";
        $params = array();
        
        if (!empty($fecha_inicio)) {
            $where .= " AND DATE(fecha) >= ?";
            $params[] = $fecha_inicio;
        }
        
        if (!empty($fecha_fin)) {
            $where .= " AND DATE(fecha) <= ?";
            $params[] = $fecha_fin;
        }
        
        // Total requests
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE $where";
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        $stmt->execute();
        $stats['total_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Requests hoy
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE DATE(fecha) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['requests_hoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Requests este mes
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                 WHERE YEAR(fecha) = YEAR(CURDATE()) AND MONTH(fecha) = MONTH(CURDATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['requests_este_mes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Por tipo
        $query = "SELECT tipo, COUNT(*) as total FROM " . $this->table_name . " 
                 WHERE $where GROUP BY tipo ORDER BY total DESC";
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        $stmt->execute();
        $stats['por_tipo'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Por cliente
        $query = "SELECT c.razon_social, COUNT(cr.id) as total
                 FROM count_request cr
                 JOIN tokens_api t ON cr.id_token_api = t.id
                 JOIN client_api c ON t.id_client_api = c.id
                 WHERE $where
                 GROUP BY c.id, c.razon_social
                 ORDER BY total DESC
                 LIMIT 10";
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        $stmt->execute();
        $stats['por_cliente'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Por día (últimos 30 días)
        $query = "SELECT DATE(fecha) as dia, COUNT(*) as total
                 FROM " . $this->table_name . "
                 WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY DATE(fecha)
                 ORDER BY dia DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['por_dia'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Registrar consulta de estadísticas
        $this->logNotification('CONSULTA_ESTADISTICAS', "Estadísticas de requests consultadas", null, json_encode([
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'total_requests' => $stats['total_requests']
        ]));
        
        return $stats;
    }

    /**
     * Limpiar requests antiguos
     */
    public function cleanOldRequests($dias = 30) {
        try {
            $this->conn->beginTransaction();
            
            // Contar requests que serán eliminados
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                     WHERE fecha < DATE_SUB(CURDATE(), INTERVAL ? DAY)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $dias, PDO::PARAM_INT);
            $stmt->execute();
            $total_eliminados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $query = "DELETE FROM " . $this->table_name . " 
                     WHERE fecha < DATE_SUB(CURDATE(), INTERVAL ? DAY)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $dias, PDO::PARAM_INT);
            
            if($stmt->execute()) {
                // Reorganizar después de limpiar
                $this->reorganizarRequests();
                
                // Registrar notificación
                $this->logNotification('LIMPIEZA_REQUESTS', "Limpieza de requests antiguos completada - Eliminados: $total_eliminados requests de más de $dias días");
                
                $this->conn->commit();
                return $total_eliminados;
            } else {
                throw new Exception("Error al ejecutar la limpieza");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_LIMPIEZA_REQUESTS', "Error en limpieza de requests: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registrar request automáticamente
     */
    public function logRequest($token_id, $tipo, $endpoint = null) {
        $this->id_token_api = $token_id;
        $this->tipo = $tipo;
        $this->fecha = date('Y-m-d H:i:s');
        $this->ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        $this->endpoint = $endpoint ?? ($_SERVER['REQUEST_URI'] ?? 'Desconocido');
        
        return $this->create();
    }

    /**
     * Reorganizar todos los IDs de requests (método público para llamadas manuales)
     */
    public function reorganizarTodosLosIds() {
        $result = $this->reorganizarRequests();
        $this->logNotification('REORGANIZACION_MANUAL_REQUESTS', "Reorganización manual de IDs de requests completada", null, json_encode($result));
        return $result;
    }

    /**
     * Obtener información del request
     */
    private function getRequestInfo() {
        $query = "SELECT cr.tipo, cr.endpoint, c.razon_social as cliente
                 FROM " . $this->table_name . " cr
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id
                 LEFT JOIN client_api c ON t.id_client_api = c.id
                 WHERE cr.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener datos antiguos
     */
    private function getOldData() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registrar cambios en las actualizaciones
     */
    private function logRequestChanges($old_data) {
        $changes = [];
        
        if ($old_data['id_token_api'] != $this->id_token_api) {
            $changes[] = "Token API cambiado";
        }
        if ($old_data['tipo'] != $this->tipo) {
            $changes[] = "Tipo: {$old_data['tipo']} → {$this->tipo}";
        }
        if ($old_data['fecha'] != $this->fecha) {
            $changes[] = "Fecha actualizada";
        }
        if ($old_data['endpoint'] != $this->endpoint) {
            $changes[] = "Endpoint actualizado";
        }
        
        if (!empty($changes)) {
            $detalles = json_encode([
                'request_id' => $this->id,
                'cambios' => $changes,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ]);
            
            $this->logNotification('REQUEST_ACTUALIZADO', "Request actualizado. Cambios: " . count($changes), null, $detalles);
        }
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