<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_cliente=$_GET['rpt_cliente'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);

$fecha_reporte=date("d/m/Y");

$nombre_cliente=nombreCliente($rpt_cliente);

$sql="select s.`cod_salida_almacenes`, s.`nro_correlativo`, s.`fecha`,
 c.`nombre_cliente`, s.`monto_final`,
       (
         select COALESCE(sum(cbd.monto_detalle), 0)
         from `cobros_cab` cb, `cobros_detalle` cbd
         where cb.cod_cobro=cbd.cod_cobro and cbd.cod_venta=s.`cod_salida_almacenes`
         and cb.cod_estado<>2 and cb.fecha_cobro <'$fecha_iniconsulta' and cb.cod_cliente='$rpt_cliente'
       ) cobrado
from `salida_almacenes` s, clientes c where s.`monto_final` >
      (
        select COALESCE(sum(cbd.monto_detalle), 0)
         from `cobros_cab` cb, `cobros_detalle` cbd
         where cb.cod_cobro=cbd.cod_cobro and cbd.cod_venta=s.`cod_salida_almacenes`
         and cb.cod_estado<>2 and cb.fecha_cobro < '$fecha_iniconsulta' and cb.cod_cliente='$rpt_cliente'
      ) and s.`cod_cliente` = c.`cod_cliente` and c.cod_cliente='$rpt_cliente' and 
      s.`salida_anulada` = 0 and
      s.`fecha` < '$fecha_iniconsulta'";	

      
$resp=mysql_query($sql);
$totalAntCli=0;
while($dat=mysql_fetch_array($resp)){
	$cuentaxCobrarCli=$dat[4]-$dat[5];
	$totalAntCli=$totalAntCli+$cuentaxCobrarCli;
} 
echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte Kardex x Cliente
	<br>Cliente: $nombre_cliente <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte
	<br>Cuenta x Cobrar a fecha Inicio: $totalAntCli
	</tr></table>";

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th>Fecha</th>
<th>Transaccion</th>
<th>Nro. Venta</th>
<th>Monto Venta</th>
<th>Nro. Cobranza</th>
<th>Monto Cobro</th>
<th>Saldo</th>
</tr>";

$sqlFechas="(select (s.`fecha`) as fecha from `salida_almacenes` s 
	where s.`cod_cliente`=$rpt_cliente and s.`salida_anulada`=0 and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta')
	union
	(select (c.`fecha_cobro`) as fecha from `cobros_cab` c where c.`cod_cliente`=$rpt_cliente and c.`cod_estado`<>2
	and c.fecha_cobro BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta')
	 order by fecha";
$respFechas=mysql_query($sqlFechas);

$saldoNuevo=$totalAntCli;

while($datFechas=mysql_fetch_array($respFechas)){
	$fechaTr=$datFechas[0];
	
	$sqlVenta=" select concat(t.`abreviatura`,' ',s.`nro_correlativo`), s.`monto_final` from `salida_almacenes` s, `tipos_docs` t 
		 where s.`cod_cliente`=$rpt_cliente and s.`fecha`='$fechaTr' and s.`salida_anulada`=0 
		and s.`cod_tipo_doc`=t.`codigo`";
	
	$respVenta=mysql_query($sqlVenta);
	while($datVenta=mysql_fetch_array($respVenta)){
		$docVenta=$datVenta[0];
		$montoVenta=$datVenta[1];
		$saldoNuevo=$saldoNuevo+$montoVenta;
		
		$montoVentaF=redondear2($montoVenta);
		$saldoNuevoF=redondear2($saldoNuevo);
		
		echo "<tr><td>$fechaTr</td><td>Venta</td><td>$docVenta</td><td>$montoVentaF</td><td>-</td><td>-</td><td>$saldoNuevoF</td></tr>";
	}
	
	$sqlCobros=" select c.`nro_cobro`,
				cd.`monto_detalle`, concat(t.abreviatura,' ', s.nro_correlativo)
		 from `cobros_cab` c,
			  `cobros_detalle` cd,
			  `salida_almacenes` s,
			  `tipos_docs` t
		 where c.`cod_cobro` = cd.`cod_cobro` and
			   cd.`cod_venta` = s.`cod_salida_almacenes` and
			   s.`cod_tipo_doc` = t.`codigo` and c.`cod_cliente` = $rpt_cliente and
			   c.`cod_estado` <> 2 and
			   c.`fecha_cobro` = '$fechaTr';";
	$respCobros=mysql_query($sqlCobros);
	while($datCobros=mysql_fetch_array($respCobros)){
		$nroCobro=$datCobros[0];
		$montoCobro=$datCobros[1];
		$ventaAsociada=$datCobros[2];
		
		$saldoNuevo=$saldoNuevo-$montoCobro;
		
		$montoCobroF=redondear2($montoCobro);

		$saldoNuevoF=redondear2($saldoNuevo);
		echo "<tr><td>$fechaTr</td><td>Cobranza</td><td>-</td><td>-</td><td>$nroCobro ($ventaAsociada)</td><td>$montoCobroF</td><td>$saldoNuevoF</td></tr>";
	}
	
}

echo "</table>";
include("imprimirInc.php");

?>