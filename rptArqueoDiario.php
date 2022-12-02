<?php
error_reporting(0);
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');


$rpt_territorio=$_GET['rpt_territorio'];
$rpt_funcionario=$_GET['rpt_funcionario'];
$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$hora_ini=$_GET['hora_ini'];
$hora_fin=$_GET['hora_fin'];
$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_iniconsultahora=$fecha_iniconsulta." ".$hora_ini.":00";
$fecha_finconsultahora=$fecha_fin." ".$hora_fin.":59";
$fecha_reporte=date("d/m/Y");

echo "<center><h3>Reporte Arqueo Diario de Caja</h3>
	<h3>Fecha Arqueo: ".strftime('%d/%m/%Y',strtotime($fecha_ini))." &nbsp;&nbsp;&nbsp; Fecha Reporte: $fecha_reporte</h3></center>";	

echo "<center><table class='textomediano'>";
echo "<tr><th colspan='2'>Saldo Inicial Caja Chica</th></tr>
<tr><th>Fecha</th><th>Monto Apertura de Caja Chica [Bs]</th></tr>";
$consulta = "select DATE_FORMAT(c.fecha_cajachica, '%d/%m/%Y'), c.monto, c.fecha_cajachica from cajachica_inicio c where 
c.fecha_cajachica BETWEEN '$fecha_iniconsulta' and '$fecha_fin'";
$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
	$fechaCajaChica = $dat[0];
	$montoCajaChica = $dat[1];
	$montoCajaChicaF=number_format($montoCajaChica,2,".",",");
	echo "<tr>
	<td align='center'>$fechaCajaChica</td>
	<td align='right'>$montoCajaChicaF</td>
	</tr>";
}
echo "</table></center><br>";


	
$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_tipopago, (select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago), 
	s.hora_salida,s.cod_chofer
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio' and cod_tipoalmacen=1)
	and CONCAT(s.fecha,' ',s.hora_salida) BETWEEN '$fecha_iniconsultahora' and '$fecha_finconsultahora' and s.`cod_chofer`='$rpt_funcionario' ";

	$sql.=" and s.cod_tipo_doc in (1,2,3)";

$sql.=" order by s.fecha, s.hora_salida";
	
$resp=mysqli_query($enlaceCon,$sql);

echo "<br><table align='center' class='textomediano' width='70%'>
<tr><th colspan='8'>Detalle de Ingresos</th></tr>
<tr>
<th>Fecha</th>
<th>Personal</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Observaciones</th>
<th>TipoPago</th>
<th>Documento</th>
<th>Monto [Bs]</th>
</tr>";

$totalVenta=0;
$totalEfectivo=0;
$totalTarjeta=0;
while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$montoVenta=ceil_dec($montoVenta,1,".");
	$totalVenta=$totalVenta+$montoVenta;
	$codTipoPago=$datos[7];
	$nombreTipoPago=$datos[8];
	$horaVenta=$datos[9];
	$personalCliente=nombreVisitador($datos['cod_chofer']);
	//$montoVentaFormat=number_format($montoVenta,2,".",",");
	
	if($codTipoPago==1){
		$totalEfectivo+=$montoVenta;
	}else{
		$montoVenta=number_format($montoVenta,1,'.','');
		$totalTarjeta+=$montoVenta;
	}
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	$totalEfectivoF=number_format($totalEfectivo,2,".",",");
	$totalTarjetaF=number_format($totalTarjeta,2,".",",");
	
	echo "<tr>
	<td>$fechaVenta $horaVenta</td>
	<td>$personalCliente</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$obsVenta</td>
	<td>$nombreTipoPago</td>
	<td>$datosDoc</td>
	<td align='right'>$montoVentaFormat</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<th>Total Efectivo:</th>
	<th align='right'>$totalEfectivoF</th>
<tr>";
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<th>Total Tarjeta Deb/Cred:</th>
	<th align='right'>$totalTarjetaF</th>
<tr>";
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<th>Total Ingresos:</th>
	<th align='right'>$totalVentaFormat</th>
<tr>";
echo "</table></br>";




echo "<br><center><table class='textomediano'>";
echo "<tr><th colspan='4'>Detalle de Gastos</th></tr>";
echo "<tr><th>Fecha</th><th>Tipo</th>
	<th>Descripcion</th><th>Monto [Bs]</th></tr>";

$consulta = "select g.cod_gasto, g.descripcion_gasto, 
	(select nombre_tipogasto from tipos_gasto where cod_tipogasto=g.cod_tipogasto)tipogasto, 
	DATE_FORMAT(g.fecha_gasto, '%d/%m/%Y'), monto, estado from gastos g where fecha_gasto BETWEEN '$fecha_iniconsulta' and '$fecha_fin'
	and g.estado=1 order by g.cod_gasto";
	
$resp = mysqli_query($enlaceCon,$consulta);
$totalGastos=0;
while ($dat = mysqli_fetch_array($resp)) {
	$codGasto = $dat[0];
	$descripcionGasto= $dat[1];
	$tipoGasto=$dat[2];
	$fechaGasto = $dat[3];
	$montoGasto = $dat[4];
	$totalGastos=$totalGastos+$montoGasto;
	$codEstado=$dat[5];	
	$montoGasto=redondear2($montoGasto);

	echo "<tr>
	<td align='center'>$fechaGasto</td>
	<td align='center'>$tipoGasto</td>
	<td align='center'>$descripcionGasto</td>
	<td align='right'>$montoGasto</td>
	</tr>";
}
$totalGastos=redondear2($totalGastos);
echo "<tr>
<td align='center'>-</td>
<td align='center'>-</td>
<th>Total Gastos</th>
<th align='right'>$totalGastos</th>
</tr>";
echo "</table></center><br>";

$saldoCajaChica=$montoCajaChica+$totalVenta-$totalGastos;
$saldoCajaChicaF=number_format($saldoCajaChica,2,".",",");

$saldoCajaChica2=$montoCajaChica+$totalEfectivo-$totalGastos;
$saldoCajaChica2F=number_format($saldoCajaChica2,2,".",",");


echo "<br><center><table class='textomediano'>";
echo "<tr><th>Saldo Inicial Caja Chica + Ingresos - Gastos   ---->  </th>
<th align='right'>$saldoCajaChicaF</th>
</tr>";
echo "<tr><th>Saldo Inicial Caja Chica + Ingresos Efectivo - Gastos   ---->  </th>
<th align='right'>$saldoCajaChica2F</th>
</tr>";
echo "</table></center><br>";




include("imprimirInc.php");
?>