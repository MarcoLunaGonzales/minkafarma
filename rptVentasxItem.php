<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli2.inc');
require('funcion_nombres.php');
require('funciones.php');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$sqlUTF=mysqli_query($enlaceCon, "SET NAMES utf8");

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ordenar=$_GET['rpt_ordenar'];
$rpt_ver=$_GET['rpt_ver'];

$globalLogo=$_COOKIE["global_logo"];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y H:i:s");

$nombre_territorio=nombreTerritorio($enlaceCon, $rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Ranking de Ventas x Item
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="select m.`codigo_material`, m.`descripcion_material`, (select p.nombre_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)as linea, m.codigo_barras, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria)cantidadventa, s.descuento, s.monto_total, s.cod_almacen
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and s.`cod_tiposalida`=1001 and  
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	group by m.`codigo_material`";
if($rpt_ordenar==0){
	$sql=$sql." order by m.descripcion_material ;";
}elseif($rpt_ordenar==1){
	$sql=$sql." order by montoVenta desc;";
}elseif($rpt_ordenar==2){
	$sql=$sql." order by cantidadventa desc;";
}
$resp=mysqli_query($enlaceCon, $sql);

echo "<br><table align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>Producto</th>
<th>Linea</th>
<th>Cantidad</th>
<th>Monto Venta</th>";
if($rpt_ver==2){
	echo "<th>Stock</th>";
}
echo "</tr>";

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

	$codAlmacenVenta=$datos[8];
	
	if($descuentoVenta>0){
		$porcentajeVentaProd=($montoVenta/$montoNota);
		$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
		$montoVenta=$montoVenta-$descuentoAdiProducto;
	}

	$stockProducto=0;
	if($rpt_ver==2){
		$stockProducto=stockProducto($enlaceCon,$codAlmacenVenta,$codItem);
	}
	
	$montoPtr=number_format($montoVenta,2,".",",");
	$cantidadFormat=number_format($cantidad,0,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	echo "<tr>
	<td>$codItem</td>
	<td>$nombreItem</td>
	<td>$nombreMarca</td>
	<td>$cantidadFormat</td>
	<td>$montoPtr</td>";
	if($rpt_ver==2){
		echo "<td align='right'>$stockProducto</td>";
	}	
	echo "</tr>";
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