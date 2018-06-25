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

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte Cobranzas
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="select cb.`cod_cobranza`, cb.`fecha_cobranza`, cb.`nro_doc`, c.`nombre_cliente`,  s.`nro_correlativo`, cb.`monto_cobranza` , cb.`observaciones`
	from `cobranzas` cb, `clientes` c, `salida_almacenes` s
	where cb.`cod_cliente`=c.`cod_cliente` and 
	cb.`fecha_cobranza` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and
	cb.`cod_venta`=s.`cod_salida_almacenes` order by 1";
	
$resp=mysql_query($sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th>Nro.Cobranza</th>
<th>Nro.Doc.Cobranza</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Nota Venta</th>
<th>Observaciones</th>
<th>Monto Cobranza</th>
</tr>";

$totalCobro=0;
while($datos=mysql_fetch_array($resp)){	
	$codCobro=$datos[0];
	$fecha=$datos[1];
	$nroCobro=$datos[2];
	$cliente=$datos[3];
	$nroVenta=$datos[4];
	$montoCobro=$datos[5];
	$montoCobro=redondear2($montoCobro);
	$obs=$datos[6];
	
	$totalCobro=$totalCobro+$montoCobro;
	$totalCobro=redondear2($totalCobro);
	
	echo "<tr>
	<td>$codCobro</td>
	<td>$nroCobro</td>
	<td>$fecha</td>
	<td>$cliente</td>
	<td>$nroVenta</td>
	<td>$obs</td>
	<td>$montoCobro</td>
	</tr>";
}
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalCobro</td>
</tr>";

echo "</table>";
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>