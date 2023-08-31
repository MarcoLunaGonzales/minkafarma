<?php
require("conexionmysqli.php");
require("estilos_almacenes.inc");

$tipoGasto=$_POST['tipo_gasto'];
$nombreTipoGasto=$_POST['nombre'];

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

$sql_inserta="insert into tipos_gasto (cod_tipogasto, nombre_tipogasto, estado, tipo) 
values($codigo,'$nombreTipoGasto','1','$tipoGasto')";

//echo $sql_inserta;

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);


echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_tiposgasto.php';
			</script>";

?>