<?php
session_start();

if (!isset($_SESSION['user_curp'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_docs_prom.html');
  exit;
}
$carpeta_curp = $_SESSION['user_curp'];

/*if (!is_dir($carpeta_curp)) {
  mkdir($carpeta_curp);
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
      <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
<title>TESJo</title>

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
            <a class="nav-link active" href="proc_prom.html" style="font-size: 18px;">PROCESO DE INGRESO</a>
            
          </div>
             
    </div>
    <form class="d-flex">
  <div class="dropdown">
    <a class="navbar-brand dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
    
      <?php
      $folder = "archivos/".$carpeta_curp;
      
      if (is_dir($folder)){
          $files = @scandir($folder); 
          if(count($files) < 2){
            $img_src = "imagenes/noimage.png";
          }else{
            $img_src = "archivos/$carpeta_curp/Fotografia.jpg";
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
  
  
  <!-- fin navbar -->
</head>
<body>





    <section>
        <div class="title">
          <h1>Se parte del Tecnol&oacute;gico de Estudios Superiores de Jocotitl&aacute;n </h1>
        </div>
      </section>
   
        
        <div class="container">
                
              
                <div class="card card-custom">
                  <img src="imagenes/subir.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Subir Documentos</h5>
                    
                    <p class="card-text">En este apartado deber&aacute;s adjuntar documentos oficiales para su validaci&oacute;n.</p>
                   
                    <a href="subir_prom.php" class="btn btn-primary">Subir documentos</a>
                    
                  </div>
                </div>
              
                <div class="card card-custom">
                  <img src="imagenes/seguimiento.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Seguimiento de Documentaci&oacute;n</h5>
                    <p class="card-text">En este apartado podr&aacute;s observar el estatus de tus documentos</p>
                     <?php
// Paso 1: Conexión a la base de datos
require_once('Conexion.php');

// Paso 2: Ejecutar la consulta
$sql = "SELECT * FROM persona WHERE curp = '{$_SESSION['user_curp']}'";
$result = $conn->query($sql);

// Paso 3: Mostrar los datos en la página HTML

if ($result->num_rows > 0) {
 
    // iterar a través de los resultados y mostrarlos en la tabla HTML
    while($row = $result->fetch_assoc()) {
      
        echo "<td><a href='seguimiento_prom.php?curp=" . $row["curp"] . "&folio=" . $row["folio"] ."&nombre=" . $row["nombre"] ."&ap_pat=". $row["ap_pat"] . "&ap_mat=". $row["ap_mat"] ."&tel_personal=". $row["tel_personal"] ."&escuela=". $row["escuela"] ."&plantel=". $row["plantel"] ."&carrera=". $row["carrera"] ."&tipo_pase=". $row["tipo_pase"] ."&correo_elec=". $row["correo_elec"] ."&doc_fotografia=". $row["doc_fotografia"] ."&doc_curp=". $row["doc_curp"] . "&doc_acta=". $row["doc_acta"] . "&doc_certificado=". $row["doc_certificado"] . "&doc_ine=". $row["doc_ine"] . "&doc_formato=". $row["doc_formato"] ."&doc_pago=". $row["doc_pago"] ."&aviso=". $row["aviso"] ."&valido=" . $row["valido"] ."' class='btn btn-primary'>Ver Documentos</a></td></tr>";

    
    }
    
} else {
    echo "0 resultados";
}

$conn->close();
?>
                  </div>
                </div>

                <!--div class="card card-custom">
                  <img src="imagenes/back.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Regresar</h5>
                    
                    <p class="card-text">Ver pasos <br><br><br> </p>
                   
                    <a href="proc_examen.html" class="btn btn-primary">Regresar pasos a seguir</a>
                    
                  </div>
                </div-->

              </div>
        
      <style>
        .container {
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          align-items: center;
        }
      
        .title {
          text-align: center;
          margin: 2rem 0;
        }
        .card-custom {
          width: 18rem;
          float: left;
          margin-right: 2rem;
        }
        .card-body {
  text-align: center; /* alinea el texto en el centro */
}
      </style>
       

 </body>
</html>
