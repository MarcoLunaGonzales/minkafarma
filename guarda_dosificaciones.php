<?php
require("conexion.inc");
require("estilos.inc");

//recogemos variables
$codSucursal=$_POST['cod_sucursal'];
$nroAutorizacion=$_POST['nro_autorizacion'];
$llaveDosificacion=$_POST['llave_dosificacion'];
$fechaLimiteEmision=$_POST['fecha_limite_emision'];

$fechaActual=date("Y-m-d");

$sql_inserta="insert into dosificaciones(fecha_dosificacion, cod_sucursal, nro_autorizacion, llave_dosificacion, 
fecha_limite_emision, cod_estado) values ('$fechaActual','$codSucursal','$nroAutorizacion','$llaveDosificacion','$fechaLimiteEmision','2')";
//echo $sql_inserta;
$resp_inserta=mysql_query($sql_inserta);

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_dosificaciones.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}

?>