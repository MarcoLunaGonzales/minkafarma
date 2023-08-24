<?php
//header('Content-Type: text/html; charset=ISO-8859-1');

require('fpdf.php');
require('conexionmysqlipdf.inc');
require('funciones.php');
require('funcion_nombres.php');
require('NumeroALetras.php');
include('phpqrcode/qrlib.php');
//header("Content-Type: text/html; charset=iso-8859-1 ");
mysqli_query($enlaceCon,"SET NAMES utf8");


/* error_reporting(E_ALL);
 ini_set('display_errors', '1');
*/


$codigoVenta=$_GET["codVenta"];
$cod_ciudad=$_COOKIE["global_agencia"];

$tamanoLargo=70;

$pdf=new FPDF('P','mm',array(74,$tamanoLargo));
$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',8);

$sqlConf="select id, valor from configuracion_facturas where id=1 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nombreTxt=$datConf[1];//$nombreTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=10 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nombreTxt2=$datConf[1];//$nombreTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=2 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$sucursalTxt=$datConf[1];//$sucursalTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=3 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$direccionTxt=$datConf[1];//$direccionTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=4 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$telefonoTxt=$datConf[1];//$telefonoTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=5 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$ciudadTxt=$datConf[1];//$ciudadTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=6 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$txt1=$datConf[1];//$txt1=mysql_result($respConf,0,1);


$sqlConf="select id, valor from siat_leyendas where id=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$txt2=$datConf[1];


$sqlConf="select id, valor from configuracion_facturas where id=9 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$nitTxt=$datConf[1];//$nitTxt=mysql_result($respConf,0,1);

$sqlDatosFactura="select '' as nro_autorizacion, '', '' as codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.siat_fechaemision, '%d/%m/%Y') 
from salida_almacenes f where f.cod_salida_almacenes=$codigoVenta";

$respDatosFactura=mysqli_query($enlaceCon,$sqlDatosFactura);
$datDatosFactura=mysqli_fetch_array($respDatosFactura);

$nroAutorizacion=$datDatosFactura[0];//$nroAutorizacion=mysql_result($respDatosFactura,0,0);
$fechaLimiteEmision=$datDatosFactura[1];//$fechaLimiteEmision=mysql_result($respDatosFactura,0,1);
$codigoControl=$datDatosFactura[2];//$codigoControl=mysql_result($respDatosFactura,0,2);
$nitCliente=$datDatosFactura[3];//$nitCliente=mysql_result($respDatosFactura,0,3);
$razonSocialCliente=$datDatosFactura[4];//$razonSocialCliente=mysql_result($respDatosFactura,0,4);
$razonSocialCliente=strtoupper($razonSocialCliente);
$fechaFactura=$datDatosFactura[5];
$cod_funcionario=$_COOKIE["global_usuario"];


//datos documento

$sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`abreviatura` as nombre, c.`nombre_cliente`, s.`nro_correlativo`, 
s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_cancelado_bs,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,
(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,
(SELECT cufd from siat_cufd where codigo=s.siat_codigocufd) as cufd,siat_cuf,siat_complemento,s.siat_codigoPuntoVenta,
s.siat_codigotipoemision,(SELECT descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda
		from `salida_almacenes` s, `tipos_docs` t, `clientes` c
		where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_cliente`=c.`cod_cliente` and
		s.`cod_tipo_doc`=t.`codigo`";

//echo "<br>".$sqlDatosVenta;

$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
$tipoPago=1;
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	$fechaVenta=$datDatosVenta[0];
	$nombreTipoDoc=$datDatosVenta[1];
	$nombreCliente=$datDatosVenta[2];
	$nroDocVenta=$datDatosVenta[3];
	$descuentoVenta=$datDatosVenta[4];
	$descuentoVenta=redondear2($descuentoVenta);
	$horaFactura=$datDatosVenta[5];
	$montoTotal2=$datDatosVenta['monto_total'];
	$montoFinal2=$datDatosVenta['monto_final'];

	$montoEfectivo2=$datDatosVenta['monto_cancelado_bs'];
	$montoCambio2=$datDatosVenta['monto_cambio'];
	$montoCambio2=$montoEfectivo2-$montoFinal2;

	$montoTotal2=redondear2($montoTotal2);
	$montoFinal2=redondear2($montoFinal2);

	$montoEfectivo2=redondear2($montoEfectivo2);
	$montoCambio2=redondear2($montoCambio2);

	$descuentoCabecera=$datDatosVenta['descuento'];
	$cod_funcionario=$datDatosVenta['cod_chofer'];
	$tipoPago=$datDatosVenta['cod_tipopago'];
	$tipoDoc=$datDatosVenta['nombre'];
	$codTipoDoc=$datDatosVenta['cod_tipo_doc'];

	$fecha_salida=$datDatosVenta['fecha'];
	$hora_salida=$datDatosVenta['hora_salida'];
	$cod_ciudad_salida=$datDatosVenta['cod_ciudad'];
	$cod_cliente=$datDatosVenta['cod_cliente'];

	$nroCufd=$datDatosVenta['cufd'];
	$cuf=$datDatosVenta['siat_cuf'];
	$siat_complemento=$datDatosVenta['siat_complemento'];
	$siat_codigopuntoventa=$datDatosVenta['siat_codigoPuntoVenta'];
	$siat_codigotipoemision=$datDatosVenta['siat_codigotipoemision'];
	$txt3=$datDatosVenta['leyenda'];

	//echo "entro detalle";
}
$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
$datResponsable=mysqli_fetch_array($respResponsable);
$nombreFuncionario=$datResponsable[0];

$y=0;
$incremento=3;
$pdf->SetFont('Arial','B',7);
$pdf->SetXY(4,$y+5);		$pdf->Cell(68,0,"- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -",0,0,"C");

$y=$y+5;
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(4,$y+5);		$pdf->Cell(68,0,"$nombreTipoDoc - $nroDocVenta", 0,0,"C");
$pdf->SetFont('Arial','',8);

$pdf->SetXY(4,$y+10);		$pdf->Cell(68,0,utf8_decode("Nombre Cliente:").utf8_decode($razonSocialCliente),0,0,"C");
$pdf->SetXY(4,$y+15);		$pdf->Cell(68,0,"NIT/CI/CEX:".$nitCliente." ".$siat_complemento,0,0,"C");
$pdf->SetXY(4,$y+20);		$pdf->Cell(68,0,utf8_decode("Fecha y Hora: ").$fechaFactura."  ".$horaFactura,0,0,"C");

$montoFinal=number_format($montoFinal2,2,'.','');

$pdf->SetXY(4,$y+25);		$pdf->Cell(68,0,"Cajero(a): $nombreFuncionario",0,0,"C");
$pdf->SetFont('Arial','B',12);
$pdf->SetXY(4,$y+30);		$pdf->Cell(68,0,"Nro. Proceso: $codigoVenta",0,0,"C");

$pdf->SetFont('Arial','B',7);
$pdf->SetXY(4,$y+35);		$pdf->Cell(68,0,"- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -",0,0,"C");
$pdf->Output();

?>