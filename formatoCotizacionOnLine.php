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


error_reporting(E_ALL);
ini_set('display_errors', '1');




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
$codigoVenta=$_GET["codigo"];

$sqlEmpresa="select nombre, nit, direccion from datos_empresa";
$respEmpresa=mysqli_query($enlaceCon,$sqlEmpresa);
$datEmpresa=mysqli_fetch_array($respEmpresa);
$nombreEmpresa=$datEmpresa[0];//$nombreEmpresa=mysql_result($respEmpresa,0,0);

//consulta cuantos items tiene el detalle
$sqlNro="select count(*) from `cotizaciones_detalle` s where s.`cod_salida_almacen`=$codigoVenta";
$respNro=mysqli_query($enlaceCon,$sqlNro);
$nroItems=mysqli_result($respNro,0,0);

$tamanoLargo=230+($nroItems*5)-5;

$logoEmpresa=obtenerValorConfiguracion($enlaceCon,13);


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


$sqlConf="select id, valor from configuracion_facturas where id=9 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nitTxt=mysqli_result($respConf,0,1);


$sqlDatosFactura="select '' as nro_autorizacion, '', '' as codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.fecha, '%d/%m/%Y') from cotizaciones f
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

$cod_funcionario=$_COOKIE["global_usuario"];

$nroDocVenta=0;
//datos documento


$sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente 
		from `cotizaciones` s 
		where s.`cod_salida_almacenes`='$codigoVenta'";
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
$tipoPago=1;
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	$fechaVenta=$datDatosVenta[0];
	$nroDocVenta=$datDatosVenta[1];
	$descuentoVenta=$datDatosVenta[2];
	$descuentoVenta=redondear2($descuentoVenta);
	$horaFactura=$datDatosVenta[3];
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
	$codTipoDoc=$datDatosVenta['cod_tipo_doc'];

	$fecha_salida=$datDatosVenta['fecha'];
	$hora_salida=$datDatosVenta['hora_salida'];
	$cod_ciudad_salida=$datDatosVenta['cod_ciudad'];

}

$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
$nombreFuncionario=mysqli_result($respResponsable,0,0);
//$nombreFuncionario=nombreVisitador($cod_funcionario);
$y=5;
$incremento=3;

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

<table align="center">
<tr><td>
	<img src="imagenes/<?=$logoEmpresa?>" style="margin: 0px;padding: 0;width: 150px;">
</td></tr>
</table>

<center>
<label class="arial-14"><b><?=$nombreEmpresa;?></b></label><br>
<label class="arial-12"><?="COTIZACIÓN N° $nroDocVenta"?></label><br>
<label class="arial-12"><?="FECHA: $fechaFactura $horaFactura"?></label><br>

<label class="arial-12"><?="Responsable: ".utf8_decode($nombreFuncionario).""?></label><br>


<label class="arial-12"><?="======================================"?></label><br>
<table width="100%"><tr align="center" class="arial-12"><td width="15%"><?="CANT."?></td><td width="25%"><?="P.U."?></td><td align="right"  width="25%"><?="Desc."?></td><td width="35%"><?="IMPORTE"?></td></tr></table>
<label class="arial-12"><?="======================================"?></label><br>
<?php
$sqlDetalle="select m.codigo_material, sum(s.`cantidad_unitaria`), m.`descripcion_material`, s.`precio_unitario`, 
		sum(s.`descuento_unitario`), sum(s.`monto_unitario`) from `cotizaciones_detalle` s, `material_apoyo` m where 
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

	$montoUnitProdDesc=$montoUnitProd-$descUnit;
	$montoUnitProdDesc=redondear2($montoUnitProdDesc);

	$montoUnitProd=redondear2($montoUnitProd);

	?>
    <table width="100%"><tr class="arial-7"><td align="left"  width="15%">(<?=$codInterno?>)</td><td colspan="3" align="left"><?=$nombreMat?></td></tr>
    <tr class="arial-8"><td align="left"  width="15%"><?="$cantUnit"?></td><td align="right" width="25%"><?="$precioUnitFactura"?></td><td align="right"  width="25%"><?="$descUnit"?></td><td align="right"  width="35%"><?="$montoUnitProdDesc"?></td></tr></table>
	<?php
	//$montoTotal=$montoTotal+$montoUnitProd;	 ESTE ERA OFICIAL
	$montoTotal=$montoTotal+$montoUnitProdDesc;
	//$montoTotal=$montoTotal+redondear2($cantUnit*$precioUnit);	
	$yyy=$yyy+6;
}



// $descuentoVenta=number_format($descuentoVenta,1,'.','')."0";
// $montoFinal=$montoTotal-$descuentoVenta;
// //$montoFinal=$montoTotal-$descuentoVenta-$descuentoVentaProd; ESTE ERA OFICIAL
// //$montoTotal=number_format($montoTotal,1,'.','')."0";
// $montoFinal=number_format($montoFinal,1,'.','')."0";

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
</table>


</center>
</div>
</body>


<script type="text/javascript">
 javascript:window.print();
 setTimeout(function () { window.location.href="registrar_salidaventas.php";}, 1000);
</script>
