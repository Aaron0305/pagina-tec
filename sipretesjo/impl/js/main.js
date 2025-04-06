function getPais(){
    var pais = {
        option: 'Pais', 
    };

    $selectPais = ajax('php/main.php', pais);
    $("#paisUsuario").append($selectPais);
}

function getEstado(idPais){
    $('#estadoUsuario').empty();
    var IdPais = idPais;
    var DataSelect = getSelect(IdPais, 'Estado');
    $("#estadoUsuario").append(DataSelect);
}

function getMunicipio(idEstado){
    $('#municipioUsuario').empty();
    var IdEstado = idEstado;
    var DataMunicipio = getSelect(IdEstado, 'Municipio');
    $("#municipioUsuario").append(DataMunicipio);
}

function getLocalidad(idMunicipio){
    $('#localidadUsuario').empty();
    var IdMunicipio = idMunicipio;
    var DataLocalidad = getSelect(IdMunicipio, 'Localidad');
    $("#localidadUsuario").append(DataLocalidad);
}

function getSelect(idDato, opt){
    var IdDato = {
        option: opt,
        idElement: idDato
    };
    $dataSelect = ajax('php/main.php', IdDato);
    return $dataSelect;
}

function ajax(link, datos){
    var content;
    $.ajax({
        async: false,
        url : link,
        data : datos,
        type : 'post',
        beforeSend: function(){
        },
        success: function(response){
            content = response;   
        }
    });
    
    return content;
}