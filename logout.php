<?php
require_once 'config/Auth.php';

// Inicializar autenticación
$auth = new Auth();

// Cerrar sesión
$auth->logout();

// Redirigir a la página de inicio de sesión
header('Location: login.php');
exit;
?>
