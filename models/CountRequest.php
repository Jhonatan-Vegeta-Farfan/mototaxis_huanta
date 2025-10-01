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

    public function read() {
        $query = "SELECT cr.*, t.token, c.razon_social 
                 FROM " . $this->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 ORDER BY cr.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // MÉTODO PARA FILTRAR POR CLIENTE
    public function getByClient($client_id) {
        $query = "SELECT cr.*, t.token, c.razon_social 
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

    // MÉTODO PARA FILTRAR POR TOKEN
    public function getByToken($token_id) {
        $query = "SELECT cr.*, t.token, c.razon_social 
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

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
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

    public function getTokens() {
        $query = "SELECT t.id, t.token, c.razon_social 
                 FROM tokens_api t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.estado = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>