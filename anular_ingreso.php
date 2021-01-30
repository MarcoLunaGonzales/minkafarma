<?php
require("conexion.inc");
require("funciones.php");

$sql="update ingreso_almacenes set ingreso_anulado=1 where cod_ingreso_almacen='$codigo_registro'";
$resp=mysql_query($sql);

//SACAMOS LA VARIABLE PARA ENVIAR EL CORREO O NO SI ES 1 ENVIAMOS CORREO DESPUES DE LA TRANSACCION
$banderaCorreo=obtenerValorConfiguracion(8);

if($banderaCorreo==1){
	header("location:sendEmailAnulacionIngreso.php?codigo=$codigo_registro");
}
else{
	echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			location.href='navegador_ingresomateriales.php';			
			</script>";	
}


?>