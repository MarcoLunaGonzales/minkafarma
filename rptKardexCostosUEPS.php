<?php
require('estilos_reportes_almacencentral.php');
require('conexion.inc');
require('function_formatofecha.php');
require('function_comparafechas.php');
require('funciones.php');

$fecha_reporte=date("d/m/Y");

$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp_territorio=mysql_query($sql_nombre_territorio);
$dat_territorio=mysql_fetch_array($resp_territorio);
$nombre_territorio=$dat_territorio[0];
$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
$resp_nombre_almacen=mysql_query($sql_nombre_almacen);
$dat_almacen=mysql_fetch_array($resp_nombre_almacen);
$nombre_almacen=$dat_almacen[0];
if($tipo_item==1)
{	$nombre_tipoitem="Muestra Médica";
	$sql_item="select descripcion, presentacion from muestras_medicas where codigo='$rpt_item'";
}
else
{	$nombre_tipoitem="Material de Apoyo";
	$sql_item="select descripcion_material from material_apoyo where codigo_material='$rpt_item'";
}
$resp_item=mysql_query($sql_item);
$dat_item=mysql_fetch_array($resp_item);
$nombre_item="$dat_item[0] $dat_item[1]";
echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Kardex de Existencia Fisica UEPS<br>Territorio: 
<strong>$nombre_territorio</strong> Almacen: <strong>$nombre_almacen</strong> Fecha inicio: <strong>$fecha_ini</strong> Fecha final: 
<strong>$fecha_fin</strong>Item: <strong>$nombre_item</strong><br>$txt_reporte</th></tr></table>";

//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);
//aqui sacamos las existencias a una fecha
$sql="select sum(id.cantidad_unitaria) FROM ingreso_almacenes i, ingreso_detalle_almacenes id
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha<'$fecha_iniconsulta'";
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

echo "<center><br><table border='1' class='texto' cellspacing='0' width='100%'>";
echo "<tr class='textomini'>
<th>Fecha</th>
<th>Tipo</th>
<th>Nro. Ingreso/Salida</th>
<th>Cant.Entrada</th>
<th>V.U.</th>
<th>V.T.</th>
<th>Cant.Salida</th>
<th>V.U.</th>
<th>V.T.</th>
<th>Existencias</th>
<th>V.U.</th>
<th>V.T.</th>
</tr>";

$fechaPivote=$fecha_iniconsulta;

//insertamos los iniciales en un vector
$ii=0;
$sqlIni="select c.`cod_ingreso`, c.`cantidad`, c.`costo_unitario` from `costos_mes_pueps` c 
	where c.mes='$mesCosto' and c.`anio`='$anioCosto' and c.`fecha_ingreso`<'$fecha_iniconsulta' and c.cod_material='$rpt_item'";
$respIni=mysql_query($sqlIni);
while($datIni=mysql_fetch_array($respIni)){
	$codIngreso=$datIni[0];
	$cantidadIngreso=$datIni[1];
	$costoUnitario=$datIni[2];
	
	$vectorIng[$ii][0]=$codIngreso;
	$vectorIng[$ii][1]=$cantidadIngreso;
	$vectorIng[$ii][2]=$cantidadIngreso;
	$vectorIng[$ii][3]=$costoUnitario;
	$ii++;
}
//fin vector inicial

$sumaIngresos=0;
$valorTotalIngresos=0;
$sumaSalidas=0;
$valorTotalSalidas=0;

