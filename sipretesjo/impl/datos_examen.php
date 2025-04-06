<?php
session_start();

if (!isset($_SESSION['user_curp'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_reg_exa.html');
  exit;
}

// Configuración de la base de datos
$host = "localhost";
$user = "root";
$password = "TDEzkkBeAPf5LS";
$dbname = "sipre";

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_curp'];

// Consulta para obtener los datos del usuario
$sql = "SELECT * FROM persona WHERE curp = '$user_id'";
$result = $conn->query($sql);

// Verificar si se encontró el registro
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No se encontraron datos para el usuario.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Página Oficial de Educación a Distancia del TESJo">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <script src="https://kit.fontawesome.com/728cc4b6c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <title>TESJo</title>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="imagenes/logo.png" alt="Bootstrap" width="270" height="70">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavAltMarkup" >
                <div class="navbar-nav ml-auto">
                  <a class="nav-link active" aria-current="page" href=""><b></b></a>
                    
                    <a class="nav-link " href="proc_examen.html" style="font-size: 18px;"><b>PROCESO DE INGRESO</b></a>
                    <!--a class="nav-link " href="Administracion.php" style="font-size: 18px;">ADMINISTRACI&Oacute;N</a-->

                    <a class="nav-link " href="logout.php" style="font-size: 18px;"><b>SALIR</b></a>
                  </div>
                  
            </div>

          </nav>
          <div class="h4 pb-0 mb-4 text-danger border-bottom border-danger border-3"></div>
    </head>
    <body>

        <div class="container mt-5">
            <div class="row justify-content-center">
              <div class="col-md-12">
                <center>  <h1 >Registro de aspirantes</h1> 
                <div>
                         <p><br>
                             Estimad@ aspirante, para continuar tu registro al programa educativo de tu elección te solicitamos proporciones los siguientes datos (es necesario completar todas las casillas).
                         </p><br></center>
                 </div>
                <form action="actualizar_usuario.php" method="POST">
                    <!--h4 style="text-align:center">Datos Personales</h4-->
 <!-- ________________________________ -->
                    <div class="row g-2">
                        <div class="col-md">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="curp" id="curp" placeholder="CURP*" value="<?php echo $row["curp"]; ?>" onclick="noeditable()" readonly>
                                    <label for="floatingInput">CURP</label>
                                </div>
                        </div>
                        <div class="col-md">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="folio" id="folio" placeholder="FOLIO*" value="<?php echo $row["folio"]; ?>" onclick="noeditable()" readonly>
                                    <label for="floatingInput">FOLIO</label>
                                </div>
                        </div>
                    </div>
<!-- ________________________________ -->

                    <div class="row g-2">
                        
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre*"  value="<?php echo $row["nombre"]; ?>" maxlength="29" pattern="^([A-ZÁÉÍÓÚ]+)(\s[A-ZÁÉÍÓÚ]+)*$" title="Solo se aceptan letras"  autocomplete="off" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
                                <label for="floatingInput">Nombre</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="ap_pat" id="ap_pat" placeholder="Apellido Paterno*" value="<?php echo $row["ap_pat"]; ?>" maxlength="19" pattern="^([A-ZÁÉÍÓÚ]+)(\s[A-ZÁÉÍÓÚ]+)*$" title="Solo se aceptan letras" autocomplete="off" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
                                <label for="floatingInput">Apellido paterno</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="ap_mat" id="ap_mat" placeholder="Apellido Materno*" value="<?php echo $row["ap_mat"]; ?>" maxlength="29" pattern="^([A-ZÁÉÍÓÚ]+)(\s[A-ZÁÉÍÓÚ]+)*$" title="Solo se aceptan letras" autocomplete="off" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
                                <label for="floatingInput">Apellido materno</label>
                            </div>
                        </div>
                    </div>

 <!-- ________________________________ -->
<!-- ________________________________ -->

                    <div class="row g-2">
                        
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="escuela" id="escuela" placeholder="Institución Educativa*" value="<?php echo $row["escuela"]; ?>" onclick="noeditable()" readonly>
                                <label for="floatingInput" >Institución Educativa</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="plantel" id="resultadoInput" placeholder="Plantel*" onclick="noeditable()" readonly>
                                <label for="floatingInput" for="resultadoInput">Plantel</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="carrera" id="carrera" placeholder="Carrera*" value="<?php echo $row["carrera"]; ?>" onclick="noeditable()" readonly>
                                <label for="floatingInput">Carrera</label>
                            </div>
                        </div>
                    </div>

 <!-- ________________________________ -->

            <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="pase" id="resultadoInput2" placeholder="Tipo pase*" onclick="noeditable()" readonly>
                        <label for="floatingInput" for="resultadoInput2">Tipo pase</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="correo_elec" id="correo_elec" placeholder="Correo electrónico*" value="<?php echo $row["correo_elec"]; ?>" autocomplete="off" required>
                        <label for="floatingInput">Correo Electr&oacute;nico</label>
                    </div>
                </div>

                <div class="col-md">
                    <div class="form-floating">

				<?php
					$sexo = isset($row["sexo"]) ? $row["sexo"] : ""; // Obtiene el correo si existe
				?>

				<?php if (!empty($sexo)): ?>
				    <input type="text" name="sexo" class="form-control" value="<?php echo htmlspecialchars($sexo); ?>" onclick="noeditable()" readonly>
				<?php else: ?>
				    <select name="sexo" class="form-select" id="sexo">
				        <option selected>Seleccione su g&eacute;nero</option>
				        <option value="Masculino">MASCULINO</option>
				        <option value="Femenino">FEMENINO</option>
				        <option value="No binario">NO BINARIO</option>
				    </select>
				<?php endif; ?>
                    </div>
                </div>
            </div>

<!-- _______________________________ -->
<h4 style="text-align: center;"> Datos de ubicaci&oacute;n </h4>
 <!-- _______________________________ -->
       

                <div class="row g-2">
                    <!-- Implementación -->
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="paisUsuario" class="form-label">Pa&iacute;s:</label>
                            <select id="paisUsuario" name="paisUsuario" onchange="getEstado(this.value)" class="form-select" aria-label="Default select example" value="<?php echo $row["pais"]; ?>" required>
                            </select>
                        </div>
                        
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="estadoUsuario" class="form-label">Estado:</label>
                            <select id="estadoUsuario" name="estadoUsuario" onchange="getMunicipio(this.value)" class="form-select" aria-label="Default select example" value="<?php echo $row["estado"]; ?>" required>
                            </select>
                        </div>
                        
                    </div>
                    <!-- Implementación -->
                </div>
                <div class="row g-2">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="municipioUsuario" class="form-label">Municipio:</label>
                            <select id="municipioUsuario" name="municipioUsuario" onchange="getLocalidad(this.value)" class="form-select" aria-label="Default select example" value="<?php echo $row["municipio"]; ?>" required>
                            </select>
                        </div>
                        
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="localidadUsuario" class="form-label">Localidad:</label>
                            <select id="localidadUsuario" name="localidadUsuario" class="form-select" aria-label="Default select example" value="<?php echo $row["localidad"]; ?>" required>
                            </select>
                        </div>
                        
                    </div>
                </div>
                <div class="row g-2">
                  
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Referencias:</label>
                            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Ejemplo: Cuartel centro, entre la Delegaci&oacute;n y la Escuela Primaria Federal" required maxlength="79"  autocomplete="off" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" value="<?php echo $row["direccion"]; ?>" required>
                        </div>
                    </div>
                </div>
 <!-- _______________________________ -->
 <!-- _______________________________ -->
<h4 style="text-align: center;"> Datos de Contacto</h4>
<!-- _______________________________ -->
     <div class="row g-2">
                <div class="col-md">
                   <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="tel_personal" id="tel_personal" placeholder="Telefono Personal*" value="<?php echo $row["tel_personal"]; ?>" minlength="10" maxlength="10" pattern="[0-9]+" title="Solo se aceptan numeros"  autocomplete="off" required>
                        <label for="floatingInput">Tel&eacute;fono Personal</label>
                    </div>
                </div>
            </div>
                    <label for="msg">En caso de no contar con teléfono de recado y fijo repetir el teléfono personal:</label><br>
<!-- _______________________________ -->
                    <div class="row g-2">
                        <div class="col-md">
                        <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="tel_recado" id="tel_recado" placeholder="Telefono para recados*" value="<?php echo $row["tel_recado"]; ?>" minlength="10" maxlength="10" pattern="[0-9]+" title="Solo se aceptan numeros" autocomplete="off" required>
                        <label for="floatingInput">Tel&eacute;fono  de recados</label>
                    </div>
                </div>
 <div class="col-md">
                <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="tel_fijo" id="tel_fijo" placeholder="Telefono Fijo*" value="<?php echo $row["tel_fijo"]; ?>" minlength="10" maxlength="10" pattern="[0-9]+" title="Solo se aceptan numeros" autocomplete="off" required>
                    <label for="floatingInput">Tel&eacute;fono Fijo</label>
                </div>
            </div>
     </div>
     <!-- _______________________________ -->
                <div class="row g-2">
                    <div class="col-md">
                        <p><input type="checkbox" name="condiciones" value="1">

                            Declaro que la información proporcionada en este formulario es completa y correcta. Entiendo que cualquier declaración errónea me hace responsable de no recibir la información sobre el proceso de ingreso.</p>
                        
                       
                      
                           <p> Puedes consultar el Aviso de Privacidad de Datos Personales en: </p>
                            <a href="https://tesjo.edomex.gob.mx/legales" target="_blank">https://tesjo.edomex.gob.mx/legales</a>
                        </p><br>
                    </div>

                    <div class="submit" align="right">
                        <input class="btn btn-primary" type="submit" id="submit" name="submit" value="ENVIAR DATOS">
                    </div>

                    <!--div class="submit">
                        <input type="submit" id="continua" name="continua" value="CONTINUAR PROCESO SEGUNDO PASO">
                    </div-->
                </form>
            </div>
       


        
        <script src="js/menu.js"></script>
   
        <script>eval(mod_pagespeed_h$45j0osqk);</script>
        <script>AOS.init();</script>
        <script>feather.replace()</script>
        <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>var swiper=new Swiper(".mySwiper",{spaceBetween:30,centeredSlides:true,autoplay:{delay:3500,disableOnInteraction:false,},pagination:{el:".swiper-pagination",clickable:true,},});</script>
    
    <script
        src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous">
    </script>

    <script src="js/main.js"></script></body>


    <script>
        $(function(){
            getPais();
            getEstado(42);
        })
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
  // Leer el valor de localStorage
  const valorGuardado = localStorage.getItem("miVariable");

  // Obtener los elementos input
  const inputPlantel = document.getElementById("resultadoInput");
  const inputPase = document.getElementById("resultadoInput2");

  // Definir los mensajes según la opción guardada
  let mensajePlantel = "No se ha seleccionado ninguna opción válida.";
  let mensajePase = "No se ha seleccionado ninguna opción válida.";

  switch (valorGuardado) {
    case "opcion1":
      mensajePlantel = "Jocotitlán";
      mensajePase = "Por examen";
      break;
    case "opcion2":
      mensajePlantel = "Jocotitlán";
      mensajePase = "Por promedio";
      break;
    case "opcion3":
      mensajePlantel = "Aculco";
      mensajePase = "Por examen";
      break;
    case "opcion4":
      mensajePlantel = "Aculco";
      mensajePase = "Por promedio";
      break;
  }

  // Asignar los valores a los inputs si existen en la página
  if (inputPlantel) inputPlantel.value = mensajePlantel;
  if (inputPase) inputPase.value = mensajePase;
});
    </script>

<script>
    function noeditable() {
        alert("Este campo no se puede editar.");
    }
</script>


</html>
