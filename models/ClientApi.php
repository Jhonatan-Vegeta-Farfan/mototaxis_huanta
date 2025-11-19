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
            $this->logNotification('ERROR_LECTURA', "Error al leer cliente API ID: {$this->id} - " . $e->getMessage());
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
        
        $this->logNotification('BUSQUEDA_CLIENTE', "Búsqueda realizada: '$keywords'. Resultados: " . $stmt->rowCount());
        return $stmt;
    }

    /**
     * Búsqueda avanzada con múltiples criterios
     */
    public function advancedSearch($ruc = null, $razon_social = null, $estado = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        
        $params = array();
        $criterios = [];
        
        if (!empty($ruc)) {
            $query .= " AND ruc LIKE ?";
            $params[] = "%{$ruc}%";
            $criterios[] = "RUC: $ruc";
        }
        
        if (!empty($razon_social)) {
            $query .= " AND razon_social LIKE ?";
            $params[] = "%{$razon_social}%";
            $criterios[] = "Razón Social: $razon_social";
        }
        
        if (!empty($estado)) {
            $query .= " AND estado = ?";
            $params[] = $estado;
            $criterios[] = "Estado: " . ($estado == 1 ? 'Activo' : 'Inactivo');
        } else {
            $query .= " AND estado = 1";
        }
        
        $query .= " ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        
        $criterios_str = implode(', ', $criterios);
        $this->logNotification('BUSQUEDA_AVANZADA_CLIENTE', "Búsqueda avanzada - Criterios: $criterios_str. Resultados: " . $stmt->rowCount());
        return $stmt;
    }

    /**
     * Crear nuevo cliente API
     */
    public function create() {
        try {
            $this->conn->beginTransaction();
            
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
                
                // Registrar notificación de creación
                $detalles = json_encode([
                    'cliente_id' => $this->id,
                    'ruc' => $this->ruc,
                    'razon_social' => $this->razon_social,
                    'fecha_registro' => $this->fecha_registro
                ]);
                
                $this->logNotification('CLIENTE_CREADO', "Nuevo cliente API creado: {$this->razon_social} (RUC: {$this->ruc})", null, $detalles);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la inserción");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_CREACION_CLIENTE', "Error al crear cliente API: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar cliente API existente
     */
    public function update() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos antiguos para comparación
            $old_data = $this->getOldData();
            
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
                // Registrar cambios
                $this->logChanges($old_data);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la actualización");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTUALIZACION_CLIENTE', "Error al actualizar cliente API ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar cliente API (eliminación lógica) y reorganizar
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos antes de eliminar
            $cliente_data = $this->getClienteData();
            
            // 1. Verificar si el cliente tiene tokens activos
            $tokenModel = new TokenApi($this->conn);
            $tokensActivos = $tokenModel->getByClient($this->id);
            $total_tokens = $tokensActivos->rowCount();
            
            if ($total_tokens > 0) {
                throw new Exception('No se puede eliminar el cliente porque tiene ' . $total_tokens . ' tokens activos asociados');
            }
            
            // 2. Eliminar lógicamente el cliente
            $query = "UPDATE " . $this->table_name . " SET estado = 0 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar el cliente");
            }
            
            // 3. Reorganizar IDs de clientes activos
            $reorganizados = $this->reorganizarClientesActivos();
            
            // 4. Registrar notificación
            $detalles = json_encode([
                'cliente_id' => $this->id,
                'razon_social' => $cliente_data['razon_social'],
                'ruc' => $cliente_data['ruc'],
                'tokens_afectados' => $total_tokens,
                'ids_reorganizados' => $reorganizados
            ]);
            
            $this->logNotification('CLIENTE_ELIMINADO', "Cliente API eliminado: {$cliente_data['razon_social']} (RUC: {$cliente_data['ruc']})", null, $detalles);
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ELIMINACION_CLIENTE', "Error al eliminar cliente API ID: {$this->id} - " . $e->getMessage());
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
            
            $cambios = [];
            
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
                    
                    $cambios[] = [
                        'viejo_id' => $cliente['id'],
                        'nuevo_id' => $new_id
                    ];
                }
                $new_id++;
            }
            
            if (!empty($cambios)) {
                $this->logNotification('REORGANIZACION_CLIENTES', "IDs de clientes reorganizados. Total cambios: " . count($cambios), null, json_encode($cambios));
            }
            
            return $cambios;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando clientes: " . $e->getMessage());
            $this->logNotification('ERROR_REORGANIZACION_CLIENTES', "Error al reorganizar clientes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Activar cliente API
     */
    public function activate() {
        try {
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . " SET estado = 1 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if($stmt->execute()) {
                // Obtener datos del cliente
                $cliente_data = $this->getClienteData();
                
                // Reorganizar después de activar
                $this->reorganizarClientesActivos();
                
                // Registrar notificación
                $this->logNotification('CLIENTE_ACTIVADO', "Cliente API activado: {$cliente_data['razon_social']} (RUC: {$cliente_data['ruc']})");
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al activar el cliente");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTIVACION_CLIENTE', "Error al activar cliente API ID: {$this->id} - " . $e->getMessage());
            return false;
        }
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
        $result = $this->reorganizarClientesActivos();
        $this->logNotification('REORGANIZACION_MANUAL_CLIENTES', "Reorganización manual de IDs de clientes completada", null, json_encode($result));
        return $result;
    }

    /**
     * Obtener datos antiguos para comparación
     */
    private function getOldData() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener datos del cliente
     */
    private function getClienteData() {
        $query = "SELECT razon_social, ruc FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registrar cambios en las actualizaciones
     */
    private function logChanges($old_data) {
        $changes = [];
        
        if ($old_data['ruc'] != $this->ruc) {
            $changes[] = "RUC: {$old_data['ruc']} → {$this->ruc}";
        }
        if ($old_data['razon_social'] != $this->razon_social) {
            $changes[] = "Razón Social: {$old_data['razon_social']} → {$this->razon_social}";
        }
        if ($old_data['telefono'] != $this->telefono) {
            $changes[] = "Teléfono: {$old_data['telefono']} → {$this->telefono}";
        }
        if ($old_data['correo'] != $this->correo) {
            $changes[] = "Correo: {$old_data['correo']} → {$this->correo}";
        }
        if ($old_data['estado'] != $this->estado) {
            $estado_old = $old_data['estado'] == 1 ? 'Activo' : 'Inactivo';
            $estado_new = $this->estado == 1 ? 'Activo' : 'Inactivo';
            $changes[] = "Estado: $estado_old → $estado_new";
        }
        
        if (!empty($changes)) {
            $detalles = json_encode([
                'cliente_id' => $this->id,
                'cambios' => $changes,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ]);
            
            $this->logNotification('CLIENTE_ACTUALIZADO', "Cliente API actualizado: {$this->razon_social}. Cambios: " . count($changes), null, $detalles);
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