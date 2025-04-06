<?php
session_start();
header ('Content-type: text/html; charset=utf-8');

if (!isset($_SESSION['user_curp'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_docs_exa.html');
  exit;
}
$carpeta_curp = $_SESSION['user_curp'];
/*if (!is_dir($carpeta_curp)) {
  mkdir($carpeta_curp);
}*/
?>

<!DOCTYPE html>
<html>
  <head>
  <title>TESJo </title>
      <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script src="https://kit.fontawesome.com/728cc4b6c5.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<nav class="navbar navbar-expand-lg bg-body-tertiary" >
    <div class="container-fluid">
        <img src="imagenes/logo.png" alt="Bootstrap" width="270" height="70">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup" >
        <div class="navbar-nav ml-auto">
          <a class="nav-link active" aria-current="page" href=""><b></b></a>
            
            
            <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle"  href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 18px;">
          <b> PROCESO DE INGRESO</b>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="proc_examen.html">Men&uacute; de pasos</a></li>
            <li><a class="dropdown-item" href="opciones.php">Proceso de documentaci&oacute;n</a></li>
           
           
          </ul>
        </li>
          </div>
             
    </div>
    <form class="d-flex">
  <div class="dropdown">
    <a class="navbar-brand dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
    
    <?php
      $folder = "archivos/".$carpeta_curp;
      if (is_dir($folder)){
          $files = @scandir($folder);
          $totalDocs = count($files); 
          if(count($files) < 2){
            $img_src = "imagenes/noimage.png";
          }else{
            $img_src = $folder."/Fotografia.jpg";
            $estatusDocs = true;
          }
      }else{
        $img_src = "imagenes/noimage.png";
      }
    ?>
    
    <img src="<?php echo $img_src ?>" alt="Logo" width="45" height="45" class="d-inline-block align-text-top">
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
  <div class="h4 pb-0 mb-4 text-danger border-bottom border-danger border-3"></div>
  </head>
  <body>
  <center>
    <h1>Bienvenido</h1>
    <h1>Lista de documentos requeridos</h1>

      <?php
        $statusActa = '<span class="badge text-bg-danger">0%</span>';
        $statusCertificado = '<span class="badge text-bg-danger">0%</span>';
        $statusCURP = '<span class="badge text-bg-danger">0%</span>';
        $statusFotografia = '<span class="badge text-bg-danger">0%</span>';
        $statusINE = '<span class="badge text-bg-danger">0%</span>';

        $thefolder = $folder;
        if (is_dir($thefolder)) {
          if ($handler = opendir($thefolder)) {
              while (false !== ($file = @readdir($handler))) {
                //echo "$file<br>";
                //if(file_exists($file)){
                  if ($file == 'ActaDeNacimiento.pdf'){
                    $statusActa = '<span class="badge text-bg-success">100%</span>';
                  }
                  if ($file == 'Certificado.pdf'){
                    $statusCertificado = '<span class="badge text-bg-success">100%</span>';
                  }
                  if ($file == 'CURP.pdf'){
                    $statusCURP = '<span class="badge text-bg-success">100%</span>';
                  }
                  if ($file == 'Fotografia.jpg'){
                    $statusFotografia = '<span class="badge text-bg-success">100%</span>';
                  }
                  if ($file == 'INE.pdf'){
                    $statusINE = '<span class="badge text-bg-success">100%</span>';
                  }
                //}            
              }
              closedir($handler);
          }
        }
      ?>

    <div class="col-6">
	<table class="table table-striped">
        <thead>
            <tr>
              <th>Documento</th>
              <th>Archivo</th>
              <th>Formato</th>
              <th>Estatus</th>
            </tr>
          </thead>
		<tr>
			<td>Fotograf&iacute;a</td>
			<td><input type="file" name="fotografia"></td>
      <td>JPG/PNG</td>
      <td><?php echo $statusFotografia ?></td>
		</tr>
		<tr>
			<td>CURP</td>
			<td><input type="file" name="curp"></td>
      <td>PDF</td>
      <td><?php echo $statusCURP ?></td>
		</tr>
		<tr>
			<td>Acta de nacimiento</td>
			<td><input type="file" name="acta"></td>
      <td>PDF</td>
      <td><?php echo $statusActa ?></td>
		</tr>
		<tr>
			<td>Constancia o certificado de bachillerato</td>
			<td><input type="file" name="certificado"></td>
      <td>PDF</td>
      <td><?php echo $statusCertificado ?></td>
		</tr>
		<tr>
			<td>Identificaci&oacute;n con fotograf&iacute;a</td>
			<td><input type="file" name="ine"></td>
      <td>PDF</td>
      <td><?php echo $statusINE ?></td>
		</tr>
	</table>
</div>

	<button onclick="guardarArchivos()" class="btn btn-primary">Adjuntar archivos</button>
  <a class="btn btn-primary" href="opciones.php" role="button">Regresar</a>
  <div id="mensaje"></div>
	<script>
		function guardarArchivos() {
			var formData = new FormData();
			formData.append("fotografia", document.getElementsByName("fotografia")[0].files[0], "Fotografia.jpg");
			formData.append("curp", document.getElementsByName("curp")[0].files[0], "CURP.pdf");
			formData.append("acta", document.getElementsByName("acta")[0].files[0], "ActaDeNacimiento.pdf");
			formData.append("certificado", document.getElementsByName("certificado")[0].files[0], "Certificado.pdf");
			formData.append("ine", document.getElementsByName("ine")[0].files[0], "INE.pdf");
      document.getElementById("mensaje").innerHTML = '<i class="fa fa-check"></i> <a href="subir.php">Archivos guardados</a>';

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'guardarArchivos.php');
			xhr.send(formData);

      alert("Archivos guardados de forma correcta.\n\nSe verificará la legibilidad de cada documento, en caso de existir algún inconveniente se le notificará");

      var url = 'TerminoPaso2.html';
      setTimeout(function(){ 
        window.location = url; 
      }, 500);

      //echo "<meta http-equiv='refresh' content='2;url=subir.php'>";
 
		}
	//	window.location.href = "TerminoPaso2.html";
	</script>
    </center>
  </body>
</html>
  
