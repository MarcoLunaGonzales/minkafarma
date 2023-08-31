<?php
require("conexion.inc");
require("estilos.inc");

$sql="select cod_tipogasto, nombre_tipogasto from tipos_gasto order by 1 desc";
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

$sql_inserta=mysqli_query($enlaceCon,"insert into tipos_gasto (cod_tipogasto, nombre_tipogasto, estado) 
values($codigo,'$nombre','1')");

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_tiposgasto.php';
			</script>";
?>