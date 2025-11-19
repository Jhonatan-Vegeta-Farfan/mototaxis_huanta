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

    // Método para obtener la conexión
    public function getConnection() {
        return $this->conn;
    }

    public function read() {
        $query = "SELECT m.*, e.razon_social as empresa 
                 FROM " . $this->table_name . " m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 ORDER BY m.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // MÉTODO DE BÚSQUEDA
    public function search($keywords) {
        $query = "SELECT m.*, e.razon_social as empresa 
                 FROM " . $this->table_name . " m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 WHERE m.numero_asignado LIKE ? OR m.nombre_completo LIKE ? OR m.dni LIKE ? OR m.placa_rodaje LIKE ?
                 ORDER BY m.id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        
        $stmt->execute();
        
        $this->logNotification('BUSQUEDA_MOTOTAXI', "Búsqueda de mototaxis: '$keywords'. Resultados: " . $stmt->rowCount());
        return $stmt;
    }

    // MÉTODO DE BÚSQUEDA AVANZADA
    public function advancedSearch($numero_asignado = null, $nombre_completo = null, $dni = null, $placa_rodaje = null) {
        $query = "SELECT m.*, e.razon_social as empresa 
                 FROM " . $this->table_name . " m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 WHERE 1=1";
        
        $params = array();
        $criterios = [];
        
        if (!empty($numero_asignado)) {
            $query .= " AND m.numero_asignado LIKE ?";
            $params[] = "%{$numero_asignado}%";
            $criterios[] = "Número: $numero_asignado";
        }
        
        if (!empty($nombre_completo)) {
            $query .= " AND m.nombre_completo LIKE ?";
            $params[] = "%{$nombre_completo}%";
            $criterios[] = "Nombre: $nombre_completo";
        }
        
        if (!empty($dni)) {
            $query .= " AND m.dni LIKE ?";
            $params[] = "%{$dni}%";
            $criterios[] = "DNI: $dni";
        }
        
        if (!empty($placa_rodaje)) {
            $query .= " AND m.placa_rodaje LIKE ?";
            $params[] = "%{$placa_rodaje}%";
            $criterios[] = "Placa: $placa_rodaje";
        }
        
        $query .= " ORDER BY m.id ASC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(($key + 1), $value);
        }
        
        $stmt->execute();
        
        $criterios_str = implode(', ', $criterios);
        $this->logNotification('BUSQUEDA_AVANZADA_MOTOTAXI', "Búsqueda avanzada de mototaxis - Criterios: $criterios_str. Resultados: " . $stmt->rowCount());
        return $stmt;
    }

    // MÉTODO PARA FILTRAR POR EMPRESA
    public function getByEmpresa($empresa_id) {
        $query = "SELECT m.*, e.razon_social as empresa 
                 FROM " . $this->table_name . " m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 WHERE m.id_empresa = ?
                 ORDER BY m.id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        
        $this->logNotification('CONSULTA_MOTOTAXIS_EMPRESA', "Consultados mototaxis de empresa ID: $empresa_id. Total: " . $stmt->rowCount());
        return $stmt;
    }

    public function create() {
        try {
            $this->conn->beginTransaction();
            
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
            $this->anio_fabricacion = $this->anio_fabricacion ? htmlspecialchars(strip_tags($this->anio_fabricacion)) : null;
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
                $this->id = $this->conn->lastInsertId();
                
                // Reorganizar IDs después de crear
                $this->reorganizarMototaxis();
                
                // Obtener información de la empresa
                $empresa_info = $this->getEmpresaInfo();
                
                // Registrar notificación
                $detalles = json_encode([
                    'mototaxi_id' => $this->id,
                    'numero_asignado' => $this->numero_asignado,
                    'nombre_completo' => $this->nombre_completo,
                    'dni' => $this->dni,
                    'empresa' => $empresa_info['razon_social'],
                    'placa_rodaje' => $this->placa_rodaje,
                    'fecha_registro' => $this->fecha_registro
                ]);
                
                $this->logNotification('MOTOTAXI_CREADO', "Nuevo mototaxi registrado: {$this->numero_asignado} - {$this->nombre_completo}", null, $detalles);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la inserción");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_CREACION_MOTOTAXI', "Error al crear mototaxi: " . $e->getMessage());
            return false;
        }
    }

    public function update() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener datos antiguos
            $old_data = $this->getOldData();
            
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
            $this->anio_fabricacion = $this->anio_fabricacion ? htmlspecialchars(strip_tags($this->anio_fabricacion)) : null;
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
                // Registrar cambios
                $this->logMototaxiChanges($old_data);
                
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al ejecutar la actualización");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ACTUALIZACION_MOTOTAXI', "Error al actualizar mototaxi ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar mototaxi y reorganizar IDs
     */
    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Obtener información del mototaxi antes de eliminar
            $mototaxi_info = $this->getMototaxiInfo();

            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al eliminar el mototaxi");
            }
            
            // Reorganizar IDs
            $reorganizados = $this->reorganizarMototaxis();
            
            // Registrar notificación
            $detalles = json_encode([
                'mototaxi_id' => $this->id,
                'numero_asignado' => $mototaxi_info['numero_asignado'],
                'nombre_completo' => $mototaxi_info['nombre_completo'],
                'empresa' => $mototaxi_info['empresa'],
                'ids_reorganizados' => $reorganizados
            ]);
            
            $this->logNotification('MOTOTAXI_ELIMINADO', "Mototaxi eliminado: {$mototaxi_info['numero_asignado']} - {$mototaxi_info['nombre_completo']}", null, $detalles);
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logNotification('ERROR_ELIMINACION_MOTOTAXI', "Error al eliminar mototaxi ID: {$this->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reorganizar IDs de mototaxis
     */
    private function reorganizarMototaxis() {
        try {
            // 1. Obtener todos los mototaxis ordenados por ID actual
            $query = "SELECT id FROM " . $this->table_name . " ORDER BY id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $mototaxis = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $cambios = [];
            
            // 2. Actualizar IDs secuencialmente
            $new_id = 1;
            foreach ($mototaxis as $mototaxi) {
                if ($mototaxi['id'] != $new_id) {
                    $update_query = "UPDATE " . $this->table_name . " SET id = ? WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $new_id);
                    $update_stmt->bindParam(2, $mototaxi['id']);
                    $update_stmt->execute();
                    
                    $cambios[] = [
                        'viejo_id' => $mototaxi['id'],
                        'nuevo_id' => $new_id
                    ];
                }
                $new_id++;
            }
            
            // 3. Resetear el auto_increment
            $reset_query = "ALTER TABLE " . $this->table_name . " AUTO_INCREMENT = 1";
            $this->conn->exec($reset_query);
            
            if (!empty($cambios)) {
                $this->logNotification('REORGANIZACION_MOTOTAXIS', "IDs de mototaxis reorganizados. Total cambios: " . count($cambios), null, json_encode($cambios));
            }
            
            return $cambios;
            
        } catch (PDOException $e) {
            error_log("Error reorganizando mototaxis: " . $e->getMessage());
            $this->logNotification('ERROR_REORGANIZACION_MOTOTAXIS', "Error al reorganizar mototaxis: " . $e->getMessage());
            return [];
        }
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
        $query = "SELECT id, razon_social FROM empresas ORDER BY razon_social";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Verificar si el número asignado ya existe
     */
    public function numeroAsignadoExists($numero_asignado, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE numero_asignado = ?";
        $params = [$numero_asignado];
        
        if ($exclude_id) {
            $query .= " AND id != ?";
            $params[] = $exclude_id;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Verificar si el DNI ya existe
     */
    public function dniExists($dni, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE dni = ?";
        $params = [$dni];
        
        if ($exclude_id) {
            $query .= " AND id != ?";
            $params[] = $exclude_id;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtener mototaxis recientemente registrados
     */
    public function getRecent($limit = 5) {
        try {
            $query = "SELECT m.*, e.razon_social as empresa 
                     FROM " . $this->table_name . " m 
                     LEFT JOIN empresas e ON m.id_empresa = e.id 
                     ORDER BY m.fecha_registro DESC 
                     LIMIT ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error en getRecent: " . $e->getMessage());
            $this->logNotification('ERROR_CONSULTA_RECIENTES', "Error al obtener mototaxis recientes: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar mototaxi por número exacto para API
     */
    public function findByNumeroAsignado($numero_asignado) {
        $query = "SELECT m.*, e.razon_social as empresa, e.ruc as ruc_empresa,
                         e.representante_legal as representante_empresa
                 FROM " . $this->table_name . " m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 WHERE m.numero_asignado = ?
                 LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $numero_asignado);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $this->logNotification('BUSQUEDA_API_MOTOTAXI', "Búsqueda API por número: $numero_asignado - Encontrado: {$result['nombre_completo']}");
        } else {
            $this->logNotification('BUSQUEDA_API_MOTOTAXI_NO_ENCONTRADO', "Búsqueda API por número: $numero_asignado - No encontrado");
        }
        
        return $result;
    }

    /**
     * Buscar mototaxis por DNI exacto para API
     */
    public function findByDni($dni) {
        $query = "SELECT m.*, e.razon_social as empresa, e.ruc as ruc_empresa
                 FROM " . $this->table_name . " m 
                 LEFT JOIN empresas e ON m.id_empresa = e.id 
                 WHERE m.dni = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $dni);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->logNotification('BUSQUEDA_API_DNI', "Búsqueda API por DNI: $dni - Resultados: " . count($result));
        
        return $result;
    }

    /**
     * Reorganizar todos los IDs de mototaxis (método público para llamadas manuales)
     */
    public function reorganizarTodosLosIds() {
        $result = $this->reorganizarMototaxis();
        $this->logNotification('REORGANIZACION_MANUAL_MOTOTAXIS', "Reorganización manual de IDs de mototaxis completada", null, json_encode($result));
        return $result;
    }

    /**
     * Obtener información de la empresa
     */
    private function getEmpresaInfo() {
        $query = "SELECT razon_social FROM empresas WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_empresa);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener información del mototaxi
     */
    private function getMototaxiInfo() {
        $query = "SELECT m.numero_asignado, m.nombre_completo, e.razon_social as empresa
                 FROM " . $this->table_name . " m
                 LEFT JOIN empresas e ON m.id_empresa = e.id
                 WHERE m.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener datos antiguos
     */
    private function getOldData() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registrar cambios en las actualizaciones
     */
    private function logMototaxiChanges($old_data) {
        $changes = [];
        
        if ($old_data['numero_asignado'] != $this->numero_asignado) {
            $changes[] = "Número Asignado: {$old_data['numero_asignado']} → {$this->numero_asignado}";
        }
        if ($old_data['nombre_completo'] != $this->nombre_completo) {
            $changes[] = "Nombre: {$old_data['nombre_completo']} → {$this->nombre_completo}";
        }
        if ($old_data['dni'] != $this->dni) {
            $changes[] = "DNI: {$old_data['dni']} → {$this->dni}";
        }
        if ($old_data['id_empresa'] != $this->id_empresa) {
            $empresa_old = $this->getEmpresaName($old_data['id_empresa']);
            $empresa_new = $this->getEmpresaName($this->id_empresa);
            $changes[] = "Empresa: $empresa_old → $empresa_new";
        }
        if ($old_data['placa_rodaje'] != $this->placa_rodaje) {
            $changes[] = "Placa: {$old_data['placa_rodaje']} → {$this->placa_rodaje}";
        }
        
        if (!empty($changes)) {
            $detalles = json_encode([
                'mototaxi_id' => $this->id,
                'cambios' => $changes,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ]);
            
            $this->logNotification('MOTOTAXI_ACTUALIZADO', "Mototaxi actualizado: {$this->numero_asignado}. Cambios: " . count($changes), null, $detalles);
        }
    }

    /**
     * Obtener nombre de la empresa
     */
    private function getEmpresaName($empresa_id) {
        $query = "SELECT razon_social FROM empresas WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $empresa_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['razon_social'] : 'Desconocido';
    }

    /**
     * Registrar notificación
     */
    private function logNotification($tipo, $mensaje, $usuario_id = null, $detalles = null) {
        $database = new Database();
        $database->logNotification($tipo, $mensaje, $usuario_id, $detalles);
    }
}
?>