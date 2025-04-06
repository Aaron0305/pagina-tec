<?php
session_start();

if (!isset($_SESSION['user_curp'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_docs_exa.html');
  exit;
  
}

$carpeta_curp = $_SESSION['user_curp'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Seguimiento de Documentos</title>
	    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script src="https://kit.fontawesome.com/728cc4b6c5.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- Scripts de Bootstrap -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<nav class="navbar navbar-expand-lg bg-body-tertiary">
		<div class="container-fluid">
			<img src="imagenes/logo.png" alt="Bootstrap" width="270" height="70">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavAltMarkup" >
			<div class="navbar-nav ml-auto">
				<a class="nav-link active" aria-current="page" href=""><b></b></a>
				<a class="nav-link active" href="proc_examen.html" style="font-size: 18px;"><b>PROCESO DE INGRESO</b></a>
				
			</div>
				
		</div>
		<form class="d-flex">
			<div class="dropdown">
				<a class="navbar-brand dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
				<img src="archivos/<?php echo $carpeta_curp ?>/Fotografia.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
				<?php echo $_SESSION['user_curp']; ?>
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
$aviso = $_GET["aviso"];
//$valido =$GET["valido"];

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
		  <div class="col-md-3">
		  <a class="btn btn-success" href="opciones.php" role="button">Regresar</a>
		  </div>
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
	<h1>Seguimiento del estatus de la documentaci&oacute;n</h1>
    <div>

   
	<form action="" method="POST" enctype="multipart/form-data">
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
                <td>Fotograf&iacute;a</td>
				<td>JPG</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Fotografia">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "archivos/".$curp."/Fotografia.jpg";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>

			</tr>
			<tr>
                <td>Curp</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#curp">Mostrar Documento</button></td>
				<td>  
			  <?php
             $filename = "archivos/".$curp."/CURP.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
		
			
			</tr>
			<tr>
                <td>Acta de Nacimiento</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ActaDeNacimiento">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "archivos/".$curp."/ActaDeNacimiento.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
		
			</tr>
			<tr>
                <td>Certificado de bachillerato</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#certificado">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "archivos/".$curp."/Certificado.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
	
			</td>
				</tr>
			<tr>
                <td>Ine</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ine">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "archivos/".$curp."/INE.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
		
			</tr>
			<tr>
                <td>Formato de pago</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formato">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "archivos/".$curp."/formato.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
			
			</tr>
			<tr>
                <td>Comprobante de pago</td>
				<td>PDF</td>
				<td>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pago">Mostrar Documento</button></td>
				<td>    <?php
             $filename = "archivos/".$curp."/pago.pdf";
            if (file_exists($filename)) {
                echo "<span class='badge text-bg-success'>Cargado</span>";
            } else {
                echo "<span class='badge text-bg-danger'>No cargado</span>";
            }
        ?></td>
			
      
			</tr>
		</table>

	</form>
	</div>
  
	</div>
<div>
<hr>
</div>
<table  class="table table-striped" >
<th>Estatus de la documentaci&oacute;n</th>
<th>observaciones</th>
<th> </th>
<tr>
  <td>   <?php
    $estado = $doc_fotografia;
    if ($estado == "En revisión") {
      echo "<span class='badge text-bg-warning'>$estado</span>";
    } elseif ($estado == "Aprobado") {
      echo "<span class='badge text-bg-success'>$estado</span>";
      echo "<a href='pdf/Guia_Pago.pdf' class='badge text-bg-warning' download>   Descargar Guía</a>";
    } elseif ($estado == "Rechazado") {
      echo "<span class='badge text-bg-danger'>$estado</span>";
    } else {
      echo $estado;
    }
  ?></td>
  <td>  <span><?php echo $aviso; ?></span></td>
  
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
                    <h4 class="modal-title">Fotograf&iacute;a</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Imagen dentro del modal -->
                    <img src="archivos/<?php echo $curp ?>/Fotografia.jpg" alt="CURP" style="width:100%;">
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
					<iframe src="archivos/<?php echo $curp ?>/CURP.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
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
						<iframe src="archivos/<?php echo $curp ?>/ActaDeNacimiento.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
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
								<iframe src="archivos/<?php echo $curp ?>/Certificado.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
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
						<iframe src="archivos/<?php echo $curp ?>/ine.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
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
						<iframe src="archivos/<?php echo $curp ?>/formato.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
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
						<iframe src="archivos/<?php echo $curp ?>/pago.pdf" style="width:100%; height:500px;" frameborder="0"></iframe>
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
