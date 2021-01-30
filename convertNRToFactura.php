<?php
require("fpdf.php");
require("conexion.inc");
require("funciones.php");

date_default_timezone_set('America/La_Paz');
$codVenta=$_POST["cod_venta"];
$razonSocial=$_POST["razon_social_convertir"];
$nitCliente=$_POST["nit_convertir"];
$fecha=date("Y-m-d");

$globalSucursal=$_COOKIE['global_agencia'];


$totalFinal=montoVentaDocumento($codVenta);
$totalFinalRedondeado=round($totalFinal);

$vectorNroCorrelativo=numeroCorrelativo(1);
$nro_correlativo=$vectorNroCorrelativo[0];
$cod_dosificacion=$vectorNroCorrelativo[2];

$sqlDatosDosif="select d.nro_autorizacion, d.llave_dosificacion 
		from dosificaciones d where d.cod_dosificacion='$cod_dosificacion'";
$respDatosDosif=mysql_query($sqlDatosDosif);
$nroAutorizacion=mysql_result($respDatosDosif,0,0);
$llaveDosificacion=mysql_result($respDatosDosif,0,1);
include 'controlcode/sin/ControlCode.php';
$controlCode = new ControlCode();
$code = $controlCode->generate($nroAutorizacion,//Numero de autorizacion
								   $nro_correlativo,//Numero de factura
								   $nitCliente,//Número de Identificación Tributaria o Carnet de Identidad
								   str_replace('-','',$fecha),//fecha de transaccion de la forma AAAAMMDD
								   $totalFinalRedondeado,//Monto de la transacción
								   $llaveDosificacion//Llave de dosificación
								   );
								   
$sqlInsertFactura="insert into facturas_venta (cod_dosificacion, cod_sucursal, nro_factura, cod_estado, razon_social, nit, fecha, importe, 
		codigo_control, cod_venta) values ('$cod_dosificacion','$globalSucursal','$nro_correlativo','1','$razonSocial','$nitCliente','$fecha','$totalFinal',
		'$code','$codVenta')";
//echo $sqlInsertFactura;
$respInsertFactura=mysql_query($sqlInsertFactura);

$sqlUpdNota="update salida_almacenes set cod_tipo_doc=1, nro_correlativo=$nro_correlativo, cod_dosificacion=$cod_dosificacion,
observaciones='Factura Convertida de NR', razon_social='$razonSocial', nit='$nitCliente' where salida_almacenes.cod_salida_almacenes=$codVenta";
$respUpdNota=mysql_query($sqlUpdNota);


echo "<script type='text/javascript' language='javascript'>
			alert('La transaccion fue completada.');
			location.href='formatoFactura2.php?codVenta=$codVenta';
			</script>";

?>