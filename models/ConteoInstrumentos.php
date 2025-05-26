<?php
require_once '../hackaton/src/DataBase.php';

class ConteoInstrumentos {
    private $db;
    
    public function __construct() {
        $this->db = DataBase::getInstance()->getConnection();
    }
    
    /**
     * Inicia un nuevo conteo de instrumentos
     */
    public function iniciarConteo($idProcedimiento, $idInstrumentador, $momento, $observaciones = null) {
        $stmt = $this->db->prepare("INSERT INTO conteo_instrumentos (id_procedimiento, id_instrumentador, momento, observaciones) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $idProcedimiento, $idInstrumentador, $momento, $observaciones);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }
    
    /**
     * Registra detalle de un conteo
     */
    public function registrarDetalleConteo($idConteo, $idInstrumento, $cantidadEsperada, $cantidadContada, $observaciones = null) {
        // Determinar si hay faltantes
        $faltante = ($cantidadEsperada > $cantidadContada) ? 1 : 0;
        
        $stmt = $this->db->prepare("INSERT INTO detalle_conteo (id_conteo, id_instrumento, cantidad_esperada, cantidad_contada, faltante, observaciones) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiiss", $idConteo, $idInstrumento, $cantidadEsperada, $cantidadContada, $faltante, $observaciones);
        
        return $stmt->execute();
    }
    
    /**
     * Finaliza un conteo y lo marca como completo
     */
    public function finalizarConteo($idConteo) {
        $stmt = $this->db->prepare("UPDATE conteo_instrumentos SET completo = 1 WHERE id = ?");
        $stmt->bind_param("i", $idConteo);
        
        return $stmt->execute();
    }
    
    /**
     * Obtiene los detalles de un conteo
     */
    public function obtenerDetallesConteo($idConteo) {
        $stmt = $this->db->prepare("
            SELECT dc.*, i.nombre as instrumento_nombre, i.tipo as instrumento_tipo
            FROM detalle_conteo dc
            JOIN instrumento i ON dc.id_instrumento = i.id
            WHERE dc.id_conteo = ?
            ORDER BY dc.id
        ");
        $stmt->bind_param("i", $idConteo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Verifica si hay instrumentos faltantes en un conteo
     */
    public function verificarFaltantes($idConteo) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM detalle_conteo WHERE id_conteo = ? AND faltante = 1");
        $stmt->bind_param("i", $idConteo);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'] > 0;
    }
    
    /**
     * Obtiene un resumen de conteos para un procedimiento
     */
    public function obtenerConteosProcedimiento($idProcedimiento) {
        $stmt = $this->db->prepare("
            SELECT ci.*, i.nombre as instrumentador_nombre,
                  (SELECT COUNT(*) FROM detalle_conteo dc WHERE dc.id_conteo = ci.id) as total_instrumentos,
                  (SELECT COUNT(*) FROM detalle_conteo dc WHERE dc.id_conteo = ci.id AND dc.faltante = 1) as faltantes
            FROM conteo_instrumentos ci
            JOIN instrumentador i ON ci.id_instrumentador = i.id
            WHERE ci.id_procedimiento = ?
            ORDER BY ci.fecha DESC
        ");
        $stmt->bind_param("i", $idProcedimiento);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
