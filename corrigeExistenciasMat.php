<?php
set_time_limit(0);
require('conexionmysqli2.inc');

$cod_almacen_arreglar=1001;

$sqlMat="select m.codigo_material, m.descripcion_material from material_apoyo m where m.estado='1' and 
m.codigo_material in (100955,93159,6546,3151,102407,98317,92518,98394,98395,101577,595054,95964,31012,594618,595034,595560,595035,96418,595545,595546,20489,26176,94347,97685,100956,100957,40,17141,90706,96223,595233,4488,102860,17134,97413,98980,98981,58520,31031,595365,595366,531,97471,90114,3760,595362,22821,11483,19327,102588,15750,595451,12021,8740,16046,16053,6971,29057,24712,21520,24711,102055,886,90759,595602,97249,90897,96501,6634,23522,100574,97206,26864,100486,219,19028,27821,97292,8141,91253,95569,595049,594632,595444,595441,97868,96057,100818,15818,98897,25460,25920,88425,94440,595364,24586,24588,89765,102214,50023,13150,595287,22658,94197,97251,97252,595568,31067,94074,30118,21465,28667,94772,595592,100219,12099,97476,94700,30124,22573,92770,595041,3850,19839) ORDER BY 1";
//echo $sqlMat;
$respMat=mysqli_query($enlaceCon, $sqlMat);

while($datMat=mysqli_fetch_array($respMat)){
	

	$codMaterialArreglar=$datMat[0];
	
	echo $codMaterialArreglar."<br>";

	//igualamos las cantidades de los ingresos detalle
	$sql_det_ingreso="select id.cod_ingreso_almacen, id.cod_material, id.cantidad_unitaria from ingreso_detalle_almacenes id, 
					ingreso_almacenes i where i.cod_ingreso_almacen=id.cod_ingreso_almacen and 
					i.cod_almacen='$cod_almacen_arreglar' and id.cod_material='$codMaterialArreglar' order by cod_ingreso_almacen";
	
	//echo $sql_det_ingreso."<br>";
	
	$resp_det_ingreso=mysqli_query($enlaceCon, $sql_det_ingreso);
	while($dat_det_ingreso=mysqli_fetch_array($resp_det_ingreso))
	{	$cod_ingreso=$dat_det_ingreso[0];
		$cod_material=$dat_det_ingreso[1];
		$cant_unit=$dat_det_ingreso[2];
		$sql_actualiza_cant=mysqli_query($enlaceCon, "update ingreso_detalle_almacenes set cantidad_restante='$cant_unit'
		where cod_ingreso_almacen='$cod_ingreso' and cod_material='$cod_material'");
	}
	//borramos la tabla salida_detalle_ingreso
	$sql_salida_detalle="select sd.cod_salida_almacen, sd.cod_material, sd.cantidad_unitaria
	from salida_detalle_almacenes sd, salida_almacenes s
						where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$cod_almacen_arreglar' 
						and sd.cod_material='$codMaterialArreglar'";
	
	//echo $sql_salida_detalle."<br>";

	$resp_salida_detalle=mysqli_query($enlaceCon, $sql_salida_detalle);
	while($dat_salida_detalle=mysqli_fetch_array($resp_salida_detalle))
	{	$cod_salida_almacen=$dat_salida_detalle[0];
		$sql_del_salidadetalleingreso="delete from salida_detalle_ingreso where cod_salida_almacen='$cod_salida_almacen' and material='$codMaterialArreglar'";
		$resp_del_salidadetalleingreso=mysqli_query($enlaceCon, $sql_del_salidadetalleingreso);
	}
	//sacamos las salidas para generar la tabla salida_detalle_ingreso
	$sql_salida_detalle="select sd.cod_salida_almacen, sd.cod_material, sd.cantidad_unitaria
	from salida_detalle_almacenes sd, salida_almacenes s
						where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$cod_almacen_arreglar' 
						and s.salida_anulada=0 and sd.cod_material='$codMaterialArreglar'";
	
	//echo $sql_salida_detalle."<br>";

	$resp_salida_detalle=mysqli_query($enlaceCon, $sql_salida_detalle);
	while($dat_salida_detalle=mysqli_fetch_array($resp_salida_detalle))
	{	$cod_salida_almacen=$dat_salida_detalle[0];
		echo $cod_salida_almacen."<br>";
		$cod_material=$dat_salida_detalle[1];
		$cant_unit_salida=$dat_salida_detalle[2];
		//inicio
		$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante from ingreso_detalle_almacenes id, 
		ingreso_almacenes i
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$cod_almacen_arreglar' 
		and id.cod_material='$cod_material' and id.cantidad_restante<>0 and i.ingreso_anulado=0 order by id.cod_ingreso_almacen";
		
		//echo "SQL DET ING. : ".$sql_detalle_ingreso."<br>";

		$resp_detalle_ingreso=mysqli_query($enlaceCon, $sql_detalle_ingreso);
		$cantidad_bandera=$cant_unit_salida;
		$bandera=0;
		while($dat_detalle_ingreso=mysqli_fetch_array($resp_detalle_ingreso))
		{	$cod_ingreso_almacen=$dat_detalle_ingreso[0];
			$cantidad_restante=$dat_detalle_ingreso[1];
			if($bandera!=1)
			{
				if($cantidad_bandera>$cantidad_restante)
				{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso 
									values('$cod_salida_almacen','$cod_ingreso_almacen','$cod_material','$cantidad_restante')";
					$resp_salida_det_ingreso=mysqli_query($enlaceCon, $sql_salida_det_ingreso);
					$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
					$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where 
									cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material'";
					$resp_upd_cantidades=mysqli_query($enlaceCon, $upd_cantidades);
				}
				else
				{		$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$cod_salida_almacen','$cod_ingreso_almacen','$cod_material','$cantidad_bandera')";
						$resp_salida_det_ingreso=mysqli_query($enlaceCon, $sql_salida_det_ingreso);
						$cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
						$bandera=1;
						$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_a_actualizar 
										where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material'";
						$resp_upd_cantidades=mysqli_query($enlaceCon, $upd_cantidades);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
				}
			}
		}
		
		//fin	
	}
	
}

echo "OK MATERIAL..... $sqlMat";
?>