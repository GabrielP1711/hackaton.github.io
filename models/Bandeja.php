<?php
require_once '../hackaton/src/DataBase.php';

class Bandeja {
    private $db;
    
    public function __construct() {
        $this->db = DataBase::getInstance()->getConnection();
    }
    
    /**
     * Crea una nueva bandeja
     */
    public function crear($idEspecialidad, $idProcedimiento = null, $estado = 'disponible') {
        $stmt = $this->db->prepare("INSERT INTO bandeja (id_especialidad, id_procedimiento, estado) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $idEspecialidad, $idProcedimiento, $estado);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }
    
    /**
     * Obtiene una bandeja por su ID
     */
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM bandeja WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Obtiene todas las bandejas
     */
    public function obtenerTodas() {
        $result = $this->db->query("SELECT * FROM bandeja");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtiene bandejas por especialidad
     */
    public function obtenerPorEspecialidad($idEspecialidad) {
        $stmt = $this->db->prepare("SELECT * FROM bandeja WHERE id_especialidad = ?");
        $stmt->bind_param("i", $idEspecialidad);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtiene bandejas por procedimiento
     */
    public function obtenerPorProcedimiento($idProcedimiento) {
        $stmt = $this->db->prepare("SELECT * FROM bandeja WHERE id_procedimiento = ?");
        $stmt->bind_param("i", $idProcedimiento);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Actualiza el estado de una bandeja
     */
    public function actualizarEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE bandeja SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $estado, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Asigna una bandeja a un procedimiento
     */
    public function asignarAProcedimiento($idBandeja, $idProcedimiento) {
        $stmt = $this->db->prepare("UPDATE bandeja SET id_procedimiento = ?, estado = 'reservada' WHERE id = ?");
        $stmt->bind_param("ii", $idProcedimiento, $idBandeja);
        
        return $stmt->execute();
    }
    
    /**
     * Libera una bandeja de un procedimiento
     */
    public function liberarDeProcedimiento($idBandeja) {
        $stmt = $this->db->prepare("UPDATE bandeja SET id_procedimiento = NULL, estado = 'disponible' WHERE id = ?");
        $stmt->bind_param("i", $idBandeja);
        
        return $stmt->execute();
    }
}
?>
