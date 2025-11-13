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

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE ruc LIKE ? OR razon_social LIKE ? OR representante_legal LIKE ?
                 ORDER BY id DESC";
        
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
}
?>