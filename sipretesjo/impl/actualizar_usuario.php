<?php
 header ('Content-type: text/html; charset=utf-8');
 require 'Conexion.php';

 $curp = $_POST['curp'];
 $folio = $_POST['folio'];
 $nombre  = $_POST['nombre'];
 $ap_pat  = $_POST['ap_pat'];
 $ap_mat = $_POST['ap_mat'];
 $escuela = $_POST['escuela'];
 $plantel = $_POST['plantel'];
 $carrera = $_POST['carrera'];
 $tipo_pase = $_POST['pase'];
 $correo_elec = $_POST['correo_elec'];
 $sexo = $_POST['sexo'];
 #$fecha_nac = '0000-00-00';
 
 /*TODO: datos de ubicación */
 $pais = $_POST['paisUsuario'];
 $estado = $_POST['estadoUsuario'];
 $municipio = $_POST['municipioUsuario'];
 $localidad = $_POST['localidadUsuario'];
 $direccion = $_POST['direccion'];
/** fin datos de ubicación */

 $tel_personal = $_POST['tel_personal'];
 $tel_fijo = $_POST['tel_fijo'];
 $tel_recado = $_POST['tel_recado'];
 
//VALIDAR SI YA EXISTE EL REGISTRO
$sql = "UPDATE persona SET  nombre='$nombre', ap_pat='$ap_pat', ap_mat='$ap_mat', escuela='$escuela', plantel='$plantel', carrera='$carrera', tipo_pase='$tipo_pase', correo_elec='$correo_elec', sexo='$sexo', pais='$pais', estado='$estado', municipio='$municipio', localidad='$localidad', direccion='$direccion', tel_personal='$tel_personal', tel_recado='$tel_recado', tel_fijo='$tel_fijo', doc_fotografia='sin_docs' where curp='$curp' and folio='$folio'";
  
if (isset($_POST['submit'])) {
   //Si el checkbox condiciones tiene valor y es igual a 1
   if (isset($_POST['condiciones']) && $_POST['condiciones'] == '1'){
      //echo '<div style="color:blue">Has Aceptado Correctamente las Condiciones de Servicio.</div>';
    $query = mysqli_query($conn, $sql);


    if ($query>0) {
       
      if($conn->query($sql) === TRUE){

          echo "Datos actualizados correctamente.";
            // Opcional: Redirigir a otra página
            // header("Location: TerminoPaso2.html");
          if ($plantel === "Jocotitlán" && $tipo_pase === "Por examen") {
                  header("Location: TerminoPaso1JE.html");
              } elseif ($plantel === "Jocotitlán" && $tipo_pase === "Por promedio") {
                  header("Location: TerminoPaso1JP.html");
              } elseif ($plantel === "Aculco" && $tipo_pase === "Por examen") {
                  header("Location: TerminoPaso1AE.html");
              } else {
                  header("Location: TerminoPaso1AP.html"); // Opción por defecto
              }
              exit();


        } else {
            echo "Error al actualizar los datos " . $conn->error;
            header("Location: datos_examen.php");
        }
    }

  } else{
      echo "<script> alert('Debes aceptar los terminos de servicio');
       location.href = 'datos_examen.php';
       </script>";
  }
}

?>

