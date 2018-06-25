<?php
echo"<head><title>Modulo Central de Inventarios</title><link href='stilos.css' rel='stylesheet' type='text/css'></head>";  
//echo "<div id='Layer1' style='position:absolute; left:0px; top:0px; width:1000px; height:50 px; z-index:1; background-color: #000000; layer-background-color: #000000; border: 1px none #000000;'><img src='imagenes/cab_peque.jpg'>";
//echo "<center><script src='xaramenu.js'></script><script Webstyle4 src='imagenes/menu_almacenes_central.js'></script></center>";
//echo "</div>";
//echo "<body background='imagenes/fondo_pagina.jpg'>";
//echo "<div style='position:absolute; left:0px; top:100px; width:1000px; border: 1px none #000000;'>";
require("conexion.inc");
$sql="select paterno, materno, nombres from funcionarios where codigo_funcionario=$global_usuario";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$paterno=$dat[0];
$materno=$dat[1];
$nombre=$dat[2];
$nombre_completo="$paterno $materno $nombre";
$sql="select descripcion from ciudades where cod_ciudad=$global_agencia";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$agencia=$dat[0];
if($global_usuario==1062 or $global_usuario==1120)
{	$responsable_global_almacen=1061;
}
else
{	$responsable_global_almacen=$global_usuario;
}
$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia' and responsable_almacen='$responsable_global_almacen'";
$resp_almacen=mysql_query($sql_almacen);
$dat_almacen=mysql_fetch_array($resp_almacen);
$global_almacen=$dat_almacen[0];
$nombre_global_almacen=$dat_almacen[1];
//sacamos la fecha y la hora
$fecha_sistema=date("d-m-Y");
$hora_sistema=date("H:i");
echo "<center><table width='100%' border=1 cellspacing=0 class='linea1' bordercolor='#000000'><tr><th colspan='3'>Modulo Central de Inventarios<th></tr><tr><th>Territorio $agencia</th><th>Usuario:$nombre_completo </th><th>Almacen: $nombre_global_almacen</th></tr><tr><th colspan='2'>Fecha del Servidor: $fecha_sistema</th><th>Hora del Servidor: $hora_sistema</th></tr></table></center><br>";

?>