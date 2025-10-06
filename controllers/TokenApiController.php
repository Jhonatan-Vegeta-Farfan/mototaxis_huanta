<?php
class TokenApiController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new TokenApi($db);
        $this->db = $db;
    }

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

    public function create() {
        $clientes = $this->model->getClientes();
        $error = '';
        $success = '';
        $db_connection = $this->db;
        
        if($_POST) {
            if (empty($_POST['id_client_api'])) {
                $error = 'Debe seleccionar un cliente.';
            } else {
                $this->model->id_client_api = $_POST['id_client_api'];
                $this->model->estado = 1;

                // Generar token único (verificar que no exista)
                $token_generated = false;
                $attempts = 0;
                $max_attempts = 5;
                
                while (!$token_generated && $attempts < $max_attempts) {
                    $token = $this->model->generateToken($this->model->id_client_api);
                    if (!$this->model->tokenExists($token)) {
                        $this->model->token = $token;
                        $token_generated = true;
                    }
                    $attempts++;
                }
                
                if ($token_generated) {
                    if($this->model->create()) {
                        $success = 'Token generado exitosamente: ' . $this->model->token;
                    } else {
                        $error = 'Error al generar el token. Intente nuevamente.';
                    }
                } else {
                    $error = 'Error al generar un token único. Intente nuevamente.';
                }
            }
        }
        include_once 'views/tokens_api/create.php';
    }

    public function edit() {
        $this->model->id = $_GET['id'];
        $clientes = $this->model->getClientes();
        $error = '';
        $success = '';
        $db_connection = $this->db;
        
        if($_POST) {
            // Validar token único (excluyendo el actual)
            $check_query = "SELECT id FROM tokens_api WHERE token = ? AND id != ? AND estado = 1";
            $check_stmt = $this->db->prepare($check_query);
            $check_stmt->bindParam(1, $_POST['token']);
            $check_stmt->bindParam(2, $_POST['id']);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                $error = 'El token ya está en uso por otro registro.';
            } else {
                $this->model->id = $_POST['id'];
                $this->model->id_client_api = $_POST['id_client_api'];
                $this->model->token = $_POST['token'];
                $this->model->fecha_registro = $_POST['fecha_registro'];
                $this->model->estado = $_POST['estado'];

                if($this->model->update()) {
                    $success = 'Token actualizado exitosamente.';
                } else {
                    $error = 'Error al actualizar el token. Intente nuevamente.';
                }
            }
        } else {
            $this->model->readOne();
        }
        include_once 'views/tokens_api/edit.php';
    }

    public function delete() {
        $this->model->id = $_GET['id'];
        
        // Verificar si tiene requests asociados
        $request_check = "SELECT COUNT(*) as total FROM count_request WHERE id_token_api = ?";
        $request_stmt = $this->db->prepare($request_check);
        $request_stmt->bindParam(1, $this->model->id);
        $request_stmt->execute();
        $request_count = $request_stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        if ($request_count > 0) {
            echo '<script>alert("No se puede eliminar el token porque tiene requests asociados. Elimine los requests primero."); window.location.href = "index.php?controller=tokens_api&action=index";</script>';
            return;
        }
        
        if($this->model->delete()) {
            header("Location: index.php?controller=tokens_api&action=index");
            exit();
        }
    }

    // NUEVO: Método para regenerar token
    public function regenerate() {
        $this->model->id = $_GET['id'];
        
        if ($this->model->readOne()) {
            // Generar nuevo token único
            $token_generated = false;
            $attempts = 0;
            $max_attempts = 5;
            
            while (!$token_generated && $attempts < $max_attempts) {
                $new_token = $this->model->generateToken($this->model->id_client_api);
                if (!$this->model->tokenExists($new_token)) {
                    $token_generated = true;
                }
                $attempts++;
            }
            
            if ($token_generated) {
                $update_query = "UPDATE tokens_api SET token = ? WHERE id = ?";
                $update_stmt = $this->db->prepare($update_query);
                $update_stmt->bindParam(1, $new_token);
                $update_stmt->bindParam(2, $this->model->id);
                
                if ($update_stmt->execute()) {
                    echo '<script>alert("Token regenerado exitosamente: ' . $new_token . '"); window.location.href = "index.php?controller=tokens_api&action=index";</script>';
                } else {
                    echo '<script>alert("Error al regenerar el token."); window.location.href = "index.php?controller=tokens_api&action=index";</script>';
                }
            } else {
                echo '<script>alert("Error al generar un token único."); window.location.href = "index.php?controller=tokens_api&action=index";</script>';
            }
        } else {
            header("Location: index.php?controller=tokens_api&action=index");
            exit();
        }
    }
}
?>