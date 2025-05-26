<?php
require_once '../hackaton/src/DataBase.php';

class Procedimiento {
    private $db;
    
    public function __construct() {
        $this->db = DataBase::getInstance()->getConnection();
    }
    
    /**
     * Crea un nuevo procedimiento quirúrgico
     */
    public function crear($nombre, $descripcion, $idPaciente) {
        $stmt = $this->db->prepare("INSERT INTO procedimiento (nombre, descripcion, id_paciente) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nombre, $descripcion, $idPaciente);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }
    
    /**
     * Obtiene un procedimiento por su ID
     */
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM procedimiento WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Obtiene todos los procedimientos
     */
    public function obtenerTodos() {
        $result = $this->db->query("SELECT * FROM procedimiento ORDER BY fecha DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtiene procedimientos activos (recientes)
     */
    public function obtenerActivos() {
        // Consideramos activos los procedimientos de los últimos 7 días
        $fechaLimite = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        $stmt = $this->db->prepare("SELECT * FROM procedimiento WHERE fecha >= ? ORDER BY fecha DESC");
        $stmt->bind_param("s", $fechaLimite);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
