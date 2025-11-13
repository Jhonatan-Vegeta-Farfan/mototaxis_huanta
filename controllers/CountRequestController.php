<?php
class CountRequestController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new CountRequest($db);
        $this->db = $db;
    }

    // Mostrar lista de requests con filtros por cliente y token
    public function index() {
        $client_id = isset($_GET['client_id']) ? intval($_GET['client_id']) : '';
        $token_id = isset($_GET['token_id']) ? intval($_GET['token_id']) : '';
        $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
        $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
        
        if (!empty($client_id)) {
            $stmt = $this->model->getByClient($client_id);
        } else if (!empty($token_id)) {
            $stmt = $this->model->getByToken($token_id);
        } else if (!empty($fecha_inicio) || !empty($fecha_fin)) {
            $stmt = $this->model->getByDateRange($fecha_inicio, $fecha_fin);
        } else {
            $stmt = $this->model->read();
        }
        
        $db_connection = $this->db;
        include_once 'views/count_request/index.php';
    }

    // Crear nuevo request
    public function create() {
        $tokens = $this->model->getTokens();
        $db_connection = $this->db;
        $error = '';
        
        if($_POST) {
            $this->model->id_token_api = intval($_POST['id_token_api']);
            $this->model->tipo = trim($_POST['tipo']);
            $this->model->fecha = $_POST['fecha'];

            // Validaciones
            if (empty($this->model->id_token_api)) {
                $error = 'El token es obligatorio';
            } elseif (empty($this->model->tipo)) {
                $error = 'El tipo de request es obligatorio';
            } elseif (empty($this->model->fecha)) {
                $error = 'La fecha es obligatoria';
            } else {
                if($this->model->create()) {
                    $_SESSION['success_message'] = 'Request registrado exitosamente';
                    header("Location: index.php?controller=count_request&action=index");
                    exit();
                } else {
                    $error = 'Error al crear el request';
                }
            }
        }
        include_once 'views/count_request/create.php';
    }

    // Editar request existente
    public function edit() {
        $this->model->id = intval($_GET['id']);
        $tokens = $this->model->getTokens();
        $db_connection = $this->db;
        $error = '';
        
        if($_POST) {
            $this->model->id = intval($_POST['id']);
            $this->model->id_token_api = intval($_POST['id_token_api']);
            $this->model->tipo = trim($_POST['tipo']);
            $this->model->fecha = $_POST['fecha'];

            // Validaciones
            if (empty($this->model->id_token_api)) {
                $error = 'El token es obligatorio';
            } elseif (empty($this->model->tipo)) {
                $error = 'El tipo de request es obligatorio';
            } elseif (empty($this->model->fecha)) {
                $error = 'La fecha es obligatoria';
            } else {
                if($this->model->update()) {
                    $_SESSION['success_message'] = 'Request actualizado exitosamente';
                    header("Location: index.php?controller=count_request&action=index");
                    exit();
                } else {
                    $error = 'Error al actualizar el request';
                }
            }
        } else {
            if (!$this->model->readOne()) {
                $_SESSION['error_message'] = 'Request no encontrado';
                header("Location: index.php?controller=count_request&action=index");
                exit();
            }
        }
        include_once 'views/count_request/edit.php';
    }

    // Eliminar request permanentemente
    public function delete() {
        $this->model->id = intval($_GET['id']);
        if($this->model->delete()) {
            $_SESSION['success_message'] = 'Request eliminado exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar el request';
        }
        header("Location: index.php?controller=count_request&action=index");
        exit();
    }

    // NUEVO: Mostrar detalles de un request específico
    public function view() {
        $this->model->id = intval($_GET['id']);
        
        if($this->model->readOne()) {
            $tokens = $this->model->getTokens();
            $db_connection = $this->db;
            include_once 'views/count_request/view.php';
        } else {
            $_SESSION['error_message'] = 'Request no encontrado';
            header("Location: index.php?controller=count_request&action=index");
            exit();
        }
    }

    // NUEVO: Estadísticas de requests
    public function estadisticas() {
        $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
        $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');
        
        $stats = $this->model->getStats($fecha_inicio, $fecha_fin);
        $db_connection = $this->db;
        
        include_once 'views/count_request/estadisticas.php';
    }

    // NUEVO: Limpiar requests antiguos
    public function limpiar() {
        $dias = isset($_GET['dias']) ? intval($_GET['dias']) : 30;
        
        if ($this->model->cleanOldRequests($dias)) {
            $_SESSION['success_message'] = "Requests antiguos (más de {$dias} días) eliminados exitosamente";
        } else {
            $_SESSION['error_message'] = 'Error al limpiar requests antiguos';
        }
        
        header("Location: index.php?controller=count_request&action=index");
        exit();
    }
}
?>