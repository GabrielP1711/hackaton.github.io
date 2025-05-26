<?php
require_once '../hackaton/src/DataBase.php';

class Trazabilidad {
    private $db;
    
    public function __construct() {
        $this->db = DataBase::getInstance()->getConnection();
    }
    
    /**
     * Registra un evento de trazabilidad
     */
    public function registrarEvento($idInstrumento, $idProcedimiento, $idInstrumentador, $tipoEvento, $descripcion, $estadoPrevio = null, $estadoNuevo = null) {
        $stmt = $this->db->prepare("INSERT INTO trazabilidad (id_instrumento, id_procedimiento, id_instrumentador, tipo_evento, descripcion, estado_previo, estado_nuevo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiissss", $idInstrumento, $idProcedimiento, $idInstrumentador, $tipoEvento, $descripcion, $estadoPrevio, $estadoNuevo);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }
    
    /**
     * Obtiene el historial de un instrumento
     */
    public function obtenerHistorialInstrumento($idInstrumento) {
        $stmt = $this->db->prepare("
            SELECT t.*, 
                   p.nombre as procedimiento_nombre, 
                   i.nombre as instrumentador_nombre 
            FROM trazabilidad t
            LEFT JOIN procedimiento p ON t.id_procedimiento = p.id
            LEFT JOIN instrumentador i ON t.id_instrumentador = i.id
            WHERE t.id_instrumento = ?
            ORDER BY t.fecha DESC
        ");
        $stmt->bind_param("i", $idInstrumento);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtiene los eventos de trazabilidad de un procedimiento
     */
    public function obtenerEventosProcedimiento($idProcedimiento) {
        $stmt = $this->db->prepare("
            SELECT t.*, 
                   ins.nombre as instrumento_nombre, 
                   i.nombre as instrumentador_nombre 
            FROM trazabilidad t
            LEFT JOIN instrumento ins ON t.id_instrumento = ins.id
            LEFT JOIN instrumentador i ON t.id_instrumentador = i.id
            WHERE t.id_procedimiento = ?
            ORDER BY t.fecha DESC
        ");
        $stmt->bind_param("i", $idProcedimiento);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtiene los instrumentos más usados
     */
    public function obtenerInstrumentosMasUsados($limite = 10) {
        $stmt = $this->db->prepare("
            SELECT i.id, i.nombre, i.estado, COUNT(t.id) as cantidad_usos
            FROM instrumento i
            LEFT JOIN trazabilidad t ON i.id = t.id_instrumento AND t.tipo_evento = 'uso'
            GROUP BY i.id
            ORDER BY cantidad_usos DESC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
