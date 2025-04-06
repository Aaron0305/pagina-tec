<?php
session_start();

if (!isset($_SESSION['user_curp'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_recibo.html');
  exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require 'Conexion.php';

    $formato = $_FILES['formato'];
    $pago = $_FILES['pago'];
   
    $curpValue = $_SESSION['user_curp'];

    $target_dir = "archivos/$curpValue/";
    if(!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); //crea la carpeta si no existe
    }

    $target_file_formato = $target_dir . basename("formato.pdf");
    $target_file_pago = $target_dir . basename("pago.pdf");
   

    move_uploaded_file($formato["tmp_name"], $target_file_formato);
    move_uploaded_file($pago["tmp_name"], $target_file_pago);

$sql = "UPDATE persona SET fecha_docs = now(), doc_fotografia='en_revision' WHERE curp = '".$curpValue."'";
    $conn->query($sql); 

    echo "Los archivos se han guardado correctamente.";
} else {
    echo "No se ha recibido ninguna solicitud POST.";
}
?>
