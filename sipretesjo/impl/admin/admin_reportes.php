<?php
session_start();
header('Content-type: text/html; charset=utf-8');

if (!isset($_SESSION['nombre'])) {
    header('Location: login_admin.html');
    exit;
}

require_once('../php/conexion.php');

// Agregar el filtro por plantel
$plantel = isset($_GET['plantel']) ? $_GET['plantel'] : 'todos'; // Valor predeterminado: todos
$condicionPlantel = ($plantel != 'todos') ? " WHERE plantel = '$plantel'" : "";

// Consulta para contar cada estado de documentación general con filtro de plantel
$query_general = "SELECT 
            SUM(CASE WHEN doc_fotografia = 'Aprobado' THEN 1 ELSE 0 END) as Aprobados,
            SUM(CASE WHEN doc_fotografia = 'Rechazado' THEN 1 ELSE 0 END) as Rechazados,
            SUM(CASE WHEN doc_fotografia = 'en_revision' THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN doc_fotografia = 'sin_docs' THEN 1 ELSE 0 END) as sin_docs
        FROM persona" . $condicionPlantel;

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

// Obtener datos de distribución por plantel (para mostrar en una nueva gráfica)
$query_planteles = "SELECT 
                plantel,
                COUNT(*) as total_alumnos
            FROM persona
            GROUP BY plantel";

$result_planteles = mysqli_query($mysqli, $query_planteles);
$stats_planteles = [
    'jocotitlán' => 0,
    'aculco' => 0
];

if ($result_planteles) {
    while ($row = mysqli_fetch_assoc($result_planteles)) {
        if (isset($row['plantel']) && isset($row['total_alumnos'])) {
            $plantel_nombre = strtolower($row['plantel']);
            if (array_key_exists($plantel_nombre, $stats_planteles)) {
                $stats_planteles[$plantel_nombre] = (int)$row['total_alumnos'];
            }
        }
    }
}

