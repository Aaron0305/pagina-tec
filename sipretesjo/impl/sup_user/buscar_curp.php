<?php
$servername = "localhost";
$username = "root"; // Cambia esto si tienes un usuario diferente
$password = "TDEzkkBeAPf5LS"; // Cambia esto si tienes una contraseña
$dbname = "sipre"; // Cambia esto al nombre de tu BD

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión a la base de datos"]));
}

if (isset($_GET['curp1'])) {
    $curp1 = $conn->real_escape_string($_GET['curp1']);
    $sql = "SELECT curp,folio,nombre,ap_pat,ap_mat,escuela,plantel,carrera,tipo_pase,correo_elec,tel_personal FROM persona WHERE curp = '$curp1'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "No se encontró la CURP"]);
    }
} else {
    echo json_encode(["error" => "CURP no proporcionada"]);
}

$conn->close();
?>
