<?php
date_default_timezone_set('America/La_Paz');
echo"<link href='stilos.css' rel='stylesheet' type='text/css'>";  
require("conexionmysqli2.inc");

$global_agencia=$_COOKIE["global_agencia"];
$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia'";
$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
$dat_almacen=mysqli_fetch_array($resp_almacen);
$global_almacen=$dat_almacen[0];
echo "<input type='hidden' value='$global_almacen' name='global_almacen' id='global_almacen'>";
$nombre_global_almacen=$dat_almacen[1];
//sacamos la fecha y la hora
$fecha_sistema=date("d-m-Y");
$hora_sistema=date("H:i");
//echo "<center><table width='100%' border=1 cellspacing=0 class='linea1'><tr><th>Territorio $agencia</th><th>Usuario:$nombre_completo </th><th>Almacen: $nombre_global_almacen</th></tr><tr><th colspan='2'>Fecha del Servidor: $fecha_sistema</th><th>Hora del Servidor: $hora_sistema</th></tr></table></center><br>";

?>