while($fechaPivote<=$fecha_finconsulta){
	
	$sqlVeriIng="select c.`cod_ingreso`,
       i.`nro_correlativo`,
       (select t.`nombre_tipoingreso` from `tipos_ingreso` t where t.`cod_tipoingreso` = i.cod_tipoingreso) as tipo_ingreso,
       c.`cantidad`, c.`costo_unitario`, (c.`cantidad`*c.`costo_unitario`) as valor, i.fecha
		from `costos_mes_pueps` c, `ingreso_almacenes` i WHERE c.`cod_ingreso` = i.`cod_ingreso_almacen` and 
		c.`fecha_ingreso` = '$fechaPivote' and c.`cod_material` = '$rpt_item'";
			
	$respVeriIng=mysql_query($sqlVeriIng);
	$nroFilasVeriIng=mysql_num_rows($respVeriIng);
	if($nroFilasVeriIng>0){
		while($datVeriIng=mysql_fetch_array($respVeriIng)){
			$codIngreso=$datVeriIng[0];
			$nroIngreso=$datVeriIng[1];
			$nombreTipoIng=$datVeriIng[2];
			$cantidadIngreso=$datVeriIng[3];
			$cantidadIngreso=redondear2($cantidadIngreso);
			$costoIngreso=$datVeriIng[4];
			$valorIngreso=$datVeriIng[5];
			$fechaIngreso=$datVeriIng[6];
			
			$sumaIngresos=$sumaIngresos+$cantidadIngreso;
			$valorTotalIngresos=$valorTotalIngresos+$valorIngreso;
			
			$vectorIng[$ii][0]=$codIngreso;
			$vectorIng[$ii][1]=$cantidadIngreso;
			$vectorIng[$ii][2]=$cantidadIngreso;
			$vectorIng[$ii][3]=$costoIngreso;
			$ii++;
			
			$cadTabla="<table border='0' class='texto' width='100%'>";
			for($jj=0;$jj<$ii;$jj++){
				$cantDetIng=$vectorIng[$jj][2];
				$costoDetIng=$vectorIng[$jj][3];
				$valorDetIng=$vectorIng[$jj][2]*$vectorIng[$jj][3];
				$cadTabla.="<tr><td width='33%'>$cantDetIng</td><td  width='33%'>$costoDetIng</td><td width='33%'>$valorDetIng</td></tr>";
			}
			$cadTabla.="</table>";
			
			echo "<tr class='textomini'>
			<td>$fechaIngreso</td>
			<td>Ingreso</td>
			<td>$nroIngreso</td>
			<td>$cantidadIngreso</td>
			<td>$costoIngreso</td>
			<td>$valorIngreso</td>
			<td>0</td>
			<td>0</td>
			<td>0</td>
			<td colspan='3'>$cadTabla</td>
			</tr>";
		}
	}
	$sqlVeriSalidas="select ss.`cod_salida_almacenes`, ss.`nro_correlativo`, ss.`fecha`, 
		(select t.`nombre_tiposalida` from `tipos_salida` t where t.`cod_tiposalida`=ss.cod_tiposalida) as tipo_salida, 
		s.`cantidad`, s.`costo`, (s.`cantidad`*s.`costo`) as valor, s.cod_ingreso 
		from `salida_ingreso_costo` s, `salida_almacenes` ss 
		where s.`cod_material` = '$rpt_item' and s.`fecha_salida` = '$fechaPivote' and 
		ss.`cod_salida_almacenes` = s.`cod_salida`";
	$respVeriSalidas=mysql_query($sqlVeriSalidas);
	while($datVeriSalidas=mysql_fetch_array($respVeriSalidas)){
		$codSalida=$datVeriSalidas[0];
		$nroSalida=$datVeriSalidas[1];
		$fechaSalida=$datVeriSalidas[2];
		$tipoSalida=$datVeriSalidas[3];
		$cantidadSalida=$datVeriSalidas[4];
		$cantidadSalida=redondear2($cantidadSalida);
		$costoSalida=$datVeriSalidas[5];
		$valorSalida=$datVeriSalidas[6];
		$codIngresoSalida=$datVeriSalidas[7];
		
		$sumaSalidas=$sumaSalidas+$cantidadSalida;
		$valorTotalSalidas=$valorTotalSalidas+$valorSalida;
		
		$cadTabla="<table border='0' class='texto' width='100%'>";
		for($jj=0;$jj<$ii;$jj++){
			$codIngVector=$vectorIng[$jj][0];
			if($codIngVector==$codIngresoSalida){
				$vectorIng[$jj][2]=$vectorIng[$jj][2]-$cantidadSalida;
				$cantDetIng=$vectorIng[$jj][2];
			}else{
				$cantDetIng=$vectorIng[$jj][2];
			}
			$costoDetIng=$vectorIng[$jj][3];
			$valorDetIng=$vectorIng[$jj][2]*$vectorIng[$jj][3];
			$cadTabla.="<tr><td width='33%'>$cantDetIng</td><td  width='33%'>$costoDetIng</td><td width='33%'>$valorDetIng</td></tr>";
		}
		$cadTabla.="</table>";
		
		echo "<tr class='textomini'>
		<td>$fechaSalida</td>
		<td>Salida</td>
		<td>$nroSalida</td>
		<td>0</td>
		<td>0</td>
		<td>0</td>
		<td>$cantidadSalida</td>
		<td>$costoSalida</td>
		<td>$valorSalida</td>
		<td colspan='3'>$cadTabla</td>
		</tr>";
	}
		
	$fechaPivote=date("Y-m-d", strtotime("$fechaPivote + 1 days")); 
}

echo "<tr><td align='center'>&nbsp;</td>
<td>&nbsp;</td>
<th align='center'>&nbsp;</th>
<th align='right'>$sumaIngresos</th>
<th align='right'>--</th>
<th align='right'>$valorTotalIngresos</th>
<th align='left'>$sumaSalidas</th>
<th align='right'>---</th>
<th align='right'>$valorTotalSalidas</th>
<th align='right'>&nbsp;</th>
<th align='right'>&nbsp;</th>
<th align='right'>&nbsp;</th>
</tr>";

echo "</table></center><br>";
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>