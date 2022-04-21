<?php
	require("conexionmysqli.php");
	require("estilos.inc");
	
	$datos=$_GET["datos"];
	
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="UPDATE acciones_terapeuticas set estado=0 where cod_accionterapeutica=$vector[$i]";
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos se procesaron correctamente.');
			location.href='navegador_accionester.php';
			</script>";


?>