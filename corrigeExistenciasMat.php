<?php
set_time_limit(0);
require('conexion.inc');

$cod_almacen_arreglar=1000;

$sqlMat="select m.codigo_material, m.descripcion_material from material_apoyo m where m.estado='Activo' and 
m.codigo_material in (1640,15,966,1168,1379,1070,1795, 27,43,46,1766,49,52,55,1164,1165,1551,28,73,1642,30,1248,82,1622,1462,25,32,88,125,90,1807,1808,1086,175,312,228,240,299,1516,315,337,338,339,1743,1517,1744,1747,359,327,336,210,1590,1418,1027,1645,1450,1433,992,1644,1452,400,1651,403,404,410,1532,1555,413,421,562,564,1753,1130,1536,1609,1606,1537,199,1215,459,463,1113,1813,485,1340,1501,498,499,1511,1512,1575,1504,535,550,1498,1470,112,1177,2503,116,119,609,1053,1116,548,730,1658,1275,685,1579,1005,917,1091,861,1049,810,1361,1402,920,549,888,784,701,1762,785,744,873,447,595,662,663,1279,731,664,1712,1567,674,866,1610,1360,173,1725,1394,1395,1548,1649,670,734,518,671,941,448,735,1763,721,558,202,654,1054,1578,1769,602,551,1547,598,91,94,95,1689,831,857,814,132,141) ORDER BY 1";
$respMat=mysql_query($sqlMat);

while($datMat=mysql_fetch_array($respMat)){
	
	$codMaterialArreglar=$datMat[0];
	
	//igualamos las cantidades de los ingresos detalle
	$sql_det_ingreso="select id.cod_ingreso_almacen, id.cod_material, id.cantidad_unitaria from ingreso_detalle_almacenes id, 
					ingreso_almacenes i where i.cod_ingreso_almacen=id.cod_ingreso_almacen and 
					i.cod_almacen='$cod_almacen_arreglar' and id.cod_material='$codMaterialArreglar' order by cod_ingreso_almacen";
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
						where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$cod_almacen_arreglar' 
						and sd.cod_material='$codMaterialArreglar'";
	$resp_salida_detalle=mysql_query($sql_salida_detalle);
	while($dat_salida_detalle=mysql_fetch_array($resp_salida_detalle))
	{	$cod_salida_almacen=$dat_salida_detalle[0];
		$sql_del_salidadetalleingreso="delete from salida_detalle_ingreso where cod_salida_almacen='$cod_salida_almacen' and material='$codMaterialArreglar'";
		$resp_del_salidadetalleingreso=mysql_query($sql_del_salidadetalleingreso);
	}
	//sacamos las salidas para generar la tabla salida_detalle_ingreso
	$sql_salida_detalle="select sd.cod_salida_almacen, sd.cod_material, sd.cantidad_unitaria
	from salida_detalle_almacenes sd, salida_almacenes s
						where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$cod_almacen_arreglar' 
						and s.salida_anulada=0 and sd.cod_material='$codMaterialArreglar'";
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
	
}

echo "OK MATERIAL..... $sqlMat";
?>