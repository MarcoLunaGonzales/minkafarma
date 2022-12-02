<?php
require('conexionmysqli.php');
$item=$_GET['item'];
$precio1=$_GET['precio1'];

	$sqlDel="delete from precios where codigo_material=$item";
	$respDel=mysqli_query($enlaceCon,$sqlDel);
	
	$sqlInsert="insert into precios values($item, 1,$precio1)";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);

echo "<img src='imagenes/guardarOK.png' width='30'><br>Precio Guardado!";
?>