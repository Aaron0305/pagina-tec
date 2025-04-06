<?php
//session_start();
header ('Content-type: text/html; charset=utf-8');
require_once("conexion.php");

@$opcion = $_POST['option'];

    if(strcmp($opcion, 'Pais') == 0){
        $sqlPais = "SELECT * FROM countries ORDER BY description ASC";
        $getPais = $mysqli->query($sqlPais);

        $selectPais = "<option value='' >Seleccione un Pa&iacute;s</option>";
        
        while ($pais = $getPais->fetch_assoc()){
            if($pais['id']==42){
                $selectPais .= "<option value='".$pais['id']."' selected>".$pais['description']."</option>";
            }else{
                $selectPais .= "<option value='".$pais['id']."'>".$pais['description']."</option>";
            }
            
        }

        echo $selectPais;
    }

    if(strcmp($opcion, 'Estado') == 0){

        $edoUser = $_POST['idElement'];

        $sqlEstado = "SELECT * FROM states WHERE id_country = ".$edoUser." ORDER BY description ASC";
        $getEstado = $mysqli->query($sqlEstado);

        $selectEstado = "<option value='' selected>Seleccione un Estado</option>";
        
        while ($estado = $getEstado->fetch_assoc()){
            $selectEstado .= "<option value='".$estado['id']."'>".$estado['description']."</option>";
        }

        echo $selectEstado;
    }

    if(strcmp($opcion, 'Municipio') == 0){

        $municipioUser = $_POST['idElement'];
        $sqlMunicipio = "SELECT * FROM municipalities WHERE id_state = ".$municipioUser." ORDER BY description ASC";
        $getMunicipio = $mysqli->query($sqlMunicipio);

        $selectEdo = "<option value='' selected>Seleccione un Municipio</option>";
        
        while ($municipio = $getMunicipio->fetch_assoc()){
            $selectEdo .= "<option value='".$municipio['id']."'>".$municipio['description']."</option>";
        }

        echo $selectEdo;
    }

    if(strcmp($opcion, 'Localidad') == 0){

        $LocalidadUser = $_POST['idElement'];
        $sqlLocalidad = "SELECT * FROM suburbs WHERE id_municipality = ".$LocalidadUser." ORDER BY description ASC";
        $getLocalidad = $mysqli->query($sqlLocalidad);

        $selectLocalidad = "<option value='' selected>Seleccione una Localidad</option>";
        
        while ($localidad = $getLocalidad->fetch_assoc()){
            $selectLocalidad .= "<option value='".$localidad['id']."'>".$localidad['description']."</option>";
        }

        echo $selectLocalidad;
    }
?>

