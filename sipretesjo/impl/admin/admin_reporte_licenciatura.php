<?php
session_start();
header('Content-type: text/html; charset=utf-8');

if (!isset($_SESSION['nombre'])) {
    header('Location: login_admin.html');
    exit;
}

// Corregir la línea con error: usar isset para verificar si existe idLic
$idLic = isset($_GET['idLic']) ? $_GET['idLic'] : 1; // Valor predeterminado: 1 (INGENIERÍA ELECTROMECÁNICA)
$carrera = random_int(1, 13); // Valor aleatorio para la carrera (esto debería ser reemplazado por una lógica real si es necesario)
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

}

require_once('../php/conexion.php');

// CORRECCIÓN: Añadir debug para verificar valores reales en la base de datos
$debug_query = "SELECT DISTINCT tipo_pase FROM persona WHERE carrera = '$carrera'";
$debug_result = mysqli_query($mysqli, $debug_query);
$debug_values = [];
if ($debug_result) {
    while ($row = mysqli_fetch_assoc($debug_result)) {
        $debug_values[] = $row['tipo_pase'];
    }
}
// Comentar o descomentar la siguiente línea para ver los valores reales en la base de datos
// echo "<pre>Valores tipo_pase en DB: " . print_r($debug_values, true) . "</pre>";

// Construir condición de filtro para plantel
$condicionPlantel = ($plantel != 'todos') ? " AND plantel = '$plantel'" : "";

// CORRECCIÓN: Mejorar el manejo del filtro tipo_pase
$condicionTipoPase = "";
if ($tipoPase != 'todos') {
    // Convertir el valor del filtro al formato que existe en la base de datos
    if ($tipoPase == 'examen') {
        $tipoPaseDB = 'Por examen';
    } elseif ($tipoPase == 'promedio') {
        $tipoPaseDB = 'Por promedio';
    } else {
        $tipoPaseDB = $tipoPase; // En caso de que se pase otro valor
    }
    
    $condicionTipoPase = " AND tipo_pase = '$tipoPaseDB'";
}

// Consulta para contar cada estado de documentación general (con los filtros aplicados)
$query_general = "SELECT 
            SUM(CASE WHEN doc_fotografia = 'Aprobado' THEN 1 ELSE 0 END) as Aprobados,
            SUM(CASE WHEN doc_fotografia = 'Rechazado' THEN 1 ELSE 0 END) as Rechazados,
            SUM(CASE WHEN doc_fotografia = 'en_revision' THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN doc_fotografia = 'sin_docs' THEN 1 ELSE 0 END) as sin_docs
        FROM persona 
        WHERE carrera = '$carrera' $condicionPlantel $condicionTipoPase";

$result_general = mysqli_query($mysqli, $query_general);
$stats_general = null;

if ($result_general) {
    $row = mysqli_fetch_assoc($result_general);
    
    // Obtener conteos del resultado de la consulta
    $stats_general = [
        'aprobados' => (int)$row['Aprobados'],
        'rechazados' => (int)$row['Rechazados'],
        'en_revision' => (int)$row['en_revision'],
        'sin_docs' => (int)$row['sin_docs']
    ];
}

// Consulta para contar cada estado de documentación para la carrera seleccionada (con los filtros aplicados)
$query = "SELECT 
            SUM(CASE WHEN doc_fotografia = 'Aprobado' THEN 1 ELSE 0 END) as Aprobados,
            SUM(CASE WHEN doc_fotografia = 'Rechazado' THEN 1 ELSE 0 END) as Rechazados,
            SUM(CASE WHEN doc_fotografia = 'en_revision' THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN doc_fotografia = 'sin_docs' THEN 1 ELSE 0 END) as sin_docs
        FROM persona 
        WHERE carrera = '$carrera' $condicionPlantel $condicionTipoPase";

$result = mysqli_query($mysqli, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    
    // Preparar datos de respuesta
    $responseData = [
        'success' => true,
        'data' => [
            'aprobados' => (int)$row['Aprobados'],
            'rechazados' => (int)$row['Rechazados'],
            'en_revision' => (int)$row['en_revision'],
            'sin_docs' => (int)$row['sin_docs']
        ]
    ];
} else {
    // Error en la consulta a la base de datos
    $responseData = [
        'success' => false,
        'message' => 'Error de consulta: ' . mysqli_error($mysqli)
    ];
}

// Nueva consulta para contar estudiantes por tipo de pase
$query_tipo_pase = "SELECT 
                    tipo_pase, 
                    COUNT(*) as cantidad 
                FROM persona 
                WHERE carrera = '$carrera' $condicionPlantel 
                GROUP BY tipo_pase";

$result_tipo_pase = mysqli_query($mysqli, $query_tipo_pase);

