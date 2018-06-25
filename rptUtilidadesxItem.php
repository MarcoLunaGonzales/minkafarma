<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ver=$_GET['rpt_ver'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte Utilidades x Item
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="select m.`codigo_material`, m.`descripcion_material`, 
	sum(sd.monto_unitario)montoVenta, sum(sd.cantidad_unitaria), sum(sd.cantidad_unitaria*sd.costo_almacen)costo
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	group by m.`codigo_material` order by 3 desc;";
	
$resp=mysql_query($sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>Item</th>
<th>Cantidad</th>
<th>Monto Venta</th>
<th>Costo</th>
<th>Utilidades</th>
</tr>";

$totalVenta=0;
$totalCosto=0;
while($datos=mysql_fetch_array($resp)){	
	$codItem=$datos[0];
	$nombreItem=$datos[1];
	$montoVenta=$datos[2];
	$cantidad=$datos[3];
	$montoCosto=$datos[4];
	
	$montoPtr=number_format($montoVenta,2,".",",");
	$cantidadFormat=number_format($cantidad,2,".",",");
	
	$totalCosto=$totalCosto+$montoCosto;
	$montoCostoFormat=number_format($montoCosto,2,".",",");
	
	$utilidad=$montoVenta-$montoCosto;
	$utilidadFormat=number_format($utilidad,2,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	echo "<tr>
	<td>$codItem</td>
	<td>$nombreItem</td>
	<td>$cantidadFormat</td>
	<td>$montoPtr</td>
	<td>$montoCostoFormat</td>
	<td>$utilidadFormat</td>
	</tr>";
}
$totalPtr=number_format($totalVenta,2,".",",");
$totalCostoFormat=number_format($totalCosto,2,".",",");
$totalUtilidad=$totalVenta-$totalCosto;
$totalUtilidadFormat=number_format($totalUtilidad,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalPtr</td>
	<td>$totalCostoFormat</td>
	<td>$totalUtilidadFormat</td>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>