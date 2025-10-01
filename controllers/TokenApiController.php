<?php
class TokenApiController {
    private $model;

    public function __construct($db) {
        $this->model = new TokenApi($db);
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/tokens_api/index.php';
    }

    public function create() {
        $clientes = $this->model->getClientes();
        
        if($_POST) {
            $this->model->id_client_api = $_POST['id_client_api'];
            $this->model->token = $_POST['token'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = 1;

            if($this->model->create()) {
                header("Location: index.php?controller=tokens_api&action=index");
                exit();
            }
        }
        include_once 'views/tokens_api/create.php';
    }

    public function edit() {
        $this->model->id = $_GET['id'];
        $clientes = $this->model->getClientes();
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->id_client_api = $_POST['id_client_api'];
            $this->model->token = $_POST['token'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = $_POST['estado'];

            if($this->model->update()) {
                header("Location: index.php?controller=tokens_api&action=index");
                exit();
            }
        } else {
            $this->model->readOne();
        }
        include_once 'views/tokens_api/edit.php';
    }

    public function delete() {
        $this->model->id = $_GET['id'];
        if($this->model->delete()) {
            header("Location: index.php?controller=tokens_api&action=index");
            exit();
        }
    }
}
?>