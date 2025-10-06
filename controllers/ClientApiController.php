<?php
class ClientApiController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new ClientApi($db);
        $this->db = $db;
    }

    public function index() {
        $search_keywords = isset($_GET['search']) ? $_GET['search'] : '';
        $advanced_search = isset($_GET['advanced_search']) ? true : false;
        
        if (!empty($search_keywords)) {
            if ($advanced_search) {
                $ruc = isset($_GET['ruc']) ? $_GET['ruc'] : '';
                $razon_social = isset($_GET['razon_social']) ? $_GET['razon_social'] : '';
                $estado = isset($_GET['estado']) ? $_GET['estado'] : '';
                
                $stmt = $this->model->advancedSearch($ruc, $razon_social, $estado);
            } else {
                $stmt = $this->model->search($search_keywords);
            }
        } else {
            $stmt = $this->model->read();
        }
        
        $db_connection = $this->db;
        include_once 'views/client_api/index.php';
    }

    public function create() {
        $error = '';
        $success = '';
        
        if($_POST) {
            // Validar RUC único
            $check_query = "SELECT id FROM client_api WHERE ruc = ? AND estado = 1";
            $check_stmt = $this->db->prepare($check_query);
            $check_stmt->bindParam(1, $_POST['ruc']);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                $error = 'El RUC ya está registrado en el sistema.';
            } else {
                $this->model->ruc = $_POST['ruc'];
                $this->model->razon_social = $_POST['razon_social'];
                $this->model->telefono = $_POST['telefono'];
                $this->model->correo = $_POST['correo'];
                $this->model->fecha_registro = $_POST['fecha_registro'];
                $this->model->estado = 1;

                if($this->model->create()) {
                    $success = 'Cliente creado exitosamente.';
                    // Redirigir después de 2 segundos
                    echo '<script>setTimeout(function(){ window.location.href = "index.php?controller=client_api&action=index"; }, 2000);</script>';
                } else {
                    $error = 'Error al crear el cliente. Intente nuevamente.';
                }
            }
        }
        
        $db_connection = $this->db;
        include_once 'views/client_api/create.php';
    }

    public function edit() {
        $this->model->id = $_GET['id'];
        $error = '';
        $success = '';
        
        if($_POST) {
            // Validar RUC único (excluyendo el actual)
            $check_query = "SELECT id FROM client_api WHERE ruc = ? AND id != ? AND estado = 1";
            $check_stmt = $this->db->prepare($check_query);
            $check_stmt->bindParam(1, $_POST['ruc']);
            $check_stmt->bindParam(2, $_POST['id']);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                $error = 'El RUC ya está registrado por otro cliente.';
            } else {
                $this->model->id = $_POST['id'];
                $this->model->ruc = $_POST['ruc'];
                $this->model->razon_social = $_POST['razon_social'];
                $this->model->telefono = $_POST['telefono'];
                $this->model->correo = $_POST['correo'];
                $this->model->fecha_registro = $_POST['fecha_registro'];
                $this->model->estado = $_POST['estado'];

                if($this->model->update()) {
                    $success = 'Cliente actualizado exitosamente.';
                } else {
                    $error = 'Error al actualizar el cliente. Intente nuevamente.';
                }
            }
        } else {
            $this->model->readOne();
        }
        
        $db_connection = $this->db;
        include_once 'views/client_api/edit.php';
    }

    public function delete() {
        $this->model->id = $_GET['id'];
        
        // Verificar si tiene tokens activos
        $token_check = "SELECT COUNT(*) as total FROM tokens_api WHERE id_client_api = ? AND estado = 1";
        $token_stmt = $this->db->prepare($token_check);
        $token_stmt->bindParam(1, $this->model->id);
        $token_stmt->execute();
        $token_count = $token_stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        if ($token_count > 0) {
            echo '<script>alert("No se puede eliminar el cliente porque tiene tokens activos. Desactive los tokens primero."); window.location.href = "index.php?controller=client_api&action=index";</script>';
            return;
        }
        
        if($this->model->delete()) {
            header("Location: index.php?controller=client_api&action=index");
            exit();
        }
    }

    // NUEVO: Método para ver estadísticas del cliente
    public function stats() {
        $this->model->id = $_GET['id'];
        
        if ($this->model->readOne()) {
            $stats = $this->model->getStats($this->model->id);
            $db_connection = $this->db;
            include_once 'views/client_api/stats.php';
        } else {
            header("Location: index.php?controller=client_api&action=index");
            exit();
        }
    }
}
?>