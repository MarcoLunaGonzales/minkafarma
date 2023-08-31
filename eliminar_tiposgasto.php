<?php
	require("conexionmysqli.php");
	require("estilos_almacenes.inc");

	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update tipos_gasto set estado=2 where cod_tipogasto=$vector[$i]";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_tiposgasto.php';
			</script>";

?>