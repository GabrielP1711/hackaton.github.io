<?php
require_once '../hackaton/src/DataBase.php';

class Especialidad {
    private $db;
    
    public function __construct() {
        $this->db = DataBase::getInstance()->getConnection();
    }
    
    /**
     * Crea una nueva especialidad
     */
    public function crear($nombre, $descripcion) {
        $stmt = $this->db->prepare("INSERT INTO especialidad (nombre, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }
    
    /**
     * Obtiene una especialidad por su ID
     */
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM especialidad WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Obtiene todas las especialidades
     */
    public function obtenerTodas() {
        $result = $this->db->query("SELECT * FROM especialidad");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
