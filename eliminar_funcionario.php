<?php
	require("conexion.inc");
	require("estilos_administracion.inc");
	$vector=explode(",",$datos);
	$cod_ciudad=$_GET['cod_ciudad'];

	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update funcionarios set estado=0 where codigo_funcionario=$vector[$i]";
		$resp=mysql_query($sql);
		$sql1="delete from usuarios_sistema where codigo_funcionario=$vector[$i]";
		$resp1=mysql_query($sql1);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_funcionarios.php?cod_ciudad=$cod_ciudad';
			</script>";

?>