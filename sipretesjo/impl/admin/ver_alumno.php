<?php
session_start();
header ('Content-type: text/html; charset=utf-8');

if (!isset($_SESSION['nombre'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_admin.html');
  exit;
}
$valido = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Seguimiento de Documentos</title>
    <script src="https://kit.fontawesome.com/728cc4b6c5.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- Scripts de Bootstrap -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<nav class="navbar navbar-expand-lg bg-body-tertiary">
		
    <div class="container-fluid">
			<img src="../imagenes/logo.png" alt="Bootstrap" width="270" height="70">
		  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup" >
            <div class="navbar-nav ml-auto">
    		        <a class="navbar-brand" href="Administracion.php" style="font-size: 18px;"><b>REGRESAR</b></a>
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
      
</head>
<body>
	<center>
<?php
// Paso 1: Obtener los parámetros curp y nombre de la URL
$curp = $_GET["curp"];
$folio = $_GET["folio"];
$nombre = $_GET["nombre"];
$ap_pat = $_GET["ap_pat"];
$ap_mat = $_GET["ap_mat"];
$tel_personal = $_GET["tel_personal"];
$correo_elec = $_GET["correo_elec"];
$escuela = $_GET["escuela"];
$plantel = $_GET["plantel"];
$carrera = $_GET["carrera"];
$tipo_pase = $_GET["tipo_pase"];
$doc_fotografia = $_GET["doc_fotografia"];
$doc_curp = $_GET["doc_curp"];
$doc_acta = $_GET["doc_acta"];
$doc_certificado = $_GET["doc_certificado"];
$doc_ine = $_GET["doc_ine"];
$doc_formato = $_GET["doc_formato"];
$doc_pago = $_GET["doc_pago"];
$aviso = 'Felicidades tus documentos han sido validados correctamente, puedes continuar con el Pago de Derechos de Examen. Consulta la guía para generar el Formato Universal de Pago (FUP).';




// Paso 2: Mostrar la información del alumno seleccionado

?>
</center>
<div class="container">
  <div class="card">
    <div class="card-body">
  		<div class="text-center">
          <h1 class="card-title" >Información del alumno</h1>
    	 
          <div class="row">
              <div class="col-md-4">
                <span ><b> CURP:</b></span>
                <span><?php echo $curp; ?></span>
              </div>
              <div class="col-md-4">
                <span ><b> FOLIO:</b></span>
                <span><?php echo $folio; ?></span>
              </div>
              <div class="col-md-4">
                <span ><b>NOMBRE:</b></span>
                <span><?php echo $nombre . ' ' . $ap_pat . ' ' . $ap_mat; ?></span>
              </div>
          </div>

          <div class="row">
              <div class="col-md-4">
                <span ><b> ESCUELA:</b></span>
                <span><?php echo $escuela; ?></span>
              </div>
              <div class="col-md-4">
                <span ><b>PLANTEL:</b></span>
                <span><?php echo $plantel; ?></span>
              </div>
              <div class="col-md-4">
                <span ><b>CARRERA:</b></span>
                <span><?php echo $carrera; ?></span>
              </div>
          </div>
          <div class="row">
              <div class="col-md-4">
                <span ><b>TIPO PASE:</b></span>
                <span><?php echo $tipo_pase; ?></span>
              </div>
              <div class="col-md-4">
                <span ><b>Correo electrónico:</b></span>
                <span><?php echo $correo_elec; ?></span>
              </div>
              <div class="col-md-4">
                  <span ><b>Teléfono personal:</b></span>
                  <span><?php echo $tel_personal; ?></span>
              </div>
          </div>
      </div>
    </div>
  </div>




<p></p>
	<center>
	<div class="container">
	<div class="card">
			<div class="card-body"></div>
	<h1>Seguimiento del Status de la documentación</h1>
    <div>

   
	<form  method="POST" enctype="multipart/form-data">
		<table class="table table-striped">
			<thead>
				<tr>
				  <th>Documento</th>
				  <th>Formato</th>
				  <th>Visualizar</th>
				  <th>Estado</th>
				  
				 
				  
				</tr>
			  </thead>
			<tr>
                <td>Fotografía</td>
				<td>JPG</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Fotografia">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "../archivos/".$curp."/Fotografia.jpg";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
			<td>

</td>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  @$curp = $_POST['curp'];
  @$doc_fotografia = ($_POST['accion'] === 'aprobar') ? 'Aprobado' : 'Rechazado';

  // Actualizar el campo correspondiente en la base de datos
  
  $conexion = mysqli_connect('localhost', 'root', 'TDEzkkBeAPf5LS', 'sipre');
  mysqli_set_charset($conexion, "utf8");

  $query = "UPDATE persona SET doc_fotografia='$doc_fotografia', valido='$valido', aviso='$aviso' WHERE curp='$curp'";
  $resultado = mysqli_query($conexion, $query);

  //$query2 = "UPDATE persona SET valido='$valido' WHERE curp='$curp'";
  //$resultado2 = mysqli_query($conexion, $query2);
}
?>
<td>

</td>




			</tr>
			<tr>
                <td>CURP</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#curp">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "../archivos/".$curp."/CURP.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
		<td>
	
	
			
</tr>
			<tr>
                <td>Acta de Nacimiento</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ActaDeNacimiento">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "../archivos/".$curp."/ActaDeNacimiento.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
					<td>
	
			</td>	
				
		</tr>
			<tr>
                <td>Constancia ó certificado de bachillerato</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#certificado">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "../archivos/".$curp."/Certificado.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
				<td>

			</td>
			
			</tr>
			<tr>
                <td>Identificación oficial</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ine">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "../archivos/".$curp."/INE.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
				<td>
	
			</td>
			</tr>
			<tr>
                <td>Formato de pago</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formato">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "../archivos/".$curp."/formato.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
			<td>
	
			</td>		
			</tr>
			<tr>
                <td>Comprobante de pago</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pago">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "../archivos/".$curp."/pago.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
			<td>

			</td>		
			</tr>
		</table>

	</form>


	</div>
  
	</div>

<div>
  <hr>
</div>

<!-- modificar status del alumno -->
  <div>
    <table class="table table-striped">
      <th>Status documentación</th>
      <th>Acción</th>
      <th>Acción</th>
	  <th>Comentario</th>
   <tr>
  
  <td>
  <?php
    $estado = $doc_fotografia;
    if ($estado == "En revision") {
      echo "<span class='badge text-bg-warning'>$estado</span>";
    } elseif ($estado == "Aprobado") {
      echo "<span class='badge text-bg-success'>$estado</span>";
    } elseif ($estado == "Rechazado") {
      echo "<span class='badge text-bg-danger'>$estado</span>";
    } else {
      echo $estado;
    }
  ?>
</td>
  <td>
  <form method="POST">
    <input type="hidden" name="curp" value="<?php echo $curp; ?>">
    <button  name="accion" value="aprobar" class="btn btn-success">Aprobado</button>
	<td> <button  name="rechazar"  value="rechazar" class="btn btn-danger">Rechazar</button></td>
	<!--td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rechazar">Enviar aviso</button></td-->
	<td><input type="text" name="comentario" id="comentario" class="form-control" placeholder="Escriba un comentario"></td>
	
  </form>


  <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  @ $curp = $_POST['curp'];
  @ $doc_fotografia = ($_POST['rechazar'] === 'rechazar') ? 'Rechazado' : 'Aprobado';
  @ $comentario = $_POST['comentario'];
  // Actualizar el campo correspondiente en la base de datos
  
  $conexion = mysqli_connect('localhost', 'root', 'TDEzkkBeAPf5LS', 'sipre');
  mysqli_set_charset($conexion, "utf8");
  $query = "UPDATE persona SET doc_fotografia='$doc_fotografia', valido='$valido', aviso='$comentario' WHERE curp='$curp'";
  $resultado = mysqli_query($conexion, $query);
}
?>




  <!--div class="modal fade" id="rechazar" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Describa la razón por la que se rechaza</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				

				<form method="POST">
  <div class="form-group">
    <label for="aviso">Aviso:</label>
    <textarea class="form-control" id="aviso" name="aviso" rows="3"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>


