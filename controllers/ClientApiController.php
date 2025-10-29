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
        $search_keywords = isset($_GET['search']) ? $_GET['search'] : '';
        $advanced_search = isset($_GET['advanced_search']) ? true : false;
        
        if (!empty($search_keywords)) {
            if ($advanced_search) {
                $ruc = isset($_GET['ruc']) ? $_GET['ruc'] : '';
                $razon_social = isset($_GET['razon_social']) ? $_GET['razon_social'] : '';
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
        
        if($_POST) {
            $this->model->ruc = $_POST['ruc'];
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->telefono = $_POST['telefono'];
            $this->model->correo = $_POST['correo'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = 1;

            // Validar RUC único
            if ($this->model->rucExists($this->model->ruc)) {
                $error = 'El RUC ya está registrado en el sistema';
            } else {
                if($this->model->create()) {
                    header("Location: index.php?controller=client_api&action=index");
                    exit();
                } else {
                    $error = 'Error al crear el cliente API';
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
        $this->model->id = $_GET['id'];
        $error = '';
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->ruc = $_POST['ruc'];
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->telefono = $_POST['telefono'];
            $this->model->correo = $_POST['correo'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = $_POST['estado'];

            // Validar RUC único excluyendo el actual
            if ($this->model->rucExists($this->model->ruc, $this->model->id)) {
                $error = 'El RUC ya está registrado en el sistema por otro cliente';
            } else {
                if($this->model->update()) {
                    header("Location: index.php?controller=client_api&action=index");
                    exit();
                } else {
                    $error = 'Error al actualizar el cliente API';
                }
            }
        } else {
            $this->model->readOne();
        }
        
        $db_connection = $this->db;
        include_once 'views/client_api/edit.php';
    }

    /**
     * Eliminar cliente API (eliminación lógica)
     */
    public function delete() {
        $this->model->id = $_GET['id'];
        if($this->model->delete()) {
            header("Location: index.php?controller=client_api&action=index");
            exit();
        } else {
            // Mostrar error si no se puede eliminar
            echo "<script>alert('Error al eliminar el cliente API'); window.location.href='index.php?controller=client_api&action=index';</script>";
        }
    }

    /**
     * NUEVO: Mostrar detalles de un cliente API específico
     */
    public function view() {
        $this->model->id = $_GET['id'];
        
        if($this->model->readOne()) {
            $db_connection = $this->db;
            include_once 'views/client_api/view.php';
        } else {
            header("Location: index.php?controller=client_api&action=index");
            exit();
        }
    }
}
?>