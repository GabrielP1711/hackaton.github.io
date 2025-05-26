<?php
require_once '../hackaton/src/DataBase.php';

class Instrumento {
    private $db;
    
    public function __construct() {
        $this->db = DataBase::getInstance()->getConnection();
    }
    
    /**
     * Registra un nuevo instrumento
     */
    public function crear($nombre, $descripcion, $stock, $idBandeja = null, $tipo, $estado) {
        $stmt = $this->db->prepare("INSERT INTO instrumento (nombre, descripción, stock, id_bandeja, tipo, estado) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $nombre, $descripcion, $stock, $idBandeja, $tipo, $estado);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }
    
    /**
     * Obtiene un instrumento por su ID
     */
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM instrumento WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Obtiene todos los instrumentos
     */
    public function obtenerTodos() {
        $result = $this->db->query("SELECT * FROM instrumento");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtiene instrumentos por bandeja
     */
    public function obtenerPorBandeja($idBandeja) {
        $stmt = $this->db->prepare("SELECT * FROM instrumento WHERE id_bandeja = ?");
        $stmt->bind_param("i", $idBandeja);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Actualiza el estado de un instrumento
     */
    public function actualizarEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE instrumento SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $estado, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Actualiza el stock de un instrumento
     */
    public function actualizarStock($id, $stock) {
        $stmt = $this->db->prepare("UPDATE instrumento SET stock = ? WHERE id = ?");
        $stmt->bind_param("ii", $stock, $id);
        
        return $stmt->execute();
    }
}
?>
