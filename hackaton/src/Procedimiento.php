<?php
// CORS Headers
class Procedimiento {
    private $conn;
    private $table = 'procedimiento';

    // Properties that match database columns
    public $id;
    public $nombre;
    public $descripcion;
    public $id_paciente;
    public $fecha;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new procedure
    public function crear() {
        $query = "INSERT INTO " . $this->table . "
                (nombre, descripcion, id_paciente, fecha)
                VALUES
                (:nombre, :descripcion, :id_paciente, :fecha)";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->id_paciente = htmlspecialchars(strip_tags($this->id_paciente));
        $this->fecha = $this->fecha ?? date('Y-m-d H:i:s');

        // Bind data
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':id_paciente', $this->id_paciente);
        $stmt->bindParam(':fecha', $this->fecha);

        try {
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Read all procedures
    public function leer() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($query);
        
        try {
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Read single procedure
    public function leer_uno() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->id_paciente = $row['id_paciente'];
            $this->fecha = $row['fecha'];

            return true;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Update procedure
    public function actualizar() {
        $query = "UPDATE " . $this->table . "
                SET
                    nombre = :nombre,
                    descripcion = :descripcion,
                    id_paciente = :id_paciente,
                    fecha = :fecha
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->id_paciente = htmlspecialchars(strip_tags($this->id_paciente));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':id_paciente', $this->id_paciente);
        $stmt->bindParam(':fecha', $this->fecha);
        $stmt->bindParam(':id', $this->id);

        try {
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Delete procedure
    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Clean id
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind id
        $stmt->bindParam(':id', $this->id);

        try {
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

require_once 'DataBase.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$db = DataBase::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Listar procedimientos
    $sql = "SELECT id, nombre, descripcion, fecha FROM procedimiento ORDER BY fecha DESC";
    $result = $db->query($sql);

    $procedimientos = [];
    while ($row = $result->fetch_assoc()) {
        $procedimientos[] = $row;
    }
    echo json_encode($procedimientos);
    $db->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear nuevo procedimiento
    $data = json_decode(file_get_contents('php://input'), true);
    $nombre = $db->real_escape_string($data['nombre']);
    $descripcion = $db->real_escape_string($data['descripcion']);
    $fecha = date('Y-m-d H:i:s');

    $sql = "INSERT INTO procedimiento (nombre, descripcion, fecha) VALUES ('$nombre', '$descripcion', '$fecha')";
    if ($db->query($sql)) {
        echo json_encode(['success' => true, 'id' => $db->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $db->error]);
    }
    $db->close();
    exit;
}
?>