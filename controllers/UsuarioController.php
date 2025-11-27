<?php
// controllers/UsuariosController.php
class UsuariosController {
    private $conn;
    private $usuario;

    public function __construct($db) {
        $this->conn = $db;
        $this->usuario = new Usuario($db);
    }

    // Listar todos los usuarios
    public function index() {
        try {
            $stmt = $this->usuario->read();
            return $stmt;
        } catch (Exception $e) {
            throw new Exception("Error al obtener usuarios: " . $e->getMessage());
        }
    }

    // Mostrar formulario de creación
    public function create() {
        // Retorna datos vacíos para el formulario
        return [
            'id' => '',
            'nombre' => '',
            'usuario' => '',
            'password' => '',
            'estado' => 1
        ];
    }

    // Guardar nuevo usuario
    public function store($data) {
        try {
            // Validar datos
            if (empty($data['nombre']) || empty($data['usuario']) || empty($data['password'])) {
                throw new Exception("Todos los campos son obligatorios");
            }

            // Verificar si el usuario ya existe
            if ($this->usuario->usuarioExists($data['usuario'])) {
                throw new Exception("El nombre de usuario ya está en uso");
            }

            $this->usuario->nombre = $data['nombre'];
            $this->usuario->usuario = $data['usuario'];
            $this->usuario->password = $data['password'];
            $this->usuario->estado = isset($data['estado']) ? $data['estado'] : 1;

            if ($this->usuario->create()) {
                return true;
            }
            throw new Exception("Error al crear el usuario");
            
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Mostrar usuario específico
    public function show($id) {
        try {
            $this->usuario->id = $id;
            if ($this->usuario->readOne()) {
                return [
                    'id' => $this->usuario->id,
                    'nombre' => $this->usuario->nombre,
                    'usuario' => $this->usuario->usuario,
                    'password' => $this->usuario->password,
                    'fecha_registro' => $this->usuario->fecha_registro,
                    'estado' => $this->usuario->estado
                ];
            }
            throw new Exception("Usuario no encontrado");
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Mostrar formulario de edición
    public function edit($id) {
        return $this->show($id);
    }

    // Actualizar usuario
    public function update($id, $data) {
        try {
            // Validar datos
            if (empty($data['nombre']) || empty($data['usuario'])) {
                throw new Exception("Nombre y usuario son obligatorios");
            }

            // Verificar si el usuario ya existe (excluyendo el actual)
            if ($this->usuario->usuarioExists($data['usuario'], $id)) {
                throw new Exception("El nombre de usuario ya está en uso");
            }

            $this->usuario->id = $id;
            $this->usuario->nombre = $data['nombre'];
            $this->usuario->usuario = $data['usuario'];
            
            // Solo actualizar password si se proporcionó uno nuevo
            if (!empty($data['password'])) {
                $this->usuario->password = $data['password'];
            } else {
                // Mantener el password actual
                $currentUser = $this->show($id);
                $this->usuario->password = $currentUser['password'];
            }
            
            $this->usuario->estado = $data['estado'];

            if ($this->usuario->update()) {
                return true;
            }
            throw new Exception("Error al actualizar el usuario");
            
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Eliminar usuario
    public function destroy($id) {
        try {
            $this->usuario->id = $id;
            if ($this->usuario->delete()) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Cambiar estado del usuario
    public function toggleStatus($id) {
        try {
            $user = $this->show($id);
            $this->usuario->id = $id;
            
            if ($user['estado'] == 1) {
                // Desactivar usuario
                if ($this->usuario->delete()) {
                    return true;
                }
            } else {
                // Activar usuario
                if ($this->usuario->activate()) {
                    return true;
                }
            }
            throw new Exception("Error al cambiar el estado del usuario");
            
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
?>