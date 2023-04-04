<?php
	require("../conexionmysqli2.inc");
	require("../estilos2.inc");
	require("configModule.php");

	$datos=$_GET['codigo_registro'];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update $table set estado_campania=3 where codigo=$vector[$i]";
		echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron aprobados.');
			location.href='$urlList2';
			</script>";

?>