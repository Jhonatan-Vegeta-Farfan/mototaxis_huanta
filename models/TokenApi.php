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

    public function read() {
        $query = "SELECT t.*, c.razon_social 
                 FROM " . $this->table_name . " t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.estado = 1 
                 ORDER BY t.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // MÉTODO GET BY CLIENT - AGREGADO
    public function getByClient($client_id) {
        $query = "SELECT t.*, c.razon_social 
                 FROM " . $this->table_name . " t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.id_client_api = ? AND t.estado = 1
                 ORDER BY t.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
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
            return true;
        }
        return false;
    }

    public function update() {
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
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET estado = 0 WHERE id = ?";
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
            $this->id_client_api = $row['id_client_api'];
            $this->token = $row['token'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }

    public function getClientes() {
        $query = "SELECT id, razon_social FROM client_api WHERE estado = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>