<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli2.inc');
require('funcion_nombres.php');

$fecha_ini=$_POST['exafinicial'];
$fecha_fin=$_POST['exaffinal'];
$codTipoDoc=$_POST['rpt_tipodoc'];
$codTipoPago=$_POST['tipo_pago'];

$verVenta 	= $_POST['rpt_ver_venta'];
$rptVer 	= '';
if($verVenta == 2){
	// Ver venta menor <= 5
	$rptVer = ' AND s.monto_final <= 5 ';
}

$codTipoDoc=implode(",",$codTipoDoc);
$codTipoPago=implode(",",$codTipoPago);


$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_POST['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon, $rpt_territorio);

echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Ventas x Documento
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`,
	(select t.`nombre_tipopago` from `tipos_pago` t where t.`cod_tipopago`=s.cod_tipopago)
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	".$rptVer."
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and 
	s.cod_tipo_doc in ($codTipoDoc) and s.cod_tipopago in ($codTipoPago) ";
// echo $sql;
$sql.=" order by s.fecha, s.nro_correlativo";

//echo $sql;
$resp=mysqli_query($enlaceCon, $sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Observaciones</th>
<th>Documento</th>
<th>Tipo de Pago</th>
<th>Monto</th>
</tr>";

$totalVenta=0;
while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$tipoPago=$datos[7];
	$totalVenta=$totalVenta+$montoVenta;
	
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$obsVenta</td>
	<td>$datosDoc</td>
	<td>$tipoPago</td>
	<td>$montoVentaFormat</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalVentaFormat</td>
<tr>";

echo "</table></br>";
include("imprimirInc.php");
?>