<?php

require('conexionmysqli2.inc');
require('funciones.php');


$sql="select distinct(p.codigo_material), cod_precio, precio from precios p where p.cod_precio=1";
$resp=mysqli_query($enlaceCon, $sql);
while($dat=mysqli_fetch_array($resp)){
	$codProducto=$dat[0];
	$precio=$dat[2];

	echo $codProducto." ".$precio."<br>";

	$sqlCiudad="select c.cod_ciudad, c.nombre_ciudad from ciudades c where c.cod_ciudad";
	$respCiudad=mysqli_query($enlaceCon, $sqlCiudad);

	/*	 BORRAMOS LOS PRECIOS   */
	$delPrecios="delete from precios where codigo_material='$codProducto'";
	$respDelPrecios=mysqli_query($enlaceCon, $delPrecios);

	while($datCiudad=mysqli_fetch_array($respCiudad)){
		$codCiudad=$datCiudad[0];

		$sqlInsert="insert into precios (codigo_material, cod_precio, precio, cod_ciudad) values 
			('$codProducto','1','$precio','$codCiudad')";
		$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	}

	echo "REGISTRADO!!";

}

?>