<!--?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $curp = $_GET['curp']; // OBTÉN LA CURP DESDE LA URL O DONDE SEA QUE LA TENGAS DISPONIBLE
  $aviso = $_POST['aviso'];

  // Establece la conexión a la base de datos
  $conexion = mysqli_connect('localhost', 'root', '', 'sipre');
  mysqli_set_charset($conexion, "utf8");
  // Verifica si hay algún error en la conexión
  if (!$conexion) {
    die('Error al conectar a la base de datos: ' . mysqli_connect_error());
  }

  // Actualiza el campo "aviso" en la tabla "persona" para la CURP correspondiente
  $query = "UPDATE persona SET aviso='$aviso' WHERE curp='$curp'";
  $resultado = mysqli_query($conexion, $query);

  // Verifica si la consulta se ejecutó correctamente
  if ($resultado) {
    echo 'Aviso guardado exitosamente en la base de datos.';
  } else {
    echo 'Error al guardar el aviso en la base de datos.';
  }

  // Cierra la conexión a la base de datos
  mysqli_close($conexion);
}
?>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div-->
</div>

  </td>
  
  </tr>
  </table>
</div>




</div>
<div class="container">
    <!-- Botón que abre el modal -->
  

    <!-- Modal -->
    <div class="modal fade" id="Fotografia" role="dialog">
        <div class="modal-dialog">
            <!-- Contenido del modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Fotografia</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Imagen dentro del modal -->
                    <img src="../archivos/<?php echo $curp ?>/Fotografia.jpg" alt="CURP" style="width:100%;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Curp -->
<div class="container">
    <!-- Botón que abre el modal -->
  

    <!-- Modal -->
	<div class="modal fade" id="curp" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="pdfModalLabel">CURP</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- Agregar el visor de PDF-->
					<iframe src="../archivos/<?php echo $curp ?>/CURP.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	    <!-- Modal -->
		<div class="modal fade" id="ActaDeNacimiento" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="pdfModalLabel">Acta de Nacimiento</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Agregar el visor de PDF  -->
						<iframe src="../archivos/<?php echo $curp ?>/ActaDeNacimiento.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
			    <!-- Modal -->
				<div class="modal fade" id="certificado" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="pdfModalLabel">Certificado</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<!-- Agregar el visor de PDF  -->
								<iframe src="../archivos/<?php echo $curp ?>/Certificado.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							</div>
						</div>
					</div>
				</div>
					    <!-- Modal -->
		<div class="modal fade" id="ine" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="pdfModalLabel">INE</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Agregar el visor de PDF  -->
						<iframe src="../archivos/<?php echo $curp ?>/INE.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="formato" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="pdfModalLabel">Formato de pago</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Agregar el visor de PDF  -->
						<iframe src="../archivos/<?php echo $curp ?>/formato.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

		  <!-- Modal -->
		  <div class="modal fade" id="pago" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="pdfModalLabel">Comprobante de pago</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Agregar el visor de PDF  -->
						<iframe src="../archivos/<?php echo $curp ?>/pago.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
</div>
</center>

</body>
</html>
