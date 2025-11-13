<?php
class ClientApiController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new ClientApi($db);
        $this->db = $db;
    }

    /**
     * Mostrar lista de clientes API con opciones de búsqueda
     */
    public function index() {
        $search_keywords = isset($_GET['search']) ? trim($_GET['search']) : '';
        $advanced_search = isset($_GET['advanced_search']) ? true : false;
        
        if (!empty($search_keywords)) {
            if ($advanced_search) {
                $ruc = isset($_GET['ruc']) ? trim($_GET['ruc']) : '';
                $razon_social = isset($_GET['razon_social']) ? trim($_GET['razon_social']) : '';
                $estado = isset($_GET['estado']) ? $_GET['estado'] : '';
                
                $stmt = $this->model->advancedSearch($ruc, $razon_social, $estado);
            } else {
                $stmt = $this->model->search($search_keywords);
            }
        } else {
            $stmt = $this->model->read();
        }
        
        $db_connection = $this->db;
        include_once 'views/client_api/index.php';
    }

    /**
     * Mostrar formulario y procesar creación de nuevo cliente API
     */
    public function create() {
        $error = '';
        $success = '';
        
        if($_POST) {
            $this->model->ruc = trim($_POST['ruc']);
            $this->model->razon_social = trim($_POST['razon_social']);
            $this->model->telefono = trim($_POST['telefono']);
            $this->model->correo = trim($_POST['correo']);
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = 1;

            // Validaciones
            if (empty($this->model->ruc)) {
                $error = 'El RUC es obligatorio';
            } elseif (!preg_match('/^\d{11}$/', $this->model->ruc)) {
                $error = 'El RUC debe tener 11 dígitos';
            } elseif (empty($this->model->razon_social)) {
                $error = 'La razón social es obligatoria';
            } else {
                // Validar RUC único
                if ($this->model->rucExists($this->model->ruc)) {
                    $error = 'El RUC ya está registrado en el sistema';
                } else {
                    if($this->model->create()) {
                        $success = 'Cliente API creado exitosamente';
                        $_SESSION['success_message'] = $success;
                        header("Location: index.php?controller=client_api&action=index");
                        exit();
                    } else {
                        $error = 'Error al crear el cliente API';
                    }
                }
            }
        }
        
        $db_connection = $this->db;
        include_once 'views/client_api/create.php';
    }

    /**
     * Mostrar formulario y procesar edición de cliente API
     */
    public function edit() {
        $this->model->id = intval($_GET['id']);
        $error = '';
        $success = '';
        
        if($_POST) {
            $this->model->id = intval($_POST['id']);
            $this->model->ruc = trim($_POST['ruc']);
            $this->model->razon_social = trim($_POST['razon_social']);
            $this->model->telefono = trim($_POST['telefono']);
            $this->model->correo = trim($_POST['correo']);
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = intval($_POST['estado']);

            // Validaciones
            if (empty($this->model->ruc)) {
                $error = 'El RUC es obligatorio';
            } elseif (!preg_match('/^\d{11}$/', $this->model->ruc)) {
                $error = 'El RUC debe tener 11 dígitos';
            } elseif (empty($this->model->razon_social)) {
                $error = 'La razón social es obligatoria';
            } else {
                // Validar RUC único excluyendo el actual
                if ($this->model->rucExists($this->model->ruc, $this->model->id)) {
                    $error = 'El RUC ya está registrado en el sistema por otro cliente';
                } else {
                    if($this->model->update()) {
                        $success = 'Cliente API actualizado exitosamente';
                        $_SESSION['success_message'] = $success;
                        header("Location: index.php?controller=client_api&action=index");
                        exit();
                    } else {
                        $error = 'Error al actualizar el cliente API';
                    }
                }
            }
        } else {
            if (!$this->model->readOne()) {
                $_SESSION['error_message'] = 'Cliente API no encontrado';
                header("Location: index.php?controller=client_api&action=index");
                exit();
            }
        }
        
        $db_connection = $this->db;
        include_once 'views/client_api/edit.php';
    }

    /**
     * Eliminar cliente API (eliminación lógica)
     */
    public function delete() {
        $this->model->id = intval($_GET['id']);
        
        // Verificar si el cliente tiene tokens activos
        $tokenModel = new TokenApi($this->db);
        $tokensActivos = $tokenModel->getByClient($this->model->id);
        
        if ($tokensActivos->rowCount() > 0) {
            $_SESSION['error_message'] = 'No se puede eliminar el cliente porque tiene tokens activos asociados';
            header("Location: index.php?controller=client_api&action=index");
            exit();
        }
        
        if($this->model->delete()) {
            $_SESSION['success_message'] = 'Cliente API eliminado exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar el cliente API';
        }
        
        header("Location: index.php?controller=client_api&action=index");
        exit();
    }

    /**
     * NUEVO: Mostrar detalles de un cliente API específico
     */
    public function view() {
        $this->model->id = intval($_GET['id']);
        
        if($this->model->readOne()) {
            // Obtener tokens del cliente
            $tokenModel = new TokenApi($this->db);
            $tokens = $tokenModel->getByClient($this->model->id);
            
            // Obtener estadísticas de requests
            $requestModel = new CountRequest($this->db);
            $requests = $requestModel->getByClient($this->model->id);
            
            $db_connection = $this->db;
            include_once 'views/client_api/view.php';
        } else {
            $_SESSION['error_message'] = 'Cliente API no encontrado';
            header("Location: index.php?controller=client_api&action=index");
            exit();
        }
    }

    /**
     * NUEVO: Activar/Desactivar cliente API
     */
    public function toggleStatus() {
        $this->model->id = intval($_GET['id']);
        
        if($this->model->readOne()) {
            $nuevoEstado = $this->model->estado == 1 ? 0 : 1;
            $this->model->estado = $nuevoEstado;
            
            if($this->model->update()) {
                $estadoTexto = $nuevoEstado == 1 ? 'activado' : 'desactivado';
                $_SESSION['success_message'] = "Cliente API {$estadoTexto} exitosamente";
            } else {
                $_SESSION['error_message'] = 'Error al cambiar el estado del cliente API';
            }
        } else {
            $_SESSION['error_message'] = 'Cliente API no encontrado';
        }
        
        header("Location: index.php?controller=client_api&action=index");
        exit();
    }
}
?>