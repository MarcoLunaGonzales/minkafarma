<?php
	require("conexionmysqli.php");
	require("estilos.inc");
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update dosificaciones set cod_estado='4' where cod_dosificacion=$vector[$i]";
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_dosificaciones.php';
			</script>";


?>