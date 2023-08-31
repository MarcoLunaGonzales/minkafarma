<?php

if( !function_exists('ceiling') )
{
    function ceiling($number, $significance = 1)
    {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
    }
}

require('function_formatofecha.php');
require('conexionmysqli2.inc');
require('funcion_nombres.php');
require('funciones.php');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$rpt_territorio=$_GET['rpt_territorio'];
$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$hora_ini=$_GET['hora_ini'];
$hora_fin=$_GET['hora_fin'];

$globalLogo=$_COOKIE["global_logo"];

ob_start();
echo "<html><head><link href='stilos.css' rel='stylesheet' type='text/css'></head></body>";
echo "<center><table border=0 class='linea' width='100%'><tr><td align='left'>
<img src='imagenes/$globalLogo' width='80'></td>
<th></th></tr></table></center><br>";
//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_iniconsultahora=$fecha_iniconsulta." ".$hora_ini.":00";
$fecha_finconsultahora=$fecha_fin." ".$hora_fin.":59";
$fecha_reporte=date("d/m/Y");
$montoCajaChica=0;
echo "<center><h3>Reporte Arqueo Diario de Caja</h3>
	<h3>Fecha Arqueo: ".strftime('%d/%m/%Y',strtotime($fecha_ini))." &nbsp;&nbsp;&nbsp; Fecha Reporte: $fecha_reporte</h3></center>";

	
$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_tipopago, (select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago), 
	s.hora_salida,s.cod_chofer,s.cod_salida_almacenes,s.salida_anulada,s.monto_cancelado_usd,s.tipo_cambio
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and CONCAT(s.fecha,' ',s.hora_salida) BETWEEN '$fecha_iniconsultahora' and '$fecha_finconsultahora' and s.cod_tipopago=1 ";
/*and s.salida_anulada=0*/

$sqlTarjetas="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_tipopago, (select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago), 
	s.hora_salida,s.cod_chofer,s.cod_salida_almacenes,s.monto_cancelado_usd,s.tipo_cambio,
 	0 as nombre_banco, (SELECT nro_tarjeta FROM tarjetas_salidas where cod_salida_almacen=s.cod_salida_almacenes limit 1)numero_tarjeta
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and CONCAT(s.fecha,' ',s.hora_salida) BETWEEN '$fecha_iniconsultahora' and '$fecha_finconsultahora' and s.cod_tipopago!=1 ";

	$sql.=" and s.cod_tipo_doc in (1,2,3,4)";
	$sqlTarjetas.=" and s.cod_tipo_doc in (1,2,3,4)";

$sql.=" order by s.fecha, s.hora_salida";
$sqlTarjetas.=" order by s.fecha, s.hora_salida";

//echo $sqlTarjetas;
$resp=mysqli_query($enlaceCon,$sql);
$respTarjeta=mysqli_query($enlaceCon,$sqlTarjetas);


echo "<br><table align='center' class='textomediano' width='100%'>
<tr><th colspan='7'>Detalle de Ventas (EFECTIVO)</th></tr>
<tr>
<th>Fecha</th>
<th>Cajero(a)</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>TipoPago</th>
<th>Documento</th>
<th>Monto [Bs]</th>
</tr>";

$totalVenta=0;
$totalEfectivo=0;
$totalEfectivoUsd=0;
$totalEfectivoBs=0;
$totalEfectivoF=0;
$totalTarjetaF=0;
$totalTarjeta=0;

while($datos=mysqli_fetch_array($resp)){
    $codigoSalida=$datos['cod_salida_almacenes'];	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	//$montoVenta=number_format($montoVenta,1,'.','');
	$totalVenta=$totalVenta+$montoVenta;	
	
	$codTipoPago=$datos[7];
	$nombreTipoPago=$datos[8];
	$horaVenta=$datos[9];
	$personalCliente=nombreVisitador($enlaceCon, $datos['cod_chofer']);
	$montoDolares=$datos['monto_cancelado_usd'];
	$tipoCambio=$datos['tipo_cambio'];	
	if($codTipoPago==1 && $datos['salida_anulada']==0){
		$totalEfectivoBs+=($montoDolares*$tipoCambio);
	    $totalEfectivo+=$montoVenta;
	    $totalEfectivoUsd+=$montoDolares;		
	}else{
		if($datos['salida_anulada']==0){
			//$montoVenta=number_format($montoVenta,1,'.','');
			$totalTarjeta+=$montoVenta;		
		}
	}
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	$totalEfectivoF=number_format($totalEfectivo,2,".",",");
	$totalEfectivoFUSD=number_format($totalEfectivoUsd,2,".",",");
	$totalEfectivoFBS=number_format($totalEfectivoBs,2,".",",");
	$totalTarjetaF=number_format($totalTarjeta,2,".",",");
	
	if($datos['salida_anulada']==0){
	  	echo "<tr>
		<td>$fechaVenta $horaVenta</td>
		<td>$personalCliente</td>
		<td>$nombreCliente</td>
		<td>$razonSocial</td>
		<td>$nombreTipoPago</td>
		<td>$datosDoc</td>
		<td align='right'>$montoVentaFormat</td>
		</tr>";
	}else{
		echo "<tr style='color:red'>
		<td><strike>$fechaVenta $horaVenta</strike></td>
		<td><strike>$personalCliente</strike></td>
		<td><strike>$nombreCliente</strike></td>
		<td><strike>$razonSocial</strike></td>
		<td><strike>$nombreTipoPago</strike></td>
		<td><strike>$datosDoc</strike></td>
		<td align='right'>$montoVentaFormat</td>
		</tr>";
	} 
	
}

