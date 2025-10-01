<?php
class ClientApiController {
    private $model;

    public function __construct($db) {
        $this->model = new ClientApi($db);
    }

    public function index() {
        $stmt = $this->model->read();
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