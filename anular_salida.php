<?php
require("conexionmysqli2.inc");

$codigo_registro=$_GET['codigo_registro'];

$sql="update salida_almacenes set salida_anulada=1 where cod_salida_almacenes='$codigo_registro' and salida_anulada=0";
$resp=mysqli_query($enlaceCon, $sql);
		
$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria, nro_lote
			from salida_detalle_ingreso
			where cod_salida_almacen='$codigo_registro'";
$resp_detalle=mysqli_query($enlaceCon, $sql_detalle);
while($dat_detalle=mysqli_fetch_array($resp_detalle))
{	$codigo_ingreso=$dat_detalle[0];
	$material=$dat_detalle[1];
	$cantidad=$dat_detalle[2];
	$nro_lote=$dat_detalle[3];
	$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
							where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
	$resp_ingreso_cantidad=mysqli_query($enlaceCon, $sql_ingreso_cantidad);
	$dat_ingreso_cantidad=mysqli_fetch_array($resp_ingreso_cantidad);
	$cantidad_restante=$dat_ingreso_cantidad[0];
	$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
	$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
					where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
	$resp_actualiza=mysqli_query($enlaceCon, $sql_actualiza);			
}
echo "<script language='Javascript'>
		alert('El registro fue anulado.');
		location.href='navegador_salidamateriales.php';
		</script>";

?>