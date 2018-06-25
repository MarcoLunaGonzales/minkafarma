<?php
require("conexion.inc");
require("estilos.inc");
$sql="select cod_tipomaterial, nombre_tipomaterial from tipos_material order by cod_tipomaterial desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
$sql_inserta=mysql_query("insert into tipos_material values($codigo,'$tipo_material','$obs_tipo_material')");
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_tiposmaterial.php';
			</script>";
?>