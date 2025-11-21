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
        return $stmt;
    }

    public function advancedSearch($ruc = null, $razon_social = null, $representante_legal = null) {
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
        
        if (!empty($representante_legal)) {
            $query .= " AND representante_legal LIKE ?";
            $params[] = "%{$representante_legal}%";
        }
        
        $query .= " ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function create() {
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
            
            return true;
        }
        return false;
    }

    public function update() {
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
            return true;
        }
        return false;
    }

    /**
     * Eliminar empresa y reorganizar IDs
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Verificar si la empresa tiene mototaxis asociados
            $query = "SELECT COUNT(*) as total FROM mototaxis WHERE id_empresa = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['total'] > 0) {
                throw new Exception('No se puede eliminar la empresa porque tiene mototaxis asociados');
            }
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar la empresa");
            }
            
            // Reorganizar IDs
            $this->reorganizarEmpresas();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error en delete: " . $e->getMessage());
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
                }
                $new_id++;
            }
            
            // 3. Resetear el auto_increment
            $reset_query = "ALTER TABLE " . $this->table_name . " AUTO_INCREMENT = 1";
            $this->conn->exec($reset_query);
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando empresas: " . $e->getMessage());
            return false;
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
        
        // Últimos registros - ACTUALIZADO: Ordenar por número asignado de mayor a menor
        $query = "SELECT numero_asignado, nombre_completo, fecha_registro 
                 FROM mototaxis 
                 WHERE id_empresa = ? 
                 ORDER BY CAST(numero_asignado AS UNSIGNED) DESC, numero_asignado DESC 
                 LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        $stats['ultimos_registros'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
     * Obtener mototaxis de una empresa específica ordenados por número asignado - NUEVO MÉTODO
     */
    public function getMototaxisByEmpresa($empresa_id) {
        $query = "SELECT m.*, e.razon_social as empresa 
                 FROM mototaxis m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 WHERE m.id_empresa = ?
                 ORDER BY CAST(m.numero_asignado AS UNSIGNED) DESC, m.numero_asignado DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Buscar mototaxis en una empresa específica - NUEVO MÉTODO
     */
    public function searchMototaxisInEmpresa($empresa_id, $keywords) {
        $query = "SELECT m.*, e.razon_social as empresa 
                 FROM mototaxis m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 WHERE m.id_empresa = ? AND 
                       (m.numero_asignado LIKE ? OR m.nombre_completo LIKE ? OR m.dni LIKE ? OR m.placa_rodaje LIKE ?)
                 ORDER BY CAST(m.numero_asignado AS UNSIGNED) DESC, m.numero_asignado DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $empresa_id);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        $stmt->bindParam(5, $keywords);
        
        $stmt->execute();
        return $stmt;
    }

    /**
     * Exportar mototaxis de una empresa a Excel - NUEVO MÉTODO
     */
    public function exportMototaxisToExcel($empresa_id) {
        $query = "SELECT m.*, e.razon_social as empresa, e.ruc as ruc_empresa
                 FROM mototaxis m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 WHERE m.id_empresa = ?
                 ORDER BY CAST(m.numero_asignado AS UNSIGNED) DESC, m.numero_asignado DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        
        // Obtener información de la empresa para el nombre del archivo
        $empresa_info = $this->readOne();
        $nombre_archivo = "mototaxis_empresa_" . ($this->razon_social ?? $empresa_id) . "_" . date('Y-m-d') . ".xls";
        
        // Configurar headers para descarga
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$nombre_archivo\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // Generar contenido Excel
        echo "ID\tNúmero Asignado\tNombre Completo\tDNI\tDirección\tPlaca Rodaje\tAño Fabricación\tMarca\tNúmero Motor\tTipo Motor\tSerie\tColor\tFecha Registro\tEmpresa\n";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo ($row['id'] ?? '') . "\t";
            echo ($row['numero_asignado'] ?? '') . "\t";
            echo ($row['nombre_completo'] ?? '') . "\t";
            echo ($row['dni'] ?? '') . "\t";
            echo ($row['direccion'] ?? '') . "\t";
            echo ($row['placa_rodaje'] ?? '') . "\t";
            echo ($row['anio_fabricacion'] ?? '') . "\t";
            echo ($row['marca'] ?? '') . "\t";
            echo ($row['numero_motor'] ?? '') . "\t";
            echo ($row['tipo_motor'] ?? '') . "\t";
            echo ($row['serie'] ?? '') . "\t";
            echo ($row['color'] ?? '') . "\t";
            echo ($row['fecha_registro'] ?? '') . "\t";
            echo ($row['empresa'] ?? '') . "\n";
        }
        exit();
    }

    /**
     * Reorganizar todos los IDs de empresas (método público para llamadas manuales)
     */
    public function reorganizarTodosLosIds() {
        return $this->reorganizarEmpresas();
    }
}
?>