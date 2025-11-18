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

    /**
     * Leer todos los clientes API activos
     */
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE estado = 1 ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Leer un cliente API específico por ID
     */
    public function readOne($id = null) {
        if ($id) {
            $this->id = $id;
        }
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->ruc = $row['ruc'];
                $this->razon_social = $row['razon_social'];
                $this->telefono = $row['telefono'];
                $this->correo = $row['correo'];
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
     * Búsqueda simple por RUC o Razón Social
     */
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE (ruc LIKE ? OR razon_social LIKE ?) AND estado = 1
                 ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        
        $stmt->execute();
        return $stmt;
    }

    /**
     * Búsqueda avanzada con múltiples criterios
     */
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
        
        $query .= " ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        return $stmt;
    }

    /**
     * Crear nuevo cliente API
     */
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
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Actualizar cliente API existente
     */
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

    /**
     * Eliminar cliente API (eliminación lógica) y reorganizar
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // 1. Verificar si el cliente tiene tokens activos
            $tokenModel = new TokenApi($this->conn);
            $tokensActivos = $tokenModel->getByClient($this->id);
            
            if ($tokensActivos->rowCount() > 0) {
                throw new Exception('No se puede eliminar el cliente porque tiene tokens activos asociados');
            }
            
            // 2. Eliminar lógicamente el cliente
            $query = "UPDATE " . $this->table_name . " SET estado = 0 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar el cliente");
            }
            
            // 3. Reorganizar IDs de clientes activos
            $this->reorganizarClientesActivos();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error en delete: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Reorganizar IDs de clientes activos
     */
    private function reorganizarClientesActivos() {
        try {
            // 1. Obtener todos los clientes activos ordenados por ID actual
            $query = "SELECT id FROM " . $this->table_name . " WHERE estado = 1 ORDER BY id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // 2. Actualizar IDs secuencialmente
            $new_id = 1;
            foreach ($clientes as $cliente) {
                if ($cliente['id'] != $new_id) {
                    $update_query = "UPDATE " . $this->table_name . " SET id = ? WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $new_id);
                    $update_stmt->bindParam(2, $cliente['id']);
                    $update_stmt->execute();
                    
                    // Actualizar también en tokens_api
                    $update_tokens = "UPDATE tokens_api SET id_client_api = ? WHERE id_client_api = ?";
                    $update_tokens_stmt = $this->conn->prepare($update_tokens);
                    $update_tokens_stmt->bindParam(1, $new_id);
                    $update_tokens_stmt->bindParam(2, $cliente['id']);
                    $update_tokens_stmt->execute();
                }
                $new_id++;
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando clientes: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Activar cliente API
     */
    public function activate() {
        $query = "UPDATE " . $this->table_name . " SET estado = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            // Reorganizar después de activar
            $this->reorganizarClientesActivos();
            return true;
        }
        return false;
    }

    /**
     * Contar tokens activos del cliente para generar identificador único
     */
    public function countTokens($client_id) {
        $query = "SELECT COUNT(*) as total FROM tokens_api WHERE id_client_api = ? AND estado = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] + 1; // +1 para el siguiente token
    }

    /**
     * Verificar si RUC ya existe
     */
    public function rucExists($ruc, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE ruc = ?";
        $params = [$ruc];
        
        if ($exclude_id) {
            $query .= " AND id != ?";
            $params[] = $exclude_id;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtener estadísticas del cliente
     */
    public function getStats($client_id) {
        $stats = [
            'total_tokens' => 0,
            'tokens_activos' => 0,
            'total_requests' => 0,
            'requests_hoy' => 0,
            'requests_este_mes' => 0
        ];
        
        // Total tokens
        $query = "SELECT COUNT(*) as total FROM tokens_api WHERE id_client_api = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        $stats['total_tokens'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Tokens activos
        $query = "SELECT COUNT(*) as total FROM tokens_api WHERE id_client_api = ? AND estado = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        $stats['tokens_activos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total requests
        $query = "SELECT COUNT(*) as total FROM count_request cr 
                 JOIN tokens_api t ON cr.id_token_api = t.id 
                 WHERE t.id_client_api = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        $stats['total_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Requests hoy
        $query = "SELECT COUNT(*) as total FROM count_request cr 
                 JOIN tokens_api t ON cr.id_token_api = t.id 
                 WHERE t.id_client_api = ? AND DATE(cr.fecha) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        $stats['requests_hoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Requests este mes
        $query = "SELECT COUNT(*) as total FROM count_request cr 
                 JOIN tokens_api t ON cr.id_token_api = t.id 
                 WHERE t.id_client_api = ? AND YEAR(cr.fecha) = YEAR(CURDATE()) 
                 AND MONTH(cr.fecha) = MONTH(CURDATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        $stats['requests_este_mes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }

    /**
     * Obtener clientes con más requests
     */
    public function getTopClients($limit = 10) {
        $query = "SELECT c.id, c.razon_social, c.ruc, COUNT(cr.id) as total_requests
                 FROM client_api c
                 LEFT JOIN tokens_api t ON c.id = t.id_client_api
                 LEFT JOIN count_request cr ON t.id = cr.id_token_api
                 WHERE c.estado = 1
                 GROUP BY c.id, c.razon_social, c.ruc
                 ORDER BY total_requests DESC
                 LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Reorganizar todos los IDs de clientes (método público para llamadas manuales)
     */
    public function reorganizarTodosLosIds() {
        return $this->reorganizarClientesActivos();
    }
}
?>