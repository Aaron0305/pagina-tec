<?php
session_start();
header('Content-type: text/html; charset=utf-8');
if (!isset($_SESSION['nombre'])) {
    header('Location: login_admin.html');
    exit;
}
// Corregir la línea con error: usar isset para verificar si existe idLic
$idLic = isset($_GET['idLic']) ? $_GET['idLic'] : 13; // Valor predeterminado: 1

// Eliminar línea problemática con el comentario sobre INGENIERÍA ELECTROMECÁNICA

// Nuevos parámetros para filtros
$plantel = isset($_GET['plantel']) ? $_GET['plantel'] : 'todos'; // Valor predeterminado: todos
$tipoPase = isset($_GET['tipoPase']) ? $_GET['tipoPase'] : 'todos'; // Valor predeterminado: todos

switch($idLic){
    case 1:
        $carrera = "INGENIERÍA ELECTROMECÁNICA";
        break;
    case 2:
        $carrera = "INGENIERÍA EN LOGÍSTICA";
        break;
    case 3:
        $carrera = "INGENIERÍA INDUSTRIAL";
        break;
    case 4:
        $carrera = "INGENIERÍA EN GESTIÓN EMPRESARIAL";
        break;
    case 5:
        $carrera = "INGENIERÍA QUÍMICA";
        break;
    case 6:
        $carrera = "INGENIERÍA EN SISTEMAS COMPUTACIONALES";
        break;
    case 7:
        $carrera = "ARQUITECTURA";
        break;
    case 8:
        $carrera = "INGENIERÍA EN ANIMACIÓN Y EFECTOS VISUALES";
        break;
    case 9:
        $carrera = "INGENIERÍA MECATRÓNICA";
        break;
    case 10:
        $carrera = "LICENCIATURA EN TURISMO";
        break;
    case 11:
        $carrera = "CONTADOR PÚBLICO";
        break;
    case 12:
        $carrera = "INGENIERÍA EN MATERIALES";
        break;
    case 13:
        $carrera = "DATOS GENERALES";
        break;
    case 14:
        $carrera = "INGENIERÍA INDUSTRIAL (a distancia)";
        break;
    case 15: 
        $carrera = "INGENIERÍA INDUSTRIAL NO ESCOLARIZADA";
    default:
        $carrera = "DATOS GENERALES"; // Asegurar un valor por defecto
}

require_once('../php/conexion.php');

// Construir condición de filtro para plantel
$condicionPlantel = ($plantel != 'todos') ? " AND plantel = '$plantel'" : "";


// Construir condición de filtro para tipo de pase
$condicionTipoPase = "";
if ($tipoPase != 'todos') {
    if ($tipoPase == 'examen') {
        $tipoPaseDB = 'Por examen';
    } elseif ($tipoPase == 'promedio') {
        $tipoPaseDB = 'Por promedio';
    } else {
        $tipoPaseDB = $tipoPase;
    }
    $condicionTipoPase = " AND tipo_pase = '$tipoPaseDB'";
}

// Consulta para contar cada estado de documentación general
// Añadir WHERE 1=1 para facilitar la concatenación de condiciones
$query_general = "SELECT
    SUM(CASE WHEN doc_fotografia = 'Aprobado' THEN 1 ELSE 0 END) as Aprobados,
    SUM(CASE WHEN doc_fotografia = 'Rechazado' THEN 1 ELSE 0 END) as Rechazados,
    SUM(CASE WHEN doc_fotografia = 'en_revision' THEN 1 ELSE 0 END) as en_revision,
    SUM(CASE WHEN doc_fotografia = 'sin_docs' THEN 1 ELSE 0 END) as sin_docs
FROM persona
WHERE 1=1";

// Si no estamos en "DATOS GENERALES", filtrar por carrera
if ($idLic != 13) {
    $query_general .= " AND carrera = '$carrera'";
}

// Añadir condiciones de filtro adicionales
$query_general .= $condicionPlantel . $condicionTipoPase;

$result_general = mysqli_query($mysqli, $query_general);
$stats_general = null;
if ($result_general) {
    $row = mysqli_fetch_assoc($result_general);
    $stats_general = [
        'aprobados' => (int)$row['Aprobados'],
        'rechazados' => (int)$row['Rechazados'],
        'en_revision' => (int)$row['en_revision'],
        'sin_docs' => (int)$row['sin_docs']
    ];
} else {
    // Manejar error de consulta
    $stats_general = [
        'aprobados' => 0,
        'rechazados' => 0,
        'en_revision' => 0,
        'sin_docs' => 0
    ];
}

