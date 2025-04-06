<?php
session_start();
header ('Content-type: text/html; charset=utf-8');

if (!isset($_SESSION['user_curp'])) {
  // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
  header('Location: login_docs_prom .html');
  exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require 'Conexion.php';

    $fotografia = $_FILES['fotografia'];
    $curp = $_FILES['curp'];
    $acta = $_FILES['acta'];
    $certificado = $_FILES['certificado'];
    $ine = $_FILES['ine'];

    $curpValue = $_SESSION['user_curp'];

    $target_dir = "archivos/$curpValue/";
    if(!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); //crea la carpeta si no existe
    }

    $target_file_fotografia = $target_dir . basename("Fotografia.jpg");
    $target_file_curp = $target_dir . basename("CURP.pdf");
    $target_file_acta = $target_dir . basename("ActaDeNacimiento.pdf");
    $target_file_certificado = $target_dir . basename("Certificado.pdf");
    $target_file_ine = $target_dir . basename("INE.pdf");

    move_uploaded_file($fotografia["tmp_name"], $target_file_fotografia);
    move_uploaded_file($curp["tmp_name"], $target_file_curp);
    move_uploaded_file($acta["tmp_name"], $target_file_acta);
    move_uploaded_file($certificado["tmp_name"], $target_file_certificado);
    move_uploaded_file($ine["tmp_name"], $target_file_ine);


    $sql = "UPDATE persona SET fecha_docs = now(), doc_fotografia='en_revision' WHERE curp = '".$curpValue."'";
    $conn->query($sql);

    echo "Los archivos se han guardado correctamente. ";
    
} else {
    echo "No se ha recibido ninguna solicitud POST.";
}
?>
