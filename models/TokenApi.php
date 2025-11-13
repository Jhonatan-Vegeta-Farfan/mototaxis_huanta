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
     * Obtener token por valor - MEJORADO CON MANEJO DE ERRORES
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
                return null; // Token o cliente inactivo
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error en getByToken: " . $e->getMessage());
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
            $base_token = bin2hex(random_bytes(24)); // 48 caracteres hexadecimales más seguro
            
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
        // Validar que el cliente existe y está activo
        $query = "SELECT id FROM client_api WHERE id = ? AND estado = 1 LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_client_api);
        
        if (!$stmt->execute() || $stmt->rowCount() === 0) {
            throw new Exception("Cliente no existe o está inactivo");
        }

        // Generar token automáticamente
        $this->token = $this->generateToken($this->id_client_api);
        $this->fecha_registro = date('Y-m-d H:i:s'); // Fecha y hora actual
        
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
     * Activar token
     */
    public function activate() {
        $query = "UPDATE " . $this->table_name . " SET estado = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
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

    /**
     * Validar token completo (token y cliente activos)
     */
    public function validateToken($token) {
        $tokenData = $this->getByToken($token);
        
        if (!$tokenData) {
            return [
                'valid' => false,
                'message' => '❌ Token no existe o es inválido'
            ];
        }
        
        if ($tokenData['estado'] == 0) {
            return [
                'valid' => false,
                'message' => '❌ Token inactivo'
            ];
        }
        
        if ($tokenData['cliente_estado'] == 0) {
            return [
                'valid' => false,
                'message' => '❌ Cliente inactivo'
            ];
        }
        
        return [
            'valid' => true,
            'message' => '✅ Token válido y activo',
            'data' => $tokenData
        ];
    }
}
?>