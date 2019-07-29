<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);

$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Ventas x Documento
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_salida_almacenes
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";

$sql.=" order by s.fecha, s.nro_correlativo";

$resp=mysql_query($sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Documento</th>
<th>Monto</th>
<th>
	<table width='100%'>
	<tr>
		<th width='50%'>Item</th>
		<th width='25%'>Cantidad</th>
		<th width='25%'>Monto</th>
	</tr>
	</table>
</th>
</tr>";

$totalVenta=0;
while($datos=mysql_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$codSalida=$datos[7];
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	
	$sqlX="select m.`codigo_material`, m.`descripcion_material`, 
	sum(sd.monto_unitario)montoVenta, sum(sd.cantidad_unitaria), sd.orden_detalle
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio') and 
	s.cod_salida_almacenes='$codSalida'
	group by m.`codigo_material`, sd.orden_detalle order by 5 desc";
	
	$respX=mysql_query($sqlX);

	$tablaDetalle="<table width='100%'>";
	
	$totalVentaX=0;
	while($datosX=mysql_fetch_array($respX)){	
		$codItem=$datosX[0];
		$nombreItem=$datosX[1];
		$montoVenta=$datosX[2];
		$cantidad=$datosX[3];
		
		$montoPtr=number_format($montoVenta,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
		
		$totalVentaX=$totalVentaX+$montoVenta;
		$tablaDetalle.="<tr>
		<td>$nombreItem</td>
		<td>$cantidadFormat</td>
		<td>$montoPtr</td>		
		</tr>";
	}
	$totalPtr=number_format($totalVentaX,2,".",",");
	$tablaDetalle.="<tr>
		<td>&nbsp;</td>
		<th>Total:</th>
		<th>$totalPtr</th>
	<tr></table>";

	
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$datosDoc</td>
	<td>$montoVentaFormat</td>
	<td>$tablaDetalle</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<th>Total Reporte</th>
	<th>$totalVentaFormat</th>
</tr>";
echo "</table></br>";


include("imprimirInc.php");
?>