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
     * Verificar credenciales de usuario CON ESTADO ACTIVO
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
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
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
                 SET nombre=:nombre, usuario=:usuario, password=:password, 
                     fecha_registro=NOW(), estado=:estado";
        
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
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Actualizar usuario
     */
    public function update() {
        // Si la contraseña está vacía, mantener la actual
        if (empty($this->password)) {
            $query = "UPDATE " . $this->table_name . " 
                     SET nombre=:nombre, usuario=:usuario, estado=:estado
                     WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table_name . " 
                     SET nombre=:nombre, usuario=:usuario, password=:password, estado=:estado
                     WHERE id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);
        
        if (!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $stmt->bindParam(":password", $this->password);
        }
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Eliminar usuario (eliminación lógica)
     */
    public function delete() {
        try {
            // No permitir eliminar el último usuario activo
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE estado = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $total_activos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Verificar si el usuario a eliminar es el último activo
            $query = "SELECT estado FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $usuario_actual = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario_actual['estado'] == 1 && $total_activos <= 1) {
                throw new Exception("No se puede eliminar el último usuario activo del sistema");
            }
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar el usuario");
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Error en delete: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleStatus() {
        try {
            // No permitir desactivar el último usuario activo
            if ($this->estado == 1) {
                $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE estado = 1";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $total_activos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                if ($total_activos <= 1) {
                    throw new Exception("No se puede desactivar el último usuario activo del sistema");
                }
            }
            
            $nuevoEstado = $this->estado == 1 ? 0 : 1;
            $query = "UPDATE " . $this->table_name . " SET estado = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $nuevoEstado);
            $stmt->bindParam(2, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al cambiar el estado del usuario");
            }
            
            $this->estado = $nuevoEstado;
            return true;
            
        } catch (Exception $e) {
            error_log("Error en toggleStatus: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
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

    /**
     * Verificar estado del usuario actual en sesión
     */
    public function checkUserStatus($user_id) {
        $query = "SELECT estado FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['estado'] == 1;
        }
        return false;
    }
}
?>