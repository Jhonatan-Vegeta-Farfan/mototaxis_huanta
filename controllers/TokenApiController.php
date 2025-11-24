<?php
class TokenApiController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new TokenApi($db);
        $this->db = $db;
    }

    /**
     * Mostrar lista de tokens con opción de filtro por cliente
     */
    public function index() {
        $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : '';
        
        if (!empty($client_id)) {
            $stmt = $this->model->getByClient($client_id);
        } else {
            $stmt = $this->model->read();
        }
        
        $db_connection = $this->db;
        include_once 'views/tokens_api/index.php';
    }

    /**
     * Mostrar formulario y procesar creación de nuevo token
     */
    public function create() {
        $clientes = $this->model->getClientes();
        $error = '';
        $success = '';
        $db_connection = $this->db;
        
        if($_POST) {
            $this->model->id_client_api = $_POST['id_client_api'];
            // Token y fecha se generan automáticamente
            $this->model->estado = 1;

            try {
                if($this->model->create()) {
                    $success = 'Token creado exitosamente. Token generado: ' . $this->model->token;
                    // Mostrar mensaje de éxito
                    echo "<script>
                        alert('Token creado exitosamente: " . $this->model->token . "');
                        window.location.href = 'index.php?controller=tokens_api&action=index';
                    </script>";
                    return;
                } else {
                    $error = 'Error al crear el token. Verifique que el cliente exista.';
                }
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
        include_once 'views/tokens_api/create.php';
    }

    /**
     * Mostrar formulario y procesar edición de token
     */
    public function edit() {
        $this->model->id = $_GET['id'];
        $clientes = $this->model->getClientes();
        $error = '';
        $success = '';
        $db_connection = $this->db;
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->id_client_api = $_POST['id_client_api'];
            $this->model->token = $_POST['token'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->estado = $_POST['estado'];

            // Validar token único excluyendo el actual
            if ($this->model->tokenExists($this->model->token, $this->model->id)) {
                $error = 'El token ya está en uso por otro registro';
            } else {
                if($this->model->update()) {
                    $success = 'Token actualizado exitosamente';
                    header("Location: index.php?controller=tokens_api&action=index&success=1");
                    exit();
                } else {
                    $error = 'Error al actualizar el token';
                }
            }
        } else {
            $this->model->readOne();
        }
        include_once 'views/tokens_api/edit.php';
    }

    /**
     * Eliminar token (eliminación lógica)
     */
    public function delete() {
        $this->model->id = $_GET['id'];
        if($this->model->delete()) {
            header("Location: index.php?controller=tokens_api&action=index&deleted=1");
            exit();
        } else {
            echo "<script>
                alert('Error al eliminar el token');
                window.location.href = 'index.php?controller=tokens_api&action=index';
            </script>";
        }
    }

    /**
     * Activar token
     */
    public function activate() {
        $this->model->id = $_GET['id'];
        if($this->model->activate()) {
            header("Location: index.php?controller=tokens_api&action=index&activated=1");
            exit();
        } else {
            echo "<script>
                alert('Error al activar el token');
                window.location.href = 'index.php?controller=tokens_api&action=index';
            </script>";
        }
    }

    /**
     * Mostrar detalles de un token específico
     */
    public function view() {
        $this->model->id = $_GET['id'];
        
        if($this->model->readOne()) {
            $clientes = $this->model->getClientes();
            $db_connection = $this->db;
            include_once 'views/tokens_api/view.php';
        } else {
            header("Location: index.php?controller=tokens_api&action=index");
            exit();
        }
    }

    /**
     * Validar token desde el panel administrativo
     */
    public function validate() {
        $this->model->id = $_GET['id'];
        
        if($this->model->readOne()) {
            // Validar el token
            $validation = $this->model->validateToken($this->model->token);
            
            if ($validation['valid']) {
                echo "<script>
                    alert('Token válido y activo');
                    window.location.href = 'index.php?controller=tokens_api&action=index';
                </script>";
            } else {
                echo "<script>
                    alert('Token inválido: " . $validation['message'] . "');
                    window.location.href = 'index.php?controller=tokens_api&action=index';
                </script>";
            }
        } else {
            header("Location: index.php?controller=tokens_api&action=index");
            exit();
        }
    }
}
?>