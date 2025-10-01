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

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET razon_social=:razon_social, ruc=:ruc, 
                     representante_legal=:representante_legal";
        
        $stmt = $this->conn->prepare($query);
        
        $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->representante_legal = htmlspecialchars(strip_tags($this->representante_legal));
        
        $stmt->bindParam(":razon_social", $this->razon_social);
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":representante_legal", $this->representante_legal);
        
        if($stmt->execute()) {
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
}
?>