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
     * Leer todos los tokens activos con información del cliente
     */
    public function read() {
        $query = "SELECT t.*, c.razon_social, c.ruc
                 FROM " . $this->table_name . " t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.estado = 1 
                 ORDER BY t.id DESC";
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
                 ORDER BY t.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Generar token automático único para el cliente
     */
    public function generateToken($client_id) {
        $clientModel = new ClientApi($this->conn);
        $clientModel->id = $client_id;
        
        if ($clientModel->readOne()) {
            $token_count = $clientModel->countTokens($client_id);
            
            // Generar token base aleatorio
            $base_token = bin2hex(random_bytes(16)); // 32 caracteres hexadecimales
            
            // Crear identificador del cliente (primeras 3 letras de razón social)
            $client_identifier = substr($clientModel->razon_social, 0, 3);
            $client_identifier = preg_replace('/[^a-zA-Z0-9]/', '', $client_identifier);
            $client_identifier = strtoupper($client_identifier);
            
            // Si el identificador está vacío, usar RUC
            if (empty($client_identifier)) {
                $client_identifier = substr($clientModel->ruc, 0, 3);
            }
            
            return $base_token . '-' . $client_identifier . '-' . $token_count;
        }
        
        return false;
    }

    /**
     * Crear nuevo token automáticamente
     */
    public function create() {
        // Validar que el cliente existe
        $clientModel = new ClientApi($this->conn);
        $clientModel->id = $this->id_client_api;
        if (!$clientModel->readOne()) {
            return false;
        }

        // Generar token automáticamente
        $this->token = $this->generateToken($this->id_client_api);
        $this->fecha_registro = date('Y-m-d'); // Fecha actual automática
        
        if (!$this->token) {
            return false;
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
            return true;
        }
        return false;
    }

    /**
     * Actualizar token existente
     */
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

    /**
     * Eliminar token (eliminación lógica)
     */
    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET estado = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Leer un token específico por ID
     */
    public function readOne() {
        $query = "SELECT t.*, c.razon_social, c.ruc
                 FROM " . $this->table_name . " t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.id = ? LIMIT 0,1";
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
}
?>