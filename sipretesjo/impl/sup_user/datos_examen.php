<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Página Oficial de Educación a Distancia del TESJo">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <script src="https://kit.fontawesome.com/728cc4b6c5.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <title>TESJo</title>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="../imagenes/logo.png" alt="Bootstrap" width="270" height="70">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavAltMarkup" >
                <div class="navbar-nav ml-auto">
                  <a class="nav-link active" aria-current="page" href=""><b></b></a>                   
                    <a class="nav-link " href="logout.php" style="font-size: 18px;"><b>SALIR</b></a>
                  </div>
                  
            </div>

          </nav>
          <div class="h4 pb-0 mb-4 text-danger border-bottom border-danger border-3"></div>
    </head>
    <body>

        <div class="container mt-5">
            <div class="row justify-content-center">
              <div class="col-md-12">
                <center>  <h1 >Actualización de aspirantes</h1> </center>
              </div>
    <!-- ________________________________ -->           
                <div class="row g-2">
                    <div class="col-md">
                        <div class="form-floating mb-3">
                            <label ></label>
                        </div>
                    </div> 

                    <div class="col-md">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="curp1" id="curp1" placeholder="Ingresa la CURP a buscar">
                        </div>
                    </div>         
                
                    <div class="col-md">
                        <div class="form-floating mb-3">
                            <button onclick="buscarCURP()" class="btn btn-primary">Buscar</button>
                        </div>         
                    </div>
                </div>
                <form action="actualizar_usuario.php" method="POST">
 <!-- ________________________________ -->
                    <div class="row g-2">
                        <div class="col-md">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="curp" id="curp" placeholder="CURP*" value="<?php echo $row["curp"]; ?>" readonly>
                                    <label for="floatingInput">CURP</label>
                                </div>
                        </div>
                        <div class="col-md">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="folio" id="folio" placeholder="FOLIO*" value="<?php echo $row["folio"]; ?>" readonly>
                                    <label for="floatingInput">FOLIO</label>
                                </div>
                        </div>
                    </div>
<!-- ________________________________ -->

                    <div class="row g-2">
                        
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre*"  value="<?php echo $row["nombre"]; ?>" maxlength="29" pattern="^([A-ZÁÉÍÓÚ]{1}[A-Za-zñáéíóú]+[\s]*)+$" title="Solo se aceptan letras"  autocomplete="off" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
                                <label for="floatingInput">Nombre</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="ap_pat" id="ap_pat" placeholder="Apellido Paterno*" value="<?php echo $row["ap_pat"]; ?>" maxlength="19" pattern="^([A-ZÁÉÍÓÚ]{1}[A-Za-zñáéíóú]+[\s]*)+$" title="Solo se aceptan letras" autocomplete="off" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
                                <label for="floatingInput">Apellido paterno</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="ap_mat" id="ap_mat" placeholder="Apellido Materno*" value="<?php echo $row["ap_mat"]; ?>" maxlength="19" pattern="^([A-ZÁÉÍÓÚ]{1}[A-Za-zñáéíóú]+[\s]*)+$" title="Solo se aceptan letras" autocomplete="off" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
                                <label for="floatingInput">Apellido materno</label>
                            </div>
                        </div>
                    </div>

 <!-- ________________________________ -->
<!-- ________________________________ -->

                    <div class="row g-2">
                        
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="escuela" id="escuela" placeholder="Institución Educativa*" value="<?php echo $row["escuela"]; ?>">
                                <label for="floatingInput" >Institución Educativa</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="plantel" id="plantel" placeholder="Plantel*" value="<?php echo $row["plantel"]; ?>">
                                <label for="floatingInput" for="resultadoInput">Plantel</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="carrera" id="carrera" placeholder="Carrera*" value="<?php echo $row["carrera"]; ?>">
                                <label for="floatingInput">Carrera</label>
                            </div>
                        </div>
                    </div>

 <!-- ________________________________ -->

            <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="tipo_pase" id="tipo_pase" placeholder="Tipo pase*" value="<?php echo $row["tipo_pase"]; ?>">
                        <label for="floatingInput" >Tipo pase</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="correo_elec" id="correo_elec" placeholder="Correo electrónico*" value="<?php echo $row["correo_elec"]; ?>" autocomplete="off" required>
                        <label for="floatingInput">Correo Electr&oacute;nico</label>
                    </div>
                </div>
                <div class="col-md">
                   <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="tel_personal" id="tel_personal" placeholder="Telefono Personal*" value="<?php echo $row["tel_personal"]; ?>" minlength="10" maxlength="10" pattern="[0-9]+" title="Solo se aceptan numeros"  autocomplete="off" required>
                        <label for="floatingInput">Tel&eacute;fono Personal</label>
                    </div>
                </div>

            </div>
     <!-- _______________________________ -->
                <div class="row g-2">
                    <div class="submit" align="right">
                        <input class="btn btn-primary" type="submit" id="submit" name="submit" value="ENVIAR DATOS">
                    </div>

                </form>
            </div>
       
        <script src="js/menu.js"></script>
   
        <script>eval(mod_pagespeed_h$45j0osqk);</script>
        <script>AOS.init();</script>
        <script>feather.replace()</script>
        <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>var swiper=new Swiper(".mySwiper",{spaceBetween:30,centeredSlides:true,autoplay:{delay:3500,disableOnInteraction:false,},pagination:{el:".swiper-pagination",clickable:true,},});</script>
    
    <script
        src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous">
    </script>

    <script src="js/main.js"></script></body>

    <script>
        $(function(){
            getPais();
            getEstado(42);
        })
    </script>

    <script>
        function buscarCURP() {
            let curp1 = document.getElementById("curp1").value;

            if (curp1.trim() === "") {
                alert("Ingrese una CURP válida");
                return;
            }

            fetch("buscar_curp.php?curp1=" + curp1)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById("curp").value = data.curp;
                        document.getElementById("folio").value = data.folio;
                        document.getElementById("nombre").value = data.nombre;
                        document.getElementById("ap_pat").value = data.ap_pat;
                        document.getElementById("ap_mat").value = data.ap_mat;
                        document.getElementById("escuela").value = data.escuela;
                        document.getElementById("plantel").value = data.plantel;
                        document.getElementById("carrera").value = data.carrera;
                        document.getElementById("tipo_pase").value = data.tipo_pase;
                        document.getElementById("correo_elec").value = data.correo_elec;
                        document.getElementById("tel_personal").value = data.tel_personal;
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    </script>


</html>
