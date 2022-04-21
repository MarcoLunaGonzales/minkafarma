<?php
require("conexion.inc");
require("estilos_almacenes.inc");
require('funciones.php');

$codFactura=$_GET["codigo_registro"];

$vectorNroCorrelativo=numeroCorrelativo(2);
$nro_correlativo=$vectorNroCorrelativo[0];
$cod_dosificacion=$vectorNroCorrelativo[2];

$sql="update salida_almacenes set nro_correlativo='$nro_correlativo', cod_tipo_doc=2, observaciones='Convertida desde Cod:$codFactura',
	razon_social='0', nit='0' where cod_salida_almacenes='$codigo_registro'";
$resp=mysql_query($sql);

$sql="update facturas_venta set cod_estado=2 where cod_venta='$codigo_registro'";
$resp=mysql_query($sql);


echo "<script language='Javascript'>
		alert('El proceso fue ejecutado correctamente.');
		location.href='navegadorVentas.php';
		</script>";

?>