<?php
require('../function_formatofecha.php');
require('../conexionmysqli.inc');
require('../funcion_nombres.php');
require('../funciones.php');

//  error_reporting(E_ALL);
//  ini_set('display_errors', '1');


$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_proveedor=$_GET['rpt_proveedor'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon, $rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte Pagos
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="SELECT p.cod_pago, p.fecha, pd.nro_doc, pro.nombre_proveedor, ia.nro_correlativo, pd.monto_detalle, p.observaciones, ia.nro_factura_proveedor
		FROM pagos_proveedor_cab p
		LEFT JOIN pagos_proveedor_detalle pd ON p.cod_pago = pd.cod_pago
		LEFT JOIN proveedores pro ON p.cod_proveedor = pro.cod_proveedor
		LEFT JOIN ingreso_almacenes ia ON pd.cod_ingreso_almacen = ia.cod_ingreso_almacen
		WHERE p.fecha BETWEEN '$fecha_iniconsulta' AND '$fecha_finconsulta'
		AND p.cod_estado <> 2";
// echo $sql;
if($rpt_proveedor!=0){
	$sql=$sql." and pro.cod_proveedor in ($rpt_proveedor)";
}
$sql=$sql." order by 1";
$resp=mysqli_query($enlaceCon, $sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th width='7%'>Nro.Cob</th>
<th width='7%'>Doc.Cob</th>
<th width='15%'>Fecha</th>
<th width='20%'>Proveedor</th>
<th width='10%'>Nro. Ingreso</th>
<th width='10%'>Nro. Factura</th>
<th width='30%'>Observaciones</th>
<th width='10%'>Monto Pago</th>
</tr>";

$totalPago=0;
while($datos=mysqli_fetch_array($resp)){	
	$codPago=$datos[0];
	$fecha=$datos[1];
	$nroPago=$datos[2];
	$proveedor=$datos[3];
	$nroVenta=$datos[4];
	$montoPago=$datos[5];
	$nroFactura=$datos[7];

	$totalPago=$totalPago+$montoPago;

	$montoPago=redondear2($montoPago);
	$obs=$datos[6];
		
	echo "<tr>
	<td align='center'>$codPago</td>
	<td align='center'>$nroPago</td>
	<td align='center'>$fecha</td>
	<td>$proveedor</td>
	<td align='center'>$nroVenta</td>
	<td align='center'>$nroFactura</td>
	<td>$obs</td>
	<td align='right'>$montoPago</td>
	</tr>";
}
$totalPago=redondear2($totalPago);

echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td align='right'>$totalPago</td>
</tr>";

echo "</table>";
?>