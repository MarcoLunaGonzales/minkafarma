<?php

require("conexionmysqli.php");
require("estilos.inc");

$sql="select cod_almacen from almacenes order by cod_almacen desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
$sql_inserta=mysqli_query($enlaceCon,"insert into almacenes values($codigo,'$territorio','$nombre_almacen','$responsable')");

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_almacenes.php';
			</script>";
?>