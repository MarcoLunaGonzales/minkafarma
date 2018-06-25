<?php

require("conexion.inc");
require("estilos.inc");

$fecha=$exafinicial;
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
//verifica que no exista repeticion de datos en nuestra estructura
$sql_pre="select codigo_funcionario from funcionarios order by codigo_funcionario desc";
$resp_pre=mysql_query($sql_pre);
$num_filas=mysql_num_rows($resp_pre);
if($num_filas==0)
{	$codigo_funcionario=1000;
}
else
{	$dat_pre=mysql_fetch_array($resp_pre);
	$codigo_funcionario=$dat_pre[0];
	$codigo_funcionario++;
}
//estado=1 es activo, 0 es retirado
$sql="insert into funcionarios values($codigo_funcionario,'$cargo','$paterno','$materno','$nombres','$fecha_real',
'$direccion','$telefono','$celular','$email','$agencia',1)";
$resp=mysql_query($sql);
echo "<script language='Javascript'>
			alert('Los datos se registraron satisfactoriamente');
			location.href='navegador_funcionarios.php?cod_ciudad=$agencia';
		</script>
	";
?>