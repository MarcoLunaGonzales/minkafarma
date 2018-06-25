<?php
echo"<head><title>Modulo Regional de Inventarios</title><link href='stilos.css' rel='stylesheet' type='text/css'></head>";  
echo "<body background='imagenes/fondo_pagina.jpg'>";
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
$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia' and responsable_almacen='$global_usuario'";
$resp_almacen=mysql_query($sql_almacen);
$dat_almacen=mysql_fetch_array($resp_almacen);
$global_almacen=$dat_almacen[0];
$nombre_global_almacen=$dat_almacen[1];
//sacamos la fecha y la hora
$fecha_sistema=date("d-m-Y");
$hora_sistema=date("H:i");
?>