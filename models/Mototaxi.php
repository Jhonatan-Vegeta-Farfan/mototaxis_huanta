<?php
class Mototaxi {
    private $conn;
    private $table_name = "mototaxis";

    public $id;
    public $numero_asignado;
    public $nombre_completo;
    public $dni;
    public $direccion;
    public $placa_rodaje;
    public $anio_fabricacion;
    public $marca;
    public $numero_motor;
    public $tipo_motor;
    public $serie;
    public $color;
    public $fecha_registro;
    public $id_empresa;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT m.*, e.razon_social as empresa 
                 FROM " . $this->table_name . " m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 ORDER BY m.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET numero_asignado=:numero_asignado, nombre_completo=:nombre_completo, 
                     dni=:dni, direccion=:direccion, placa_rodaje=:placa_rodaje, 
                     anio_fabricacion=:anio_fabricacion, marca=:marca, 
                     numero_motor=:numero_motor, tipo_motor=:tipo_motor, 
                     serie=:serie, color=:color, fecha_registro=:fecha_registro, 
                     id_empresa=:id_empresa";
        
        $stmt = $this->conn->prepare($query);
        
        $this->numero_asignado = htmlspecialchars(strip_tags($this->numero_asignado));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->dni = htmlspecialchars(strip_tags($this->dni));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->placa_rodaje = htmlspecialchars(strip_tags($this->placa_rodaje));
        $this->anio_fabricacion = htmlspecialchars(strip_tags($this->anio_fabricacion));
        $this->marca = htmlspecialchars(strip_tags($this->marca));
        $this->numero_motor = htmlspecialchars(strip_tags($this->numero_motor));
        $this->tipo_motor = htmlspecialchars(strip_tags($this->tipo_motor));
        $this->serie = htmlspecialchars(strip_tags($this->serie));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
        $this->id_empresa = htmlspecialchars(strip_tags($this->id_empresa));
        
        $stmt->bindParam(":numero_asignado", $this->numero_asignado);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":dni", $this->dni);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":placa_rodaje", $this->placa_rodaje);
        $stmt->bindParam(":anio_fabricacion", $this->anio_fabricacion);
        $stmt->bindParam(":marca", $this->marca);
        $stmt->bindParam(":numero_motor", $this->numero_motor);
        $stmt->bindParam(":tipo_motor", $this->tipo_motor);
        $stmt->bindParam(":serie", $this->serie);
        $stmt->bindParam(":color", $this->color);
        $stmt->bindParam(":fecha_registro", $this->fecha_registro);
        $stmt->bindParam(":id_empresa", $this->id_empresa);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET numero_asignado=:numero_asignado, nombre_completo=:nombre_completo, 
                     dni=:dni, direccion=:direccion, placa_rodaje=:placa_rodaje, 
                     anio_fabricacion=:anio_fabricacion, marca=:marca, 
                     numero_motor=:numero_motor, tipo_motor=:tipo_motor, 
                     serie=:serie, color=:color, fecha_registro=:fecha_registro, 
                     id_empresa=:id_empresa
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->numero_asignado = htmlspecialchars(strip_tags($this->numero_asignado));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->dni = htmlspecialchars(strip_tags($this->dni));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->placa_rodaje = htmlspecialchars(strip_tags($this->placa_rodaje));
        $this->anio_fabricacion = htmlspecialchars(strip_tags($this->anio_fabricacion));
        $this->marca = htmlspecialchars(strip_tags($this->marca));
        $this->numero_motor = htmlspecialchars(strip_tags($this->numero_motor));
        $this->tipo_motor = htmlspecialchars(strip_tags($this->tipo_motor));
        $this->serie = htmlspecialchars(strip_tags($this->serie));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
        $this->id_empresa = htmlspecialchars(strip_tags($this->id_empresa));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":numero_asignado", $this->numero_asignado);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":dni", $this->dni);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":placa_rodaje", $this->placa_rodaje);
        $stmt->bindParam(":anio_fabricacion", $this->anio_fabricacion);
        $stmt->bindParam(":marca", $this->marca);
        $stmt->bindParam(":numero_motor", $this->numero_motor);
        $stmt->bindParam(":tipo_motor", $this->tipo_motor);
        $stmt->bindParam(":serie", $this->serie);
        $stmt->bindParam(":color", $this->color);
        $stmt->bindParam(":fecha_registro", $this->fecha_registro);
        $stmt->bindParam(":id_empresa", $this->id_empresa);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->numero_asignado = $row['numero_asignado'];
            $this->nombre_completo = $row['nombre_completo'];
            $this->dni = $row['dni'];
            $this->direccion = $row['direccion'];
            $this->placa_rodaje = $row['placa_rodaje'];
            $this->anio_fabricacion = $row['anio_fabricacion'];
            $this->marca = $row['marca'];
            $this->numero_motor = $row['numero_motor'];
            $this->tipo_motor = $row['tipo_motor'];
            $this->serie = $row['serie'];
            $this->color = $row['color'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->id_empresa = $row['id_empresa'];
            return true;
        }
        return false;
    }

    public function getEmpresas() {
        $query = "SELECT id, razon_social FROM empresas";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>