<?php
require('conexion.inc');
$item=$_GET['item'];
$cod_ingreso=$_GET['cod_ingreso'];
$estante=$_GET['estante'];
$fila=$_GET['fila'];
	
	$sqlInsert="update ingreso_detalle_almacenes set cod_ubicacionfila='$fila', cod_ubicacionestante='$estante' where 
	cod_ingreso_almacen='$cod_ingreso' and cod_material='$item'";
	$respInsert=mysql_query($sqlInsert);

echo "Se guardo correctamente!";
?>