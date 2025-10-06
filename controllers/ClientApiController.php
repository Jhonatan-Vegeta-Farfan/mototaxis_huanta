<?php
class ClientApiController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new ClientApi($db);
        $this->db = $db;
    }

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

    public function create() {
        if($_POST) {
            $this->model->ruc = $_POST['ruc'];
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->telefono = $_POST['telefono'];
            $this->model->correo = $_POST['correo'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = 1;

            if($this->model->create()) {
                header("Location: index.php?controller=client_api&action=index");
                exit();
            }
        }
        $db_connection = $this->db;
        include_once 'views/client_api/create.php';
    }

    public function edit() {
        $this->model->id = $_GET['id'];
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->ruc = $_POST['ruc'];
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->telefono = $_POST['telefono'];
            $this->model->correo = $_POST['correo'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = $_POST['estado'];

            if($this->model->update()) {
                header("Location: index.php?controller=client_api&action=index");
                exit();
            }
        } else {
            $this->model->readOne();
        }
        $db_connection = $this->db;
        include_once 'views/client_api/edit.php';
    }

    public function delete() {
        $this->model->id = $_GET['id'];
        if($this->model->delete()) {
            header("Location: index.php?controller=client_api&action=index");
            exit();
        }
    }
}
?>