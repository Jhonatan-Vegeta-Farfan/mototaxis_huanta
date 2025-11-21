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
                 ORDER BY cr.id DESC";
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
                 ORDER BY cr.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }

    public function getByToken($token_id) {
        $query = "SELECT cr.*, t.token, c.razon_social, c.id as client_id, c.ruc
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE cr.id_token_api = ?
                 ORDER BY cr.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $token_id);
        $stmt->execute();
        return $stmt;
    }

    public function getByDateRange($fecha_inicio, $fecha_fin) {
        $query = "SELECT cr.*, t.token, c.razon_social, c.id as client_id, c.ruc
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE 1=1";
        
        $params = array();
        
        if (!empty($fecha_inicio)) {
            $query .= " AND DATE(cr.fecha) >= ?";
            $params[] = $fecha_inicio;
        }
        
        if (!empty($fecha_fin)) {
            $query .= " AND DATE(cr.fecha) <= ?";
            $params[] = $fecha_fin;
        }
        
        $query .= " ORDER BY cr.id DESC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function create() {
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
            
            return true;
        }
        return false;
    }

    public function update() {
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
            return true;
        }
        return false;
    }

    /**
     * Eliminar request y reorganizar IDs
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar el request");
            }
            
            // Reorganizar IDs
            $this->reorganizarRequests();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error en delete: " . $e->getMessage());
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
            
            // 2. Actualizar IDs secuencialmente
            $new_id = 1;
            foreach ($requests as $request) {
                if ($request['id'] != $new_id) {
                    $update_query = "UPDATE " . $this->table_name . " SET id = ? WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $new_id);
                    $update_stmt->bindParam(2, $request['id']);
                    $update_stmt->execute();
                }
                $new_id++;
            }
            
            // 3. Resetear el auto_increment
            $reset_query = "ALTER TABLE " . $this->table_name . " AUTO_INCREMENT = 1";
            $this->conn->exec($reset_query);
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando requests: " . $e->getMessage());
            return false;
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
        
        return $stats;
    }

    /**
     * Limpiar requests antiguos
     */
    public function cleanOldRequests($dias = 30) {
        $query = "DELETE FROM " . $this->table_name . " 
                 WHERE fecha < DATE_SUB(CURDATE(), INTERVAL ? DAY)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $dias, PDO::PARAM_INT);
        
        if($stmt->execute()) {
            // Reorganizar después de limpiar
            $this->reorganizarRequests();
            return true;
        }
        return false;
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
        return $this->reorganizarRequests();
    }
}
?>