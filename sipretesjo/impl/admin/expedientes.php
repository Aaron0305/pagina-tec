<?php
header ('Content-type: text/html; charset=utf-8');

//Creamos el archivo
$nombreExpediente = "Expedientes";
$nombreFile = $nombreExpediente.".zip";
$folder = "../archivos/";

$zip = new \ZipArchive();

//abrimos el archivo y lo preparamos para agregarle archivos
$zip->open($nombreFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

//indicamos cual es la carpeta que se quiere comprimir
$origen = realpath($folder);

//Ahora usando funciones de recursividad vamos a explorar todo el directorio y a enlistar todos los archivos contenidos en la carpeta
$files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($origen),
            \RecursiveIteratorIterator::LEAVES_ONLY
);

//Ahora recorremos el arreglo con los nombres los archivos y carpetas y se adjuntan en el zip
foreach ($files as $name => $file)
{
   if (!$file->isDir())
   {
       $filePath = $file->getRealPath();
       $relativePath = substr($filePath, strlen($origen) + 1);

       $zip->addFile($filePath, $relativePath);
   }
}

//Se cierra el Zip
$zip->close();


// Creamos las cabezeras que forzaran la descarga del archivo como archivo zip.
header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename=".$nombreFile);
// leemos el archivo creado
readfile($nombreFile);
// Por último eliminamos el archivo temporal creado
unlink($nombreFile);//Destruye el archivo temporal

?>