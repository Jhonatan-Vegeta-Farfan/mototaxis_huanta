<?php
class EmpresaController {
    private $model;

    public function __construct($db) {
        $this->model = new Empresa($db);
    }

    public function index() {
        // Verificar si hay búsqueda
        $search_keywords = isset($_GET['search']) ? trim($_GET['search']) : '';
        $advanced_search = isset($_GET['advanced_search']) ? true : false;
        
        if (!empty($search_keywords)) {
            if ($advanced_search) {
                // Búsqueda avanzada
                $ruc = isset($_GET['ruc']) ? trim($_GET['ruc']) : '';
                $razon_social = isset($_GET['razon_social']) ? trim($_GET['razon_social']) : '';
                $representante_legal = isset($_GET['representante_legal']) ? trim($_GET['representante_legal']) : '';
                
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
        $error = '';
        $success = '';
        
        if($_POST) {
            $this->model->razon_social = trim($_POST['razon_social']);
            $this->model->ruc = trim($_POST['ruc']);
            $this->model->representante_legal = trim($_POST['representante_legal']);

            // Validaciones
            if (empty($this->model->razon_social)) {
                $error = 'La razón social es obligatoria';
            } elseif (empty($this->model->ruc)) {
                $error = 'El RUC es obligatorio';
            } elseif (!preg_match('/^\d{11}$/', $this->model->ruc)) {
                $error = 'El RUC debe tener 11 dígitos';
            } elseif (empty($this->model->representante_legal)) {
                $error = 'El representante legal es obligatorio';
            } else {
                // Verificar si el RUC ya existe
                $query = "SELECT id FROM empresas WHERE ruc = ?";
                $stmt = $this->model->conn->prepare($query);
                $stmt->bindParam(1, $this->model->ruc);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $error = 'El RUC ya está registrado en el sistema';
                } else {
                    if($this->model->create()) {
                        $success = 'Empresa creada exitosamente';
                        $_SESSION['success_message'] = $success;
                        header("Location: index.php?controller=empresas&action=index");
                        exit();
                    } else {
                        $error = 'Error al crear la empresa';
                    }
                }
            }
        }
        include_once 'views/empresas/create.php';
    }

    public function edit() {
        $this->model->id = intval($_GET['id']);
        $error = '';
        $success = '';
        
        if($_POST) {
            $this->model->id = intval($_POST['id']);
            $this->model->razon_social = trim($_POST['razon_social']);
            $this->model->ruc = trim($_POST['ruc']);
            $this->model->representante_legal = trim($_POST['representante_legal']);

            // Validaciones
            if (empty($this->model->razon_social)) {
                $error = 'La razón social es obligatoria';
            } elseif (empty($this->model->ruc)) {
                $error = 'El RUC es obligatorio';
            } elseif (!preg_match('/^\d{11}$/', $this->model->ruc)) {
                $error = 'El RUC debe tener 11 dígitos';
            } elseif (empty($this->model->representante_legal)) {
                $error = 'El representante legal es obligatorio';
            } else {
                // Verificar si el RUC ya existe (excluyendo el actual)
                $query = "SELECT id FROM empresas WHERE ruc = ? AND id != ?";
                $stmt = $this->model->conn->prepare($query);
                $stmt->bindParam(1, $this->model->ruc);
                $stmt->bindParam(2, $this->model->id);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $error = 'El RUC ya está registrado en el sistema por otra empresa';
                } else {
                    if($this->model->update()) {
                        $success = 'Empresa actualizada exitosamente';
                        $_SESSION['success_message'] = $success;
                        header("Location: index.php?controller=empresas&action=index");
                        exit();
                    } else {
                        $error = 'Error al actualizar la empresa';
                    }
                }
            }
        } else {
            if (!$this->model->readOne()) {
                $_SESSION['error_message'] = 'Empresa no encontrada';
                header("Location: index.php?controller=empresas&action=index");
                exit();
            }
        }
        include_once 'views/empresas/edit.php';
    }

    public function delete() {
        $this->model->id = intval($_GET['id']);
        
        // Verificar si la empresa tiene mototaxis asociados
        $query = "SELECT COUNT(*) as total FROM mototaxis WHERE id_empresa = ?";
        $stmt = $this->model->conn->prepare($query);
        $stmt->bindParam(1, $this->model->id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['total'] > 0) {
            $_SESSION['error_message'] = 'No se puede eliminar la empresa porque tiene mototaxis asociados';
            header("Location: index.php?controller=empresas&action=index");
            exit();
        }
        
        if($this->model->delete()) {
            $_SESSION['success_message'] = 'Empresa eliminada exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar la empresa';
        }
        header("Location: index.php?controller=empresas&action=index");
        exit();
    }

    // NUEVO MÉTODO PARA VER DETALLES
    public function view() {
        $this->model->id = intval($_GET['id']);
        
        if($this->model->readOne()) {
            // Obtener mototaxis de la empresa
            $mototaxiModel = new Mototaxi($this->model->conn);
            $mototaxis = $mototaxiModel->getByEmpresa($this->model->id);
            
            include_once 'views/empresas/view.php';
        } else {
            $_SESSION['error_message'] = 'Empresa no encontrada';
            header("Location: index.php?controller=empresas&action=index");
            exit();
        }
    }

    // NUEVO MÉTODO PARA BÚSQUEDA ESPECÍFICA
    public function search() {
        $search_keywords = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (!empty($search_keywords)) {
            $stmt = $this->model->search($search_keywords);
        } else {
            $stmt = $this->model->read();
        }
        
        include_once 'views/empresas/index.php';
    }

    // NUEVO MÉTODO: Estadísticas de la empresa
    public function estadisticas() {
        $this->model->id = intval($_GET['id']);
        
        if ($this->model->readOne()) {
            $mototaxiModel = new Mototaxi($this->model->conn);
            
            // Obtener estadísticas
            $stats = [
                'total_mototaxis' => 0,
                'mototaxis_por_año' => [],
                'mototaxis_por_marca' => []
            ];
            
            $mototaxis = $mototaxiModel->getByEmpresa($this->model->id);
            if ($mototaxis) {
                $stats['total_mototaxis'] = $mototaxis->rowCount();
                
                // Procesar datos para gráficos
                while ($mototaxi = $mototaxis->fetch(PDO::FETCH_ASSOC)) {
                    $año = $mototaxi['anio_fabricacion'] ?? null;
                    $marca = $mototaxi['marca'] ?? 'No especificada';
                    
                    if ($año) {
                        if (!isset($stats['mototaxis_por_año'][$año])) {
                            $stats['mototaxis_por_año'][$año] = 0;
                        }
                        $stats['mototaxis_por_año'][$año]++;
                    }
                    
                    if (!isset($stats['mototaxis_por_marca'][$marca])) {
                        $stats['mototaxis_por_marca'][$marca] = 0;
                    }
                    $stats['mototaxis_por_marca'][$marca]++;
                }
            }
            
            include_once 'views/empresas/estadisticas.php';
        } else {
            $_SESSION['error_message'] = 'Empresa no encontrada';
            header("Location: index.php?controller=empresas&action=index");
            exit();
        }
    }
}
?>