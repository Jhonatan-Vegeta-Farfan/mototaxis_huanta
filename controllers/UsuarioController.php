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
            $this->model->estado = isset($_POST['estado']) ? 1 : 0;

            // Validaciones
            if (empty($this->model->nombre)) {
                $error = 'El nombre es obligatorio';
            } elseif (empty($this->model->usuario)) {
                $error = 'El usuario es obligatorio';
            } elseif (empty($this->model->password)) {
                $error = 'La contraseña es obligatoria';
            } elseif (strlen($this->model->password) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            } elseif ($_POST['password'] !== $_POST['confirm_password']) {
                $error = 'Las contraseñas no coinciden';
            } else {
                // Validar usuario único
                if ($this->model->usuarioExists($this->model->usuario)) {
                    $error = 'El usuario ya está registrado en el sistema';
                } else {
                    if($this->model->create()) {
                        $_SESSION['success_message'] = 'Usuario creado exitosamente';
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
                // Validar usuario único excluyendo el actual
                if ($this->model->usuarioExists($this->model->usuario, $this->model->id)) {
                    $error = 'El usuario ya está registrado en el sistema por otro usuario';
                } else {
                    // Si la contraseña está vacía, mantener la actual
                    if (empty($this->model->password)) {
                        // Obtener el usuario actual para mantener la contraseña
                        $current_user = $this->model->readOne();
                        if ($current_user) {
                            $this->model->password = ''; // Se mantendrá la actual en el modelo
                        }
                    } else {
                        // Validar nueva contraseña
                        if (strlen($this->model->password) < 6) {
                            $error = 'La nueva contraseña debe tener al menos 6 caracteres';
                        } elseif ($_POST['password'] !== $_POST['confirm_password']) {
                            $error = 'Las contraseñas no coinciden';
                        }
                    }
                    
                    if (empty($error)) {
                        if($this->model->update()) {
                            $_SESSION['success_message'] = 'Usuario actualizado exitosamente';
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
     * Eliminar usuario (eliminación física)
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
            try {
                if($this->model->toggleStatus()) {
                    $estadoTexto = $this->model->estado == 1 ? 'activado' : 'desactivado';
                    $_SESSION['success_message'] = "Usuario {$estadoTexto} exitosamente";
                    
                    // Si el usuario desactivado es el mismo que está en sesión, cerrar sesión
                    if ($this->model->id == $_SESSION['user_id'] && $this->model->estado == 0) {
                        session_destroy();
                        header("Location: login.php");
                        exit();
                    }
                } else {
                    $_SESSION['error_message'] = 'Error al cambiar el estado del usuario';
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
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