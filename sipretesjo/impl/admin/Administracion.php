<?php
session_start();
header ('Content-type: text/html; charset=utf-8');

if (!isset($_SESSION['nombre'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_admin.html');
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
 
    <title>Registros</title>
    <script src="https://kit.fontawesome.com/728cc4b6c5.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">


<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <img src="../imagenes/logo.png" alt="Bootstrap" width="270" height="70">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup" >
      <div class="navbar-nav ml-auto">
            <a class="nav-link active" aria-current="page" href="admin_aprobados.php"style="font-size: 18px;"><u>Aspirantes aprobados</u></a>
            <a class="nav-link active" aria-current="page" href="admin_revision.php"style="font-size: 18px;"><u>Aspirantes en revisión</u></a>
            <a class="nav-link active" aria-current="page" href="admin_sin_docs.php"style="font-size: 18px;"><u>Aspirantes sin documentación</u></a>
            <a class="nav-link active" aria-current="page" href="admin_rechazados.php"style="font-size: 18px;"><u>Aspirantes rechazados</u></a>
            <a class="nav-link active" aria-current="page" href="admin_reportes.php"style="font-size: 18px;"><u>Reportes</u></a>

          </div>
             
    </div>
    <form class="d-flex">
  <div class="dropdown">
    <a class="navbar-brand dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="../imagenes/perfil.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
      <?php echo $_SESSION['nombre']; ?>
    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
      <li>
        <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
      </li>
    </ul>
  </div>
</form>
  </nav>
  <div class="h4 pb-0 mb-4 text-danger border-bottom border-danger border-3"></div>
</head>
<body>
    <div class="row">
      <div class="col text-start">
        <h1>Lista de aspirantes registrados</h1>
      </div>
      <div class="col text-end">
         <a href ="expedientes.php" class="btn btn-success" style="margin-right: 20px;">Descargar expedientes en general</a>
         <!--a href="descargar_archivo.php" class="btn btn-success" style="margin-right: 20px;">Descargar CSV</a-->
	 <button id="downloadCSV" class="btn btn-success">Descargar CSV</button>
      </div>
   </div>
   <div class="row">
     <div class="col text-end">
        <form action="procesar_csv.php" method="post" enctype="multipart/form-data">
            <input type="file" name="archivo_csv" accept=".csv" required>
            <button type="submit"class="btn btn-success" style="margin-right: 20px;">Subir archivo CSV</button>
        </form>
      </div>
   </div>

<br>

<div class="row" style="width:95%; margin: 0px auto">
    <table id="dataTable" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Curp</th>
                <th>Folio</th>
                <th>Nombre (s)</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Carrera</th>
                <th>Plantel</th>
                <th>Tipo pase</th>
                <th>Estatus</th>
                <th>Ver Aspirante</th>
                <th>Expediente</th>
                <th>Validó</th>
            </tr>
            <tr>
                <th></th>
                <th><input type="text" placeholder="Buscar CURP"></th>
                <th><input type="text" placeholder="Buscar Folio"></th>
                <th><input type="text" placeholder="Buscar Nombre"></th>
                <th><input type="text" placeholder="Buscar A. Paterno"></th>
                <th><input type="text" placeholder="Buscar A. Materno"></th>
                <th><select id="filter-carrera"><option value="">Todas</option></select></th>
                <th><select id="filter-plantel"><option value="">Todos</option></select></th>
                <th><select id="filter-tipo-pase"><option value="">Todos</option></select></th>
                <th><select id="filter-estatus"><option value="">Todos</option></select></th>
                <th></th>
                <th></th>
                <th><select id="filter-valido"><option value="">Todos</option></select></th>
            </tr>
        </thead>
        <tbody>
            <?php
                    // Paso 1: Conexión a la base de datos
                    require_once('../php/conexion.php');

                    // Paso 2: Ejecutar la consulta
                    $sql = "SELECT * FROM persona";
                    $result = $mysqli->query($sql);

                    // Paso 3: Mostrar los datos en la página HTML

                    if ($result->num_rows > 0) {
                      
                        $trData = "";

                        // iterar a través de los resultados y mostrarlos en la tabla HTML
                        while($row = $result->fetch_assoc()) {

                            $trData .= "
                                    <tr> 
                                        <td><img src='../archivos/".$row["curp"]."/Fotografia.jpg"."' alt='".$row["nombre"]."' width='30' height='30'></td>
                                        <td>".$row["curp"]."</td>
                                        <td>".$row["folio"]."</td>
                                        <td>".$row["nombre"]."</td>
                                        <td>".$row["ap_pat"]."</td>
                                        <td>".$row["ap_mat"]."</td>
                                        <td>".$row["carrera"]."</td>
                                        <td>".$row["plantel"]."</td>
                                        <td>".$row["tipo_pase"]."</td>
                                        <td>".$row["doc_fotografia"]."</td>
                                        <td><a href='ver_alumno.php?curp=" . $row["curp"] . "&folio=" . $row["folio"] ."&nombre=" . $row["nombre"] ."&ap_pat=". $row["ap_pat"] . "&ap_mat=". $row["ap_mat"] ."&tel_personal=". $row["tel_personal"] ."&correo_elec=". $row["correo_elec"] . "&escuela=" . $row["escuela"] . "&plantel=" . $row["plantel"] . "&carrera=" . $row["carrera"] . "&tipo_pase=" . $row["tipo_pase"] ."&doc_fotografia=". $row["doc_fotografia"]  ."&doc_curp=". $row["doc_curp"] . "&doc_acta=". $row["doc_acta"] . "&doc_certificado=". $row["doc_certificado"] . "&doc_ine=". $row["doc_ine"] . "&doc_formato=". $row["doc_formato"] ."&doc_pago=". $row["doc_pago"] ."&aviso=". $row["aviso"] ."' class='btn btn-primary'>Ver</a></td>
                                        <td><a href='expediente.php?curp=" . $row["curp"]."' class='btn btn-success'  role='button'>Descargar expediente</a></td>
                                        <td>".$row["valido"]."</td>
                                    </tr>
                                ";
                        
                        }
                        echo $trData;
                    } else {
                        echo "0 resultados";
                    }
                    ?>
        </tbody>
    </table>
</div>

<!-- Agregar las librerías necesarias -->
<script src="https://code.jquery.com/jquery-3.6.1.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#dataTable').DataTable({
        scrollY: 300,
        scrollX: true,
        pagingType: 'full_numbers',
        ordering: false, // 🚫 Deshabilita el ordenamiento automático 🚫
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
        },
        initComplete: function () {
            var api = this.api();

            function createDropdownFilter(columnIndex, selectElement) {
                var column = api.column(columnIndex);
                var uniqueValues = [];

                // Obtener valores únicos y agregarlos al select
                column.data().unique().sort().each(function (d) {
                    if (uniqueValues.indexOf(d) === -1) {
                        uniqueValues.push(d);
                        selectElement.append('<option value="' + d + '">' + d + '</option>');
                    }
                });

                // Filtrar la tabla al seleccionar una opción
                selectElement.on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                });
            }

            // Aplicar los filtros desplegables a las columnas correspondientes
            createDropdownFilter(6, $('#filter-carrera'));    // Carrera
            createDropdownFilter(7, $('#filter-plantel'));    // Plantel
            createDropdownFilter(8, $('#filter-tipo-pase'));  // Tipo Pase
            createDropdownFilter(9, $('#filter-estatus'));    // Estatus
            createDropdownFilter(12, $('#filter-valido'));    // Validó
        }
    });

    // Función para descargar CSV solo con los datos filtrados
    $('#downloadCSV').on('click', function () {
        var csv = [];
        var rows = table.rows({ search: 'applied' }).data(); // Obtener solo las filas filtradas

        // Encabezados sin Foto, Ver Aspirante ni Expediente
        var headers = ["Curp", "Folio", "Nombre (s)", "Apellido Paterno", "Apellido Materno", "Carrera", "Plantel", "Tipo pase", "Estatus", "Validó"];
        csv.push(headers.join(",")); 

        // Obtener datos de las filas filtradas excluyendo columnas 0 (Foto), 10 (Ver Aspirante) y 11 (Expediente)
        rows.each(function (rowData) {
            var row = [];
            for (var i = 1; i < rowData.length; i++) {
                if (i !== 10 && i !== 11) { // Omitir columnas de Foto, Ver Aspirante y Expediente
                    row.push('"' + rowData[i] + '"');
                }
            }
            csv.push(row.join(","));
        });

        // Crear archivo CSV con UTF-8 y BOM para caracteres especiales
        var csvContent = "\uFEFF" + csv.join("\n");
        var csvFile = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
        var downloadLink = document.createElement("a");
        downloadLink.href = URL.createObjectURL(csvFile);
        downloadLink.download = "aspirantes_filtrados.csv";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    });
});
</script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <script src="js/main.js"></script></body>

</body>
</html>