$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<th>Total Efectivo:</th>
	<th align='right'>$totalEfectivoF</th>
</tr>";
echo "</table></br>";

//VENTAS TARJETA


echo "<br><table align='center' class='textomediano' width='100%'>
<tr><th colspan='9'>Detalle de Ventas Otros Tipos de Pago (Tarjeta D/C- Transferencia)</th></tr>
<tr>
<th>Fecha</th>
<th>Cajero(a)</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>TipoPago</th>
<th>Documento</th>
<th>Banco</th>
<th>Tarjeta</th>
<th>Monto [Bs]</th>
</tr>";

$totalTarjeta=0;
while($datos=mysqli_fetch_array($respTarjeta)){
    $codigoSalida=$datos['cod_salida_almacenes'];	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	//$montoVenta=number_format($montoVenta,1,'.','');
	$totalVenta=$totalVenta+$montoVenta;
	$codTipoPago=$datos[7];
	$nombreTipoPago=$datos[8];
	$horaVenta=$datos[9];
	$bancoNombre=$datos['nombre_banco'];
	$tarjetaNumero=$datos['numero_tarjeta'];
	$personalCliente=nombreVisitador($enlaceCon, $datos['cod_chofer']);
		
	if($codTipoPago==1){
		$totalEfectivo+=$montoVenta;
	}else{
		//$montoVenta=number_format($montoVenta,1,'.','');
		$totalTarjeta+=$montoVenta;
	}

	if($bancoNombre==""){
		$bancoNombre="OTRO";
	}
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	$totalEfectivoF=number_format($totalEfectivo,2,".",",");
	$totalTarjetaF=number_format($totalTarjeta,2,".",",");
	
	echo "<tr>
	<td>$fechaVenta $horaVenta</td>
	<td>$personalCliente</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$nombreTipoPago</td>
	<td>$datosDoc</td>
	<td>$bancoNombre</td>
	<td align='right'>$tarjetaNumero</td>
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
	<td>&nbsp;</td>
	<th>Total Otros Tipos de Pago</th>
	<th align='right'>$totalTarjetaF</th>
</tr>";

echo "</table></br>";

$totalGastos=0;

$saldoCajaChica=$montoCajaChica+$totalTarjeta-$totalGastos;
$saldoCajaChicaF=number_format($saldoCajaChica,2,".",",");

$saldoCajaChica2=$montoCajaChica+$totalEfectivo-$totalGastos;
$saldoCajaChica2F=number_format($saldoCajaChica2,2,".",",");


$totalIngresos=($totalEfectivo+$totalTarjeta);
$totalIngresosFormat=number_format($totalIngresos,2,".",",");
echo "<br><table align='center' class='textomediano' width='100%'>";

$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr style='font-size:15px;'>
	<th>Total Efectivo:</th>
	<th align='right'>$totalEfectivoF</th>
</tr>";
echo "<tr style='font-size:15px;'>
	<th>Total Otros Tipos de Pago </th>
	<th align='right'>$totalTarjetaF</th>
</tr>";
echo "<tr style='font-size:25px;'>
	<th>Total Ingresos:</th>
	<th align='right'>$totalIngresosFormat</th>
</tr>";
echo "</table></br></body></html>";

$html = ob_get_clean();

$nombreFuncionario=nombreVisitador($enlaceCon, $rpt_funcionario);
if(!isset($_GET["ruta"])){	
	descargarPDFArqueoCajaVertical("Cierre.".strftime('%d-%m-%Y',strtotime($fecha_ini)).".".$nombreFuncionario,$html);	
}else{
	$rutaCompleta=$_GET["ruta"];
	$rutaCompleta=str_replace("@","/",$rutaCompleta);
	guardarPDFArqueoCajaVertical("Cierre.".strftime('%d-%m-%Y',strtotime($fecha_ini)).".".$nombreFuncionario,$html,$rutaCompleta);
	echo "<script language='Javascript'>
      alert('Los datos fueron registrados exitosamente.');
      location.href='depositos/list.php';
      </script>";
}

?>



