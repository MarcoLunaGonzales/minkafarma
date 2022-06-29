<?php
require('conexionmysqli.php');
$item=$_GET['item'];
$precio3=$_GET['precio3'];

	$sqlDel="delete from precios where codigo_material=$item";
	$respDel=mysqli_query($enlaceCon,$sqlDel);
	
	$sqlInsert="insert into precios values('$item', 1, '$precio3')";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);

echo "Precio Guardado!";
?>