<?php
require('estilos_reportes_almacencentral.php');
require('conexion.inc');
require('function_formatofecha.php');
require('function_comparafechas.php');
require('funciones.php');

/*$fecha_reporte=date("d/m/Y");
$rptLote=$_GET["rptLote"];*/

$rpt_almacen=1000;

	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysql_query($sql_nombre_almacen);
	$dat_almacen=mysql_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$dat_almacen[0];
	echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Kardex de Existencia x Lote Valorado<br>Territorio: 
	<strong>$nombre_territorio</strong> Almacen: <strong>$nombre_almacen</strong> Fecha inicio: <strong>$fecha_ini</strong> Fecha final: 
	<strong>$fecha_fin</strong>
	<br>$txt_reporte</th></tr></table>";

	
$sqlAjustes="update `ingreso_detalle_almacenes` set 
	`ingreso_detalle_almacenes`.`costo_almacen`=0 
	where `ingreso_detalle_almacenes`.`cod_ingreso_almacen` in 
	(select `ingreso_almacenes`.`cod_ingreso_almacen` from `ingreso_almacenes` where 
	`ingreso_almacenes`.`cod_tipoingreso`=1002 and `ingreso_almacenes`.`cod_almacen`=$rpt_almacen)";
$respAjustes=mysql_query($sqlAjustes);

$sqlLotes="select distinct(id.`cod_material`)  from `ingreso_almacenes` i, `ingreso_detalle_almacenes` id
	where i.`cod_ingreso_almacen`=id.`cod_ingreso_almacen` and 
	i.`cod_almacen`=$rpt_almacen and i.`ingreso_anulado`=0 and id.cod_material=498
	order by 1";

	//and id.lote='4FZ01'
	$respLotes=mysql_query($sqlLotes);
