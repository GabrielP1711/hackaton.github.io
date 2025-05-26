<?php
session_start();
require_once '../hackaton/src/DataBase.php';
require_once 'models/Instrumentador.php';

class Auth {
    private $instrumentador;
    
    public function __construct() {
        $this->instrumentador = new Instrumentador();
    }
    
    /**
     * Registra un nuevo instrumentador
     */
    public function registrar($nombre, $contrasena, $confirmarContrasena) {
        // Validar que las contraseñas coinciden
        if ($contrasena !== $confirmarContrasena) {
            return ["error" => "Las contraseñas no coinciden"];
        }
        
        // Validar que el nombre no está ya en uso
        $db = DataBase::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM instrumentador WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ["error" => "El nombre de usuario ya está en uso"];
        }
        
        // Registrar el nuevo instrumentador
        $idInstrumentador = $this->instrumentador->registrar($nombre, $contrasena);
        
        if ($idInstrumentador) {
            return ["success" => true, "mensaje" => "Registro exitoso. Ahora puede iniciar sesión.", "id" => $idInstrumentador];
        } else {
            return ["error" => "Error al registrar el usuario"];
        }
    }
    
    /**
     * Inicia sesión de un instrumentador
     */
    public function login($nombre, $contrasena) {
        $usuario = $this->instrumentador->login($nombre, $contrasena);
        
        if ($usuario) {
            // Iniciar sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['autenticado'] = true;
            
            return ["success" => true, "usuario" => $usuario];
        } else {
            return ["error" => "Nombre de usuario o contraseña incorrectos"];
        }
    }
    
    /**
     * Cierra la sesión actual
     */
    public function logout() {
        // Destruir variables de sesión
        $_SESSION = array();
        
        // Destruir la sesión
        session_destroy();
        
        return ["success" => true, "mensaje" => "Sesión cerrada correctamente"];
    }
    
    /**
     * Verifica si el usuario está autenticado
     */
    public function estaAutenticado() {
        return isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true;
    }
    
    /**
     * Obtiene información del usuario autenticado
     */
    public function getUsuarioActual() {
        if ($this->estaAutenticado()) {
            return [
                'id' => $_SESSION['usuario_id'],
                'nombre' => $_SESSION['usuario_nombre']
            ];
        }
        
        return null;
    }
}
?>
