<?php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Instrumental Quirúrgico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">SGIQ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#procedimientos">Procedimientos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#inventario">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#trazabilidad">Trazabilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reportes">Reportes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1>Sistema de Gestión de Instrumental Quirúrgico</h1>
                <div class="alert alert-info">
                    Bienvenido al sistema de gestión y trazabilidad de instrumental quirúrgico.
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Procedimientos Activos</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary">Nuevo Procedimiento</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Alertas del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div id="alertas">
                            <!-- Aquí se mostrarán las alertas dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