// Determinar título según el plantel seleccionado
$plantelTitulo = ($plantel == 'todos') ? 'Datos Generales - Todos los planteles' : 'Datos Generales - Plantel ' . ucfirst($plantel);

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
        .chart-container { height: 300px; margin-bottom: 20px; width: 100%;}
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
            display: block !important;
        }
        .filter-btn {
            margin-bottom: 10px;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
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
    
    <!-- LÍNEA ROJA -->
    <div class="h4 pb-0 mb-4 text-danger border-bottom border-danger border-3"></div>

    <!-- CONTENEDORES -->
    <div class="container-fluid">
        <div class="row">
            <!-- Contenedor izquierdo (30%) -->
            <div class="col-md-4 border-container">
                <!-- Agregar filtro de plantel -->
                <div class="dropdown w-100 mb-3">
                    <button class="btn btn-success dropdown-toggle w-100 filter-btn" type="button" id="showPlantelBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-building me-2"></i>Plantel: <?php echo ($plantel == 'todos') ? 'Todos' : ucfirst($plantel); ?>
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="showPlantelBtn">
                        <li><a href="admin_reporte_licenciatura.php?plantel=todos" class="dropdown-item">Todos</a></li>
                        <li><a href="admin_reporte_licenciatura.php?plantel=jocotitlan" class="dropdown-item">Jocotitlán</a></li>
                        <li><a href="admin_reporte_licenciatura.php?plantel=aculco" class="dropdown-item">Aculco</a></li>
                    </ul>
                </div>

                <div class="dropdown w-100">
                    <button class="btn btn-primary dropdown-toggle w-100" type="button" id="showCareersBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-graduation-cap me-2"></i>Selecciona una carrera
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="showCareersBtn">
                        <li><a href="admin_reporte_licenciatura.php?idLic=13&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="General">Datos Generales</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=1&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Electromecanica">Ingenier&iacute;a en Electromecánica</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=4&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Gestion Empresarial">Ingeniería en Gestión Empresarial</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=3&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Industrial">Ingeniería Industrial</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=5&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Quimica">Ingeniería Química</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=6&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Sistemas">Ingeniería en Sistemas Computacionales</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=12&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Materiales">Ingeniería en Materiales</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=7&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Arquitectura">Licenciatura en Arquitectura</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=8&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Animacion">Ingeniería en Animación Digital y Efectos Visuales</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=9&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Mecatronica">Ingeniería en Mecatrónica</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=10&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Turismo">Ingeniería en Turismo</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=11&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Contador">Licenciatura en Contador Público</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=2&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="Logistica">Ingeniería en Logística</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=14&plantel=<?php echo $plantel; ?>" class="dropdown-item career-item" data-career="INGENIERÍA INDUSTRIAL (a distancia)">INGENIERÍA INDUSTRIAL (a distancia)</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contenedor derecho (70%) -->
            <div class="col-md-8 border-container">
                <div id="chartsContainer">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 id="selectedCareerTitle" class="mb-0 text-center flex-grow-1"><i class="fas fa-chart-pie me-2"></i><?php echo $plantelTitulo; ?></h3>
                        <!-- Botón para exportar a PDF -->
                        <button id="exportPdfBtn" class="btn btn-success export-btn no-print">
                            <i class="fas fa-file-pdf me-2"></i>Exportar a PDF
                        </button>
                    </div>
                    
                    <!-- Información de filtro aplicado -->
                    <?php if ($plantel != 'todos'): ?>
                    <div class="alert alert-info mb-4">
                        <strong>Filtro aplicado:</strong> Plantel: <?php echo ucfirst($plantel); ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Sección para imprimir -->
                    <div id="printSection">
                        <!-- Gráficas -->
                        <div class="row">
                            <div class="col-md-4">
                                <div id="acceptanceChart" class="chart-container"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="documentationChart" class="chart-container"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="plantelDistributionChart" class="chart-container"></div>
                            </div>
                        </div>
                        
                        <!-- Línea divisoria roja -->
                        <div class="border-top border-danger border-3 my-4"></div>
                        <!-- Estadísticas -->
                        <div class="row">
                            <div class="col">
                                <h4 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h4>
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
                                
                                <?php if ($plantel == 'todos'): ?>
                                <!-- Estadísticas por plantel (solo cuando el filtro es 'todos') -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h4 class="mb-3"><i class="fas fa-building me-2"></i>Distribución por Plantel</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="stats-card">
                                                    <h5 class="text-primary"><i class="fas fa-map-marker-alt me-2"></i>Jocotitlán</h5>
                                                    <p class="h4" id="jocotitlanCount"><?php echo $stats_planteles['jocotitlán']; ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="stats-card">
                                                    <h5 class="text-success"><i class="fas fa-map-marker-alt me-2"></i>Aculco</h5>
                                                    <p class="h4" id="aculcoCount"><?php echo $stats_planteles['aculco']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
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
        
        // Datos para la distribución por plantel
        const plantelStats = {
            jocotitlan: <?php echo $stats_planteles['jocotitlán']; ?>,
            aculco: <?php echo $stats_planteles['aculco']; ?>
        };
        
        // Plantel actual seleccionado
        const plantelActual = "<?php echo ($plantel == 'todos') ? 'Todos los planteles' : 'Plantel ' . ucfirst($plantel); ?>";
        const esPlantelFiltrado = "<?php echo ($plantel != 'todos') ? 'true' : 'false'; ?>";
        
        // Función para dibujar gráficas
        function drawCharts() {
            // Gráfica de aceptación
            const acceptanceData = google.visualization.arrayToDataTable([
                ['Estado', 'Cantidad'],
                ['Aceptados', generalStats.aprobados],
                ['Rechazados', generalStats.rechazados]
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
            
            new google.visualization.PieChart(document.getElementById('acceptanceChart'))
                .draw(acceptanceData, acceptanceOptions);
            
            // Gráfica de documentación
            const docsData = google.visualization.arrayToDataTable([
                ['Documentación', 'Cantidad'],
                ['En Revisión', generalStats.en_revision],
                ['Sin Documentos', generalStats.sin_docs]
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
            
            new google.visualization.PieChart(document.getElementById('documentationChart'))
                .draw(docsData, docsOptions);
                
            // Gráfica de distribución por plantel 
            // Para todos los planteles mostramos el gráfico, para un plantel específico mostramos mensaje
            if (esPlantelFiltrado === 'false') {
                // Solo dibujamos cuando hay datos disponibles
                if (plantelStats.jocotitlan > 0 || plantelStats.aculco > 0) {
                    const plantelData = google.visualization.arrayToDataTable([
                        ['Plantel', 'Cantidad'],
                        ['Jocotitlán', plantelStats.jocotitlan],
                        ['Aculco', plantelStats.aculco]
                    ]);
                    
                    const plantelOptions = {
                        title: 'Distribución de Alumnos por Plantel',
                        colors: ['#007bff', '#28a745'],
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
                    
                    new google.visualization.PieChart(document.getElementById('plantelDistributionChart'))
                        .draw(plantelData, plantelOptions);
                } else {
                    // Si no hay datos disponibles
                    document.getElementById('plantelDistributionChart').innerHTML = 
                        '<div class="alert alert-warning text-center">No hay datos disponibles para mostrar</div>';
                }
            } else {
                // Si está filtrado por un plantel específico
                document.getElementById('plantelDistributionChart').innerHTML = 
                    '<div class="alert alert-info text-center">Distribución no disponible al filtrar por plantel específico</div>';
            }
        }
        
        $(document).ready(function() {
            // Inicialización de jsPDF
            window.jsPDF = window.jspdf.jsPDF;
            
            // Dibujar gráficas generales cuando la página cargue
            google.charts.setOnLoadCallback(drawCharts);
            
            // Función para exportar a PDF
            $('#exportPdfBtn').click(function() {
                const doc = new jsPDF('p', 'mm', 'a4');
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                
                // Agregar logo y título
                doc.setFontSize(16);
                doc.text('REPORTE DE ESTADÍSTICAS', pageWidth/2, 20, { align: 'center' });
                doc.setFontSize(14);
                
                // Obtener el título actual con plantel incluido
                const currentTitle = $('#selectedCareerTitle').text().trim();
                doc.text(currentTitle, pageWidth/2, 30, { align: 'center' });
                
                // Fecha actual
                const today = new Date();
                const dateStr = today.toLocaleDateString('es-MX');
                doc.setFontSize(10);
                doc.text('Fecha de generación: ' + dateStr, pageWidth - 15, 40, { align: 'right' });
                
                // Crear imagen de la sección a exportar
                html2canvas(document.getElementById('printSection'), {
                    scale: 2,
                    backgroundColor: '#ffffff'
                }).then(function(canvas) {
                    // Obtener la imagen como base64
                    const imgData = canvas.toDataURL('image/png');
                    
                    // Calcular dimensiones
                    const contentWidth = pageWidth - 40;
                    const contentHeight = (canvas.height * contentWidth) / canvas.width;
                    
                    // Agregar la imagen al PDF
                    doc.addImage(imgData, 'PNG', 20, 50, contentWidth, contentHeight);
                    
                    // Agregar pie de página
                    const footerY = contentHeight + 60;
                    doc.setFontSize(10);
                    doc.text('Este reporte contiene información estadística actualizada al ' + dateStr, pageWidth/2, footerY, { align: 'center' });
                    
                    // Guardar el PDF
                    const pdfTitle = currentTitle.replace(/[^\w\s]/gi, '').trim();
                    doc.save('Reporte_' + pdfTitle + '_' + dateStr.replace(/\//g, '-') + '.pdf');
                });
            });
        });
    </script>
</body>
</html>