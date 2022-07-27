<?php
require('function_formatofecha.php');
require('function_comparafechas.php');

function recalculaCostos($enlaceCon,$codigoItem,$rpt_almacen){
	$fecha_iniconsulta="2000-01-01";
	$fecha_finconsulta="2030-12-31";

	$sqlMateriales="select m.`codigo_material`, m.`descripcion_material` from `material_apoyo` m where m.`codigo_material` in ($codigoItem)";
	$respMateriales=mysqli_query($enlaceCon,$sqlMateriales);
	while($datMateriales=mysqli_fetch_array($respMateriales)){

		$codigoItem=$datMateriales[0];
		$nombreItem=$datMateriales[1];
		
		//aqui sacamos las existencias a una fecha
		$sql="select sum(id.cantidad_unitaria) FROM ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
		i.ingreso_anulado=0 and id.cod_material='$codigoItem' and i.fecha<'$fecha_iniconsulta'";
		$resp=mysqli_query($enlaceCon,$sql);
		$dat_existencias_afecha=mysqli_fetch_array($resp);
		$cantidad_ingresada_afecha=$dat_existencias_afecha[0];
		$sql_salidas_afecha="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
		where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
		s.salida_anulada=0 and sd.cod_material='$codigoItem' and s.fecha<'$fecha_iniconsulta'";
		$resp_salidas_afecha=mysqli_query($enlaceCon,$sql_salidas_afecha);
		$dat_salidas_afecha=mysqli_fetch_array($resp_salidas_afecha);
		$cantidad_sacada_afecha=$dat_salidas_afecha[0];
		$cantidad_inicial_kardex=$cantidad_ingresada_afecha-$cantidad_sacada_afecha;
			//sacamos el costo promedio del mes anterior
			list($anio, $mes, $dia) = explode('-', $fecha_iniconsulta);
			$mesCosto=$mes-1;
			$anioCosto=$anio;
			if($mesCosto==0){
				$mesCosto=12;
				$anioCosto=$anio-1;
			}
			
			$sqlCostoAnterior="select c.`costo_unitario` from `costo_promedio_mes` c where c.`cod_almacen`='$rpt_almacen' and 
				c.mes='$mesCosto' and c.`anio`='$anioCosto' and c.`cod_material`='$codigoItem'";
			
			$respCostoAnterior=mysqli_query($enlaceCon,$sqlCostoAnterior);
			$nroFilasCostoAnterior=mysqli_num_rows($respCostoAnterior);
			if($nroFilasCostoAnterior==1){
				$datCostoAnterior=mysqli_fetch_array($respCostoAnterior);
				$costoUnitarioAnteriorItem=$datCostoAnterior[0];
				//$costoUnitarioAnteriorItem=mysql_result($respCostoAnterior,0,0);
			}else{
				$costoUnitarioAnteriorItem=0;
			}
			
			$valorAnteriorItem=$costoUnitarioAnteriorItem*$cantidad_inicial_kardex;
			
			$nuevoCostoPromedio=$costoUnitarioAnteriorItem;
			
			//fin sacar costo promedio
		//FIN EXISTENCIAS

		$sql_fechas_ingresos="select distinct(i.fecha) from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
		i.ingreso_anulado=0 and id.cod_material='$codigoItem' and i.fecha>='$fecha_iniconsulta' and i.fecha<='$fecha_finconsulta' order by i.fecha";
		$resp_fechas_ingresos=mysqli_query($enlaceCon,$sql_fechas_ingresos);
		$i=1;
		while($dat_fechas_ingresos=mysqli_fetch_array($resp_fechas_ingresos))
		{	$vector_fechas_ingresos[$i]=$dat_fechas_ingresos[0];
			$i++;
		}
		$sql_fechas_salidas="select distinct(s.fecha) from salida_almacenes s, salida_detalle_almacenes sd
		where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
		s.salida_anulada=0 and sd.cod_material='$codigoItem' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' order by s.fecha";
		$resp_fechas_salidas=mysqli_query($enlaceCon,$sql_fechas_salidas);
		$j=1;
		while($dat_fechas_salidas=mysqli_fetch_array($resp_fechas_salidas))
		{	$vector_fechas_salidas[$j]=$dat_fechas_salidas[0];
			$j++;
		}
		$i=$i-1;
		$j=$j-1;
		$ii=1;
		$jj=1;
		$zz=1;
		while($ii<=$i and $jj<=$j)
		{	$fecha_ingresos=$vector_fechas_ingresos[$ii];
			$fecha_salidas=$vector_fechas_salidas[$jj];
			//echo "resultado".$fecha_ingresos."-".$fecha_salidas;
			//echo "resultado de comparaFechas".compara_fechas($fecha_ingresos,$fecha_salidas);
			if(compara_fechas($fecha_ingresos,$fecha_salidas)<0)
			{	$vector_final_fechas[$zz]=$fecha_ingresos;
				$ii++;
			}
			if(compara_fechas($fecha_ingresos,$fecha_salidas)==0)
			{	$vector_final_fechas[$zz]=$fecha_ingresos;
				$ii++;
				$jj++;
			}
			if(compara_fechas($fecha_ingresos,$fecha_salidas)>0)
			{	$vector_final_fechas[$zz]=$fecha_salidas;
				$jj++;
			}

			$zz++;
		}
		if($ii==$i+1)
		{	for($kk=$jj;$kk<=$j;$kk++)
			{	$vector_final_fechas[$zz]=$vector_fechas_salidas[$kk];
				$zz++;
			}
		}
		if($jj==$j+1)
		{	for($kk=$ii;$kk<=$i;$kk++)
			{	$vector_final_fechas[$zz]=$vector_fechas_ingresos[$kk];
				$zz++;
			}
		}
		$cantidad_kardex=$cantidad_inicial_kardex;
		$valor_kardex=$valorAnteriorItem;
		
		$suma_ingresos=0;
		$suma_salidas=0;
		echo "<table>";
		for($indice=1;$indice<=$zz;$indice++)
		{	$fecha_consulta=$vector_final_fechas[$indice];
			//hacemos la consulta para ingresos
			$sql_ingresos="select i.nro_correlativo, id.cantidad_unitaria, i.observaciones, ti.nombre_tipoingreso, 
			id.precio_neto, ti.cod_tipoingreso, i.cod_ingreso_almacen
			from ingreso_almacenes i, ingreso_detalle_almacenes id, tipos_ingreso ti
			where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
			i.ingreso_anulado=0 and id.cod_material='$codigoItem' and i.fecha='$fecha_consulta'";
			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
			while($dat_ingresos=mysqli_fetch_array($resp_ingresos))
			{	$nro_ingreso=$dat_ingresos[0];
				$cantidad_ingreso=$dat_ingresos[1];
				$obs_ingreso=$dat_ingresos[2];
				$nombre_ingreso=$dat_ingresos[3];
				$precioNetoIngreso=$dat_ingresos[4];
				$codTipoIngreso=$dat_ingresos[5];
				$codIngresoAlmacen=$dat_ingresos[6];
				
				$suma_ingresos=$suma_ingresos+$cantidad_ingreso;
				$cantidad_kardex=$cantidad_kardex+$cantidad_ingreso;
				
				if($codTipoIngreso!=1030){
					$valorNetoIngreso=$precioNetoIngreso*$cantidad_ingreso;	
					$nuevoCostoPromedio=($valorNetoIngreso+$valor_kardex)/$cantidad_kardex;
					
					/*echo "<br>valor ingreso".$valorNetoIngreso;
					echo "<br>valor anterior".$valor_kardex;
					echo "<br>cantidad kardex".$cantidad_kardex;*/
					
					$sqlUpdCosto="update ingreso_detalle_almacenes set costo_promedio='$nuevoCostoPromedio', costo_almacen='$precioNetoIngreso' where 
						cod_ingreso_almacen='$codIngresoAlmacen' and cod_material='$codigoItem'";
					$respUpdCosto=mysqli_query($enlaceCon,$sqlUpdCosto);
				}
				if($codTipoIngreso==1030){
					$valorNetoIngreso=$nuevoCostoPromedio*$cantidad_ingreso;	
					$sqlUpdCosto="update ingreso_detalle_almacenes set costo_promedio='$nuevoCostoPromedio' where 
						cod_ingreso_almacen='$codIngresoAlmacen' and cod_material='$codigoItem'";
					$respUpdCosto=mysqli_query($enlaceCon,$sqlUpdCosto);
				}
				
				$valor_kardex=$valor_kardex+$valorNetoIngreso;
			}
			//hacemos la consulta para salidas
			$sql_salidas="select s.nro_correlativo, sd.cantidad_unitaria, ts.nombre_tiposalida, s.observaciones, 
				s.territorio_destino, s.cod_salida_almacenes, ts.cod_tiposalida
			from salida_almacenes s, salida_detalle_almacenes sd, tipos_salida ts
			where s.cod_tiposalida=ts.cod_tiposalida and s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
			s.salida_anulada=0 and sd.cod_material='$codigoItem' and s.fecha='$fecha_consulta'";
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			while($dat_salidas=mysqli_fetch_array($resp_salidas))
			{	$nro_salida=$dat_salidas[0];
				$cantidad_salida=$dat_salidas[1];
				$nombre_salida=$dat_salidas[2];
				$obs_salida=$dat_salidas[3];
				$territorio_destino=$dat_salidas[4];
				$cod_salida=$dat_salidas[5];
				$codTipoSalida=$dat_salidas[6];
				$cantidad_kardex=$cantidad_kardex-$cantidad_salida;
				$suma_salidas=$suma_salidas+$cantidad_salida;
				
				$valor_kardex=$valor_kardex-($cantidad_salida*$nuevoCostoPromedio);
				
				$sqlUpd="update salida_detalle_almacenes set costo_almacen='$nuevoCostoPromedio' where 
					cod_salida_almacen='$cod_salida' and cod_material='$codigoItem'"; 
				$respUpd=mysqli_query($enlaceCon,$sqlUpd);
				
				if($codTipoSalida==1030){
					//costeamos el ingreso 
					$sqlIngCambioItem="select i.`cod_ingreso_almacen` from `ingreso_almacenes` i where i.`cod_salida_almacen`=$cod_salida";
					$respIngCambioItem=mysqli_query($enlaceCon,$sqlIngCambioItem);
					$nroFilasIngCambioItem=mysqli_num_rows($respIngCambioItem);
					if($nroFilasIngCambioItem==1){
						$datIngCambioItem=mysqli_fetch_array($respIngCambioItem);
						$codIngresoCambio=$datIngCambioItem[0];
						//codIngresoCambio=mysql_result($respIngCambioItem,0,0);
						$sqlUpdCosto="update ingreso_detalle_almacenes set costo_almacen='$nuevoCostoPromedio' where
							cod_ingreso_almacen='$codIngresoCambio'";
						$respUpdCosto=mysqli_query($enlaceCon,$sqlUpdCosto);
					}
					//fin costear ingreso por cambio de item
				}
			}
		}
		$suma_saldo_final=$suma_ingresos-$suma_salidas+$cantidad_inicial_kardex;	
		
		$sqlDel="delete from costo_promedio_mes where cod_material='$codigoItem' and mes='$mes' and anio='$anio'";
		$respDel=mysqli_query($enlaceCon,$sqlDel);
		
		$valorInventario=$suma_saldo_final+$nuevoCostoPromedio;
		
		$sqlInsert="insert into costo_promedio_mes values('$rpt_almacen', '$anio', '$mes', '$codigoItem', '$suma_saldo_final', '$nuevoCostoPromedio', '$valorInventario')";
		$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	}
	return(1);
}

?>