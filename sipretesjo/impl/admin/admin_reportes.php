<?php
session_start();
header('Content-type: text/html; charset=utf-8');

if (!isset($_SESSION['nombre'])) {
    header('Location: login_admin.html');
    exit;
}

require_once('../php/conexion.php');

// Consulta para contar cada estado de documentación general (sin filtrar por carrera)
$query_general = "SELECT 
            SUM(CASE WHEN doc_fotografia = 'Aprobado' THEN 1 ELSE 0 END) as Aprobados,
            SUM(CASE WHEN doc_fotografia = 'Rechazado' THEN 1 ELSE 0 END) as Rechazados,
            SUM(CASE WHEN doc_fotografia = 'en_revision' THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN doc_fotografia = 'sin_docs' THEN 1 ELSE 0 END) as sin_docs
        FROM persona";

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
            display: block !important; /* Forzar visibilidad del botón */
        }
        
        /* Add print-specific styles */
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
                        <li><a href="admin_reporte_licenciatura.php?idLic=1" class="dropdown-item career-item" data-career="Electromecanica">Ingenier&iacute;a en Electromecánica</a></li>
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
                        <h3 id="selectedCareerTitle" class="mb-0 text-center flex-grow-1"><i class="fas fa-chart-pie me-2"></i>Datos Generales</h3>
                        <!-- Botón para exportar a PDF -->
                        <button id="exportPdfBtn" class="btn btn-success export-btn no-print">
                            <i class="fas fa-file-pdf me-2"></i>Exportar a PDF
                        </button>
                    </div>
                    
                    <!-- Wrap the content to be printed in a div with id="printSection" -->
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
        
        // Función para dibujar gráficas basado en los datos proporcionados
        function drawCharts(stats, title) {
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
            // Inicialización de jsPDF
            window.jsPDF = window.jspdf.jsPDF;
            
            // Dibujar gráficas generales cuando la página cargue
            google.charts.setOnLoadCallback(function() {
                drawCharts(generalStats, 'Datos Generales');
            });
            
            // Manejar selección de carrera
            $('.career-item').click(function() {
                const careerName = $(this).text();
                const careerId = $(this).data('career');
                
                $('#selectedCareerTitle').html(`<i class="fas fa-university me-2"></i>${careerName}`);
                
                // Si es "Datos Generales", mostrar esos datos
                if (careerId === 'General') {
                    $('#acceptedCount').text(generalStats.aprobados);
                    $('#rejectedCount').text(generalStats.rechazados);
                    $('#completeDocsCount').text(generalStats.en_revision);
                    $('#incompleteDocsCount').text(generalStats.sin_docs);
                    
                    google.charts.setOnLoadCallback(function() {
                        drawCharts(generalStats, careerName);
                    });
                    return;
                }
                
                // Para otras carreras, hacer la llamada AJAX
                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: { 
                        action: 'getCareerStats',
                        career: careerId 
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            const stats = response.data;
                            
                            // Actualizar estadísticas en las tarjetas
                            $('#acceptedCount').text(stats.aprobados);
                            $('#rejectedCount').text(stats.rechazados);
                            $('#completeDocsCount').text(stats.en_revision);
                            $('#incompleteDocsCount').text(stats.sin_docs);
                            
                            // Dibujar gráficas
                            google.charts.setOnLoadCallback(function() {
                                drawCharts(stats, careerName);
                            });
                        } else {
                            // Mostrar mensaje de error
                            alert('Error al cargar los datos: ' + (response.message || 'Error desconocido'));
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error de conexión al servidor: ' + error);
                    }
                });
            });
            
            // Función para exportar a PDF
            $('#exportPdfBtn').click(function() {
                const doc = new jsPDF('p', 'mm', 'a4');
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                
                // Agregar logo y título
                doc.setFontSize(16);
                doc.text('REPORTE DE ESTADÍSTICAS', pageWidth/2, 20, { align: 'center' });
                doc.setFontSize(14);
                
                // Obtener el título actual que está siendo mostrado
                const currentTitle = $('#selectedCareerTitle').text().trim();
                doc.text(currentTitle, pageWidth/2, 30, { align: 'center' });
                
                // Fecha actual
                const today = new Date();
                const dateStr = today.toLocaleDateString('es-MX');
                doc.setFontSize(10);
                doc.text('Fecha de generación: ' + dateStr, pageWidth - 15, 40, { align: 'right' });
                
                // Crear imagen de la sección a exportar
                html2canvas(document.getElementById('printSection'), {
                    scale: 2, // Mejor calidad
                    backgroundColor: '#ffffff'
                }).then(function(canvas) {
                    // Obtener la imagen como base64
                    const imgData = canvas.toDataURL('image/png');
                    
                    // Calcular dimensiones para mantener la proporción
                    const contentWidth = pageWidth - 40; // Márgenes de 20mm a cada lado
                    const contentHeight = (canvas.height * contentWidth) / canvas.width;
                    
                    // Agregar la imagen al PDF
                    doc.addImage(imgData, 'PNG', 20, 50, contentWidth, contentHeight);
                    
                    // Agregar pie de página
                    const footerY = contentHeight + 60;
                    doc.setFontSize(10);
                    doc.text('Este reporte contiene información estadística actualizada al ' + dateStr, pageWidth/2, footerY, { align: 'center' });
                    
                    // Guardar el PDF con el nombre basado en el título actual
                    const pdfTitle = currentTitle.replace(/[^\w\s]/gi, '').trim();
                    doc.save('Reporte_' + pdfTitle + '_' + dateStr.replace(/\//g, '-') + '.pdf');
                });
            });
        });
    </script>
</body>
</html>