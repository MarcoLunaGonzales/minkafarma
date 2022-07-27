<?php

require_once 'config.php';

if(!isset($_COOKIE["global_usuario"])){
  ?><!-- <script type="text/javascript">$( document ).ready(function() {Swal.fire("ERROR!", "Inicie Sesion!", "error");/*window.close();*/});</script> --><?php
}


set_time_limit(0);
error_reporting(0);

date_default_timezone_set('America/La_Paz');
if(!function_exists('log_querys')){
  require_once('register_logs.php');  
}

$dirNoti="";
if(!function_exists('register_globals')){
require_once('register_globals.php');

	register_globals();

	if(!isset($estilosVenta)){
        //verificar niveles en url para insertar librerias
        $niv_url=substr_count($_SERVER["REQUEST_URI"], '/');

        //echo "NIVURL:  ".$niv_url;
        
        switch ($niv_url) {
          case 2:require_once("librerias.php");$dirNoti="";break;
          case 3:require_once("librerias2.php");$dirNoti="../";break;
          case 4:require_once("librerias3.php");$dirNoti="../../";break;
        }


        if(!isset($indexGerencia)){
            ?>
          <div class="cargar">
            <p style='padding-top: 35px; font-size: 14px;text-align: center;' class="text-warning font-weight-bold">Procesando Datos XXX</p>
         </div>
            <?php

        }
        ?>
        
         <div class="cargar-ajax d-none">
           <div class="div-loading text-center">
              <h4 class="text-warning font-weight-bold" id="texto_ajax_titulo">Procesando Datos</h4>
              <p class="text-muted">Aguard&aacute; un momento por favor</p>  
           </div>
         </div>
        <?php
	}
}else{

}

$enlaceCon=mysqli_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);


if (mysqli_connect_errno())
{
	echo "Error en la conexión: " . mysqli_connect_error();
}
mysqli_set_charset($enlaceCon,"utf8");

if (!function_exists('mysqli_result')) {
    function mysqli_result($result, $number, $field=0) {
        mysqli_data_seek($result, $number);
        $row = mysqli_fetch_array($result);
        return $row[$field];
    }
}