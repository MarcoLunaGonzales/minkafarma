
<?php
require('../function_formatofecha.php');
require('../conexionmysqli2.inc');
require('../funcion_nombres.php');
require('../funciones.php');
require("../estilos_almacenes.inc");
// error_reporting(E_ALL);
// ini_set('display_errors', '1');


$fecha_fin=$_GET['fecha_fin'];
$fecha_ini=$_GET['fecha_ini'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon, $rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte de Obligaciones x Pagar
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="SELECT ia.cod_ingreso_almacen, ia.nro_correlativo, ia.fecha, p.nombre_proveedor, ia.monto_ingreso,
		(
			SELECT COALESCE(sum(ppd.monto_detalle), 0)
			FROM pagos_proveedor_cab pp, pagos_proveedor_detalle ppd
			WHERE pp.cod_pago=ppd.cod_pago 
			AND ppd.cod_ingreso_almacen=ia.cod_ingreso_almacen
			AND pp.cod_estado<>2 
			AND pp.fecha 
			BETWEEN '$fecha_iniconsulta' AND '$fecha_finconsulta'
		) AS pagado,
		DATEDIFF(ia.fecha_factura_proveedor, ia.fecha) AS dias_diferencia
		FROM ingreso_almacenes ia, proveedores p
		WHERE ia.monto_ingreso >
		(
			SELECT COALESCE(sum(ppd.monto_detalle), 0)
			FROM pagos_proveedor_cab pp, pagos_proveedor_detalle ppd
			WHERE pp.cod_pago=ppd.cod_pago 
			AND ppd.cod_ingreso_almacen=ia.cod_ingreso_almacen
			AND pp.cod_estado<>2 
			AND pp.fecha 
			BETWEEN '$fecha_iniconsulta' AND '$fecha_finconsulta'
		)
		AND ia.cod_proveedor = p.cod_proveedor
		AND ia.ingreso_anulado = 0
		AND ia.cod_almacen = 1000
		AND ia.cod_tipoingreso = 1000
		AND ia.cod_tipopago = 4
		AND ia.fecha BETWEEN '$fecha_iniconsulta' AND '$fecha_finconsulta'
		ORDER BY p.nombre_proveedor, ia.fecha";	  
// echo $sql;

$resp=mysqli_query($enlaceCon, $sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th>N.R.</th>
<th>Fecha</th>
<th>Cliente</th>
<th>MontoVenta</th>
<th>A Pagar</th>
<th>Saldo</th>
<th>Días de </br>Vigencia</th>
</tr>";

$totalxPagar=0;
while($datos=mysqli_fetch_array($resp)){	
	$codSalida=$datos[0];
	$nroVenta=$datos[1];
	$fechaVenta=$datos[2];
	$nombreCliente=$datos[3];
	$montoVenta=$datos[4];
	$montoPagar=$datos[5];
	$montoxPagar=$montoVenta-$montoPagar;
	
	
	$montoPagar=redondear2($montoPagar);
	$montoxPagar=redondear2($montoxPagar);
	$montoVenta=redondear2($montoVenta);
	
	$diasDiferencia=$datos[6];

	if($montoxPagar>1){
		$totalxPagar=$totalxPagar+$montoxPagar;
		echo "<tr>
		<td align='center'>$nroVenta</td>
		<td align='center'>$fechaVenta</td>
		<td>$nombreCliente</td>
		<td align='right'>$montoVenta</td>
		<td align='right'>$montoPagar</td>
		<td align='right'>$montoxPagar</td>
		<td align='center'>$diasDiferencia</td>
		</tr>";
	}
}
$totalxPagar=redondear2($totalxPagar);
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td align='right'>$totalxPagar</td>
	<td></td>
</tr>";

echo "</table>";
?>