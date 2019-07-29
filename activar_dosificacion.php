<?php
	require("conexion.inc");
	require("estilos.inc");
	$codRegistro=$_GET['codigo_registro'];
	
	
		$sqlUpd="select cod_sucursal from dosificaciones where cod_dosificacion=$codRegistro";
		//echo $sqlUpd;
		$respUpd=mysql_query($sqlUpd);
		$codSucursal=mysql_result($respUpd,0,0);
		
		$sqlUpd1="update dosificaciones set cod_estado='3' where cod_sucursal in ($codSucursal) and cod_estado in (1,2)";
		$respUpd1=mysql_query($sqlUpd1);
		
		$sql="update dosificaciones set cod_estado='1' where cod_dosificacion=$codRegistro";
		$resp=mysql_query($sql);


		echo "<script language='Javascript'>
			alert('Activado!');
			location.href='navegador_dosificaciones.php';
			</script>";


?>