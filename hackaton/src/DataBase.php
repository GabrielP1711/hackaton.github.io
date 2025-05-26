<?php
class DataBase
{
    private static $instance = null;
    private $connection;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'hackaton';

    // Constructor privado para prevenir múltiples instancias
    private function __construct()
    {
        // Crear una nueva conexión a la base de datos
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Verificar si hay errores en la conexión
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Obtener la instancia singleton
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DataBase();
        }

        return self::$instance;
    }

    // Obtener la conexión a la base de datos
    public function getConnection()
    {
        return $this->connection;
    }
}
?>