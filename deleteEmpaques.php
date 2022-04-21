<?php
	require("conexionmysqli.php");
	require("estilos.inc");
	
	$datos=$_GET["datos"];
	
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="UPDATE empaques set estado=0 where cod_empaque=$vector[$i]";
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos se procesaron correctamente.');
			location.href='navegador_empaques.php';
			</script>";


?>