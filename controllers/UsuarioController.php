<?php
class UsuarioController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new Usuario($db);
        $this->db = $db;
    }

    /**
     * Mostrar lista de usuarios
     */
    public function index() {
        $stmt = $this->model->read();
        $db_connection = $this->db;
        include_once 'views/usuarios/index.php';
    }

    /**
     * Mostrar formulario y procesar creación de nuevo usuario
     */
    public function create() {
        $error = '';
        $success = '';
        
        if($_POST) {
            $this->model->nombre = trim($_POST['nombre']);
            $this->model->usuario = trim($_POST['usuario']);
            $this->model->password = trim($_POST['password']);
            $this->model->estado = intval($_POST['estado']);

            // Validaciones
            if (empty($this->model->nombre)) {
                $error = 'El nombre es obligatorio';
            } elseif (empty($this->model->usuario)) {
                $error = 'El usuario es obligatorio';
            } elseif (empty($this->model->password)) {
                $error = 'La contraseña es obligatoria';
            } elseif (strlen($this->model->password) < 4) {
                $error = 'La contraseña debe tener al menos 4 caracteres';
            } else {
                // Validar usuario único
                if ($this->model->usuarioExists($this->model->usuario)) {
                    $error = 'El usuario ya está registrado en el sistema';
                } else {
                    if($this->model->create()) {
                        $success = 'Usuario creado exitosamente';
                        $_SESSION['success_message'] = $success;
                        header("Location: index.php?controller=usuarios&action=index");
                        exit();
                    } else {
                        $error = 'Error al crear el usuario';
                    }
                }
            }
        }
        
        $db_connection = $this->db;
        include_once 'views/usuarios/create.php';
    }

    /**
     * Mostrar formulario y procesar edición de usuario
     */
    public function edit() {
        $this->model->id = intval($_GET['id']);
        $error = '';
        $success = '';
        
        if($_POST) {
            $this->model->id = intval($_POST['id']);
            $this->model->nombre = trim($_POST['nombre']);
            $this->model->usuario = trim($_POST['usuario']);
            $this->model->password = trim($_POST['password']);
            $this->model->estado = intval($_POST['estado']);

            // Validaciones
            if (empty($this->model->nombre)) {
                $error = 'El nombre es obligatorio';
            } elseif (empty($this->model->usuario)) {
                $error = 'El usuario es obligatorio';
            } else {
                // Si se proporcionó nueva contraseña, validarla
                if (!empty($this->model->password) && strlen($this->model->password) < 4) {
                    $error = 'La contraseña debe tener al menos 4 caracteres';
                } else {
                    // Validar usuario único excluyendo el actual
                    if ($this->model->usuarioExists($this->model->usuario, $this->model->id)) {
                        $error = 'El usuario ya está registrado en el sistema por otro usuario';
                    } else {
                        // Si no se proporcionó nueva contraseña, mantener la actual
                        if (empty($this->model->password)) {
                            $current_user = new Usuario($this->db);
                            $current_user->id = $this->model->id;
                            if ($current_user->readOne()) {
                                $this->model->password = $current_user->password;
                            }
                        }
                        
                        if($this->model->update()) {
                            $success = 'Usuario actualizado exitosamente';
                            $_SESSION['success_message'] = $success;
                            header("Location: index.php?controller=usuarios&action=index");
                            exit();
                        } else {
                            $error = 'Error al actualizar el usuario';
                        }
                    }
                }
            }
        } else {
            if (!$this->model->readOne()) {
                $_SESSION['error_message'] = 'Usuario no encontrado';
                header("Location: index.php?controller=usuarios&action=index");
                exit();
            }
        }
        
        $db_connection = $this->db;
        include_once 'views/usuarios/edit.php';
    }

    /**
     * Eliminar usuario (eliminación lógica)
     */
    public function delete() {
        $this->model->id = intval($_GET['id']);
        
        try {
            if($this->model->delete()) {
                $_SESSION['success_message'] = 'Usuario eliminado exitosamente';
            } else {
                $_SESSION['error_message'] = 'Error al eliminar el usuario';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }
        
        header("Location: index.php?controller=usuarios&action=index");
        exit();
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleStatus() {
        $this->model->id = intval($_GET['id']);
        
        if($this->model->readOne()) {
            $nuevoEstado = $this->model->estado == 1 ? 0 : 1;
            $this->model->estado = $nuevoEstado;
            
            if($this->model->update()) {
                $estadoTexto = $nuevoEstado == 1 ? 'activado' : 'desactivado';
                $_SESSION['success_message'] = "Usuario {$estadoTexto} exitosamente";
            } else {
                $_SESSION['error_message'] = 'Error al cambiar el estado del usuario';
            }
        } else {
            $_SESSION['error_message'] = 'Usuario no encontrado';
        }
        
        header("Location: index.php?controller=usuarios&action=index");
        exit();
    }

    /**
     * Mostrar detalles de un usuario específico
     */
    public function view() {
        $this->model->id = intval($_GET['id']);
        
        if($this->model->readOne()) {
            $db_connection = $this->db;
            include_once 'views/usuarios/view.php';
        } else {
            $_SESSION['error_message'] = 'Usuario no encontrado';
            header("Location: index.php?controller=usuarios&action=index");
            exit();
        }
    }
}
?>