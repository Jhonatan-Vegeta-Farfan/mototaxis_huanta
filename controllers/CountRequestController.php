<?php
class CountRequestController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new CountRequest($db);
        $this->db = $db;
    }

    public function index() {
        // Verificar si hay filtro por cliente o token
        $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : '';
        $token_id = isset($_GET['token_id']) ? $_GET['token_id'] : '';
        
        if (!empty($client_id)) {
            // Filtrar por cliente específico
            $stmt = $this->model->getByClient($client_id);
        } else if (!empty($token_id)) {
            // Filtrar por token específico
            $stmt = $this->model->getByToken($token_id);
        } else {
            // Mostrar todos los registros
            $stmt = $this->model->read();
        }
        
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

    // NUEVA FUNCIÓN HELPER PARA LA VISTA
    public function getClientIdFromToken($tokenId) {
        $tokenModel = new TokenApi($this->db);
        $tokenModel->id = $tokenId;
        if ($tokenModel->readOne()) {
            return $tokenModel->id_client_api;
        }
        return $tokenId;
    }
}
?>