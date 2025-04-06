<?php
header ('Content-type: text/html; charset=utf-8');
session_start();

require 'Conexion.php';

$curp = $_POST['curp'];
$folio = $_POST['folio'];

// Buscar la CURP del usuario en la base de datos
$sql = "SELECT * FROM persona WHERE curp = '$curp' and folio = '$folio'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_curp'] = $user['curp']; // Guardar el ID del usuario en la sesión
    $_SESSION['user_folio'] = $user['folio']; // Guardar el nombre del usuario en la sesión
    header("Location: datos_examen.php");
} else {
  
  // Si la CURP es incorrecta, mostrar un mensaje de error y volver a la página de inicio de sesión
  $titulo = "Por favor ingrese sus datos";
  $mensaje = "Los datos son incorrectos, Vuelva a intentarlo.";
  
  echo "<script>
    alert('$mensaje');
    window.location.href = 'login_reg_exa.html';
</script>";
}