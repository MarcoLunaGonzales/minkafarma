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

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte Ventas x Vendedor
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";


$sql="select f.`codigo_funcionario`, concat(f.`paterno`,' ',f.`materno`,' ',f.`nombres`)as vendedor,
       sum(sd.monto_unitario) montoVenta
from `salida_almacenes` s,
     `salida_detalle_almacenes` sd, `funcionarios` f
where s.`cod_salida_almacenes` = sd.`cod_salida_almacen` and
      s.`fecha` BETWEEN '$fecha_iniconsulta' and
      '$fecha_finconsulta'      and
      s.`salida_anulada` = 0 and
      s.`cod_almacen` in (
                           select a.`cod_almacen`
                           from `almacenes` a
                           where a.`cod_ciudad` = '$rpt_territorio'
      ) and 
      s.`cod_chofer`=f.`codigo_funcionario` group by f.`codigo_funcionario`";		
$resp=mysql_query($sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>Vendedor</th>
<th>Monto Venta</th>
</tr>";

$totalVenta=0;
while($datos=mysql_fetch_array($resp)){	
	$codItem=$datos[0];
	$nombrePersona=$datos[1];
	$montoVenta=$datos[2];
	
	$montoPtr=number_format($montoVenta,2,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	echo "<tr>
	<td>$codItem</td>
	<td>$nombrePersona</td>
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
include("imprimirInc.php");
?>