<?php
class CountRequestController {
    private $model;

    public function __construct($db) {
        $this->model = new CountRequest($db);
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/count_request/index.php';
    }

    public function create() {
        $tokens = $this->model->getTokens();
        
        if($_POST) {
            $this->model->id_token_api = $_POST['id_token_api'];
            $this->model->tipo = $_POST['tipo'];
            $this->model->fecha = $_POST['fecha'];

            if($this->model->create()) {
                header("Location: index.php?controller=count_request&action=index");
                exit();
            }
        }
        include_once 'views/count_request/create.php';
    }

    public function edit() {
        $this->model->id = $_GET['id'];
        $tokens = $this->model->getTokens();
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->id_token_api = $_POST['id_token_api'];
            $this->model->tipo = $_POST['tipo'];
            $this->model->fecha = $_POST['fecha'];

            if($this->model->update()) {
                header("Location: index.php?controller=count_request&action=index");
                exit();
            }
        } else {
            $this->model->readOne();
        }
        include_once 'views/count_request/edit.php';
    }

    public function delete() {
        $this->model->id = $_GET['id'];
        if($this->model->delete()) {
            header("Location: index.php?controller=count_request&action=index");
            exit();
        }
    }
}
?>