<?php
require("conexion.inc");
require("estilos_almacenes.inc");


$sql="update salida_almacenes set  where cod_salida_almacenes='$codigo_registro'";
$resp=mysql_query($sql);

$sql="update facturas_venta set cod_estado=2 where cod_venta='$codigo_registro'";
$resp=mysql_query($sql);


echo "<script language='Javascript'>
		alert('El registro fue anulado.');
		location.href='navegadorVentas.php';
		</script>";

?>