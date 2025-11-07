<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $usuario;
    public $password;
    public $fecha_registro;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Verificar credenciales de usuario
     */
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE usuario = ? AND estado = 1 
                 LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        
        try {
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verificar contraseña (en texto plano según la estructura actual)
                if ($password === $row['password']) {
                    $this->id = $row['id'];
                    $this->nombre = $row['nombre'];
                    $this->usuario = $row['usuario'];
                    $this->fecha_registro = $row['fecha_registro'];
                    $this->estado = $row['estado'];
                    return true;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Leer todos los usuarios
     */
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Leer un usuario específico
     */
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->usuario = $row['usuario'];
            $this->password = $row['password'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }

    /**
     * Crear nuevo usuario
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET nombre=:nombre, usuario=:usuario, password=:password, estado=:estado";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":estado", $this->estado);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Actualizar usuario
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET nombre=:nombre, usuario=:usuario, password=:password, estado=:estado
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Eliminar usuario (eliminación lógica)
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
     * Verificar si usuario ya existe
     */
    public function usuarioExists($usuario, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE usuario = ?";
        $params = [$usuario];
        
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