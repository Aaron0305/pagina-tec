<?php
session_start();
header ('Content-type: text/html; charset=utf-8');

$banderaF = 0;
$banderaP = 0;

if (!isset($_SESSION['user_curp'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_recibo.html');
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
    <title>TESJo</title>
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script src="https://kit.fontawesome.com/728cc4b6c5.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
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
            <li><a class="dropdown-item" href="subir.php">Subir documentos</a></li>
            <li><a class="dropdown-item" href="segimiento.php">Seguimiento de documentos</a></li>
           
           
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
    
  
    <?php
        $statusFormato = '<span class="badge text-bg-danger">0%</span>';
        $statusPago = '<span class="badge text-bg-danger">0%</span>';

        $thefolder = $folder;
        if (is_dir($thefolder)) {
          if ($handler = opendir($thefolder)) {
              while (false !== ($file = @readdir($handler))) {
                //echo "$file<br>";
                //if(file_exists($file)){
                  if ($file == 'formato.pdf'){
                    $statusFormato = '<span class="badge text-bg-success">100%</span>';
                    $banderaF = 1;
                  }
                  if ($file == 'pago.pdf'){
                    $statusPago = '<span class="badge text-bg-success">100%</span>';
                    $banderaP = 1;
                  }
                //}            
              }
              closedir($handler);
          }
        }
      ?>    
    <h1>Lista de documentos requeridos</h1>
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
			<td>Formato de pago</td>
			<td><input type="file" name="formato"></td>
      <td>PDF</td>
      <td><?php echo $statusFormato ?></td>
		</tr>
		<tr>
			<td>Baucher</td>
			<td><input type="file" name="pago"></td>
      <td>PDF</td>
      <td><?php echo $statusPago ?></td>
		</tr>

	</table>
</div>

	

  <?php
    if ($banderaF == 1 && $banderaP == 1) {

  ?>
    <a class="btn btn-primary" href="opciones.php" role="button">Consultar Documentos</a>
  <?php
      
    }else{

  ?>
    <button onclick="guardarpago()" class="btn btn-primary">Guardar archivos</button>
  <?php
    }
  ?>

  <a class="btn btn-primary" href="TerminoPaso3.html" role="button">Continuar</a>
  <div id="mensaje"></div>
	<script>
		function guardarpago() {
			var formData = new FormData();
			formData.append("formato", document.getElementsByName("formato")[0].files[0], "formato.pdf");
			formData.append("pago", document.getElementsByName("pago")[0].files[0], "pago.pdf");

      document.getElementById("mensaje").innerHTML = '<i class="fa fa-check"></i> Archivos guardados';

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'guardarpago.php');
			xhr.send(formData);

      alert("Archivos guardados de forma correcta.\n\nSe verificará la legibilidad de cada documento, en caso de existir algún inconveniente se le notificará");

      var url = 'recibo.php';
      setTimeout(function(){ 
        window.location = url; 
      }, 500);

 
		}

	</script>
    </center>
  </body>
</html>
  
