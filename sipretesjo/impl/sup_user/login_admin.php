<?php
session_start();
require '../php/conexion.php';

if(isset($_POST['nombre']) && isset($_POST['contraseña'])) {
    $usuario = $_POST['nombre'];
    $contrasena = $_POST['contraseña'];
    
    // Verificar si el usuario y la contraseña son correctos
    $query = "SELECT * FROM admin WHERE nombre='$usuario' AND contraseña='$contrasena'";
    $resultado = mysqli_query($mysqli, $query);
    
    if(mysqli_num_rows($resultado) > 0) {
        // Iniciar sesión y redirigir al usuario a la página principal
        $_SESSION['nombre'] = $usuario;
        header("Location: datos_examen.php");
    } else {
        // Mostrar un mensaje de error si los datos son incorrectos
        echo "Nombre de usuario o contraseña incorrectos";
    }
}
?>
