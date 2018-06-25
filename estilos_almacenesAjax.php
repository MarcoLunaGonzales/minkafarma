<?php
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
$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia'";
$resp_almacen=mysql_query($sql_almacen);
$dat_almacen=mysql_fetch_array($resp_almacen);
$global_almacen=$dat_almacen[0];
echo "<input type='hidden' value='$global_almacen' name='global_almacen' id='global_almacen'>";
$nombre_global_almacen=$dat_almacen[1];
?>