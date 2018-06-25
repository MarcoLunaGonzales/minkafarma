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

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte de Costo de Venta x Item
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

	
$sql="select m.`codigo_material`, m.`descripcion_material`, 
	sum(sd.`cantidad_unitaria` * sd.`costo_almacen`)montoVenta from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and s.`cod_tiposalida`=1001 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	group by m.`codigo_material` order by 3 desc;";
	
$resp=mysql_query($sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>Item</th>
<th>Monto Venta</th>
</tr>";

$totalVenta=0;
while($datos=mysql_fetch_array($resp)){	
	$codItem=$datos[0];
	$nombreItem=$datos[1];
	$montoVenta=$datos[2];
	
	$montoPtr=number_format($montoVenta,2,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	echo "<tr>
	<td>$codItem</td>
	<td>$nombreItem</td>
	<td>$montoPtr</td>
	</tr>";
}
$totalPtr=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalPtr</td>
<tr>";

echo "</table>";
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>