// Array para almacenar los resultados por tipo de pase
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
    <!-- Agregar bibliotecas jsPDF y html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        .career-list { max-height: 400px; overflow-y: auto; }
        .career-item:hover { background-color: #f8f9fa; cursor: pointer; }
        .chart-container { height: 300px; margin-bottom: 20px; }
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
        /* Estilos para la versión de impresión */
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
    </style>
</head>
<body>
    <!-- NAVBAR ORIGINAL (CONTENIDO SUPERIOR) -->
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
    
    <!-- LÍNEA ROJA ORIGINAL -->
    <div class="h4 pb-0 mb-4 text-danger border-bottom border-danger border-3"></div>

    <!-- CONTENEDORES ORIGINALES -->
    <div class="container-fluid">
        <div class="row">
            <!-- Contenedor izquierdo (30%) -->
            <div class="col-md-4 border-container">

            <!-- NUEVO FILTRO: Plantel -->
            <div class="dropdown w-100 mb-3">
                    <button class="btn btn-success dropdown-toggle w-100 filter-btn" type="button" id="showPlantelBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-building me-2"></i>Plantel: <?php echo ($plantel == 'todos') ? 'Todos' : ucfirst($plantel); ?>
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="showPlantelBtn">
                        <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=todos&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item">Todos</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=jocotitlan&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item">Jocotitlan</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=aculco&tipoPase=<?php echo $tipoPase; ?>" class="dropdown-item">Aculco</a></li>
                    </ul>
                </div>
                <!-- Filtro de Carrera -->
                <div class="dropdown w-100 mb-3">
                    <button class="btn btn-primary dropdown-toggle w-100" type="button" id="showCareersBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-graduation-cap me-2"></i>Selecciona una carrera
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="showCareersBtn">
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
                    </ul>
                </div>
                
                <!-- NUEVO FILTRO: Tipo Pase -->
                <div class="dropdown w-100">
                    
                    <ul class="dropdown-menu w-100" aria-labelledby="showTipoPaseBtn">
                        <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=<?php echo $plantel; ?>&tipoPase=todos" class="dropdown-item">Todos</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=<?php echo $plantel; ?>&tipoPase=examen" class="dropdown-item">Por Examen</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=<?php echo $idLic; ?>&plantel=<?php echo $plantel; ?>&tipoPase=promedio" class="dropdown-item">Por Promedio</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contenedor derecho (70%) -->
            <div class="col-md-8 border-container">
                <div id="chartsContainer">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 id="selectedCareerTitle" class="mb-0 text-center flex-grow-1"><i class="fas fa-chart-pie me-2"></i><?php echo $carrera; ?></h3>
                        <!-- Botón para exportar a PDF (reposicionado) -->
                        <button id="exportPdfBtn" class="btn btn-success export-btn no-print">
                            <i class="fas fa-file-pdf me-2"></i>Exportar a PDF
                        </button>
                    </div>
                    
                    <!-- Información de filtros activos -->
                    <div class="alert alert-info mb-4">
                        <strong>Filtros activos:</strong> 
                        Plantel: <?php echo ($plantel == 'todos') ? 'Todos' : ucfirst($plantel); ?>, 
                        Tipo de Pase: <?php echo ($tipoPase == 'todos') ? 'Todos' : ($tipoPase == 'examen' ? 'Por Examen' : 'Por Promedio'); ?>
                        
                        <?php if (!empty($debug_values)): ?>
                        <!-- Comentar o descomentar para depuración -->
                        <!-- <br><small>(Valores en BD: <?php echo implode(', ', $debug_values); ?>)</small> -->
                        <?php endif; ?>
                    </div>
                    
                    <!-- Sección que se imprimirá en el PDF -->
                    <div id="printSection">
                        <!-- Gráficas -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div id="acceptanceChart" class="chart-container"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="documentationChart" class="chart-container"></div>
                            </div>
                        </div>
                        
                        <!-- NUEVA GRÁFICA: Distribución por tipo de pase -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div id="tipoPaseChart" class="chart-container"></div>
                            </div>
                        </div>
                        
                        <!-- Línea divisoria roja entre gráficas y estadísticas -->
                        <div class="border-top border-danger border-3 my-4"></div>
                        
                        <!-- Estadísticas -->
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
                                
                                <!-- NUEVA SECCIÓN: Estadísticas por tipo de pase -->
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
        // Carga de Google Charts
        google.charts.load('current', {'packages':['corechart']});
        
        // Datos para las gráficas generales
        const generalStats = {
            aprobados: <?php echo $stats_general['aprobados']; ?>,
            rechazados: <?php echo $stats_general['rechazados']; ?>,
            en_revision: <?php echo $stats_general['en_revision']; ?>,
            sin_docs: <?php echo $stats_general['sin_docs']; ?>
        };
        
        // Datos para la gráfica de tipo de pase
        const tipoPaseStats = {
            por_examen: <?php echo $stats_tipo_pase['Por examen']; ?>,
            por_promedio: <?php echo $stats_tipo_pase['Por promedio']; ?>
        };
        
        // Variable para guardar el título de la carrera actual
        let currentCareerTitle = "<?php echo $carrera; ?>";
        
        // Obtener información de filtros para incluir en el PDF
        const plantelActual = "<?php echo ($plantel == 'todos') ? 'Todos' : ucfirst($plantel); ?>";
        const tipoPaseActual = "<?php echo ($tipoPase == 'todos') ? 'Todos' : ($tipoPase == 'examen' ? 'Por Examen' : 'Por Promedio'); ?>";
        
        // Función para dibujar gráficas basado en los datos proporcionados
        function drawCharts(stats, tipoPaseData, title) {
            // Actualizar el título de carrera actual
            currentCareerTitle = title;
            document.getElementById('selectedCareerTitle').innerHTML = `<i class="fas fa-chart-pie me-2"></i>${title}`;
            
            // Asegurar que el botón de exportar siempre esté visible
            document.getElementById('exportPdfBtn').style.display = 'block';
            
            // Gráfica de aceptación
            const acceptanceData = google.visualization.arrayToDataTable([
                ['Estado', 'Cantidad'],
                ['Aceptados', stats.aprobados],
                ['Rechazados', stats.rechazados]
            ]);
            
            new google.visualization.PieChart(document.getElementById('acceptanceChart'))
                .draw(acceptanceData, {
                    title: 'Índice de Aceptación',
                    colors: ['#28a745', '#dc3545'],
                    pieHole: 0.4
                });
            
            // Gráfica de documentación
            const docsData = google.visualization.arrayToDataTable([
                ['Documentación', 'Cantidad'],
                ['En Revisión', stats.en_revision],
                ['Sin Documentos', stats.sin_docs]
            ]);
            
            new google.visualization.PieChart(document.getElementById('documentationChart'))
                .draw(docsData, {
                    title: 'Estado de Documentación',
                    colors: ['#17a2b8', '#ffc107'],
                    pieHole: 0.4
                });
                
            // Nueva gráfica: Distribución por tipo de pase
            const tipoPaseChartData = google.visualization.arrayToDataTable([
                ['Tipo de Pase', 'Cantidad'],
                ['Por Examen', tipoPaseData.por_examen],
                ['Por Promedio', tipoPaseData.por_promedio]
            ]);
            
            new google.visualization.PieChart(document.getElementById('tipoPaseChart'))
                .draw(tipoPaseChartData, {
                    title: 'Distribución por Tipo de Pase',
                    colors: ['#17a2b8', '#28a745'],
                    pieHole: 0.4
                });
        }
        
        $(document).ready(function() {
            // Dibujar gráficas generales cuando la página cargue
            google.charts.setOnLoadCallback(function() {
                drawCharts(generalStats, tipoPaseStats, '<?php echo $carrera; ?>');
            });
            
            // Inicialización de jsPDF
            window.jsPDF = window.jspdf.jsPDF;
            
            // Función para exportar a PDF
            $('#exportPdfBtn').click(function() {
                const doc = new jsPDF('p', 'mm', 'a4');
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                
                // Agregar solo el título 
                doc.setFontSize(16);
                doc.text('Reporte de Estadísticas', pageWidth/2, 20, { align: 'center' });
                doc.setFontSize(14);
                doc.text(currentCareerTitle, pageWidth/2, 30, { align: 'center' });
                
                // Agregar información de filtros
                doc.setFontSize(12);
                doc.text(`Plantel: ${plantelActual} | Tipo de Pase: ${tipoPaseActual}`, pageWidth/2, 40, { align: 'center' });
                // Capturar las gráficas como imágenes
                html2canvas(document.getElementById('acceptanceChart')).then(function(canvas) {
                    const imgData = canvas.toDataURL('image/png');
                    doc.addImage(imgData, 'PNG', 10, 50, pageWidth - 20, 70);
                    
                    html2canvas(document.getElementById('documentationChart')).then(function(canvas) {
                        const imgData = canvas.toDataURL('image/png');
                        doc.addImage(imgData, 'PNG', 10, 130, pageWidth - 20, 70);
                        
                        html2canvas(document.getElementById('tipoPaseChart')).then(function(canvas) {
                            const imgData = canvas.toDataURL('image/png');
                            doc.addImage(imgData, 'PNG', 10, 210, pageWidth - 20, 70);
                            
                            // Agregar estadísticas en formato texto
                            doc.addPage();
                            doc.setFontSize(14);
                            doc.text('Datos numéricos:', 10, 20);
                            doc.setFontSize(12);
                            doc.text(`Aprobados: ${generalStats.aprobados}`, 20, 35);
                            doc.text(`Rechazados: ${generalStats.rechazados}`, 20, 45);
                            doc.text(`En revisión de documentos: ${generalStats.en_revision}`, 20, 55);
                            doc.text(`Sin documentos: ${generalStats.sin_docs}`, 20, 65);
                            doc.text(`Por examen: ${tipoPaseStats.por_examen}`, 20, 75);
                            doc.text(`Por promedio: ${tipoPaseStats.por_promedio}`, 20, 85);
                            
                            // Guardar el PDF
                            doc.save(`Estadisticas_${currentCareerTitle.replace(/\s+/g, '_')}.pdf`);
                        });
                    });
                });
            });
        });
    </script>
    </body>
    </html>

