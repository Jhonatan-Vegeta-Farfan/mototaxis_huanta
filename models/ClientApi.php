<?php
class ClientApi {
    private $conn;
    private $table_name = "client_api";

    public $id;
    public $ruc;
    public $razon_social;
    public $telefono;
    public $correo;
    public $fecha_registro;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE estado = 1 ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE (ruc LIKE ? OR razon_social LIKE ?) AND estado = 1
                 ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        
        $stmt->execute();
        return $stmt;
    }

    public function advancedSearch($ruc = null, $razon_social = null, $estado = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        
        $params = array();
        
        if (!empty($ruc)) {
            $query .= " AND ruc LIKE ?";
            $params[] = "%{$ruc}%";
        }
        
        if (!empty($razon_social)) {
            $query .= " AND razon_social LIKE ?";
            $params[] = "%{$razon_social}%";
        }
        
        if (!empty($estado)) {
            $query .= " AND estado = ?";
            $params[] = $estado;
        } else {
            $query .= " AND estado = 1";
        }
        
        $query .= " ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET ruc=:ruc, razon_social=:razon_social, telefono=:telefono, 
                     correo=:correo, fecha_registro=:fecha_registro, estado=:estado";
        
        $stmt = $this->conn->prepare($query);
        
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":razon_social", $this->razon_social);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":fecha_registro", $this->fecha_registro);
        $stmt->bindParam(":estado", $this->estado);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET ruc=:ruc, razon_social=:razon_social, telefono=:telefono, 
                     correo=:correo, fecha_registro=:fecha_registro, estado=:estado
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":razon_social", $this->razon_social);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":correo", $this->correo);
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
            $this->ruc = $row['ruc'];
            $this->razon_social = $row['razon_social'];
            $this->telefono = $row['telefono'];
            $this->correo = $row['correo'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }

    public function countTokens($client_id) {
        $query = "SELECT COUNT(*) as total FROM tokens_api WHERE id_client_api = ? AND estado = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] + 1;
    }

    // NUEVO: Obtener estadísticas del cliente
    public function getStats($client_id) {
        $stats = [
            'total_tokens' => 0,
            'total_requests' => 0
        ];
        
        // Contar tokens
        $query_tokens = "SELECT COUNT(*) as total FROM tokens_api WHERE id_client_api = ? AND estado = 1";
        $stmt_tokens = $this->conn->prepare($query_tokens);
        $stmt_tokens->bindParam(1, $client_id);
        $stmt_tokens->execute();
        $row_tokens = $stmt_tokens->fetch(PDO::FETCH_ASSOC);
        $stats['total_tokens'] = $row_tokens['total'];
        
        // Contar requests
        $query_requests = "SELECT COUNT(*) as total 
                          FROM count_request cr 
                          JOIN tokens_api t ON cr.id_token_api = t.id 
                          WHERE t.id_client_api = ?";
        $stmt_requests = $this->conn->prepare($query_requests);
        $stmt_requests->bindParam(1, $client_id);
        $stmt_requests->execute();
        $row_requests = $stmt_requests->fetch(PDO::FETCH_ASSOC);
        $stats['total_requests'] = $row_requests['total'];
        
        return $stats;
    }
}
?>