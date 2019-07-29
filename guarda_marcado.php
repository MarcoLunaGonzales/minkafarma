<?php

require("conexion.inc");
require("estilos.inc");
require("funcion_nombres.php");

$claveMarcado=$_POST["clave_marcado"];

$fechaActual=date("Y-m-d H:i:s");

$sql="select codigo_funcionario from usuarios_sistema where contrasena='$claveMarcado'";
$resp=mysql_query($sql);
$numFilas=mysql_num_rows($resp);

if($numFilas>0){
	while($dat=mysql_fetch_array($resp)){
		$codUsuario=$dat[0];
		$nombreUsuario=nombreVisitador($codUsuario);
		$sqlInsert="insert into marcados_personal (cod_funcionario, fecha_marcado, estado) values 
		($codUsuario, '$fechaActual', 1)";
		$respInsert=mysql_query($sqlInsert);
		
		echo "<script language='Javascript'>
			alert('MARCADO EXITOSO!!!!!!!. Bienvenido $nombreUsuario');
			location.href='registrar_marcado.php';
			</script>";
	}
}else{
	echo "<script language='Javascript'>
			alert('ERROR!!!!.');
			location.href='registrar_marcado.php';
			</script>";	
}
?>