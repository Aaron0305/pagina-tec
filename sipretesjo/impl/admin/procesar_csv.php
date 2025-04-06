<?php
session_start();

// Configuración de conexión a las bases de datos
$host = "localhost";
$usuario = "root"; 
$password = "TDEzkkBeAPf5LS";
$base_datos1 = "sipre";
$base_datos2 = "sistemas"; // Segunda base de datos

// Conectar a la primera base de datos
$conexion1 = new mysqli($host, $usuario, $password, $base_datos1);
if ($conexion1->connect_error) {
    die("Error de conexión a la BD 1: " . $conexion1->connect_error);
}

// Conectar a la segunda base de datos
$conexion2 = new mysqli($host, $usuario, $password, $base_datos2);
if ($conexion2->connect_error) {
    die("Error de conexión a la BD 2: " . $conexion2->connect_error);
}

// Asegurar codificación UTF-8
$conexion1->set_charset("utf8mb4");
$conexion2->set_charset("utf8mb4");

$insercionesExitosas = true; // Bandera para controlar si todas las inserciones son exitosas

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $directorio = "uploads/";

    // Verifica si la carpeta de destino existe
    if (!file_exists($directorio)) {
        mkdir($directorio, 0755, true);
    }

    $archivo_csv = $directorio . basename($_FILES["archivo_csv"]["name"]);
    $tipoArchivo = strtolower(pathinfo($archivo_csv, PATHINFO_EXTENSION));

    // Verifica que sea un CSV
    if ($tipoArchivo !== "csv") {
        echo "<script>alert('Error: Solo se permiten archivos CSV.'); window.history.back();</script>";
        exit;
    }

    // Mueve el archivo a la carpeta
    if (move_uploaded_file($_FILES["archivo_csv"]["tmp_name"], $archivo_csv)) {
        
        // Abre el archivo CSV
        if (($handle = fopen($archivo_csv, "r")) !== FALSE) {
            fgetcsv($handle); // Omitir encabezado si es necesario

            while (($datos = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($datos) < 9) { // Ajusta según la cantidad de columnas
                    echo "<script>alert('Error en formato CSV.'); window.history.back();</script>";
                    $insercionesExitosas = false;
                    break;
                }

                // Sanitizar los datos
                $curp = $conexion1->real_escape_string($datos[0]); 
                $folio = $conexion1->real_escape_string($datos[1]);
                $nombre = $conexion1->real_escape_string($datos[2]);
                $ap_pat = $conexion1->real_escape_string($datos[3]);
                $ap_mat = $conexion1->real_escape_string($datos[4]);
                $escuela = $conexion1->real_escape_string($datos[5]);
                $carrera = $conexion1->real_escape_string($datos[6]);
                $correo_elec = $conexion1->real_escape_string($datos[7]);
                $tel_personal = $conexion1->real_escape_string($datos[8]);

                // Verifica si la clave primaria ya existe en la BD1
                $sqlVerificar1 = "SELECT COUNT(*) FROM persona WHERE curp = '$curp'";
                $resultado1 = $conexion1->query($sqlVerificar1);
                $fila1 = $resultado1->fetch_row();
                
                // Verifica si la clave primaria ya existe en la BD2
                $sqlVerificar2 = "SELECT COUNT(*) FROM persona WHERE curp = '$curp'";
                $resultado2 = $conexion2->query($sqlVerificar2);
                $fila2 = $resultado2->fetch_row();

                if ($fila1[0] > 0 || $fila2[0] > 0) {
                    echo "<script>alert('Error: La clave primaria $curp ya existe en una de las bases de datos.');</script>";
                    $insercionesExitosas = false;
                    continue;
                }

                // Inserción en ambas bases de datos
                $sql1 = "INSERT INTO persona (curp, folio, nombre, ap_pat, ap_mat, escuela, carrera, correo_elec, tel_personal) 
                         VALUES ('$curp', '$folio', '$nombre', '$ap_pat', '$ap_mat', '$escuela', '$carrera', '$correo_elec', '$tel_personal')";
                
                $sql2 = "INSERT INTO persona (curp, folio, nombre, ap_pat, ap_mat, escuela, carrera, correo_elec, tel_personal) 
                         VALUES ('$curp', '$folio', '$nombre', '$ap_pat', '$ap_mat', '$escuela', '$carrera', '$correo_elec', '$tel_personal')";

                // Ejecutar ambas inserciones
                if (!$conexion1->query($sql1) || !$conexion2->query($sql2)) {
                    echo "<script>alert('Error al insertar en una de las bases de datos.');</script>";
                    $insercionesExitosas = false;
                }
            }
            fclose($handle);
        } else {
            echo "<script>alert('Error al abrir el archivo.'); window.history.back();</script>";
            $insercionesExitosas = false;
        }
    } else {
        echo "<script>alert('Error al subir el archivo.'); window.history.back();</script>";
        $insercionesExitosas = false;
    }
}

// Si todas las inserciones fueron exitosas, redirigir
if ($insercionesExitosas) {
    header("Location: Administracion.php");
    exit();
}

// Cerrar conexiones
$conexion1->close();
$conexion2->close();
?>
