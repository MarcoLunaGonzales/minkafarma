<?php

require_once 'config.php';
require_once 'estilos.inc';

set_time_limit(0);
error_reporting(0);

if(!isset($_COOKIE["global_usuario"])){
  ?>
  <script type="text/javascript">
    $( document ).ready(function() {Swal.fire("ERROR!", "Inicie Sesion!", "error");
      location.href="index.html";
    });
    </script>
    <?php
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
?>
