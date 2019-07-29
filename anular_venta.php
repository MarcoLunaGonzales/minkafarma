<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$global_almacen=$_COOKIE["global_almacen"];
		
$sql_detalle="select cod_salida_almacen, cod_material, cantidad_unitaria, lote, fecha_vencimiento, cod_ingreso_almacen
			from salida_detalle_almacenes 
			where cod_salida_almacen='$codigo_registro'";
$resp_detalle=mysql_query($sql_detalle);
while($dat_detalle=mysql_fetch_array($resp_detalle))
{	$codVenta=$dat_detalle[0];
	$codMaterial=$dat_detalle[1];
	$cantidadSalida=$dat_detalle[2];
	$loteMaterial=$dat_detalle[3];
	$fechaVencMaterial=$dat_detalle[4];
	$codIngresoX=$dat_detalle[5];
	
	$cantidadSalidaPivote=$cantidadSalida;
	
	if($cantidadSalidaPivote>0){
		$sqlIngresos="select i.cod_ingreso_almacen, id.cod_material, id.cantidad_unitaria, id.cantidad_restante, 
		(id.cantidad_unitaria-id.cantidad_restante)saldo 
		from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$global_almacen' and 
		i.ingreso_anulado='0' and id.cod_material='$codMaterial' and id.lote='$loteMaterial' and id.cod_ingreso_almacen='$codIngresoX' 
		order by saldo desc";
		$respIngresos=mysql_query($sqlIngresos);
		while($datIngresos=mysql_fetch_array($respIngresos)){
			$codIngreso=$datIngresos[0];
			$codMaterialIng=$datIngresos[1];
			$cantidadUnitariaIng=$datIngresos[2];
			$cantidadRestante=$datIngresos[3];
			$maximoDevolver=$cantidadUnitariaIng-$cantidadRestante;
			
			if($maximoDevolver>=$cantidadSalida){
				$sqlUpdate="update ingreso_detalle_almacenes set cantidad_restante=cantidad_restante+$cantidadSalidaPivote where 
				cod_ingreso_almacen='$codIngreso' and cod_material='$codMaterialIng' and lote='$loteMaterial'";
				$respUpdate=mysql_query($sqlUpdate);
				$cantidadSalidaPivote=0;
			}else{
				$sqlUpdate="update ingreso_detalle_almacenes set cantidad_restante=cantidad_restante+$maximoDevolver where 
				cod_ingreso_almacen='$codIngreso' and cod_material='$codMaterialIng' and lote='$loteMaterial'";
				$respUpdate=mysql_query($sqlUpdate);
				$cantidadSalidaPivote=$cantidadSalidaPivote-$maximoDevolver;
			}
		}	
	}
}

$sql="update salida_almacenes set salida_anulada=1, estado_salida=3 where cod_salida_almacenes='$codigo_registro'";
$resp=mysql_query($sql);

$sql="update facturas_venta set cod_estado=2 where cod_venta='$codigo_registro'";
$resp=mysql_query($sql);


echo "<script language='Javascript'>
		alert('El registro fue anulado.');
		location.href='navegadorVentas.php';
		</script>";

?>