<?php
class CountRequestController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new CountRequest($db);
        $this->db = $db;
    }

    public function index() {
        $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : '';
        $token_id = isset($_GET['token_id']) ? $_GET['token_id'] : '';
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
        
        if (!empty($client_id)) {
            $stmt = $this->model->getByClient($client_id);
        } else if (!empty($token_id)) {
            $stmt = $this->model->getByToken($token_id);
        } else if (!empty($tipo) || !empty($fecha)) {
            // Búsqueda avanzada
            $stmt = $this->advancedSearch($tipo, $fecha);
        } else {
            $stmt = $this->model->read();
        }
        
        $db_connection = $this->db;
        include_once 'views/count_request/index.php';
    }

    public function create() {
        $tokens = $this->model->getTokens();
        $error = '';
        $success = '';
        $db_connection = $this->db;
        
        if($_POST) {
            if (empty($_POST['id_token_api'])) {
                $error = 'Debe seleccionar un token.';
            } else {
                $this->model->id_token_api = $_POST['id_token_api'];
                $this->model->tipo = $_POST['tipo'];
                $this->model->fecha = $_POST['fecha'];

                if($this->model->create()) {
                    $success = 'Request registrado exitosamente.';
                    // Redirigir después de 2 segundos
                    echo '<script>setTimeout(function(){ window.location.href = "index.php?controller=count_request&action=index"; }, 2000);</script>';
                } else {
                    $error = 'Error al registrar el request. Intente nuevamente.';
                }
            }
        }
        include_once 'views/count_request/create.php';
    }

    public function edit() {
        $this->model->id = $_GET['id'];
        $tokens = $this->model->getTokens();
        $error = '';
        $success = '';
        $db_connection = $this->db;
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->id_token_api = $_POST['id_token_api'];
            $this->model->tipo = $_POST['tipo'];
            $this->model->fecha = $_POST['fecha'];

            if($this->model->update()) {
                $success = 'Request actualizado exitosamente.';
            } else {
                $error = 'Error al actualizar el request. Intente nuevamente.';
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

    // NUEVO: Búsqueda avanzada
    private function advancedSearch($tipo = null, $fecha = null) {
        $query = "SELECT cr.*, t.token, c.razon_social, c.id as client_id 
                 FROM " . $this->model->table_name . " cr 
                 LEFT JOIN tokens_api t ON cr.id_token_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE 1=1";
        
        $params = array();
        
        if (!empty($tipo)) {
            $query .= " AND cr.tipo = ?";
            $params[] = $tipo;
        }
        
        if (!empty($fecha)) {
            $query .= " AND cr.fecha = ?";
            $params[] = $fecha;
        }
        
        $query .= " ORDER BY cr.id DESC";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        return $stmt;
    }

    // NUEVO: Estadísticas
    public function stats() {
        $stats_by_type = $this->model->getStatsByType();
        $total_requests = $this->model->read()->rowCount();
        
        $db_connection = $this->db;
        include_once 'views/count_request/stats.php';
    }
}
?>