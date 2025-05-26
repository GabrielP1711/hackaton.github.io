<?php
require_once 'DataBase.php'; // Cambia esta línea - estaba usando '../DataBase.php'
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$db = DataBase::getInstance()->getConnection();
$sql = "SELECT id, nombre FROM procedimiento ORDER BY nombre ASC";
$result = $db->query($sql);

$procedimientos = [];
while ($row = $result->fetch_assoc()) {
    $procedimientos[] = [
        "id" => $row["id"],
        "nombre" => $row["id"] . ": " . $row["nombre"] // Formateado como solicitaste
    ];
}

echo json_encode($procedimientos);
$db->close();
?>