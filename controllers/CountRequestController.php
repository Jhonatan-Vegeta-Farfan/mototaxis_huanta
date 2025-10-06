<?php
class CountRequestController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new CountRequest($db);
        $this->db = $db;
    }

    /**
     * Mostrar lista de count requests con opciones de filtro
     */
    public function index() {
        $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : '';
        $token_id = isset($_GET['token_id']) ? $_GET['token_id'] : '';
        
        if (!empty($client_id)) {
            $stmt = $this->model->getByClient($client_id);
        } else if (!empty($token_id)) {
            $stmt = $this->model->getByToken($token_id);
        } else {
            $stmt = $this->model->read();
        }
        
        $db_connection = $this->db;
        include_once 'views/count_request/index.php';
    }

    /**
     * Crear nuevo count request
     */
    public function create() {
        $tokens = $this->model->getTokens();
        $db_connection = $this->db;
        
        if($_POST) {
            $this->model->id_token_api = $_POST['id_token_api'];
            $this->model->tipo = $_POST['tipo'];
            $this->model->fecha = $_POST['fecha'];

            if($this->model->create()) {
                header("Location: index.php?controller=count_request&action=index");
                exit();
            } else {
                $error = "Error al crear el request. Verifique que el token exista.";
            }
        }
        include_once 'views/count_request/create.php';
    }

    /**
     * Editar count request existente
     */
    public function edit() {
        $this->model->id = $_GET['id'];
        $tokens = $this->model->getTokens();
        $db_connection = $this->db;
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->id_token_api = $_POST['id_token_api'];
            $this->model->tipo = $_POST['tipo'];
            $this->model->fecha = $_POST['fecha'];

            if($this->model->update()) {
                header("Location: index.php?controller=count_request&action=index");
                exit();
            } else {
                $error = "Error al actualizar el request.";
            }
        } else {
            $this->model->readOne();
        }
        include_once 'views/count_request/edit.php';
    }

    /**
     * Eliminar count request
     */
    public function delete() {
        $this->model->id = $_GET['id'];
        if($this->model->delete()) {
            header("Location: index.php?controller=count_request&action=index");
            exit();
        } else {
            echo "Error al eliminar el request.";
        }
    }

    /**
     * Función helper para obtener client_id desde token_id
     */
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