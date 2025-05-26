<?php
require_once '../hackaton/src/DataBase.php';

class Instrumentador {
    private $db;
    
    public function __construct() {
        $this->db = DataBase::getInstance()->getConnection();
    }
    
    /**
     * Registra un nuevo instrumentador en el sistema
     */
    public function registrar($nombre, $contrasena) {
        // Encriptar contraseña para mayor seguridad
        $contrasenaSegura = password_hash($contrasena, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("INSERT INTO instrumentador (nombre, contraseña) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $contrasenaSegura);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }
    
    /**
     * Verifica las credenciales del instrumentador
     */
    public function login($nombre, $contrasena) {
        $stmt = $this->db->prepare("SELECT id, nombre, contraseña FROM instrumentador WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verificar si la contraseña coincide con el hash almacenado
            if (password_verify($contrasena, $user['contraseña'])) {
                return $user;
            }
        }
        
        return false;
    }
    
    /**
     * Obtiene información de un instrumentador por su ID
     */
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT id, nombre FROM instrumentador WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Obtiene todos los instrumentadores registrados
     */
    public function obtenerTodos() {
        $result = $this->db->query("SELECT id, nombre FROM instrumentador");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