// Consulta para contar estudiantes por tipo de pase
// Modificar la construcción de la consulta para que sea consistente con query_general
$query_tipo_pase = "SELECT
    tipo_pase,
    COUNT(*) as cantidad
FROM persona
WHERE 1=1";

// Si no estamos en "DATOS GENERALES", filtrar por carrera
if ($idLic != 13) {
    $query_tipo_pase .= " AND carrera = '$carrera'";
}

// Añadir solo condición de plantel (no de tipo de pase, porque esto es lo que estamos contando)
$query_tipo_pase .= $condicionPlantel . " GROUP BY tipo_pase";

$result_tipo_pase = mysqli_query($mysqli, $query_tipo_pase);
$stats_tipo_pase = [
    'Por examen' => 0,
    'Por promedio' => 0
];

if ($result_tipo_pase) {
    while ($row = mysqli_fetch_assoc($result_tipo_pase)) {
        if (isset($row['tipo_pase']) && isset($row['cantidad'])) {
            $stats_tipo_pase[$row['tipo_pase']] = (int)$row['cantidad'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros</title>
    <script src="https://kit.fontawesome.com/728cc4b6c5.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        .career-list { max-height: 400px; overflow-y: auto; }
        .career-item:hover { background-color: #f8f9fa; cursor: pointer; }
        .chart-container { 
            height: 280px; 
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px;
            background-color: #fff;
        }
        .border-container { border: 2px solid #dee2e6; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .stats-card { border-left: 4px solid #0d6efd; padding: 10px; margin-bottom: 10px; }
        .stats-general-section {
            padding: 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .export-btn {
            margin-bottom: 20px;
            float: right;
        }
        .filter-btn {
            margin-bottom: 10px;
        }
        .divider-line {
            width: 100%;
            height: 1px;
            background-color: #dee2e6;
            margin: 15px 0;
        }
        .btn-group-vertical {
            width: 100%;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printSection, #printSection * {
                visibility: visible;
            }
            #printSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
        /* Estilos mejorados para las gráficas */
        @media (min-width: 1200px) {
            .chart-container { height: 320px; }
        }
        @media (max-width: 767px) {
            .chart-container { 
                height: 240px;
                margin-bottom: 25px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <img src="../imagenes/logo.png" alt="Bootstrap" width="270" height="70">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ml-auto">
                    <a class="nav-link active" aria-current="page" href="Administracion.php" style="font-size: 18px;">REPORTES GENERALES</a>
                </div>
            </div>
            <form class="d-flex">
                <div class="dropdown">
                    <a class="navbar-brand dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../imagenes/perfil.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                        <?php echo $_SESSION['nombre']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </form>
        </div>
    </nav>
    <div class="h4 pb-0 mb-4 text-danger border-bottom border-danger border-3"></div>
    <div class="container-fluid">
        <div class="row">
            <!-- Contenedor izquierdo (30%) -->
            <div class="col-md-4 border-container">
                <!-- Grupo de botones vertical -->
                <div class="btn-group-vertical w-100">
                    <!-- Botón Plantel -->
                    <div class="dropdown w-100 mb-3">
                        <button class="btn btn-success dropdown-toggle w-100 filter-btn" type="button" id="showPlantelBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-building me-2"></i>Plantel: <?php 
                                switch($plantel){
                                    case "jocotitlan": 
                                        echo "Jocotitlán";
                                    break;

                                    default : echo ($plantel == 'todos') ? 'Todos' : ucfirst($plantel);
                                }
                                     ?>
                            
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="showPlantelBtn">
                            <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=todos&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item">Todos</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=jocotitlan&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item">Jocotitl&aacute;n</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=aculco&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item">Aculco</a></li>
                        </ul>
                    </div>
                    <!-- Botón Carrera -->
                    <div class="dropdown w-100 mb-3">
                        <button class="btn btn-primary dropdown-toggle w-100" type="button" id="showCareersBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-graduation-cap me-2"></i>Selecciona una carrera
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="showCareersBtn">
                            <?php if($plantel != 'aculco'): ?>
                            <li><a href="admin_reporte_licenciatura.php?idLic=13&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>"class="dropdown-item career-item" data-career="General">Datos Generales</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=1&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Electromecanica">Ingeniería en Electromecánica</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=4&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Gestion Empresarial">Ingeniería en Gestión Empresarial</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=3&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Industrial">Ingeniería Industrial</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=5&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Quimica">Ingeniería Química</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=6&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Sistemas">Ingeniería en Sistemas Computacionales</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=12&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Materiales">Ingeniería en Materiales</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=7&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Arquitectura">Licenciatura en Arquitectura</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=8&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Animacion">Ingeniería en Animación Digital y Efectos Visuales</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=9&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Mecatronica">Ingeniería en Mecatrónica</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=10&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Turismo">Ingeniería en Turismo</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=11&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Contador">Licenciatura en Contador Público</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=2&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Logistica">Ingeniería en Logística</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=14&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="INGENIERÍA INDUSTRIAL (a distancia)">INGENIERÍA INDUSTRIAL (a distancia)</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=15&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="INGENIERÍA INDUSTRIAL NO ESCOLARIZADA">INGENIERÍA INDUSTRIAL NO ESCOLARIZADA</a></li>
                            <?php endif; ?>
                            
                            <?php if($plantel == 'aculco'): ?>
                            <li><a href="admin_reporte_licenciatura.php?idLic=13&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>"class="dropdown-item career-item" data-career="General">Datos Generales</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=3&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Industrial">Ingeniería Industrial</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=10&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Turismo">Ingeniería en Turismo</a></li>
                            <li><a href="admin_reporte_licenciatura.php?idLic=11&plantel=<?php echo $plantel; ?>&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item career-item" data-career="Contador">Licenciatura en Contador Público</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <!-- Línea divisoria -->
                    <div class="divider-line"></div>
                    <!-- Botones para filtrar por tipo de pase -->
                    <div class="d-flex justify-content-between w-100">
                        <a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=<?php echo $plantel; ?>&tipoPase=promedio" class="btn btn-info flex-grow-1 me-2 <?php echo ($tipoPase == 'promedio') ? 'active' : ''; ?>">
                            <i class="fas fa-star me-2"></i>Por Promedio
                        </a>
                        <a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=<?php echo $plantel; ?>&tipoPase=examen" class="btn btn-warning flex-grow-1 <?php echo ($tipoPase == 'examen') ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt me-2"></i>Por Examen
                        </a>
                    </div>
                    <!-- Botón para mostrar todos -->
                </div>
            </div>
            <!-- Contenedor derecho (70%) -->
            <div class="col-md-8 border-container">
                <div id="chartsContainer">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 id="selectedCareerTitle" class="mb-0 text-center flex-grow-1"><i class="fas fa-chart-pie me-2"></i><?php echo $carrera; ?></h3>
                        <button id="exportPdfBtn" class="btn btn-success export-btn no-print">
                            <i class="fas fa-file-pdf me-2"></i>Exportar a PDF
                        </button>
                    </div>
                    <div class="alert alert-info mb-4">
                        <strong>Filtros activos:</strong>
                        Plantel: <?php echo ($plantel == 'todos') ? 'Todos' : ucfirst($plantel); ?>,
                        Tipo de Pase: <?php echo ($tipoPase == 'todos') ? 'Todos' : ($tipoPase == 'examen' ? 'Por Examen' : 'Por Promedio'); ?>
                    </div>
                    <div id="printSection">
                        <!-- CAMBIO AQUÍ: Las tres gráficas en una sola fila -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div id="acceptanceChart" class="chart-container"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="documentationChart" class="chart-container"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="tipoPaseChart" class="chart-container"></div>
                            </div>
                        </div>
                        <div class="border-top border-danger border-3 my-4"></div>
                        <div class="row">
                            <div class="col">
                                <h4 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Estadísticas Generales</h4>
                                <div class="row" id="generalStats">
                                    <div class="col-md-3">
                                        <div class="stats-card">
                                            <h5 class="text-success"><i class="fas fa-check-circle me-2"></i>Aceptados</h5>
                                            <p class="h4" id="acceptedCount"><?php echo $stats_general['aprobados']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stats-card">
                                            <h5 class="text-danger"><i class="fas fa-times-circle me-2"></i>Rechazados</h5>
                                            <p class="h4" id="rejectedCount"><?php echo $stats_general['rechazados']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stats-card">
                                            <h5 class="text-primary"><i class="fas fa-file-alt me-2"></i>Revisión</h5>
                                            <p class="h4" id="completeDocsCount"><?php echo $stats_general['en_revision']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stats-card">
                                            <h5 class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Sin Documentos</h5>
                                            <p class="h4" id="incompleteDocsCount"><?php echo $stats_general['sin_docs']; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h4 class="mb-3"><i class="fas fa-id-card me-2"></i>Distribución por Tipo de Pase</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="stats-card">
                                                    <h5 class="text-info"><i class="fas fa-file-alt me-2"></i>Por Examen</h5>
                                                    <p class="h4"><?php echo $stats_tipo_pase['Por examen']; ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="stats-card">
                                                    <h5 class="text-success"><i class="fas fa-graduation-cap me-2"></i>Por Promedio</h5>
                                                    <p class="h4"><?php echo $stats_tipo_pase['Por promedio']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Carga de la biblioteca Google Charts
        google.charts.load('current', {'packages':['corechart']});
        
        // Datos estadísticos de PHP a JavaScript
        const generalStats = {
            aprobados: <?php echo $stats_general['aprobados']; ?>,
            rechazados: <?php echo $stats_general['rechazados']; ?>,
            en_revision: <?php echo $stats_general['en_revision']; ?>,
            sin_docs: <?php echo $stats_general['sin_docs']; ?>
        };
        
        const tipoPaseStats = {
            por_examen: <?php echo $stats_tipo_pase['Por examen']; ?>,
            por_promedio: <?php echo $stats_tipo_pase['Por promedio']; ?>
        };
        
        let currentCareerTitle = "<?php echo $carrera; ?>";
        const plantelActual = "<?php echo ($plantel == 'todos') ? 'Todos' : ucfirst($plantel); ?>";
        const tipoPaseActual = "<?php echo ($tipoPase == 'todos') ? 'Todos' : ($tipoPase == 'examen' ? 'Por Examen' : 'Por Promedio'); ?>";
        
        // Función para dibujar las gráficas
        function drawCharts(stats, tipoPaseData, title) {
            currentCareerTitle = title;
            document.getElementById('selectedCareerTitle').innerHTML = `<i class="fas fa-chart-pie me-2"></i>${title}`;
            document.getElementById('exportPdfBtn').style.display = 'block';
            
            // Gráfica de aceptación
            const acceptanceData = google.visualization.arrayToDataTable([
                ['Estado', 'Cantidad'],
                ['Aceptados', stats.aprobados],
                ['Rechazados', stats.rechazados]
            ]);
            
            const acceptanceOptions = {
                title: 'Índice de Aceptación',
                colors: ['#28a745', '#dc3545'],
                pieHole: 0.4,
                chartArea: {width: '90%', height: '80%'},
                legend: {position: 'bottom'},
                fontSize: 12,
                is3D: false,
                tooltip: {showColorCode: true},
                animation: {
                    startup: true,
                    duration: 1000,
                    easing: 'out'
                }
            };
            
            const acceptanceChart = new google.visualization.PieChart(document.getElementById('acceptanceChart'));
            acceptanceChart.draw(acceptanceData, acceptanceOptions);
            
            // Gráfica de documentación
            const docsData = google.visualization.arrayToDataTable([
                ['Documentación', 'Cantidad'],
                ['En Revisión', stats.en_revision],
                ['Sin Documentos', stats.sin_docs]
            ]);
            
            const docsOptions = {
                title: 'Estado de Documentación',
                colors: ['#17a2b8', '#ffc107'],
                pieHole: 0.4,
                chartArea: {width: '90%', height: '80%'},
                legend: {position: 'bottom'},
                fontSize: 12,
                is3D: false,
                tooltip: {showColorCode: true},
                animation: {
                    startup: true,
                    duration: 1000,
                    easing: 'out'
                }
            };
            
            const docsChart = new google.visualization.PieChart(document.getElementById('documentationChart'));
            docsChart.draw(docsData, docsOptions);
            
            // Gráfica de tipo de pase
            const tipoPaseChartData = google.visualization.arrayToDataTable([
                ['Tipo de Pase', 'Cantidad'],
                ['Por Examen', tipoPaseData.por_examen],
                ['Por Promedio', tipoPaseData.por_promedio]
            ]);
            
            const tipoPaseOptions = {
                title: 'Distribución por Tipo de Pase',
                colors: ['#17a2b8', '#28a745'],
                pieHole: 0.4,
                chartArea: {width: '90%', height: '80%'},
                legend: {position: 'bottom'},
                fontSize: 12,
                is3D: false,
                tooltip: {showColorCode: true},
                animation: {
                    startup: true,
                    duration: 1000,
                    easing: 'out'
                }
            };
            
            const tipoPaseChart = new google.visualization.PieChart(document.getElementById('tipoPaseChart'));
            tipoPaseChart.draw(tipoPaseChartData, tipoPaseOptions);
            
            // Ajustar tamaño de los gráficos cuando cambia el tamaño de la ventana
            window.addEventListener('resize', function() {
                acceptanceChart.draw(acceptanceData, acceptanceOptions);
                docsChart.draw(docsData, docsOptions);
                tipoPaseChart.draw(tipoPaseChartData, tipoPaseOptions);
            });
        }
        
        $(document).ready(function() {
            // Cargar las gráficas cuando Google Charts esté listo
            google.charts.setOnLoadCallback(function() {
                drawCharts(generalStats, tipoPaseStats, '<?php echo $carrera; ?>');
            });
            
            // Configuración para jsPDF
            window.jsPDF = window.jspdf.jsPDF;
            
            // Función para exportar a PDF
            $('#exportPdfBtn').click(function() {
                const doc = new jsPDF('p', 'mm', 'a4');
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                
                // Agregar logo en la parte superior
                const logoImg = new Image();
                logoImg.src = '../imagenes/logo.png';
                logoImg.onload = function() {
                    // Convertir logo a base64 para incluirlo en el PDF
                    const canvas = document.createElement('canvas');
                    canvas.width = logoImg.width;
                    canvas.height = logoImg.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(logoImg, 0, 0);
                    const logoData = canvas.toDataURL('image/png');

                    // Añadir logo al PDF (más pequeño y en esquina superior izquierda)
                    doc.addImage(logoData, 'PNG', 10, 10, 40, 10);
                    
                    // Agregar título del reporte (centrado y con formato más grande)
                    doc.setFontSize(16);
                    doc.setFont('helvetica', 'normal');
                    doc.setFont('helvetica', 'bold');
doc.text('Reporte: ' + currentCareerTitle, pageWidth / 2, 25, { align: 'center' });

// Agregar información de filtros
doc.setFontSize(12);
doc.setFont('helvetica', 'normal');
doc.text('Plantel: ' + plantelActual + ' | Tipo de Pase: ' + tipoPaseActual, pageWidth / 2, 35, { align: 'center' });
doc.text('Fecha: ' + new Date().toLocaleDateString(), pageWidth / 2, 40, { align: 'center' });

// Dibujar línea horizontal
doc.setLineWidth(0.5);
doc.line(10, 45, pageWidth - 10, 45);

// Capturar y agregar las gráficas
html2canvas(document.getElementById('printSection')).then(function(canvas) {
    const imgData = canvas.toDataURL('image/png');
    const imgWidth = pageWidth - 20;
    const imgHeight = canvas.height * imgWidth / canvas.width;
    
    // Añadir la imagen al PDF, ajustada al ancho de página con márgenes
    doc.addImage(imgData, 'PNG', 10, 50, imgWidth, imgHeight);
    
    // Añadir pie de página
    doc.setFontSize(10);
    // Guardar el PDF
    doc.save('Reporte_' + currentCareerTitle.replace(/ /g, '_') + '.pdf');
});
                };
            });
            
            // Evento para resaltar la carrera seleccionada en el menú desplegable
            $('.career-item').each(function() {
                if ($(this).text().includes('<?php echo $carrera; ?>')) {
                    $(this).addClass('active');
                }
            });
            
            // Botón para ver todos los tipos de pase
            $('#showAllTypesBtn').click(function() {
                window.location.href = 'admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=<?php echo $plantel; ?>&tipoPase=todos';
            });
        });
    </script>
</body>
</html>