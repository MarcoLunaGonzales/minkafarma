<?php
require('conexionmysqli.php');
$item=$_GET['item'];
$precio1=$_GET['precio1'];
$precio2=$_GET['precio2'];
$precio3=$_GET['precio3'];
$precio4=$_GET['precio4'];

	$sqlDel="delete from precios where codigo_material=$item";
	$respDel=mysqli_query($enlaceCon,$sqlDel);
	
	$sqlInsert="insert into precios values($item, 1,$precio1)";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($item, 2,$precio2)";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($item, 3,$precio3)";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($item, 4,$precio4)";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);

echo "Precio Guardado!";
?>