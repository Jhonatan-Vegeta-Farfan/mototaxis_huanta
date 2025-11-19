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
                    
                    // Registrar inicio de sesión exitoso
                    $this->logNotification('LOGIN_EXITOSO', "Inicio de sesión exitoso: $username", $this->id);
                    
                    return true;
                } else {
                    $this->logNotification('LOGIN_FALLIDO', "Intento de inicio de sesión fallido - Contraseña incorrecta: $username");
                }
            } else {
                $this->logNotification('LOGIN_FALLIDO', "Intento de inicio de sesión fallido - Usuario no encontrado: $username");
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            $this->logNotification('ERROR_LOGIN', "Error en proceso de login: " . $e->getMessage());
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
        try {
            $this->conn->beginTransaction();
            
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
                $this->id = $this->conn->lastInsertId();
                
                // Reorganizar IDs después de crear
                $this->reorganizarUsuarios();
                
                // Registrar notificación
                $detalles = json_encode([
                    'usuario_id' => $this->id,
                    'nombre' => $this->nombre,
                    'usuario' => $this->usuario,
                    'estado' => $this->estado == 1 ? 'Activo' : 'Inactivo',
                    'fecha_registro' => date('Y-m-d H:i:s')
                ]);
                
                $this->logNotification('USUARIO_CREADO', "Nuevo usuario creado: {$this->nombre} ({$this->usuario})", null, $detalles);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la inserción");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_CREACION_USUARIO', "Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar usuario
     */
    public function update() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos antiguos
            $old_data = $this->getOldData();
            
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
                // Registrar cambios
                $this->logUsuarioChanges($old_data);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la actualización");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTUALIZACION_USUARIO', "Error al actualizar usuario ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar usuario (eliminación lógica) y reorganizar
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos del usuario antes de eliminar
            $usuario_data = $this->getUsuarioData();
            
            // No permitir eliminar el último usuario activo
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE estado = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $total_activos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            if ($total_activos <= 1) {
                throw new Exception("No se puede eliminar el último usuario activo del sistema");
            }
            
            $query = "UPDATE " . $this->table_name . " SET estado = 0 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar el usuario");
            }
            
            // Reorganizar IDs de usuarios activos
            $reorganizados = $this->reorganizarUsuariosActivos();
            
            // Registrar notificación
            $detalles = json_encode([
                'usuario_id' => $this->id,
                'nombre' => $usuario_data['nombre'],
                'usuario' => $usuario_data['usuario'],
                'usuarios_activos_restantes' => $total_activos - 1,
                'ids_reorganizados' => $reorganizados
            ]);
            
            $this->logNotification('USUARIO_ELIMINADO', "Usuario eliminado: {$usuario_data['nombre']} ({$usuario_data['usuario']})", null, $detalles);
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ELIMINACION_USUARIO', "Error al eliminar usuario ID: {$this->id} - " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Reorganizar IDs de usuarios activos
     */
    private function reorganizarUsuariosActivos() {
        try {
            // 1. Obtener todos los usuarios activos ordenados por ID actual
            $query = "SELECT id FROM " . $this->table_name . " WHERE estado = 1 ORDER BY id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $cambios = [];
            
            // 2. Actualizar IDs secuencialmente
            $new_id = 1;
            foreach ($usuarios as $usuario) {
                if ($usuario['id'] != $new_id) {
                    $update_query = "UPDATE " . $this->table_name . " SET id = ? WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $new_id);
                    $update_stmt->bindParam(2, $usuario['id']);
                    $update_stmt->execute();
                    
                    $cambios[] = [
                        'viejo_id' => $usuario['id'],
                        'nuevo_id' => $new_id
                    ];
                }
                $new_id++;
            }
            
            if (!empty($cambios)) {
                $this->logNotification('REORGANIZACION_USUARIOS', "IDs de usuarios reorganizados. Total cambios: " . count($cambios), null, json_encode($cambios));
            }
            
            return $cambios;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando usuarios: " . $e->getMessage());
            $this->logNotification('ERROR_REORGANIZACION_USUARIOS', "Error al reorganizar usuarios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Reorganizar todos los usuarios
     */
    private function reorganizarUsuarios() {
        try {
            // 1. Obtener todos los usuarios ordenados por ID actual
            $query = "SELECT id FROM " . $this->table_name . " ORDER BY id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $cambios = [];
            
            // 2. Actualizar IDs secuencialmente
            $new_id = 1;
            foreach ($usuarios as $usuario) {
                if ($usuario['id'] != $new_id) {
                    $update_query = "UPDATE " . $this->table_name . " SET id = ? WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $new_id);
                    $update_stmt->bindParam(2, $usuario['id']);
                    $update_stmt->execute();
                    
                    $cambios[] = [
                        'viejo_id' => $usuario['id'],
                        'nuevo_id' => $new_id
                    ];
                }
                $new_id++;
            }
            
            // 3. Resetear el auto_increment
            $reset_query = "ALTER TABLE " . $this->table_name . " AUTO_INCREMENT = 1";
            $this->conn->exec($reset_query);
            
            if (!empty($cambios)) {
                $this->logNotification('REORGANIZACION_USUARIOS', "IDs de usuarios reorganizados. Total cambios: " . count($cambios), null, json_encode($cambios));
            }
            
            return $cambios;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando usuarios: " . $e->getMessage());
            $this->logNotification('ERROR_REORGANIZACION_USUARIOS', "Error al reorganizar usuarios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Activar usuario
     */
    public function activate() {
        try {
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . " SET estado = 1 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if($stmt->execute()) {
                // Obtener datos del usuario
                $usuario_data = $this->getUsuarioData();
                
                // Reorganizar después de activar
                $this->reorganizarUsuariosActivos();
                
                // Registrar notificación
                $this->logNotification('USUARIO_ACTIVADO', "Usuario activado: {$usuario_data['nombre']} ({$usuario_data['usuario']})");
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al activar el usuario");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTIVACION_USUARIO', "Error al activar usuario ID: {$this->id} - " . $e->getMessage());
            return false;
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
     * Reorganizar todos los IDs de usuarios (método público para llamadas manuales)
     */
    public function reorganizarTodosLosIds() {
        $result = $this->reorganizarUsuarios();
        $this->logNotification('REORGANIZACION_MANUAL_USUARIOS', "Reorganización manual de IDs de usuarios completada", null, json_encode($result));
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
     * Obtener datos del usuario
     */
    private function getUsuarioData() {
        $query = "SELECT nombre, usuario FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registrar cambios en las actualizaciones
     */
    private function logUsuarioChanges($old_data) {
        $changes = [];
        
        if ($old_data['nombre'] != $this->nombre) {
            $changes[] = "Nombre: {$old_data['nombre']} → {$this->nombre}";
        }
        if ($old_data['usuario'] != $this->usuario) {
            $changes[] = "Usuario: {$old_data['usuario']} → {$this->usuario}";
        }
        if ($old_data['password'] != $this->password) {
            $changes[] = "Contraseña actualizada";
        }
        if ($old_data['estado'] != $this->estado) {
            $estado_old = $old_data['estado'] == 1 ? 'Activo' : 'Inactivo';
            $estado_new = $this->estado == 1 ? 'Activo' : 'Inactivo';
            $changes[] = "Estado: $estado_old → $estado_new";
        }
        
        if (!empty($changes)) {
            $detalles = json_encode([
                'usuario_id' => $this->id,
                'cambios' => $changes,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ]);
            
            $this->logNotification('USUARIO_ACTUALIZADO', "Usuario actualizado: {$this->nombre}. Cambios: " . count($changes), null, $detalles);
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