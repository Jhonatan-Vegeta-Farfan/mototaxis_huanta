<?php
class MototaxiController {
    private $model;

    public function __construct($db) {
        $this->model = new Mototaxi($db);
    }

    public function index() {
        // Verificar si hay búsqueda
        $search_keywords = isset($_GET['search']) ? trim($_GET['search']) : '';
        $advanced_search = isset($_GET['advanced_search']) ? true : false;
        $empresa_id = isset($_GET['empresa_id']) ? intval($_GET['empresa_id']) : '';
        
        if (!empty($search_keywords)) {
            if ($advanced_search) {
                // Búsqueda avanzada
                $numero_asignado = isset($_GET['numero_asignado']) ? trim($_GET['numero_asignado']) : '';
                $nombre_completo = isset($_GET['nombre_completo']) ? trim($_GET['nombre_completo']) : '';
                $dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';
                $placa_rodaje = isset($_GET['placa_rodaje']) ? trim($_GET['placa_rodaje']) : '';
                
                $stmt = $this->model->advancedSearch($numero_asignado, $nombre_completo, $dni, $placa_rodaje);
            } else {
                // Búsqueda simple
                $stmt = $this->model->search($search_keywords);
            }
        } else if (!empty($empresa_id)) {
            // Filtrar por empresa específica
            $stmt = $this->model->getByEmpresa($empresa_id);
        } else {
            // Mostrar todos los registros
            $stmt = $this->model->read();
        }
        
        include_once 'views/mototaxis/index.php';
    }

