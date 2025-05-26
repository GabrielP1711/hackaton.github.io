<?php
require_once 'config/Auth.php';
require_once 'models/Procedimiento.php';
require_once 'models/Instrumento.php';
require_once 'models/Bandeja.php';
require_once 'models/Trazabilidad.php';
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

// Verificar que se proporciona un ID de procedimiento
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$idProcedimiento = (int)$_GET['id'];

// Inicializar modelos
$procedimientoModel = new Procedimiento();
$instrumentoModel = new Instrumento();
$bandejaModel = new Bandeja();
$trazabilidadModel = new Trazabilidad();
$conteoModel = new ConteoInstrumentos();

// Obtener información del procedimiento
$procedimiento = $procedimientoModel->obtenerPorId($idProcedimiento);

// Si no se encuentra el procedimiento, redirigir
if (!$procedimiento) {
    header('Location: index.php');
    exit;
}

// Obtener las bandejas asignadas a este procedimiento
$bandejas = $bandejaModel->obtenerPorProcedimiento($idProcedimiento);

// Obtener eventos de trazabilidad para este procedimiento
$eventos = $trazabilidadModel->obtenerEventosProcedimiento($idProcedimiento);

// Obtener los conteos realizados para este procedimiento
$conteos = $conteoModel->obtenerConteosProcedimiento($idProcedimiento);

// Determinar si hay instrumentos faltantes en el conteo final (si existe)
$hayFaltantes = false;
$conteoFinal = null;
foreach ($conteos as $conteo) {
    if ($conteo['momento'] === 'final') {
        $conteoFinal = $conteo;
        if ($conteo['faltantes'] > 0) {
            $hayFaltantes = true;
        }
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($procedimiento['nombre']); ?> - Sistema de Gestión de Instrumental Quirúrgico</title>
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
                <h2><?php echo htmlspecialchars($procedimiento['nombre']); ?></h2>
                
                <?php if ($hayFaltantes): ?>
                    <div class="alert alert-danger">
                        <strong>¡Atención!</strong> Este procedimiento tiene instrumentos faltantes en el conteo final.
                    </div>
                <?php endif; ?>
                
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Detalles del Procedimiento</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($procedimiento['fecha'])); ?></p>
                                <p><strong>ID Paciente:</strong> <?php echo $procedimiento['id_paciente'] ? htmlspecialchars($procedimiento['id_paciente']) : 'No especificado'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Descripción:</strong></p>
                                <p><?php echo htmlspecialchars($procedimiento['descripcion']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Bandejas Asignadas</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($bandejas)): ?>
                                    <div class="alert alert-info">No hay bandejas asignadas a este procedimiento.</div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($bandejas as $bandeja): ?>
                                            <div class="list-group-item">
                                                <h6 class="mb-1">Bandeja #<?php echo $bandeja['id']; ?></h6>
                                                <p class="mb-1">Estado: <span class="badge bg-<?php echo ($bandeja['estado'] === 'disponible') ? 'success' : (($bandeja['estado'] === 'reservada') ? 'warning' : 'danger'); ?>"><?php echo $bandeja['estado']; ?></span></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-3">
                                    <a href="asignar_bandeja.php?id=<?php echo $idProcedimiento; ?>" class="btn btn-outline-primary">Asignar Bandeja</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Conteo de Instrumentos</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($conteos)): ?>
                                    <div class="alert alert-info">No se ha realizado ningún conteo para este procedimiento.</div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($conteos as $conteo): ?>
                                            <a href="ver_conteo.php?id=<?php echo $conteo['id']; ?>" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">Conteo <?php echo ucfirst($conteo['momento']); ?></h6>
                                                    <small><?php echo date('d/m/Y H:i', strtotime($conteo['fecha'])); ?></small>
                                                </div>
                                                <p class="mb-1">Responsable: <?php echo htmlspecialchars($conteo['instrumentador_nombre']); ?></p>
                                                <p class="mb-1">
                                                    <span class="badge bg-primary"><?php echo $conteo['total_instrumentos']; ?> instrumentos</span>
                                                    <?php if ($conteo['faltantes'] > 0): ?>
                                                        <span class="badge bg-danger"><?php echo $conteo['faltantes']; ?> faltantes</span>
                                                    <?php endif; ?>
                                                </p>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-3">
                                    <a href="nuevo_conteo.php?id=<?php echo $idProcedimiento; ?>&tipo=inicial" class="btn btn-outline-primary me-2">Conteo Inicial</a>
                                    <a href="nuevo_conteo.php?id=<?php echo $idProcedimiento; ?>&tipo=final" class="btn btn-outline-success">Conteo Final</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Historial de Eventos</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($eventos)): ?>
                                    <div class="alert alert-info">No hay eventos registrados para este procedimiento.</div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($eventos as $evento): ?>
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1"><?php echo ucfirst($evento['tipo_evento']); ?> de Instrumento</h6>
                                                    <small><?php echo date('d/m/Y H:i', strtotime($evento['fecha'])); ?></small>
                                                </div>
                                                <p class="mb-1">Instrumento: <?php echo htmlspecialchars($evento['instrumento_nombre']); ?></p>
                                                <p class="mb-1">Responsable: <?php echo htmlspecialchars($evento['instrumentador_nombre']); ?></p>
                                                <p class="mb-1">Descripción: <?php echo htmlspecialchars($evento['descripcion']); ?></p>
                                                <?php if ($evento['estado_previo'] && $evento['estado_nuevo']): ?>
                                                    <p class="mb-1">Cambio de estado: <?php echo $evento['estado_previo']; ?> → <?php echo $evento['estado_nuevo']; ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Acciones</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="solicitar_instrumental.php?id=<?php echo $idProcedimiento; ?>" class="btn btn-success">Solicitar Instrumental</a>
                                    <a href="editar_procedimiento.php?id=<?php echo $idProcedimiento; ?>" class="btn btn-warning">Editar Procedimiento</a>
                                    <a href="generar_reporte.php?id=<?php echo $idProcedimiento; ?>" class="btn btn-info">Generar Reporte</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
