<?php
require_once 'config/Auth.php';
require_once 'models/Procedimiento.php';
require_once 'models/Especialidad.php';

// Inicializar autenticación
$auth = new Auth();

// Si no está autenticado, redirigir a la página de login
if (!$auth->estaAutenticado()) {
    header('Location: login.php');
    exit;
}

// Obtener usuario actual
$usuarioActual = $auth->getUsuarioActual();

// Inicializar modelos
$procedimientoModel = new Procedimiento();
$especialidadModel = new Especialidad();

// Obtener todas las especialidades para el formulario
$especialidades = $especialidadModel->obtenerTodas();

$mensaje = '';
$error = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $idPaciente = $_POST['id_paciente'] ?? null;
    
    // Validar datos
    if (empty($nombre)) {
        $error = 'El nombre del procedimiento es obligatorio';
    } else {
        // Crear nuevo procedimiento
        $idProcedimiento = $procedimientoModel->crear($nombre, $descripcion, $idPaciente);
        
        if ($idProcedimiento) {
            $mensaje = 'Procedimiento creado exitosamente';
            // Opcionalmente, redireccionar a la página del procedimiento
            // header('Location: ver_procedimiento.php?id=' . $idProcedimiento);
            // exit;
        } else {
            $error = 'Error al crear el procedimiento';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Procedimiento - Sistema de Gestión de Instrumental Quirúrgico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">SGIQ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#procedimientos">Procedimientos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#instrumentos">Instrumentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#bandejas">Bandejas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#trazabilidad">Trazabilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#reportes">Reportes</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <span class="nav-item nav-link text-light">Hola, <?php echo htmlspecialchars($usuarioActual['nombre']); ?></span>
                    <a class="nav-link" href="logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2>Nuevo Procedimiento Quirúrgico</h2>
                
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-success"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Información del Procedimiento</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="nuevo_procedimiento.php">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Procedimiento</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="id_paciente" class="form-label">ID del Paciente</label>
                                <input type="text" class="form-control" id="id_paciente" name="id_paciente">
                                <div class="form-text">Identificador único del paciente en el sistema hospitalario</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha y Hora</label>
                                <input type="datetime-local" class="form-control" id="fecha" name="fecha" value="<?php echo date('Y-m-d\TH:i'); ?>">
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary me-md-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Procedimiento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
