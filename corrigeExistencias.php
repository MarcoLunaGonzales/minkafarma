<?php
set_time_limit(0);
require('conexion.inc');

$cod_almacen_arreglar=$codigo_almacen;
//igualamos las cantidades de los ingresos detalle
$sql_det_ingreso="select id.cod_ingreso_almacen, id.cod_material, id.cantidad_unitaria from ingreso_detalle_almacenes id, 
				ingreso_almacenes i where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$cod_almacen_arreglar' order by cod_ingreso_almacen";
$resp_det_ingreso=mysql_query($sql_det_ingreso);
while($dat_det_ingreso=mysql_fetch_array($resp_det_ingreso))
{	$cod_ingreso=$dat_det_ingreso[0];
	$cod_material=$dat_det_ingreso[1];
	$cant_unit=$dat_det_ingreso[2];
	$sql_actualiza_cant=mysql_query("update ingreso_detalle_almacenes set cantidad_restante='$cant_unit'
	where cod_ingreso_almacen='$cod_ingreso' and cod_material='$cod_material'");
}
//borramos la tabla salida_detalle_ingreso
$sql_salida_detalle="select sd.cod_salida_almacen, sd.cod_material, sd.cantidad_unitaria
from salida_detalle_almacenes sd, salida_almacenes s
					where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$cod_almacen_arreglar'";
$resp_salida_detalle=mysql_query($sql_salida_detalle);
while($dat_salida_detalle=mysql_fetch_array($resp_salida_detalle))
{	$cod_salida_almacen=$dat_salida_detalle[0];
	$sql_del_salidadetalleingreso="delete from salida_detalle_ingreso where cod_salida_almacen='$cod_salida_almacen'";
	$resp_del_salidadetalleingreso=mysql_query($sql_del_salidadetalleingreso);
}
//sacamos las salidas para generar la tabla salida_detalle_ingreso
$sql_salida_detalle="select sd.cod_salida_almacen, sd.cod_material, sd.cantidad_unitaria
from salida_detalle_almacenes sd, salida_almacenes s
					where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$cod_almacen_arreglar' and s.salida_anulada=0";
$resp_salida_detalle=mysql_query($sql_salida_detalle);
while($dat_salida_detalle=mysql_fetch_array($resp_salida_detalle))
{	$cod_salida_almacen=$dat_salida_detalle[0];
    echo $cod_salida_almacen."<br>";
	$cod_material=$dat_salida_detalle[1];
	$cant_unit_salida=$dat_salida_detalle[2];
	//inicio
	$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante from ingreso_detalle_almacenes id, 
	ingreso_almacenes i
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$cod_almacen_arreglar' 
	and id.cod_material='$cod_material' and id.cantidad_restante<>0 and i.ingreso_anulado=0 order by id.cod_ingreso_almacen";
	$resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
	$cantidad_bandera=$cant_unit_salida;
	$bandera=0;
	while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
	{	$cod_ingreso_almacen=$dat_detalle_ingreso[0];
		$cantidad_restante=$dat_detalle_ingreso[1];
		if($bandera!=1)
		{
			if($cantidad_bandera>$cantidad_restante)
			{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso 
								values('$cod_salida_almacen','$cod_ingreso_almacen','$cod_material','$cantidad_restante')";
				$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
				$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
				$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where 
								cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material'";
				$resp_upd_cantidades=mysql_query($upd_cantidades);
			}
			else
			{		$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$cod_salida_almacen','$cod_ingreso_almacen',
											'$cod_material','$cantidad_bandera')";
					$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
					$cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
					$bandera=1;
					$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_a_actualizar 
									where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material'";
					$resp_upd_cantidades=mysql_query($upd_cantidades);
					$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
			}
		}
	}
	
	//fin
	
	
}
echo "OK MATERIAL.....";
?>