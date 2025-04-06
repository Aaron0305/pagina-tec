<?php
 header ('Content-type: text/html; charset=utf-8');
 require '../Conexion.php';

 $curp = $_POST['curp'];
 $folio = $_POST['folio'];
 $nombre  = $_POST['nombre'];
 $ap_pat  = $_POST['ap_pat'];
 $ap_mat = $_POST['ap_mat'];
 $escuela = $_POST['escuela'];
 $plantel = $_POST['plantel'];
 $carrera = $_POST['carrera'];
 $tipo_pase = $_POST['tipo_pase'];
 $correo_elec = $_POST['correo_elec'];
 $tel_personal = $_POST['tel_personal'];
 $doc_curp = 'Alejandro';
 
//VALIDAR SI YA EXISTE EL REGISTRO
$sql = "UPDATE persona SET  nombre='$nombre', ap_pat='$ap_pat', ap_mat='$ap_mat', escuela='$escuela', plantel='$plantel', carrera='$carrera', tipo_pase='$tipo_pase', correo_elec='$correo_elec', tel_personal='$tel_personal', doc_curp='$doc_curp' where curp='$curp' and folio='$folio'";

    $query = mysqli_query($conn, $sql);

    if ($query>0) {      
      if($conn->query($sql) === TRUE){
            echo "<script> alert('Datos actualizados de forma correcta');
            location.href = 'datos_examen.php';
            </script>";
        } else {
            echo "<script> alert('Error al actualizar los datos ' . '$conn->error');
            location.href = 'datos_examen.php';
            </script>";
        } 
    }
?>

