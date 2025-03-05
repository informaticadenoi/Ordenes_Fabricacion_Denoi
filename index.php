<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Fabricación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-dark .navbar-brand {
            color: #00aaff;
            font-weight: bold;
        }
        .container {
            margin-top: 20px;
        }
        .table th {
            background-color: #e0e0e0;
        }
        .title {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">denoi</a>
            <button class="btn btn-outline-light">Salir</button>
        </div>
    </nav>

    <!-- Menú de navegación -->
    <div class="container mt-3">
        <nav class="nav nav-pills nav-justified">
            <a class="nav-link" href="#">Dashboard</a>
            <a class="nav-link" href="#">Planificación</a>
            <a class="nav-link" href="#">Categorías</a>
            <a class="nav-link" href="#">Catálogos</a>
            <a class="nav-link" href="#">Características</a>
            <a class="nav-link" href="#">Clientes</a>
            <a class="nav-link" href="#">Capas</a>
            <a class="nav-link" href="#">Colchones</a>
            <a class="nav-link" href="#">Inactivos</a>
            <a class="nav-link active" href="#">Fabricación</a>
            <a class="nav-link" href="#">Gestión</a>
        </nav>
    </div>

    <!-- Submenú -->
    <div class="container mt-3">
        <nav class="nav nav-tabs">
            <a class="nav-link active" href="#">Multitron 1</a>
            <a class="nav-link" href="#">Multitron 2</a>
            <a class="nav-link" href="#">Optron 1</a>
            <a class="nav-link" href="#">Optron 2</a>
            <a class="nav-link" href="#">Carcasa/Dueffe</a>
            <a class="nav-link" href="#">Ensacado</a>
            <a class="nav-link" href="#">Platabanda</a>
            <a class="nav-link" href="#">Bordado</a>
            <a class="nav-link" href="#">Corte Espuma</a>
            <a class="nav-link" href="#">Pegado Línea 1</a>
            <a class="nav-link" href="#">Pegado Línea 2</a>
        </nav>
    </div>

    <!-- Contenido Principal -->
    <div class="container mt-4">
        <h2 class="title">ORDEN DE FABRICACIÓN MULTITRON 1</h2>

        <!-- Tabla de datos -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ORDEN</th>
                        <th>MODELO</th>
                        <th>ORDEN FABRICACIÓN</th>
                        <th>ACCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>KUBIC</td>
                        <td>912</td>
                        <td><span class="badge bg-success">Finalizado</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>LATEX GEL PLUS</td>
                        <td>930</td>
                        <td><span class="badge bg-success">Finalizado</span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>AIR VISCO H22 JG</td>
                        <td>924</td>
                        <td><span class="badge bg-success">Finalizado</span></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>NOVA 2023</td>
                        <td>910</td>
                        <td><span class="badge bg-success">Finalizado</span></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>ACTIVE LP</td>
                        <td>918</td>
                        <td><span class="badge bg-success">Finalizado</span></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>MEMORY VEX JUPITER</td>
                        <td>929</td>
                        <td><span class="badge bg-success">Finalizado</span></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>LIVING</td>
                        <td>918</td>
                        <td><span class="badge bg-success">Finalizado</span></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>VISCOSENSITIVE BASIC ANTERIOR</td>
                        <td>927</td>
                        <td><span class="badge bg-success">Finalizado</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
