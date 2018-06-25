<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_fin=$_GET['fecha_fin'];
$fecha_ini=$_GET['fecha_ini'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);

$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte de Cuentas x Cobrar
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="select s.`cod_salida_almacenes`, s.`nro_correlativo`, s.`fecha`, c.`nombre_cliente`, s.`monto_final`,
       (
         select COALESCE(sum(cbd.monto_detalle), 0)
         from `cobros_cab` cb, `cobros_detalle` cbd
         where cb.cod_cobro=cbd.cod_cobro and cbd.cod_venta=s.`cod_salida_almacenes`
         and cb.cod_estado<>2 and cb.fecha_cobro between '$fecha_iniconsulta' and
               '$fecha_finconsulta'
       ) cobrado
from `salida_almacenes` s, clientes c where s.`monto_final` >
      (
        select COALESCE(sum(cbd.monto_detalle), 0)
         from `cobros_cab` cb, `cobros_detalle` cbd
         where cb.cod_cobro=cbd.cod_cobro and cbd.cod_venta=s.`cod_salida_almacenes`
         and cb.cod_estado<>2 and cb.fecha_cobro between '$fecha_iniconsulta' and
               '$fecha_finconsulta'
      ) and s.`cod_cliente` = c.`cod_cliente` and
      s.`salida_anulada` = 0 and s.cod_almacen=1000 and s.cod_tiposalida=1001 and 
      s.`fecha` between '$fecha_iniconsulta' and
      '$fecha_finconsulta'
order by c.nombre_cliente,
         s.fecha";	  


//echo $sql;

$resp=mysql_query($sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th>N.R.</th>
<th>Fecha</th>
<th>Cliente</th>
<th>MontoVenta</th>
<th>A Cuenta</th>
<th>Saldo</th>
</tr>";

$totalxCobrar=0;
while($datos=mysql_fetch_array($resp)){	
	$codSalida=$datos[0];
	$nroVenta=$datos[1];
	$fechaVenta=$datos[2];
	$nombreCliente=$datos[3];
	$montoVenta=$datos[4];
	$montoCobro=$datos[5];
	$montoxCobrar=$montoVenta-$montoCobro;
	
	
	$montoCobro=redondear2($montoCobro);
	$montoxCobrar=redondear2($montoxCobrar);
	$montoVenta=redondear2($montoVenta);
	

	if($montoxCobrar>1){
		$totalxCobrar=$totalxCobrar+$montoxCobrar;
		echo "<tr>
		<td align='center'>$nroVenta</td>
		<td align='center'>$fechaVenta</td>
		<td>$nombreCliente</td>
		<td align='right'>$montoVenta</td>
		<td align='right'>$montoCobro</td>
		<td align='right'>$montoxCobrar</td>
		</tr>";
	}
}
$totalxCobrar=redondear2($totalxCobrar);
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td align='right'>$totalxCobrar</td>
</tr>";

echo "</table>";
include("imprimirInc.php");

?>