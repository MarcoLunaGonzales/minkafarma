<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli2.inc');
require('funcion_nombres.php');
require('funciones.php');


$sqlUTF=mysqli_query($enlaceCon, "SET NAMES utf8");

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ver=$_GET['rpt_ver'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon, $rpt_territorio);

$cod_almacen=0;
$sql_almacen="select cod_almacen from almacenes where cod_ciudad='$rpt_territorio'";
$resp_almacen=mysqli_query($enlaceCon, $sql_almacen);
if($dat_almacen=mysqli_fetch_array($resp_almacen)){
	$cod_almacen=$dat_almacen[0];
}


echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Productos a Reponer
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="select m.`codigo_material`, m.`descripcion_material`, (select p.nombre_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)as linea, m.codigo_barras, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, max(s.fecha) as ultimaventa
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and s.`cod_tiposalida`=1001 and  
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	group by m.`codigo_material` order by montoVenta desc;";
	
	//echo $sql;
$resp=mysqli_query($enlaceCon, $sql);

echo "<br><table align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>Producto</th>
<th>Linea</th>
<th>Cantidad</th>
<th>Monto Venta</th>
<th>Fecha Ultima Venta</th>
<th>Stock Ultima Venta</th>
<th>Observaciones</th>
</tr>";

$totalVenta=0;
while($datos=mysqli_fetch_array($resp)){	
	$codItem=$datos[0];
	$nombreItem=$datos[1];
	$nombreMarca=$datos[2];
	$barCode=$datos[3];
	
	$montoVenta=$datos[4];
	$cantidad=$datos[5];

	$descuentoVenta=$datos[6];
	$montoNota=$datos[7];

	$fechaUltimaVenta=$datos[8];
	
	if($descuentoVenta>0){
		$porcentajeVentaProd=($montoVenta/$montoNota);
		$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
		$montoVenta=$montoVenta-$descuentoAdiProducto;
	}

	$stockFechaUltimaVenta=stockProductoAFecha($enlaceCon, $cod_almacen, $codItem, $fechaUltimaVenta);

	$obsStock="";
	if($stockFechaUltimaVenta<=0){
		$stockFechaUltimaVenta=0;
		$obsStock="<span class='textomedianorojo'>El Producto debe Reponerse!</span>";
	}
	
	$montoPtr=number_format($montoVenta,2,".",",");
	$cantidadFormat=number_format($cantidad,0,".",",");
	
	if( ($rpt_ver==0 && $stockFechaUltimaVenta==0) || $rpt_ver==1 ){

		$totalVenta=$totalVenta+$montoVenta;

		echo "<tr>
		<td>$barCode</td>
		<td>$nombreItem</td>
		<td>$nombreMarca</td>
		<td>$cantidadFormat</td>
		<td>$montoPtr</td>
		<td>$fechaUltimaVenta</td>
		<td>$stockFechaUltimaVenta</td>
		<td>$obsStock</td>	
		</tr>";		
	}
}
$totalPtr=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalPtr</td>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>