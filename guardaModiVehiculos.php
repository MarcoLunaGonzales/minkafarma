<?php
require("conexion.inc");
require("estilos.inc");

$codigo=$_POST['codigo'];
$placa=$_POST['placa'];
$nombre=$_POST['nombre'];
$peso=$_POST['peso'];

$sql="update vehiculos set placa='$placa', nombre='$nombre', peso_maximo='$peso' where codigo='$codigo'";
$resp=mysql_query($sql);

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_vehiculos.php';
			</script>";
?>