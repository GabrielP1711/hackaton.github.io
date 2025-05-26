<?php
session_start();
require_once 'DataBase.php'; // Ajusta la ruta según sea necesario

// Obtener la instancia de la conexión
$conexion = DataBase::getInstance()->getConnection();

if ($conexion->connect_errno) {
    die("Conexion Fallida" . $conexion->connect_errno);
}

// Inicializar la variable de error
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $contrasena = $_POST["contrasena"];

    $consulta = "SELECT * FROM usuario WHERE id = '$id' AND contrasena = '$contrasena'";
    try {
        $resultado = mysqli_query($conexion, $consulta);
        if ($resultado) {
            if ($resultado->num_rows > 0) {
                $user = $resultado->fetch_assoc();
                if($user['rol'] == 'usuario'){
                    header("Location: index.php?InicioSesion=enespera");
                }else{
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_correo'] = $user['correo'];
                    $_SESSION['user_nombres'] = $user['nombres'];
                    $_SESSION['user_apellidos'] = $user['apellidos'];
                    $_SESSION['user_direccion'] = $user['direccion'];
                    $_SESSION['user_telefono'] = $user['telefono'];
                    $_SESSION['user_rol'] = $user['rol'];
                    $sesionabierta = 1;
                    $stmtiniciarsesion = $conexion->prepare("UPDATE usuario SET sesion_abierta = ? WHERE id = ?");
                    $stmtiniciarsesion->bind_param("ii", $sesionabierta, $user['id']);
                    $stmtiniciarsesion->execute();

                        // Redirección basada en el rol del usuario
                    if ($user['rol'] == 'administrador') {
                        header("Location: Administrador.php");
                    } else if ($user['rol'] == 'medico') {
                        header("Location: Medico.html");
                    } else if ($user['rol'] == 'paciente') {
                        header("Location: PacienteInicio.html");
                    }
                    
                }

                exit();
            } else {
                $error = "Id o Contraseña inválidos";
            }
        } else {
            $error = "Inicio de sesión fallido";
        }
    } catch (mysqli_sql_exception $e) {
        $error = "Error en el registro";
    }
    $_SESSION['error'] = $error;
    header("Location: iniciarsesion.php");
    exit();
}
?>