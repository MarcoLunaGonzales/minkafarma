<?php
require('conexion.inc');
$item=$_GET['item'];
$precio3=$_GET['precio3'];

	$sqlDel="delete from precios where codigo_material=$item";
	$respDel=mysql_query($sqlDel);
	
	$sqlInsert="insert into precios values('$item', 1, '$precio3')";
	$respInsert=mysql_query($sqlInsert);

echo "Precio Guardado!";
?>