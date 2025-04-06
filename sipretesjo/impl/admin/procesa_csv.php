<?php
session_start();

// Configuración de conexión a MySQL
$host = "localhost";
$usuario = "root"; // Cambiar si es otro usuario
$password = "TDEzkkBeAPf5LS";
$base_datos = "sipre";

// Conectar a la base de datos
$conexion = new mysqli($host, $usuario, $password, $base_datos);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4"); // Asegura que los caracteres especiales se guarden bien

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
        echo "Error: Solo se permiten archivos CSV.";
        exit;
    }

    // Mueve el archivo a la carpeta
    if (move_uploaded_file($_FILES["archivo_csv"]["tmp_name"], $archivo_csv)) {
       

        // Abre el archivo CSV
        if (($handle = fopen($archivo_csv, "r")) !== FALSE) {
            fgetcsv($handle); // Omitir encabezado si es necesario

            while (($datos = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($datos) < 3) { // Ajusta según la cantidad de columnas
                    echo "Error en formato CSV.<br>";
                     continue;
                }

                // Sanitizar los datos
                $curp = $conexion->real_escape_string($datos[0]); 
	        $folio = $conexion->real_escape_string($datos[1]);
	        $nombre = $conexion->real_escape_string($datos[2]);
	        $ap_pat = $conexion->real_escape_string($datos[3]);
	        $ap_mat = $conexion->real_escape_string($datos[4]);
	        $escuela = $conexion->real_escape_string($datos[5]);
	        $carrera = $conexion->real_escape_string($datos[6]);
	        $correo_elec = $conexion->real_escape_string($datos[7]);
	        $tel_personal = $conexion->real_escape_string($datos[8]);

		// Verifica si la clave primaria ya existe
                $sqlVerificar = "SELECT COUNT(*) FROM persona WHERE curp = '$curp'";
                $resultado = $conexion->query($sqlVerificar);
                $fila = $resultado->fetch_row();
                if ($fila[0] > 0) {
                    //echo "Error: La CURP '$curp' ya existe. No se insertó el registro.<br>";
                    echo "<script>alert('Error: La clave primaria $curp ya existe.'); window.history.back();</script>";
		    continue;
		    //alert("Registros duplicados por favor revisar el archivo");
		    //header("Location: Administracion.php");
                }



                // Inserción en la base de datos
                $sql = "INSERT INTO persona (curp, folio, nombre, ap_pat, ap_mat, escuela, carrera, correo_elec, tel_personal) VALUES ('$curp', '$folio', '$nombre', '$ap_pat', '$ap_mat', '$escuela', '$carrera', '$correo_elec', '$tel_personal')";
		header("Location: Administracion.php");                
                if (!$conexion->query($sql) && !$conexion->query($sql2)) {
                    echo "Error al insertar: " . $conexion->error . "<br>";
                }
            }
            fclose($handle);
        } else {
		echo "<script>alert('Error al abrir el archivo.'); window.history.back();</script>";
//            echo "Error al abrir el archivo.";
        }
    } else {
	  echo "<script>alert('Error al subir el archivo.'); window.history.back();</script>";
//        echo "Error al subir el archivo.";
    }
}

$conexion->close();
?>
