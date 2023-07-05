<?php
header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set('America/La_Paz');

require_once 'config.php';

if(!isset($_COOKIE["global_usuario"])){
  ?>
  <script type="text/javascript">
    $( document ).ready(function() {Swal.fire("ERROR!", "Inicie Sesion!", "error");
      location.href="index.html";
    });
    </script>
    <?php
}


if(!function_exists('register_globals')){
	require_once('register_globals.php');
	register_globals();

	if(!isset($estilosVenta)){
        //verificar niveles en url para insertar librerias
        $niv_url=substr_count($_SERVER["REQUEST_URI"], '/'); 
        switch ($niv_url) {
          case 2:require_once("librerias.php");$dirNoti="";break;
          case 3:require_once("librerias2.php");$dirNoti="../";break;
          case 4:require_once("librerias3.php");$dirNoti="../../";break;
        }
		}
}else{
}

$enlaceCon=mysqli_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);

if (mysqli_connect_errno())
{
	echo "Error en la conexión: " . mysqli_connect_error();
}
mysqli_set_charset($enlaceCon,"utf8");