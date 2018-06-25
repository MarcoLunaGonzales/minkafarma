<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005
*/
require("conexion.inc");
if($usuario_rrhh!="")
{	require("estilos_rrhh.php");
}
else
{	require("estilos.inc");
}
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
	direccion='$direccion', telefono='$telefono',celular='$celular', email='$email', cod_ciudad='$agencia', estado=$estado where codigo_funcionario=$codigo";
$resp=mysql_query($sql);
$estado_final=$estado;
echo "inicial $estado_inicial final $estado_final";
if($estado_inicial==1 && $estado_final==0)
{
 	//esta parte envia un correo a recursos humanos
	$adicionales="FROM:Administrador del sistema HERMES";
	$url_origen="http://200.105.203.2/visita_medica/navegador_funcionarios.php?cod_ciudad=$agencia";
	echo "<script language='JavaScript'>location.href='http://www.cofar.com.bo/correo_baja_hermes.php?adicionales=$adicionales&nombre_funcionario=$nombre_funcionario&url_origen=$url_origen';</script>";
	//header("location:http://www.cofar.com.bo/correo_baja_hermes.php?adicionales=$adicionales&nombre_funcionario=$nombre_funcionario&url_origen=$url_origen"); 
	$sql_del="delete from usuarios_sistema where codigo_funcionario='$codigo'";
	$resp_del=mysql_query($sql_del);
	
}
else
{
	echo "<script language='Javascript'>
			alert('Los datos se modificaron satisfactoriamente');
			location.href='navegador_funcionarios.php?cod_ciudad=$agencia';
		</script>";  
}
?>
