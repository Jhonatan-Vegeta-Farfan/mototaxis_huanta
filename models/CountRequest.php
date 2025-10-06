<?php
class CountRequest {
    private $conn;
    private $table_name = "count_request";

    public $id;
    public $id_token_api;
    public $tipo;
    public $fecha;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Leer todos los count requests con información relacionada
     */
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

    /**
     * Obtener requests por cliente específico
     */
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

    /**
     * Obtener requests por token específico
     */
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

    /**
     * Crear nuevo count request
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET id_token_api=:id_token_api, tipo=:tipo, fecha=:fecha";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id_token_api = htmlspecialchars(strip_tags($this->id_token_api));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        
        $stmt->bindParam(":id_token_api", $this->id_token_api);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":fecha", $this->fecha);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualizar count request existente
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET id_token_api=:id_token_api, tipo=:tipo, fecha=:fecha
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id_token_api = htmlspecialchars(strip_tags($this->id_token_api));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":id_token_api", $this->id_token_api);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Eliminar count request
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Leer un count request específico por ID
     */
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
            return true;
        }
        return false;
    }

    /**
     * Obtener lista de tokens activos para dropdown
     */
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
     * Obtener estadísticas de requests por tipo
     */
    public function getStatsByType($client_id = null) {
        $query = "SELECT tipo, COUNT(*) as total 
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 WHERE 1=1";
        
        $params = [];
        
        if ($client_id) {
            $query .= " AND t.id_client_api = ?";
            $params[] = $client_id;
        }
        
        $query .= " GROUP BY tipo ORDER BY total DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtener estadísticas de requests por fecha
     */
    public function getStatsByDate($client_id = null, $limit = 30) {
        $query = "SELECT fecha, COUNT(*) as total 
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 WHERE 1=1";
        
        $params = [];
        
        if ($client_id) {
            $query .= " AND t.id_client_api = ?";
            $params[] = $client_id;
        }
        
        $query .= " GROUP BY fecha ORDER BY fecha DESC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}
?>