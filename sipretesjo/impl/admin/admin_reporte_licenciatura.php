<?php
session_start();
header('Content-type: text/html; charset=utf-8');

if (!isset($_SESSION['nombre'])) {
    header('Location: login_admin.html');
    exit;
}

// Corregir la línea con error: usar isset para verificar si existe idLic
$idLic = isset($_GET['idLic']) ? $_GET['idLic'] : 1; // Valor predeterminado: 1 (INGENIERÍA ELECTROMECÁNICA)
$carrera = "INGENIERÍA ELECTROMECÁNICA";

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
}

require_once('../php/conexion.php');

// Consulta para contar cada estado de documentación general (sin filtrar por carrera)
$query_general = "SELECT 
            SUM(CASE WHEN doc_fotografia = 'Aprobado' THEN 1 ELSE 0 END) as Aprobados,
            SUM(CASE WHEN doc_fotografia = 'Rechazado' THEN 1 ELSE 0 END) as Rechazados,
            SUM(CASE WHEN doc_fotografia = 'en_revision' THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN doc_fotografia = 'sin_docs' THEN 1 ELSE 0 END) as sin_docs
        FROM persona WHERE carrera = '$carrera'";

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

// Consulta para contar cada estado de documentación para la carrera seleccionada
$query = "SELECT 
            SUM(CASE WHEN doc_fotografia = 'Aprobado' THEN 1 ELSE 0 END) as Aprobados,
            SUM(CASE WHEN doc_fotografia = 'Rechazado' THEN 1 ELSE 0 END) as Rechazados,
            SUM(CASE WHEN doc_fotografia = 'en_revision' THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN doc_fotografia = 'sin_docs' THEN 1 ELSE 0 END) as sin_docs
        FROM persona 
        WHERE carrera = '$carrera'";

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
                <div class="dropdown w-100">
                    <button class="btn btn-primary dropdown-toggle w-100" type="button" id="showCareersBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-graduation-cap me-2"></i>Selecciona una carrera
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="showCareersBtn">
                        <li><a href="admin_reportes.php?idLic=13" class="dropdown-item career-item" data-career="General">Datos Generales</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=1" class="dropdown-item career-item" data-career="Electromecanica">Ingeniería en Electromecánica</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=4" class="dropdown-item career-item" data-career="Gestion Empresarial">Ingeniería en Gestión Empresarial</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=3" class="dropdown-item career-item" data-career="Industrial">Ingeniería Industrial</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=5" class="dropdown-item career-item" data-career="Quimica">Ingeniería Química</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=6" class="dropdown-item career-item" data-career="Sistemas">Ingeniería en Sistemas Computacionales</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=12" class="dropdown-item career-item" data-career="Materiales">Ingeniería en Materiales</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=7" class="dropdown-item career-item" data-career="Arquitectura">Licenciatura en Arquitectura</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=8" class="dropdown-item career-item" data-career="Animacion">Ingeniería en Animación Digital y Efectos Visuales</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=9" class="dropdown-item career-item" data-career="Mecatronica">Ingeniería en Mecatrónica</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=10" class="dropdown-item career-item" data-career="Turismo">Ingeniería en Turismo</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=11" class="dropdown-item career-item" data-career="Contador">Licenciatura en Contador Público</a></li>
                        <li><a href="admin_reporte_licenciatura.php?idLic=2" class="dropdown-item career-item" data-career="Logistica">Ingeniería en Logística</a></li>
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
        
        // Variable para guardar el título de la carrera actual
        let currentCareerTitle = "<?php echo $carrera; ?>";
        
        // Función para dibujar gráficas basado en los datos proporcionados
        function drawCharts(stats, title) {
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
        }
        
        $(document).ready(function() {
            // Dibujar gráficas generales cuando la página cargue
            google.charts.setOnLoadCallback(function() {
                drawCharts(generalStats, '<?php echo $carrera; ?>');
            });
            
            // Inicialización de jsPDF
            window.jsPDF = window.jspdf.jsPDF;
            
            // Función para exportar a PDF
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
    
        // Fecha actual (para usar solo en el pie de página)
        const today = new Date();
        const dateStr = today.toLocaleDateString('es-MX');
    
    // Capturar logo del TecNM primero
        html2canvas(document.querySelector('.navbar img'), {
            scale: 2,
            backgroundColor: null
        }).then(function(logoCanvas) {
            // Obtener la imagen del logo como base64
            const logoData = logoCanvas.toDataURL('image/png');
        
            // Añadir el logo en la esquina superior izquierda
            const logoWidth = 40;
            const logoHeight = (logoCanvas.height * logoWidth) / logoCanvas.width;
            doc.addImage(logoData, 'PNG', 10, 10, logoWidth, logoHeight);
        
            // Agregar línea roja fina como en la imagen de referencia
            const lineY = 45;
                doc.setDrawColor(220, 53, 69); // Color rojo (bootstrap danger)
                doc.setLineWidth(0.3); // Línea más fina
                doc.line(19, lineY, pageWidth - 19, lineY); // Línea de extremo a extremo
        
            // Crear imagen de la sección principal a exportar
            html2canvas(document.getElementById('printSection'), {
                scale: 2, // Mejor calidad
                backgroundColor: '#ffffff'
            }).then(function(canvas) {
                // Obtener la imagen como base64
                const imgData = canvas.toDataURL('image/png');
                
                // Calcular dimensiones para mantener la proporción
                const contentWidth = pageWidth - 40; // Márgenes de 20mm a cada lado
                const contentHeight = (canvas.height * contentWidth) / canvas.width;
                
                // Agregar la imagen al PDF (ajustar posición Y para dejar espacio al logo)
                doc.addImage(imgData, 'PNG', 20, 55, contentWidth, contentHeight);
                
                // Agregar pie de página con la fecha
                const footerY = contentHeight + 65;
                doc.setFontSize(10);
                doc.text('Este reporte contiene información estadística actualizada al ' + dateStr, pageWidth/2, footerY, { align: 'center' });
            
                // Guardar el PDF
                doc.save('Reporte_' + currentCareerTitle.replace(/\s/g, '_') + '_' + dateStr.replace(/\//g, '-') + '.pdf');
            });
        });
    });
            
            // Manejar selección de carrera con navegación normal sin AJAX
            $('.career-item').click(function(e) {
                // No usamos e.preventDefault() para permitir la navegación normal del navegador
                // Esto evita problemas de formato JSON y permite cargas de página completas
                
                // Si es necesario hacer algo antes de la navegación, se puede hacer aquí
                const careerName = $(this).text();
                console.log("Navegando a: " + careerName);
                
                // Importante: dejamos que el navegador haga la navegación normal
                // No hacemos AJAX aquí, lo que evita el error de JSON
            });
        });
    </script>
</body>
</html>