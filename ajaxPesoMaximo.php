<?php
require("conexion.inc");
$codVehiculo=$_GET['codVehiculo'];
if($codVehiculo!=0){
	$sql="select peso_maximo from vehiculos where codigo in ('$codVehiculo')";
	$resp=mysql_query($sql);
	$pesoMaximo=mysql_result($resp,0,0);

	echo "<input type='hidden' name='pesoMaximoVehiculo' id='pesoMaximoVehiculo' value='$pesoMaximo'>";
	echo "Peso Maximo: $pesoMaximo";
}else{
	echo "<input type='hidden' name='pesoMaximoVehiculo' id='pesoMaximoVehiculo' value='0'>";
	echo "Peso Maximo: 0";
}


?>
