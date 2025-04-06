<?php
session_start();

// Configuración de conexión a MySQL
$host = "localhost";
$usuario = "root"; // Cambiar si es otro usuario
$password = "TDEzkkBeAPf5LS";
$base_datos = "sipre";

// Conectar a la base de datos
$conexion = new mysqli($host, $usuario, $password, $base_datos);
$conexion->set_charset("utf8"); // Asegurar codificación UTF-8

// Verifica la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Nombre del archivo CSV
$filename = "datos_exportados.csv";

// Encabezados para descargar el archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header("Pragma: no-cache");
header("Expires: 0");

// Abre la salida en modo escritura
$output = fopen('php://output', 'w');

// Agregar BOM UTF-8 para evitar problemas con Excel
fwrite($output, "\xEF\xBB\xBF");

// Escribe la primera fila con los nombres de las columnas
fputcsv($output, ['CURP', 'FOLIO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'CARRERA', 'PLANTEL', 'TIPO PASE', 'CORREO', 'TELEFONO', 'ESTATUS']); // Ajusta los nombres de las columnas

// Consulta a la base de datos
$query = "SELECT curp,folio,nombre,ap_pat,ap_mat,carrera,plantel,tipo_pase,correo_elec,tel_personal,doc_fotografia FROM persona"; // Ajusta la consulta
$result = $conexion->query($query);

// Recorre los datos y los escribe en el CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Cierra la conexión
//$conn->close();
?>
