<?php
class Empresa {
    private $conn;
    private $table_name = "empresas";

    public $id;
    public $razon_social;
    public $ruc;
    public $representante_legal;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener la conexión
    public function getConnection() {
        return $this->conn;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE ruc LIKE ? OR razon_social LIKE ? OR representante_legal LIKE ?
                 ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        
        $stmt->execute();
        
        $this->logNotification('BUSQUEDA_EMPRESA', "Búsqueda de empresas: '$keywords'. Resultados: " . $stmt->rowCount());
        return $stmt;
    }

    public function advancedSearch($ruc = null, $razon_social = null, $representante_legal = null) {
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
        
        if (!empty($representante_legal)) {
            $query .= " AND representante_legal LIKE ?";
            $params[] = "%{$representante_legal}%";
            $criterios[] = "Representante: $representante_legal";
        }
        
        $query .= " ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        
        $criterios_str = implode(', ', $criterios);
        $this->logNotification('BUSQUEDA_AVANZADA_EMPRESA', "Búsqueda avanzada de empresas - Criterios: $criterios_str. Resultados: " . $stmt->rowCount());
        return $stmt;
    }

    public function create() {
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO " . $this->table_name . " 
                     SET razon_social=:razon_social, ruc=:ruc, 
                         representante_legal=:representante_legal, fecha_registro=NOW()";
            
            $stmt = $this->conn->prepare($query);
            
            $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
            $this->ruc = htmlspecialchars(strip_tags($this->ruc));
            $this->representante_legal = htmlspecialchars(strip_tags($this->representante_legal));
            
            $stmt->bindParam(":razon_social", $this->razon_social);
            $stmt->bindParam(":ruc", $this->ruc);
            $stmt->bindParam(":representante_legal", $this->representante_legal);
            
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                
                // Reorganizar IDs después de crear
                $this->reorganizarEmpresas();
                
                // Registrar notificación
                $detalles = json_encode([
                    'empresa_id' => $this->id,
                    'razon_social' => $this->razon_social,
                    'ruc' => $this->ruc,
                    'representante_legal' => $this->representante_legal,
                    'fecha_registro' => date('Y-m-d H:i:s')
                ]);
                
                $this->logNotification('EMPRESA_CREADA', "Nueva empresa creada: {$this->razon_social} (RUC: {$this->ruc})", null, $detalles);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la inserción");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_CREACION_EMPRESA', "Error al crear empresa: " . $e->getMessage());
            return false;
        }
    }

    public function update() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos antiguos
            $old_data = $this->getOldData();
            
            $query = "UPDATE " . $this->table_name . " 
                     SET razon_social=:razon_social, ruc=:ruc, 
                         representante_legal=:representante_legal
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
            $this->ruc = htmlspecialchars(strip_tags($this->ruc));
            $this->representante_legal = htmlspecialchars(strip_tags($this->representante_legal));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            $stmt->bindParam(":razon_social", $this->razon_social);
            $stmt->bindParam(":ruc", $this->ruc);
            $stmt->bindParam(":representante_legal", $this->representante_legal);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                // Registrar cambios
                $this->logEmpresaChanges($old_data);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la actualización");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTUALIZACION_EMPRESA', "Error al actualizar empresa ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar empresa y reorganizar IDs
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos de la empresa antes de eliminar
            $empresa_data = $this->getEmpresaData();

            // Verificar si la empresa tiene mototaxis asociados
            $query = "SELECT COUNT(*) as total FROM mototaxis WHERE id_empresa = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['total'] > 0) {
                throw new Exception('No se puede eliminar la empresa porque tiene ' . $result['total'] . ' mototaxis asociados');
            }
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar la empresa");
            }
            
            // Reorganizar IDs
            $reorganizados = $this->reorganizarEmpresas();
            
            // Registrar notificación
            $detalles = json_encode([
                'empresa_id' => $this->id,
                'razon_social' => $empresa_data['razon_social'],
                'ruc' => $empresa_data['ruc'],
                'mototaxis_afectados' => $result['total'],
                'ids_reorganizados' => $reorganizados
            ]);
            
            $this->logNotification('EMPRESA_ELIMINADA', "Empresa eliminada: {$empresa_data['razon_social']} (RUC: {$empresa_data['ruc']})", null, $detalles);
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ELIMINACION_EMPRESA', "Error al eliminar empresa ID: {$this->id} - " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Reorganizar IDs de empresas
     */
    private function reorganizarEmpresas() {
        try {
            // 1. Obtener todas las empresas ordenadas por ID actual
            $query = "SELECT id FROM " . $this->table_name . " ORDER BY id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $cambios = [];
            
            // 2. Actualizar IDs secuencialmente
            $new_id = 1;
            foreach ($empresas as $empresa) {
                if ($empresa['id'] != $new_id) {
                    $update_query = "UPDATE " . $this->table_name . " SET id = ? WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $new_id);
                    $update_stmt->bindParam(2, $empresa['id']);
                    $update_stmt->execute();
                    
                    // Actualizar también en mototaxis
                    $update_mototaxis = "UPDATE mototaxis SET id_empresa = ? WHERE id_empresa = ?";
                    $update_mototaxis_stmt = $this->conn->prepare($update_mototaxis);
                    $update_mototaxis_stmt->bindParam(1, $new_id);
                    $update_mototaxis_stmt->bindParam(2, $empresa['id']);
                    $update_mototaxis_stmt->execute();
                    
                    $cambios[] = [
                        'viejo_id' => $empresa['id'],
                        'nuevo_id' => $new_id
                    ];
                }
                $new_id++;
            }
            
            // 3. Resetear el auto_increment
            $reset_query = "ALTER TABLE " . $this->table_name . " AUTO_INCREMENT = 1";
            $this->conn->exec($reset_query);
            
            if (!empty($cambios)) {
                $this->logNotification('REORGANIZACION_EMPRESAS', "IDs de empresas reorganizados. Total cambios: " . count($cambios), null, json_encode($cambios));
            }
            
            return $cambios;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando empresas: " . $e->getMessage());
            $this->logNotification('ERROR_REORGANIZACION_EMPRESAS', "Error al reorganizar empresas: " . $e->getMessage());
            return [];
        }
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->razon_social = $row['razon_social'];
            $this->ruc = $row['ruc'];
            $this->representante_legal = $row['representante_legal'];
            $this->fecha_registro = $row['fecha_registro'];
            return true;
        }
        return false;
    }

    /**
     * Obtener estadísticas de la empresa
     */
    public function getStats($empresa_id) {
        $stats = [
            'total_mototaxis' => 0,
            'mototaxis_por_año' => [],
            'mototaxis_por_marca' => [],
            'ultimos_registros' => []
        ];
        
        // Total mototaxis
        $query = "SELECT COUNT(*) as total FROM mototaxis WHERE id_empresa = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        $stats['total_mototaxis'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Mototaxis por año
        $query = "SELECT anio_fabricacion, COUNT(*) as total 
                 FROM mototaxis 
                 WHERE id_empresa = ? AND anio_fabricacion IS NOT NULL 
                 GROUP BY anio_fabricacion 
                 ORDER BY anio_fabricacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        $stats['mototaxis_por_año'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Mototaxis por marca
        $query = "SELECT marca, COUNT(*) as total 
                 FROM mototaxis 
                 WHERE id_empresa = ? AND marca IS NOT NULL AND marca != '' 
                 GROUP BY marca 
                 ORDER BY total DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        $stats['mototaxis_por_marca'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Últimos registros
        $query = "SELECT numero_asignado, nombre_completo, fecha_registro 
                 FROM mototaxis 
                 WHERE id_empresa = ? 
                 ORDER BY fecha_registro DESC 
                 LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        $stats['ultimos_registros'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Registrar consulta de estadísticas
        $this->logNotification('CONSULTA_ESTADISTICAS_EMPRESA', "Estadísticas consultadas para empresa ID: $empresa_id");
        
        return $stats;
    }

    /**
     * Verificar si la empresa tiene mototaxis asociados
     */
    public function hasMototaxis($empresa_id) {
        $query = "SELECT COUNT(*) as total FROM mototaxis WHERE id_empresa = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    /**
     * Obtener empresas con más mototaxis
     */
    public function getTopEmpresas($limit = 10) {
        $query = "SELECT e.id, e.razon_social, e.ruc, COUNT(m.id) as total_mototaxis
                 FROM empresas e
                 LEFT JOIN mototaxis m ON e.id = m.id_empresa
                 GROUP BY e.id, e.razon_social, e.ruc
                 ORDER BY total_mototaxis DESC
                 LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Reorganizar todos los IDs de empresas (método público para llamadas manuales)
     */
    public function reorganizarTodosLosIds() {
        $result = $this->reorganizarEmpresas();
        $this->logNotification('REORGANIZACION_MANUAL_EMPRESAS', "Reorganización manual de IDs de empresas completada", null, json_encode($result));
        return $result;
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
     * Obtener datos de la empresa
     */
    private function getEmpresaData() {
        $query = "SELECT razon_social, ruc FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registrar cambios en las actualizaciones
     */
    private function logEmpresaChanges($old_data) {
        $changes = [];
        
        if ($old_data['razon_social'] != $this->razon_social) {
            $changes[] = "Razón Social: {$old_data['razon_social']} → {$this->razon_social}";
        }
        if ($old_data['ruc'] != $this->ruc) {
            $changes[] = "RUC: {$old_data['ruc']} → {$this->ruc}";
        }
        if ($old_data['representante_legal'] != $this->representante_legal) {
            $changes[] = "Representante Legal: {$old_data['representante_legal']} → {$this->representante_legal}";
        }
        
        if (!empty($changes)) {
            $detalles = json_encode([
                'empresa_id' => $this->id,
                'cambios' => $changes,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ]);
            
            $this->logNotification('EMPRESA_ACTUALIZADA', "Empresa actualizada: {$this->razon_social}. Cambios: " . count($changes), null, $detalles);
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