<?php

	require("conexionmysqli2.inc");
	//require('estilos_inicio_adm.inc');
	$datos=$_GET["datos"];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update material_apoyo set estado=0 where codigo_material=$vector[$i]";
		echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_material.php';
			</script>";


?>