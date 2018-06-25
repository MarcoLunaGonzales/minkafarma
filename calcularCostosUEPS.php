<?php
require('conexion.inc');
require("estilos_administracion.inc");
require('function_formatofecha.php');
require('function_comparafechas.php');

$fecha_reporte=date("d/m/Y");
$rpt_almacen=$_GET['rpt_almacen'];

$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);

//sacamos el costo promedio del mes anterior
list($anioCosto, $mesCosto, $diaCosto) = split('-', $fecha_iniconsulta);
$mesAnteriorCosto=$mesCosto-1;
$anioAnteriorCosto=$anioCosto;
if($mesAnteriorCosto==0){
	$mesAnteriorCosto=12;
	$anioAnteriorCosto=$anioCosto-1;
}

echo "<table align='center' class='textotit'><tr><th>Proceso de Costeo de Materiales UEPS</th></tr></table>";


$sqlMateriales="select m.`codigo_material`, m.`descripcion_material` from `material_apoyo` m where m.`codigo_material`=96";
$respMateriales=mysql_query($sqlMateriales);
while($datMateriales=mysql_fetch_array($respMateriales)){

	$codigoItem=$datMateriales[0];
	$nombreItem=$datMateriales[1];
	
	//INSERTAMOS EN LA TABLA DE INGRESOS COSTOS LO DEL MES DE EJECUCION
	$sqlCostos="select c.cod_ingreso, c.`fecha_ingreso`, c.`cantidad`, c.`cantidad_restante`, c.`costo_unitario` 
		from `costos_mes_pueps` c where c.mes='$mesAnteriorCosto' and c.`anio`='$anioAnteriorCosto'
		and c.`cod_material`='$codigoItem'";
	$respCostos=mysql_query($sqlCostos);
	while($datCostos=mysql_fetch_array($respCostos)){
		$codIngreso=$datCostos[0];
		$fechaIngreso=$datCostos[1];
		$cantidad=$datCostos[2];
		$cantidadRestante=$datCostos[3];
		$costoUnitario=$datCostos[4];
		$sqlDelCosto="delete costos_mes_pueps where c.mes='$mesCosto' and c.`anio`='$anioCosto' and c.`cod_material`='$codigoItem'";
		$respDelCosto=mysql_query($sqlDelCosto);
		
		$sqlInsertCosto="INSERT INTO `costos_mes_pueps` (`anio`,`mes`,`cod_almacen`,`cod_material`,`cod_ingreso`,`fecha_ingreso`,`cantidad`,
			`cantidad_restante`,`costo_unitario`) VALUES 
			('$anioCosto','$mesCosto','$rpt_almacen','$codigoItem','$codIngreso','$fechaIngreso','$cantidad','$cantidadRestante','$costoUnitario')";
		$respInsertCosto=mysql_query($sqlInsertCosto);
		
	}
	
	$sqlIng="select i.`cod_ingreso_almacen`, i.`fecha`, id.`cantidad_unitaria`, id.`costo_almacen` 
		from `ingreso_almacenes` i, `ingreso_detalle_almacenes` id 
		where i.`cod_ingreso_almacen`=id.`cod_ingreso_almacen` and 
		i.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and i.`ingreso_anulado`=0 and id.`cod_material`=$codigoItem";
	$respIng=mysql_query($sqlIng);
	while($datIng=mysql_fetch_array($respIng)){
		$codIngreso=$datIng[0];
		$fechaIngreso=$datIng[1];
		$cantidad=$datIng[2];
		$costoUnitario=$datIng[3];

		$sqlInsertCosto="INSERT INTO `costos_mes_pueps` (`anio`,`mes`,`cod_almacen`,`cod_material`,`cod_ingreso`,`fecha_ingreso`,`cantidad`,
			`cantidad_restante`,`costo_unitario`) VALUES 
			('$anioCosto','$mesCosto','$rpt_almacen','$codigoItem','$codIngreso','$fechaIngreso','$cantidad','$cantidad','$costoUnitario')";
		$respInsertCosto=mysql_query($sqlInsertCosto);
		
	}
	
	//FIN INSERTAR TABLA DE INGRESOS
	
	
	//INICIO RECALCULO SALIDAS DETALLE INGRESO COSTOS
	$sql_salida_detalle="select sd.cod_salida_almacen, sd.cod_material, sd.cantidad_unitaria, s.fecha
		from salida_detalle_almacenes sd, salida_almacenes s
						where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' 
						and s.salida_anulada=0 and sd.cod_material='$codigoItem' and s.fecha between '$fecha_iniconsulta' and '$fecha_finconsulta'";
							
	$resp_salida_detalle=mysql_query($sql_salida_detalle);
	while($dat_salida_detalle=mysql_fetch_array($resp_salida_detalle))
	{	$cod_salida_almacen=$dat_salida_detalle[0];
		echo $cod_salida_almacen."<br>";
		$cod_material=$dat_salida_detalle[1];
		$cant_unit_salida=$dat_salida_detalle[2];
		$fechaSalida=$dat_salida_detalle[3];
		//inicio		
		$sql_detalle_ingreso="select c.`cod_ingreso`, c.`cantidad_restante`, c.costo_unitario from `costos_mes_pueps` c 
			WHERE c.`mes`='$mesCosto' and c.`anio`='$anioCosto' and c.`cod_material`='$codigoItem' and 
			c.cantidad_restante>0 order by c.`fecha_ingreso` desc";
			
		echo $sql_detalle_ingreso;

		
		$resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
		$cantidad_bandera=$cant_unit_salida;
		$bandera=0;
		
		while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
		{	$cod_ingreso_almacen=$dat_detalle_ingreso[0];
			$cantidad_restante=$dat_detalle_ingreso[1];
			$costoUnitarioIngreso=$dat_detalle_ingreso[2];
			
			if($bandera!=1)
			{
				if($cantidad_bandera>$cantidad_restante)
				{	$sql_salida_det_ingreso="insert into salida_ingreso_costo 
									values('$cod_salida_almacen','$cod_ingreso_almacen','$codigoItem',
									'$fechaSalida','$cantidad_restante','$costoUnitarioIngreso')";
					
					$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
					$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
					$upd_cantidades="update costos_mes_pueps set cantidad_restante=0 where 
									cod_ingreso='$cod_ingreso_almacen' and cod_material='$codigoItem' and 
									mes='$mesCosto' and anio='$anioCosto'";					
					$resp_upd_cantidades=mysql_query($upd_cantidades);
				}
				else
				{		$sql_salida_det_ingreso="insert into salida_ingreso_costo values('$cod_salida_almacen','$cod_ingreso_almacen',
												'$codigoItem','$fechaSalida','$cantidad_bandera','$costoUnitarioIngreso')";
						$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
						$cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;

						echo "<br>".$sql_salida_det_ingreso;
						
						$bandera=1;
						$upd_cantidades="update costos_mes_pueps set cantidad_restante=$cantidad_a_actualizar 
										where cod_ingreso='$cod_ingreso_almacen' and cod_material='$codigoItem' and 
									mes='$mesCosto' and anio='$anioCosto'";
						$resp_upd_cantidades=mysql_query($upd_cantidades);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
				}
			}
		}	
	}
//FIN RECALCULO SALIDAS DETALLE INGRESO COSTOS
	
}
?>