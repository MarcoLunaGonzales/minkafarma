<?php


require("conexion.inc");
require("estilos.inc");

$codCiudadInsert=$_POST['agencia'];
//esta parte saca el nombre del funcionario
$sql_nombre_fun="select paterno, materno, nombres, estado from funcionarios where codigo_funcionario='$codigo'";
$resp_nombre_fun=mysql_query($sql_nombre_fun);
$dat_nombre_fun=mysql_fetch_array($resp_nombre_fun);
$nombre_funcionario="$dat_nombre_fun[0] $dat_nombre_fun[1] $dat_nombre_fun[2]";
$estado_inicial=$dat_nombre_fun[3];
//esta parte envia el mail al usuario
$fecha=$exafinicial;
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$sql="update funcionarios set cod_cargo=$cargo, paterno='$paterno',materno='$materno',nombres='$nombres',fecha_nac='$fecha_real',
	direccion='$direccion', telefono='$telefono',celular='$celular', email='$email', cod_ciudad='$codCiudadInsert', estado=$estado where codigo_funcionario=$codigo";
//echo $sql;
	$resp=mysql_query($sql);
$estado_final=$estado;
//echo "inicial $estado_inicial final $estado_final";
echo "<script language='Javascript'>
			alert('Los datos se modificaron satisfactoriamente');
			location.href='navegador_funcionarios.php?cod_ciudad=$agencia';
		</script>";  
?>
