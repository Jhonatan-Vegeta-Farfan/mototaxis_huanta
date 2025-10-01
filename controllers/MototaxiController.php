<?php
class MototaxiController {
    private $model;

    public function __construct($db) {
        $this->model = new Mototaxi($db);
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/mototaxis/index.php';
    }

    public function create() {
        $empresas = $this->model->getEmpresas();
        
        if($_POST) {
            $this->model->numero_asignado = $_POST['numero_asignado'];
            $this->model->nombre_completo = $_POST['nombre_completo'];
            $this->model->dni = $_POST['dni'];
            $this->model->direccion = $_POST['direccion'];
            $this->model->placa_rodaje = $_POST['placa_rodaje'];
            $this->model->anio_fabricacion = $_POST['anio_fabricacion'];
            $this->model->marca = $_POST['marca'];
            $this->model->numero_motor = $_POST['numero_motor'];
            $this->model->tipo_motor = $_POST['tipo_motor'];
            $this->model->serie = $_POST['serie'];
            $this->model->color = $_POST['color'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->id_empresa = $_POST['id_empresa'];

            if($this->model->create()) {
                header("Location: index.php?controller=mototaxis&action=index");
                exit();
            }
        }
        include_once 'views/mototaxis/create.php';
    }

    public function edit() {
        $this->model->id = $_GET['id'];
        $empresas = $this->model->getEmpresas();
        
        if($_POST) {
            $this->model->id = $_POST['id'];
            $this->model->numero_asignado = $_POST['numero_asignado'];
            $this->model->nombre_completo = $_POST['nombre_completo'];
            $this->model->dni = $_POST['dni'];
            $this->model->direccion = $_POST['direccion'];
            $this->model->placa_rodaje = $_POST['placa_rodaje'];
            $this->model->anio_fabricacion = $_POST['anio_fabricacion'];
            $this->model->marca = $_POST['marca'];
            $this->model->numero_motor = $_POST['numero_motor'];
            $this->model->tipo_motor = $_POST['tipo_motor'];
            $this->model->serie = $_POST['serie'];
            $this->model->color = $_POST['color'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->id_empresa = $_POST['id_empresa'];

            if($this->model->update()) {
                header("Location: index.php?controller=mototaxis&action=index");
                exit();
            }
        } else {
            $this->model->readOne();
        }
        include_once 'views/mototaxis/edit.php';
    }

    public function delete() {
        $this->model->id = $_GET['id'];
        if($this->model->delete()) {
            header("Location: index.php?controller=mototaxis&action=index");
            exit();
        }
    }
}
?>