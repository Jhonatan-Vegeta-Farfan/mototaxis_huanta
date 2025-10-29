<?php
class EmpresaController {
    private $model;

    public function __construct($db) {
        $this->model = new Empresa($db);
    }

    public function index() {
        // Verificar si hay búsqueda
        $search_keywords = isset($_GET['search']) ? $_GET['search'] : '';
        $advanced_search = isset($_GET['advanced_search']) ? true : false;
        
        if (!empty($search_keywords)) {
            if ($advanced_search) {
                // Búsqueda avanzada
                $ruc = isset($_GET['ruc']) ? $_GET['ruc'] : '';
                $razon_social = isset($_GET['razon_social']) ? $_GET['razon_social'] : '';
                $representante_legal = isset($_GET['representante_legal']) ? $_GET['representante_legal'] : '';
                
                $stmt = $this->model->advancedSearch($ruc, $razon_social, $representante_legal);
            } else {
                // Búsqueda simple
                $stmt = $this->model->search($search_keywords);
            }
        } else {
            // Mostrar todos los registros
            $stmt = $this->model->read();
        }
        
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

    // NUEVO MÉTODO PARA VER DETALLES
    public function view() {
        $this->model->id = $_GET['id'];
        
        if($this->model->readOne()) {
            include_once 'views/empresas/view.php';
        } else {
            header("Location: index.php?controller=empresas&action=index");
            exit();
        }
    }

    // NUEVO MÉTODO PARA BÚSQUEDA ESPECÍFICA
    public function search() {
        $search_keywords = isset($_GET['q']) ? $_GET['q'] : '';
        
        if (!empty($search_keywords)) {
            $stmt = $this->model->search($search_keywords);
        } else {
            $stmt = $this->model->read();
        }
        
        include_once 'views/empresas/index.php';
    }
}
?>