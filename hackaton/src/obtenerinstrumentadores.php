<?php

// Verificar que sea una solicitud GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Solo se permiten solicitudes GET']);
    exit();
}

// Incluir la conexión a la base de datos
require_once './DataBase.php';

try {
    // Obtener conexión a la base de datos
    $db = DataBase::getInstance()->getConnection();
    
    // Consulta para obtener todos los instrumentadores
    $sql = "SELECT id, nombre FROM instrumentadores ORDER BY nombre ASC";
    $result = $db->query($sql);
    
    if (!$result) {
        throw new Exception("Error en la consulta: " . $db->error);
    }
    
    // Crear array para almacenar los resultados
    $instrumentadores = [];
    
    // Recorrer los resultados y almacenarlos en el array
    while ($row = $result->fetch_assoc()) {
        $instrumentadores[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre']
        ];
    }
    
    // Devolver los resultados como JSON
    echo json_encode([
        'status' => 'success',
        'data' => $instrumentadores
    ]);
    
} catch (Exception $e) {
    // Devolver error
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los instrumentadores: ' . $e->getMessage()
    ]);
} finally {
    // Cerrar la conexión
    if (isset($db)) {
        $db->close();
    }
}
?>