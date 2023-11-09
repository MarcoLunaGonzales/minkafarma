<?php
require('conexionmysqli.php');
$item=$_GET['item'];
$precio3=$_GET['precio3'];

$globalSucursal=$_COOKIE['global_agencia'];

	$sqlDel="delete from precios where codigo_material='$item' and cod_ciudad='$globalSucursal'";
	$respDel=mysqli_query($enlaceCon,$sqlDel);
	
	$sqlInsert="insert into precios (codigo_material, cod_precio, precio, cod_ciudad, descuento_unitario) 
	values('$item', 1, '$precio3','$globalSucursal','0')";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);

echo "Precio Guardado!";
?>