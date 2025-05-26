<?php


// Incluir la conexión a la base de datos
require_once './DataBase.php';

// Función para generar un nombre de archivo único
function generateUniqueFileName($originalName) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $extension;
}

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inicializar respuesta
    $response = ['status' => 'error', 'message' => ''];
    
    try {
        // Obtener la conexión a la base de datos
        $db = DataBase::getInstance()->getConnection();
        
        // Obtener los datos del formulario
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        // Validar datos
        if (empty($id) || empty($nombre) || empty($password)) {
            $response['message'] = 'El ID, nombre y contraseña son obligatorios';
            echo json_encode($response);
            exit();
        }
        
        // Hash de la contraseña para seguridad
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Procesar imagen si existe
        $imagen_path = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Directorio para guardar imágenes
            $upload_dir = '../uploads/';
            
            // Crear directorio si no existe
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generar nombre único para la imagen
            $imagen_nombre = generateUniqueFileName($_FILES['imagen']['name']);
            $imagen_path = $upload_dir . $imagen_nombre;
            
            // Mover la imagen al directorio de destino
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path)) {
                $response['message'] = 'Error al subir la imagen';
                echo json_encode($response);
                exit();
            }
            
            // Guardar la ruta relativa para la base de datos
            $imagen_path = 'uploads/' . $imagen_nombre;
        }
        
        // Preparar la consulta SQL (añadida la columna password)
        $sql = "INSERT INTO instrumentador (id, nombre, contraseña, imagen) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        // Vincular parámetros (añadido el parámetro password)
        $stmt->bind_param('ssss', $id, $nombre, $passwordHash, $imagen_path);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Instrumentador registrado correctamente';
        } else {
            $response['message'] = 'Error al registrar el instrumentador: ' . $db->error;
        }
        
        // Cerrar la consulta
        $stmt->close();
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    } finally {
        // Cerrar la conexión si existe
        if (isset($db)) {
            $db->close();
        }
    }
    
    // Devolver respuesta como JSON
    echo json_encode($response);
    exit();
} else {
    // Si no es una solicitud POST
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido. Use POST para enviar datos.'
    ]);
    exit();
}
?>