    public function create() {
        $empresas = $this->model->getEmpresas();
        $error = '';
        $success = '';
        
        if($_POST) {
            $this->model->numero_asignado = trim($_POST['numero_asignado']);
            $this->model->nombre_completo = trim($_POST['nombre_completo']);
            $this->model->dni = trim($_POST['dni']);
            $this->model->direccion = trim($_POST['direccion']);
            $this->model->placa_rodaje = trim($_POST['placa_rodaje']);
            $this->model->anio_fabricacion = !empty($_POST['anio_fabricacion']) ? intval($_POST['anio_fabricacion']) : null;
            $this->model->marca = trim($_POST['marca']);
            $this->model->numero_motor = trim($_POST['numero_motor']);
            $this->model->tipo_motor = trim($_POST['tipo_motor']);
            $this->model->serie = trim($_POST['serie']);
            $this->model->color = trim($_POST['color']);
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->id_empresa = intval($_POST['id_empresa']);

            // Validaciones
            if (empty($this->model->numero_asignado)) {
                $error = 'El número asignado es obligatorio';
            } elseif (empty($this->model->nombre_completo)) {
                $error = 'El nombre completo es obligatorio';
            } elseif (empty($this->model->dni)) {
                $error = 'El DNI es obligatorio';
            } elseif (!preg_match('/^\d{8}$/', $this->model->dni)) {
                $error = 'El DNI debe tener 8 dígitos';
            } elseif (empty($this->model->placa_rodaje)) {
                $error = 'La placa de rodaje es obligatoria';
            } elseif (empty($this->model->fecha_registro)) {
                $error = 'La fecha de registro es obligatoria';
            } elseif (empty($this->model->id_empresa)) {
                $error = 'La empresa es obligatoria';
            } else {
                // Verificar si el número asignado ya existe
                $query = "SELECT id FROM mototaxis WHERE numero_asignado = ?";
                $stmt = $this->model->getConnection()->prepare($query);
                $stmt->bindParam(1, $this->model->numero_asignado);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $error = 'El número asignado ya está registrado en el sistema';
                } else {
                    // Verificar si el DNI ya existe
                    $query = "SELECT id FROM mototaxis WHERE dni = ?";
                    $stmt = $this->model->getConnection()->prepare($query);
                    $stmt->bindParam(1, $this->model->dni);
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $error = 'El DNI ya está registrado en el sistema';
                    } else {
                        if($this->model->create()) {
                            $success = 'Mototaxi creado exitosamente';
                            $_SESSION['success_message'] = $success;
                            header("Location: index.php?controller=mototaxis&action=index");
                            exit();
                        } else {
                            $error = 'Error al crear el mototaxi';
                        }
                    }
                }
            }
        }
        include_once 'views/mototaxis/create.php';
    }

    public function edit() {
        $this->model->id = intval($_GET['id']);
        $empresas = $this->model->getEmpresas();
        $error = '';
        $success = '';
        
        if($_POST) {
            $this->model->id = intval($_POST['id']);
            $this->model->numero_asignado = trim($_POST['numero_asignado']);
            $this->model->nombre_completo = trim($_POST['nombre_completo']);
            $this->model->dni = trim($_POST['dni']);
            $this->model->direccion = trim($_POST['direccion']);
            $this->model->placa_rodaje = trim($_POST['placa_rodaje']);
            $this->model->anio_fabricacion = !empty($_POST['anio_fabricacion']) ? intval($_POST['anio_fabricacion']) : null;
            $this->model->marca = trim($_POST['marca']);
            $this->model->numero_motor = trim($_POST['numero_motor']);
            $this->model->tipo_motor = trim($_POST['tipo_motor']);
            $this->model->serie = trim($_POST['serie']);
            $this->model->color = trim($_POST['color']);
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->id_empresa = intval($_POST['id_empresa']);

            // Validaciones
            if (empty($this->model->numero_asignado)) {
                $error = 'El número asignado es obligatorio';
            } elseif (empty($this->model->nombre_completo)) {
                $error = 'El nombre completo es obligatorio';
            } elseif (empty($this->model->dni)) {
                $error = 'El DNI es obligatorio';
            } elseif (!preg_match('/^\d{8}$/', $this->model->dni)) {
                $error = 'El DNI debe tener 8 dígitos';
            } elseif (empty($this->model->placa_rodaje)) {
                $error = 'La placa de rodaje es obligatoria';
            } elseif (empty($this->model->fecha_registro)) {
                $error = 'La fecha de registro es obligatoria';
            } elseif (empty($this->model->id_empresa)) {
                $error = 'La empresa es obligatoria';
            } else {
                // Verificar si el número asignado ya existe (excluyendo el actual)
                $query = "SELECT id FROM mototaxis WHERE numero_asignado = ? AND id != ?";
                $stmt = $this->model->getConnection()->prepare($query);
                $stmt->bindParam(1, $this->model->numero_asignado);
                $stmt->bindParam(2, $this->model->id);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $error = 'El número asignado ya está registrado en el sistema';
                } else {
                    // Verificar si el DNI ya existe (excluyendo el actual)
                    $query = "SELECT id FROM mototaxis WHERE dni = ? AND id != ?";
                    $stmt = $this->model->getConnection()->prepare($query);
                    $stmt->bindParam(1, $this->model->dni);
                    $stmt->bindParam(2, $this->model->id);
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $error = 'El DNI ya está registrado en el sistema';
                    } else {
                        if($this->model->update()) {
                            $success = 'Mototaxi actualizado exitosamente';
                            $_SESSION['success_message'] = $success;
                            header("Location: index.php?controller=mototaxis&action=index");
                            exit();
                        } else {
                            $error = 'Error al actualizar el mototaxi';
                        }
                    }
                }
            }
        } else {
            if (!$this->model->readOne()) {
                $_SESSION['error_message'] = 'Mototaxi no encontrado';
                header("Location: index.php?controller=mototaxis&action=index");
                exit();
            }
        }
        include_once 'views/mototaxis/edit.php';
    }

    public function delete() {
        $this->model->id = intval($_GET['id']);
        
        if($this->model->delete()) {
            $_SESSION['success_message'] = 'Mototaxi eliminado exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar el mototaxi';
        }
        header("Location: index.php?controller=mototaxis&action=index");
        exit();
    }

    // NUEVO MÉTODO PARA VER DETALLES
    public function view() {
        $this->model->id = intval($_GET['id']);
        
        if($this->model->readOne()) {
            $empresas = $this->model->getEmpresas();
            include_once 'views/mototaxis/view.php';
        } else {
            $_SESSION['error_message'] = 'Mototaxi no encontrado';
            header("Location: index.php?controller=mototaxis&action=index");
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
        
        include_once 'views/mototaxis/index.php';
    }

    // NUEVO MÉTODO: Exportar a Excel
    public function exportar() {
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';
        
        switch($tipo) {
            case 'empresa':
                $empresa_id = intval($_GET['empresa_id']);
                $stmt = $this->model->getByEmpresa($empresa_id);
                $nombre_archivo = "mototaxis_empresa_{$empresa_id}_" . date('Y-m-d') . ".xls";
                break;
            case 'busqueda':
                $search_keywords = isset($_GET['q']) ? $_GET['q'] : '';
                $stmt = $this->model->search($search_keywords);
                $nombre_archivo = "mototaxis_busqueda_" . date('Y-m-d') . ".xls";
                break;
            default:
                $stmt = $this->model->read();
                $nombre_archivo = "mototaxis_completo_" . date('Y-m-d') . ".xls";
        }
        
        // Configurar headers para descarga
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$nombre_archivo\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // Generar contenido Excel
        echo "ID\tNúmero Asignado\tNombre Completo\tDNI\tDirección\tPlaca Rodaje\tAño Fabricación\tMarca\tNúmero Motor\tTipo Motor\tSerie\tColor\tFecha Registro\tEmpresa\n";
        
        if ($stmt) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo ($row['id'] ?? '') . "\t";
                echo ($row['numero_asignado'] ?? '') . "\t";
                echo ($row['nombre_completo'] ?? '') . "\t";
                echo ($row['dni'] ?? '') . "\t";
                echo ($row['direccion'] ?? '') . "\t";
                echo ($row['placa_rodaje'] ?? '') . "\t";
                echo ($row['anio_fabricacion'] ?? '') . "\t";
                echo ($row['marca'] ?? '') . "\t";
                echo ($row['numero_motor'] ?? '') . "\t";
                echo ($row['tipo_motor'] ?? '') . "\t";
                echo ($row['serie'] ?? '') . "\t";
                echo ($row['color'] ?? '') . "\t";
                echo ($row['fecha_registro'] ?? '') . "\t";
                echo ($row['empresa'] ?? '') . "\n";
            }
        }
        exit();
    }

    // NUEVO MÉTODO: Estadísticas
    public function estadisticas() {
        $stats = [
            'total_mototaxis' => 0,
            'por_empresa' => [],
            'por_año' => [],
            'por_marca' => []
        ];
        
        // Total de mototaxis
        $stmt = $this->model->read();
        if ($stmt) {
            $stats['total_mototaxis'] = $stmt->rowCount();
        }
        
        // Por empresa
        $query = "SELECT e.razon_social, COUNT(m.id) as total 
                 FROM mototaxis m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 GROUP BY e.id, e.razon_social 
                 ORDER BY total DESC";
        $stmt = $this->model->getConnection()->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stats['por_empresa'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Por año de fabricación
        $query = "SELECT anio_fabricacion, COUNT(*) as total 
                 FROM mototaxis 
                 WHERE anio_fabricacion IS NOT NULL 
                 GROUP BY anio_fabricacion 
                 ORDER BY anio_fabricacion DESC";
        $stmt = $this->model->getConnection()->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stats['por_año'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Por marca
        $query = "SELECT marca, COUNT(*) as total 
                 FROM mototaxis 
                 WHERE marca IS NOT NULL AND marca != '' 
                 GROUP BY marca 
                 ORDER BY total DESC";
        $stmt = $this->model->getConnection()->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $stats['por_marca'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include_once 'views/mototaxis/estadisticas.php';
    }
}
?>