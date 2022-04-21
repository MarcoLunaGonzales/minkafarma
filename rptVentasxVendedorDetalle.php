<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_territorio=$_GET['rpt_territorio'];
$rpt_persona=$_GET['rpt_persona'];
$rpt_grupo=$_GET['rpt_grupo'];
$nombreGrupos=nombreGrupo($rpt_grupo);
//$arrayRptGrupo=implode(',',$rpt_grupo);

//echo $rpt_grupo;

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<h1>Reporte Ventas x Vendedor Detallado</h1>
	<h2>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte
	<br>Grupos: $nombreGrupos</h2>";
	
$sql="select s.fecha, (select t.abreviatura from tipos_docs t where t.codigo=s.cod_tipo_doc), s.nro_correlativo, 
(select cli.nombre_cliente from clientes cli where cli.cod_cliente=s.cod_cliente), 
(select CONCAT(f.paterno,' ',f.nombres) from funcionarios f where f.codigo_funcionario=s.cod_chofer), 
m.`codigo_material`, m.`descripcion_material`, 
	(sd.monto_unitario-sd.descuento_unitario)as montoProducto, sd.cantidad_unitaria, sd.cantidad_unitaria*sd.costo_almacen, s.descuento, s.monto_total
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and m.cod_grupo in ($rpt_grupo) and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio') and s.cod_tiposalida=1001
	and s.cod_chofer in ($rpt_persona)
	order by 1,3,7 desc";
	
//echo $sql;
$resp=mysql_query($sql);

echo "<center><table class='texto'>
<tr>
<th>Fecha</th>
<th>Nro</th>
<th>Cliente</th>
<th>Vendedor</th>
<th>CodItem</th>
<th>Material</th>
<th>Cantidad</th>
<th>Costo[u]</th>
<th>Precio[u]</th>
<th>CostoTotal</th>
<th>VentaTotal</th>
<th>Descuento</th>
<th>Utilidad[Bs]</th>
</tr>";

$totalVenta=0;
$totalCosto=0;
while($datos=mysql_fetch_array($resp)){	
	$fecha=$datos[0];
	$nroNota=$datos[1]."-".$datos[2];
	$nombreCliente=$datos[3];
	$nombreVendedor=$datos[4];
	
	$codItem=$datos[5];
	$nombreItem=$datos[6];
	$montoVenta=$datos[7];
	$cantidad=$datos[8];
	$montoCosto=$datos[9];
	
	$precioUnitario=$montoVenta/$cantidad;
	$costoUnitario=$montoCosto/$cantidad;
	
	$precioUnitarioF=number_format($precioUnitario,2,".",",");
	$costoUnitarioF=number_format($costoUnitario,2,".",",");
	
	$descuentoVenta=$datos[10];
	$montoNota=$datos[11];
	
	$descuentoAdiProducto=0;
	
	$montoVenta=$montoVenta-$descuentoVenta;
	/*if($descuentoVenta>0){
		$porcentajeVentaProd=($montoVenta/$montoNota);
		//$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
		$montoVenta=$montoVenta-$descuentoAdiProducto;
	}*/
	
	$descuentoAdiProductoF=number_format($descuentoAdiProducto,2,".",",");
	
	$montoPtr=number_format($montoVenta,2,".",",");
	$cantidadFormat=number_format($cantidad,2,".",",");
	
	$totalCosto=$totalCosto+$montoCosto;
	$montoCostoFormat=number_format($montoCosto,2,".",",");
	
	$utilidad=$montoVenta-$montoCosto;
	$utilidadFormat=number_format($utilidad,2,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	echo "<tr>
	<td>$fecha</td>
	<td>$nroNota</td>
	<td>$nombreCliente</td>
	<td>$nombreVendedor</td>

	<td>$codItem</td>
	<td>$nombreItem</td>
	<td>$cantidadFormat</td>
	<td>$costoUnitarioF</td>	
	<td>$precioUnitarioF</td>
	<td>$montoCostoFormat</td>
	<td>$montoPtr</td>
	<td>$descuentoAdiProductoF</td>
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
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>	
	<th>Total:</td>
	<th>$totalCostoFormat</th>
	<th>$totalPtr</th>
	<td>&nbsp;</td>
	<th>$totalUtilidadFormat</th>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>