while($datLotes=mysql_fetch_array($respLotes)){
	
	$rpt_item=$datLotes[0];
	
	echo "<center><h3>$rptLote</h3></center>";
	//desde esta parte viene el reporte en si
	$fecha_iniconsulta="2010-01-01";
	$fecha_finconsulta="2014-05-30";
	//aqui sacamos las existencias a una fecha
	$sql="select sum(id.cantidad_unitaria) FROM ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha<'$fecha_iniconsulta'";
	
	//echo $sql."<br>";
	
	$resp=mysql_query($sql);
	$dat_existencias_afecha=mysql_fetch_array($resp);
	$cantidad_ingresada_afecha=$dat_existencias_afecha[0];
	$sql_salidas_afecha="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha<'$fecha_iniconsulta'";
	$resp_salidas_afecha=mysql_query($sql_salidas_afecha);
	$dat_salidas_afecha=mysql_fetch_array($resp_salidas_afecha);
	$cantidad_sacada_afecha=$dat_salidas_afecha[0];
	$cantidad_inicial_kardex=$cantidad_ingresada_afecha-$cantidad_sacada_afecha;
	
	list($anio, $mes, $dia) = split('-', $fecha_iniconsulta);
	$mesCosto=$mes-1;
	$anioCosto=$anio;
	if($mesCosto==0){
		$mesCosto=12;
		$anioCosto=$anio-1;
	}
	
	$sqlCostoAnterior="select c.`costo_unitario` from `costo_promedio_mes` c where c.`cod_almacen`='$rpt_almacen' and 
		c.mes='$mesCosto' and c.`anio`='$anioCosto' and c.`cod_material`='$rpt_item'";
	
	$respCostoAnterior=mysql_query($sqlCostoAnterior);
	$nroFilasCostoAnterior=mysql_num_rows($respCostoAnterior);
	if($nroFilasCostoAnterior==1){
		$costoUnitarioAnteriorItem=mysql_result($respCostoAnterior,0,0);
	}else{
		$costoUnitarioAnteriorItem=0;
	}
	$valorAnterior=$costoUnitarioAnteriorItem*$cantidad_inicial_kardex;
	//fin existencias a una fecha
	
	echo "<br><table class='texto' align='center'><tr><th>Existencia a fecha inicio reporte:  $cantidad_inicial_kardex</th>
	<th>Valor en Costos a inicio reporte:  $valorAnterior</th></tr></table>";
	
	echo "<center><br><table border='1' class='textomini' cellspacing='0' width='100%'>";
	echo "<tr class='textomini'>
	<th>Fecha</th>
	<th>Transaccion</th>
	<th>Nro.</th>
	<th>Entrada</th>
	<th>Salida</th>
	<th>Saldo</th>
	<th>Costo</th>
	<th>Debe</th>
	<th>Haber</th>
	<th>Saldo</th>
	<th>Tipo</th>
	<th>Observaciones</th>
	</tr>";
	
	$sql_fechas_ingresos="select distinct(i.fecha) from ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha>='$fecha_iniconsulta' and i.fecha<='$fecha_finconsulta' 
	order by i.fecha";
	$resp_fechas_ingresos=mysql_query($sql_fechas_ingresos);
	$i=1;
	while($dat_fechas_ingresos=mysql_fetch_array($resp_fechas_ingresos))
	{	$vector_fechas_ingresos[$i]=$dat_fechas_ingresos[0];
		$i++;
	}
	$sql_fechas_salidas="select distinct(s.fecha) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' 
	order by s.fecha";
	
	//echo $sql_fechas_salidas;
	
	$resp_fechas_salidas=mysql_query($sql_fechas_salidas);
	$j=1;
	while($dat_fechas_salidas=mysql_fetch_array($resp_fechas_salidas))
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
	$valor_kardex=$valorAnterior;
	
	$suma_ingresos=0;
	$suma_salidas=0;
	$suma_valorIngresos=0;
	$suma_valorSalidas=0;
	
	for($indice=1;$indice<=$zz;$indice++)
	{	$fecha_consulta=$vector_final_fechas[$indice];
		//hacemos la consulta para ingresos
		$sql_ingresos="select i.nro_correlativo, id.cantidad_unitaria, i.observaciones, ti.nombre_tipoingreso, id.costo_almacen, i.cod_ingreso_almacen
		from ingreso_almacenes i, ingreso_detalle_almacenes id, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
		i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha='$fecha_consulta'";
		
		$resp_ingresos=mysql_query($sql_ingresos);
		while($dat_ingresos=mysql_fetch_array($resp_ingresos))
		{	$nro_ingreso=$dat_ingresos[0];
			$cantidad_ingreso=$dat_ingresos[1];
			$obs_ingreso=$dat_ingresos[2];
			$nombre_ingreso=$dat_ingresos[3];
			$costoIngreso=$dat_ingresos[4];
			$codIngresoAlmacen=$dat_ingresos[5];
			
			if($nombre_ingreso=="POR AJUSTE"){
				$sqlUpd="update ingreso_detalle_almacenes set costo_almacen='$nuevoCostoPromedio' where 
						cod_ingreso_almacen='$codIngresoAlmacen' and cod_material='$rpt_item'";
				//echo $sqlUpd."nuevo costo<br><br> $nuevoCostoPromedio $valor_kardex $cantidad_kardex<br><br>";
				$respUpd=mysql_query($sqlUpd);
				$suma_ingresos=$suma_ingresos+$cantidad_ingreso;
				$cantidad_kardex=$cantidad_kardex+$cantidad_ingreso;
				
				$valorIngreso=$cantidad_ingreso*$nuevoCostoPromedio;
				
				$suma_valorIngresos=$suma_valorIngresos+$valorIngreso;
				$valor_kardex=$valor_kardex+$valorIngreso;
				$nuevoCostoPromedio=$valor_kardex/$cantidad_kardex;
			}else{
				$suma_ingresos=$suma_ingresos+$cantidad_ingreso;
				$cantidad_kardex=$cantidad_kardex+$cantidad_ingreso;
				$valorIngreso=$cantidad_ingreso*$costoIngreso;
				$suma_valorIngresos=$suma_valorIngresos+$valorIngreso;
				$valor_kardex=$valor_kardex+$valorIngreso;
				$nuevoCostoPromedio=$valor_kardex/$cantidad_kardex;
			}
			
			
			
			/*if($nombre_ingreso=="POR AJUSTE"){
				$sqlUpd="update ingreso_detalle_almacenes set costo_almacen='$nuevoCostoPromedio' where 
						cod_ingreso_almacen='$codIngresoAlmacen' and cod_material='$rpt_item' and lote='$rptLote'";
				echo $sqlUpd."nuevo costo<br><br> $nuevoCostoPromedio $valor_kardex $cantidad_kardex<br><br>";
				$respUpd=mysql_query($sqlUpd);
			}*/
			//echo $nuevoCostoPromedio."<br>";
			
			$cantIngFormt=redondear2($cantidad_ingreso);
			$cantKardexFormt=redondear2($cantidad_kardex);
			$nuevoCostoFormt=redondear2($nuevoCostoPromedio);
			$valorIngFormt=redondear2($valorIngreso);
			$valorKardexFormt=redondear2($valor_kardex);
			
			echo "<tr><td align='center'>$fecha_consulta</td>
			<td>Ingreso</td>
			<td align='center'>$nro_ingreso</td>
			<td align='right'>$cantIngFormt</td>
			<td align='right'>0</td>
			<td align='right'>$cantKardexFormt</td>
			<td align='center'>$nuevoCostoFormt</td>
			<td align='right'>$valorIngFormt</td>
			<td align='right'>0</td>
			<td align='right'>$valorKardexFormt</td>
			<td align='left'>$nombre_ingreso</td>
			<td>&nbsp;$obs_ingreso</td>
			</tr>";
		}
		//hacemos la consulta para salidas
		$sql_salidas="select s.nro_correlativo, sd.cantidad_unitaria, ts.nombre_tiposalida, 
		s.observaciones, s.territorio_destino, s.cod_salida_almacenes, sd.costo_almacen
		from salida_almacenes s, salida_detalle_almacenes sd, tipos_salida ts
		where s.cod_tiposalida=ts.cod_tiposalida and s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
		s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha='$fecha_consulta'";
		
		//echo $sql_salidas;
		
		$resp_salidas=mysql_query($sql_salidas);
		while($dat_salidas=mysql_fetch_array($resp_salidas))
		{	$nro_salida=$dat_salidas[0];
			$cantidad_salida=$dat_salidas[1];
			$nombre_salida=$dat_salidas[2];
			$obs_salida=$dat_salidas[3];
			$cod_salida=$dat_salidas[5];
			$costoSalida=$dat_salidas[6];
			$territorio_destino=$dat_salidas[4];
				$sql_nombre_territorio_destino="select descripcion from ciudades where cod_ciudad='$territorio_destino'";
				$resp_nombre_territorio_destino=mysql_query($sql_nombre_territorio_destino);
				$dat_nombre_territorio_destino=mysql_fetch_array($resp_nombre_territorio_destino);
				$nombre_territorio_destino=$dat_nombre_territorio_destino[0];
			$cantidad_kardex=$cantidad_kardex-$cantidad_salida;
			$valor_kardex=$valor_kardex-($cantidad_salida*$costoSalida);
			$suma_salidas=$suma_salidas+$cantidad_salida;
			$valorSalida=$costoSalida*$cantidad_salida;
			$suma_valorSalidas=$suma_valorSalidas+$valorSalida;
			
			$sqlUpd="update salida_detalle_almacenes set costo_almacen='$nuevoCostoPromedio' where 
					cod_salida_almacen='$cod_salida' and cod_material='$rpt_item'";
						
			$respUpd=mysql_query($sqlUpd);
			
			
			$cantSalidaFormt=redondear2($cantidad_salida);
			$cantKardexFormt=redondear2($cantidad_kardex);
			$costoSalidaFormt=redondear2($costoSalida);
			$valorSalidaFormt=redondear2($valorSalida);
			$valorKardexFormt=redondear2($valor_kardex);
			
			echo "<tr><td align='center'>$fecha_consulta</td>
			<td>Salida</td>
			<td align='center'>$nro_salida</td>
			<td align='right'>0</td>
			<td align='right'>$cantSalidaFormt</td>
			<td align='right'>$cantKardexFormt</td>
			<td align='center'>$costoSalidaFormt</td>
			<td align='right'>0</td>
			<td align='right'>$valorSalidaFormt</td>
			<td align='right'>$valorKardexFormt</td>
			<td align='left'>$nombre_salida</td>
			<td>&nbsp;$obs_salida</td></tr>";
		}
	}
	$suma_saldo_final=$suma_ingresos-$suma_salidas+$cantidad_inicial_kardex;
	$saldoFinalValor=$suma_valorIngresos-$suma_valorSalidas+$valorAnterior;
	
	
	$sumaIngFormt=redondear2($suma_ingresos);
	$sumaSalFormt=redondear2($suma_salidas);
	$sumaSaldoFormt=redondear2($suma_saldo_final);
	$sumaValIngFormt=redondear2($suma_valorIngresos);
	$sumaValSalFormt=redondear2($suma_valorSalidas);
	$saldoFinalValorFormt=redondear2($saldoFinalValor);
	
	echo "<tr><td align='center'>&nbsp;</td><td>&nbsp;</td>
	<td align='center'>&nbsp;</td>
	<th align='right'>$sumaIngFormt</td>
	<th align='right'>$sumaSalFormt</td>
	<th align='right'>$sumaSaldoFormt</td>
	<td align='left'>&nbsp;</td>
	<th align='right'>$sumaValIngFormt</td>
	<th align='right'>$sumaValSalFormt</td>
	<th align='right'>$saldoFinalValorFormt</td>
	</tr>";
	echo "</table></center><br>";

}	
?>