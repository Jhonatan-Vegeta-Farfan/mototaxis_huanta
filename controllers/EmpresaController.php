<?php
class EmpresaController {
    private $model;

    public function __construct($db) {
        $this->model = new Empresa($db);
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/empresas/index.php';
    }

    public function create() {
        if($_POST) {
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->ruc = $_POST['ruc'];
            $this->model->representante_legal = $_POST['representante_legal'];

            if($this->model->create()) {
                header("Location: index.php?controller=empresas&action=index");
                exit();
            }
        }
        include_once 'views/empresas/create.php';
    }

    public function edit() {
        $this->model->id = $_GET['id'];
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->ruc = $_POST['ruc'];
            $this->model->representante_legal = $_POST['representante_legal'];

            if($this->model->update()) {
                header("Location: index.php?controller=empresas&action=index");
                exit();
            }
        } else {
            $this->model->readOne();
        }
        include_once 'views/empresas/edit.php';
    }

    public function delete() {
        $this->model->id = $_GET['id'];
        if($this->model->delete()) {
            header("Location: index.php?controller=empresas&action=index");
            exit();
        }
    }
}
?>