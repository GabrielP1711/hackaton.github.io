<?php
// Cambia la ruta si DataBase.php está en src/
require_once 'DataBase.php';

// Leer los datos enviados desde el frontend
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar que los datos existan
if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    exit();
}

// Extraer los datos del formulario
$nombre = $data['nombre'] ?? '';
$descripcion = $data['descripcion'] ?? '';
$id_paciente = $data['id_paciente'] ?? '';
$fecha = $data['fecha'] ?? '';

// Validar que todos los campos estén completos
if (empty($nombre) || empty($descripcion) || empty($id_paciente) || empty($fecha)) {
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
    exit();
}

// Conectar a la base de datos
$db = DataBase::getInstance()->getConnection();

// Preparar la consulta para insertar los datos
$stmt = $db->prepare("INSERT INTO procedimiento (nombre, descripcion, id_paciente, fecha) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssis', $nombre, $descripcion, $id_paciente, $fecha);

// Ejecutar la consulta y verificar si fue exitosa
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Procedimiento registrado exitosamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar en la base de datos']);
}

// Cerrar la conexión
$stmt->close();
$db->close();
?>