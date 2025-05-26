<?php
require_once 'config/Auth.php';
require_once 'models/Procedimiento.php';
require_once 'models/Instrumento.php';
require_once 'models/Bandeja.php';
require_once 'models/ConteoInstrumentos.php';

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
$instrumentoModel = new Instrumento();
$bandejaModel = new Bandeja();
$conteoModel = new ConteoInstrumentos();

// Obtener lista de procedimientos activos para el selector
$procedimientos = $procedimientoModel->obtenerActivos();

// Obtener instrumentos disponibles
$instrumentos = $instrumentoModel->obtenerTodos();

$mensaje = '';
$error = '';

// Procesar formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProcedimiento = $_POST['id_procedimiento'] ?? '';
    $instrumentosSeleccionados = $_POST['instrumentos'] ?? [];
    
    if (empty($idProcedimiento)) {
        $error = 'Debe seleccionar un procedimiento';
    } elseif (empty($instrumentosSeleccionados)) {
        $error = 'Debe seleccionar al menos un instrumento';
    } else {
        // Iniciar conteo inicial
        $idConteo = $conteoModel->iniciarConteo(
            $idProcedimiento, 
            $usuarioActual['id'],
            'inicial',
            'Solicitud inicial de instrumentos para procedimiento'
        );
        
        if ($idConteo) {
            $exito = true;
            
            // Registrar cada instrumento en el detalle de conteo
            foreach ($instrumentosSeleccionados as $idInstrumento) {
                // Por defecto solicitamos 1 de cada instrumento, esto podría ser ajustado
                $cantidad = 1;
                $resultado = $conteoModel->registrarDetalleConteo($idConteo, $idInstrumento, $cantidad, $cantidad);
                if (!$resultado) {
                    $exito = false;
                }
            }
            
            if ($exito) {
                $conteoModel->finalizarConteo($idConteo);
                $mensaje = 'Solicitud de instrumentos registrada exitosamente';
            } else {
                $error = 'Error al registrar algunos instrumentos';
            }
        } else {
            $error = 'Error al crear la solicitud de instrumentos';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Instrumental - Sistema de Gestión de Instrumental Quirúrgico</title>
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
                <h2>Solicitar Instrumental Quirúrgico</h2>
                
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-success"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Formulario de Solicitud</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="solicitar_instrumental.php">
                            <div class="mb-3">
                                <label for="id_procedimiento" class="form-label">Seleccione el Procedimiento</label>
                                <select class="form-select" id="id_procedimiento" name="id_procedimiento" required>
                                    <option value="">-- Seleccione un procedimiento --</option>
                                    <?php foreach ($procedimientos as $proc): ?>
                                        <option value="<?php echo $proc['id']; ?>">
                                            <?php echo htmlspecialchars($proc['nombre']); ?> - 
                                            <?php echo date('d/m/Y H:i', strtotime($proc['fecha'])); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Seleccione los Instrumentos Necesarios</label>
                                <div class="row">
                                    <?php foreach ($instrumentos as $instrumento): ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="instrumentos[]" 
                                                       value="<?php echo $instrumento['id']; ?>" 
                                                       id="instrumento_<?php echo $instrumento['id']; ?>">
                                                <label class="form-check-label" for="instrumento_<?php echo $instrumento['id']; ?>">
                                                    <?php echo htmlspecialchars($instrumento['nombre']); ?>
                                                    <span class="badge bg-<?php echo ($instrumento['estado'] === 'bueno') ? 'success' : 'warning'; ?>">
                                                        <?php echo $instrumento['estado']; ?>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary me-md-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
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
