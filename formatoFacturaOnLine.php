<script type="text/javascript">
	function printDiv(nombreDiv) {
     var contenido= document.getElementById(nombreDiv).innerHTML;
     var contenidoOriginal= document.body.innerHTML;

     document.body.innerHTML = contenido;

     window.print();

     document.body.innerHTML = contenidoOriginal;
}
</script>

<style type="text/css">
	body {color:#000 }
	/*@media print {
      body {
        color:#C2C0C0 !important;
      }
    }*/
</style>
<?php
$estilosVenta=1;
require('conexionmysqli.inc');
require('funciones.php');
require('funcion_nombres.php');
require('NumeroALetras.php');
include('phpqrcode/qrlib.php');

/*error_reporting(E_ALL);
ini_set('display_errors', '1');
*/



?>
<body>
<style type="text/css">
	.arial-12{
        font-size: 16px;  /*14*/
	}
	.arial-7{
        font-size: 14px;  /*14*/
	}
	.arial-8{
        font-size: 15px;  /*14*/
	}
</style>
<?php
$cod_ciudad=$_COOKIE["global_agencia"];
$codigoVenta=$_GET["codVenta"];



//consulta cuantos items tiene el detalle
$sqlNro="select count(*) from `salida_detalle_almacenes` s where s.`cod_salida_almacen`=$codigoVenta";
$respNro=mysqli_query($enlaceCon,$sqlNro);
$nroItems=mysqli_result($respNro,0,0);

$tamanoLargo=400+($nroItems*10)-10;

?><div style="width:320;margin:0;padding-left:30px !important;padding-right:30px !important;height:<?=$tamanoLargo?>; font-family:Arial;">
<?php	

$sqlConf="select id, valor from configuracion_facturas where id=1 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nombreTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=10 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nombreTxt2=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=2 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$sucursalTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=3 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$direccionTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=4 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$telefonoTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=5 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$ciudadTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=6 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt1=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from siat_leyendas where id=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt2=mysqli_result($respConf,0,1);

//ALEATORIAMENTE SON DOS PORQUE AL PRIMER RAND SIEMPRE RETORNA EL MISMO
// $sqlConf="SELECT descripcionLeyenda FROM siat_sincronizarlistaleyendasfactura where codigoActividad=471110 ORDER BY rand() LIMIT 1;";
// $respConf=mysqli_query($enlaceCon,$sqlConf);
// $txt3=mysqli_result($respConf,0,0);

// $sqlConf="SELECT descripcionLeyenda FROM siat_sincronizarlistaleyendasfactura where codigoActividad=471110 ORDER BY rand() LIMIT 1;";
// $respConf=mysqli_query($enlaceCon,$sqlConf);
// $txt3=mysqli_result($respConf,0,0);


$sqlConf="select id, valor from configuracion_facturas where id=9 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nitTxt=mysqli_result($respConf,0,1);


// $sqlDatosFactura="select '' as nro_autorizacion, '', f.codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.fecha, '%d/%m/%Y') from facturas_venta f
// 	where f.cod_venta=$codigoVenta";
$sqlDatosFactura="select '' as nro_autorizacion, '', '' as codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.siat_fechaemision, '%d/%m/%Y'), f.fecha from salida_almacenes f
	where f.cod_salida_almacenes=$codigoVenta";
//echo $sqlDatosFactura;
$respDatosFactura=mysqli_query($enlaceCon,$sqlDatosFactura);
$nroAutorizacion=mysqli_result($respDatosFactura,0,0);
$fechaLimiteEmision=mysqli_result($respDatosFactura,0,1);
$codigoControl=mysqli_result($respDatosFactura,0,2);
$nitCliente=mysqli_result($respDatosFactura,0,3);
$razonSocialCliente=mysqli_result($respDatosFactura,0,4);
$razonSocialCliente=strtoupper($razonSocialCliente);
$fechaFactura=mysqli_result($respDatosFactura,0,5);

$fechaFacturaFormatoMy=mysqli_result($respDatosFactura,0,6);

$cod_funcionario=$_COOKIE["global_usuario"];
//datos documento



$sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, c.`nombre_cliente`, s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,(SELECT cufd from siat_cufd where codigo=s.siat_codigocufd) as cufd,siat_cuf,siat_complemento,s.siat_codigoPuntoVenta,s.siat_codigotipoemision,(SELECT descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda
		from `salida_almacenes` s, `tipos_docs` t, `clientes` c
		where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_cliente`=c.`cod_cliente` and
		s.`cod_tipo_doc`=t.`codigo`";
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
	$montoEfectivo2=$datDatosVenta['monto_efectivo'];
	$montoCambio2=$datDatosVenta['monto_cambio'];
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
}
$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
$nombreFuncionario=mysqli_result($respResponsable,0,0);
//$nombreFuncionario=nombreVisitador($cod_funcionario);
$y=5;
$incremento=3;


if($siat_codigotipoemision==2){
    $sqlConf="select id, valor from siat_leyendas where id=3";
}else{
    $sqlConf="select id, valor from siat_leyendas where id=2";
}
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txtLeyendaFin=mysqli_result($respConf,0,1);


?>

<script type="text/javascript">
	// Conclusión
(function() {
  /**
   * Ajuste decimal de un número.
   *
   * @param {String}  tipo  El tipo de ajuste.
   * @param {Number}  valor El numero.
   * @param {Integer} exp   El exponente (el logaritmo 10 del ajuste base).
   * @returns {Number} El valor ajustado.
   */
  function decimalAdjust(type, value, exp) {
    // Si el exp no está definido o es cero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // Si el valor no es un número o el exp no es un entero...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  // Decimal round
  if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
  }
  // Decimal floor
  if (!Math.floor10) {
    Math.floor10 = function(value, exp) {
      return decimalAdjust('floor', value, exp);
    };
  }
  // Decimal ceil
  if (!Math.ceil10) {
    Math.ceil10 = function(value, exp) {
      return decimalAdjust('ceil', value, exp);
    };
  }
})();
</script>

<center>
<label class="arial-12">FACTURA<br>CON DERECHO A CRÉDITO FISCAL</label><br>
<label class="arial-12"><?=$nombreTxt?></label><br>
<!-- <p class="arial-12"><?=$nombreTxt2?></p> -->
<label class="arial-12"><?=$sucursalTxt?></label><br>
<label class="arial-12">No. Punto de Venta <?=$siat_codigopuntoventa?></label><br>
<label class="arial-12"><?=$direccionTxt?></label><br>
<label class="arial-12"><?="Telefono ".$telefonoTxt?></label><br>
<div style="border-bottom: 1px solid black;border-bottom-style: dotted;">
	<label class="arial-12"><?=$ciudadTxt?></label>
</div>

<label class="arial-12"><?="NIT: $nitTxt"?></label><br>
<label class="arial-12"><?="$nombreTipoDoc N° $nroDocVenta"?></label><br>
<div class="d-flex" style="width:300px;hyphens: auto;word-wrap: break-word;word-break: break-word;border-bottom: 1px solid black;border-bottom-style: dotted;">
<label class="arial-12"><?="CÓD. DE AUTORIZACIÓN: $cuf"?></label>
	</div>	
<label class="arial-12"><?="NOMBRE/RAZÓN SOCIAL: ".utf8_decode($razonSocialCliente).""?></label><br>
<label class="arial-12"><?="NIT/CI/CEX:	$nitCliente ".$siat_complemento?></label><br>
<label class="arial-12"><?="COD. CLIENTE:	$cod_cliente"?></label><br>
<label class="arial-12"><?="FECHA EMISIÓN: $fechaFactura $horaFactura"?></label><br>
<label class="arial-12"><?="======================================"?></label><br>
<table width="100%">
	<tr align="center" class="arial-12"><td width="15%">Cantidad</td>
		<td width="10%">P.U.</td>
		<td width="25%">Monto</td>
		<td align="right" width="20%">Desc.</td>
		<td width="30%">SubTotal</td></tr></table>
<label class="arial-12"><?="======================================"?></label><br>
<?php
$sqlDetalle="select m.codigo_material, sum(s.`cantidad_unitaria`), m.`descripcion_material`, s.`precio_unitario`, 
		sum(s.`descuento_unitario`), sum(s.`monto_unitario`) from `salida_detalle_almacenes` s, `material_apoyo` m where 
		m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`=$codigoVenta 
		group by s.cod_material
		order by s.orden_detalle";
$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

$yyy=65;

$montoTotal=0;$descuentoVentaProd=0;
while($datDetalle=mysqli_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$nombreMat=$datDetalle[2];
	$precioUnit=$datDetalle[3];
	$descUnit=$datDetalle[4];
	//$montoUnit=$datDetalle[5];
	$montoUnit=($cantUnit*$precioUnit)-$descUnit;
	
	//recalculamos el precio unitario para mostrar en la factura.
	//$precioUnitFactura=$montoUnit/$cantUnit;
	$precioUnitFactura=($cantUnit*$precioUnit)/$cantUnit;
	$cantUnit=redondear2($cantUnit);
	$precioUnit=redondear2($precioUnit);
	$montoUnit=redondear2($montoUnit);
	
	$precioUnitFactura=redondear2($precioUnitFactura);

	// - $descUnit
	$descUnit=redondear2($descUnit);	
	$descuentoVentaProd+=$descUnit;
	$montoUnitProd=($cantUnit*$precioUnit);

	$montoUnitProdF=redondear2($montoUnitProd);

	$montoUnitProdDesc=$montoUnitProd-$descUnit;
	$montoUnitProdDesc=redondear2($montoUnitProdDesc);

	$montoUnitProd=redondear2($montoUnitProd);

	?>
    <table width="100%">
    	<tr class="arial-7">
    		<td align="left"  width="15%">(<?=$codInterno?>)</td>
    		<td colspan="4" align="left"><?=$nombreMat?></td></tr>
    	<tr class="arial-8">
    		<td align="left"  width="10%"><?="$cantUnit"?></td>
    		<td align="right" width="15%"><?="$precioUnitFactura"?></td>
    		<td align="right" width="25%"><?="$montoUnitProdF"?></td>
    		<td align="right"  width="20%"><?="$descUnit"?></td>
    		<td align="right"  width="30%"><?="$montoUnitProdDesc"?></td>
    	</tr></table>
	<?php
	//$montoTotal=$montoTotal+$montoUnitProd;	 ESTE ERA OFICIAL
	$montoTotal=$montoTotal+$montoUnitProdDesc;
	//$montoTotal=$montoTotal+redondear2($cantUnit*$precioUnit);	
	$yyy=$yyy+6;
}

$descuentoVenta=number_format($descuentoVenta,2,'.','');
$montoFinal=$montoTotal-$descuentoVenta;
//$montoFinal=$montoTotal-$descuentoVenta;
//$montoTotal=number_format($montoTotal,1,'.','')."0";
$montoFinal=number_format($montoFinal,2,'.','');



/*?><script>
     var subtotal=Math.ceil10(<?=$montoTotal?>, -1); 
     var subfinal=Math.ceil10(<?=$montoFinal?>, -1);    	
</script>
<?php

if(isset($_GET["var_php2"])){
   $montoFinal=$_GET["var_php2"];
   $montoTotal=$_GET["var_php"];
}else{
     echo "<script language='javascript'>
             window.location.href = window.location.href + '&var_php=' + subtotal + '&var_php2=' + subfinal;</script>";
}*/




//$montoTotal2 = "<script> document.writeln(subtotal); </script>";
//$montoFinal2 = "<script> document.writeln(subfinal); </script>";
//$montoFinal=$montoTotal2-$descuentoVenta;
?>
<div style="border-bottom: 1px solid black;border-bottom-style: dotted;">
</div>
<!-- <label class="arial-12"><?="======================================"?></label><br> -->
<table width="100%">
	<tr align="center" class="arial-7"><td width="10%"></td><td align="right"><?="SUBTOTAL Bs:"?></td><td align="right"><?="$montoTotal"?></td></tr>
	<tr align="center" class="arial-7"><td width="10%"></td><td align="right"><?="DESCUENTO Bs:"?></td><td align="right"><?="$descuentoVenta"?></td></tr>
	<tr align="center" class="arial-7"><td width="10%"></td><td align="right"><?="TOTAL Bs:"?></td><td align="right"><?="$montoFinal"?></td></tr>
	<tr align="center" class="arial-7"><td width="10%"></td><td align="right"><?="MONTO A PAGAR Bs:"?></td><td align="right"><?="$montoFinal"?></td></tr>
	<tr align="center" class="arial-7"><td width="10%"></td><td align="right"><?="IMPORTE BASE CRÉDITO FISCAL:"?></td><td align="right"><?="$montoFinal"?></td></tr>
</table>
<?php
$arrayDecimal=explode('.', $montoFinal);
if(count($arrayDecimal)>1){
	list($montoEntero, $montoDecimal) = explode('.', $montoFinal);
}else{
	list($montoEntero,$montoDecimal)=array($montoFinal,0);
}

if($montoDecimal==""){
	$montoDecimal="00";
}
$txtMonto=NumeroALetras::convertir($montoEntero);

?>
<label class="arial-12" style="float: left;"><?="Son:  $txtMonto"." ".$montoDecimal."/100 Bolivianos"?></label>
<table width="100%">
	<!-- <tr align="center" class="arial-8"><td width="50%"></td><td></tr> -->
	<tr align="center" class="arial-8"><td width="50%"><?="Total Recibido:  $montoEfectivo2"?></td><td><?="Total Cambio:  $montoCambio2"?></td></tr>
	<?php 
	if($tipoPago==2){
	?>
	<tr align="center" class="arial-8"><td width="50%"><?="PAGO CON TARJETA"?></td><td></td></tr>
	<?php	
	}?>
</table>
<div style="border-bottom: 1px solid black;border-bottom-style: dotted;">
</div>
<!-- <label class="arial-12"><?="======================================"?></label><br> -->
<!-- <div class="d-flex" style="width:300px;hyphens: auto;word-wrap: break-word;word-break: break-word;">
<label class="arial-12"><?="CUF: $cuf"?></label>
	</div> -->
<!-- <label class="arial-12"><?="FECHA LIMITE DE EMISION: $fechaLimiteEmision"?></label><br> -->
<label class="arial-12"><?="Proceso: $codigoVenta"?></label><br>
<div style="border-bottom: 1px solid black;border-bottom-style: dotted;">
<label class="arial-12"><?="Cajero(a): $nombreFuncionario"?></label>
</div>



<!-- ESTA PARTE ES PARA LAS CAMPANIAS -->
<?php
	$arrayCampaniaCliente=obtenerVentaClienteCampania($enlaceCon, $cod_cliente, $fecha_salida);
	$abrevCampaniaHabilitada=$arrayCampaniaCliente[0];
	$totalVentaClienteCampania=$arrayCampaniaCliente[1];
?>



<table>
	<tr><td colspan="2" align="center"><label class="arial-7" style="align-content: center;">C: <?=$abrevCampaniaHabilitada;?> -> <?=$totalVentaClienteCampania?></label></td></tr>
	<tr><td><label class="arial-7"><?=$txt2?></label></td><td>
<?php

$sqlDir="select valor_configuracion from configuraciones where id_configuracion=46";
$respDir=mysqli_query($enlaceCon,$sqlDir);
$urlDir=mysqli_result($respDir,0,0);
$cadenaQR=$urlDir."/consulta/QR?nit=$nitTxt&cuf=$cuf&numero=$nroDocVenta&t=2";
$codeContents = $cadenaQR; 

$fechahora=date("dmy.His");
$fileName="qrs/".$fechahora.$nroDocVenta.".png"; 
    
QRcode::png($codeContents, $fileName,QR_ECLEVEL_L, 4);
?>
<img src="<?=$fileName?>" style="margin: 0px;padding: 0;width: 150px;">
</td></tr></table>

<div style="width:97%"><label class="arial-7"><?=$txt3?></label><br><br><label class="arial-7">"<?=$txtLeyendaFin?>"</label><br></div>

</center>
</div>
</body>


<script type="text/javascript">
 javascript:window.print();
 setTimeout(function () { window.location.href="registrar_salidaventas.php";}, 1000);
</script>
