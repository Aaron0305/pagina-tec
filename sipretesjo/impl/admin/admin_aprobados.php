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

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <img src="../imagenes/logo.png" alt="Bootstrap" width="270" height="70">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup" >
      <div class="navbar-nav ml-auto">
            <a class="nav-link active" aria-current="page" href="Administracion.php"style="font-size: 18px;">ASPIRANTES EN GENERAL</a>
            <!--a class="nav-link" href="http://35.225.100.30/login/index.php" style="font-size: 18px;">PLATAFORMA VIRTUAL</a>
            <a class="nav-link" href="Cindustrial.html" style="font-size: 18px;">INGENIER&Iacute;A INDUSTRIAL</a>
            <a class="nav-link" href="registro.html" style="font-size: 18px;">PROCESO DE INGRESO</a-->
            <!--a class="nav-link" href="login_admin.html" style="font-size: 18px;"><b>ADMINISTRACI&Oacute;N</b></a-->
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
                </thead>
                <tbody>

                  <?php
                    // Paso 1: Conexión a la base de datos
                    require_once('../php/conexion.php');

                    // Paso 2: Ejecutar la consulta
                    $sql = "SELECT * FROM persona where doc_fotografia='Aprobado'";
                    $result = $mysqli->query($sql);

                    // Paso 3: Mostrar los datos en la página HTML

                    if ($result->num_rows > 0) {
                      
                        $trData = "";

                        // iterar a través de los resultados y mostrarlos en la tabla HTML
                        while($row = $result->fetch_assoc()) {
                            //echo "<tr><td>" . $row["curp"]. "</td><td>" . $row["nombre"]."</td><td>" . $row["ap_pat"]."</td><td>" . $row["ap_mat"];
                            //echo "<td><a href='ver_alumno.php?curp=" . $row["curp"] . "&nombre=" . $row["nombre"] ."&ap_pat=". $row["ap_pat"] . "&ap_mat=". $row["ap_mat"] ."&tel_personal=". $row["tel_personal"] ."&correo_elec=". $row["correo_elec"] ."&doc_fotografia=". $row["doc_fotografia"]  ."&doc_curp=". $row["doc_curp"] . "&doc_acta=". $row["doc_acta"] . "&doc_certificado=". $row["doc_certificado"] . "&doc_ine=". $row["doc_ine"] . "&doc_formato=". $row["doc_formato"] ."&doc_pago=". $row["doc_pago"] ."&aviso=". $row["aviso"] ."' class='btn btn-primary'>Ver</a></td></tr>";

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
    <script
        src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous">
    </script>

    <script>

      $(function(){
        var table = $('#dataTable').DataTable({
            scrollY: 300,
            scrollX: true,
            pagingType: 'full_numbers',
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
              }
        });
      });
    </script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <script src="js/main.js"></script></body>

</body>
</html>
