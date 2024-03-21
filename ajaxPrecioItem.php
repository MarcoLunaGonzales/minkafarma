<?php
require("funciones.php");
require("funcionesVentas.php");
require("conexionmysqli2.inc");

$codMaterial = $_GET["codmat"];
$indice = $_GET["indice"];
$codTipoPrecio=$_GET["tipoPrecio"];
$codCliente=$_GET["cod_cliente"];
if($codCliente==""){
	$codCliente=0;
}

$codigoCiudadGlobal=$_COOKIE["global_agencia"];
$globalAdmin=$_COOKIE["global_admin_cargo"];

//Bandera para mostrar 1 Decimal o 2 Decimales en el precio
$bandera1DecimalPrecioVenta=obtenerValorConfiguracion($enlaceCon,27);


$arrayPreciosAplicar=precioCalculadoParaFacturacion($enlaceCon,$codMaterial,$codigoCiudadGlobal,$codCliente);

$precioProductoBase=$arrayPreciosAplicar[0];
$txtValidacionPrecioCero=$arrayPreciosAplicar[1];
/*Cuando el precio es abierto se envia una cadena con |xxx|*/
$txtValidacionPrecioCero=str_replace("|xxx|", $indice, $txtValidacionPrecioCero);


$descuentoBs=$arrayPreciosAplicar[2];
$descuentoPorcentaje=$arrayPreciosAplicar[3];
$nombrePrecioAplicar=$arrayPreciosAplicar[4];

if($bandera1DecimalPrecioVenta==1){
	$precioProductoBase=round($precioProductoBase,1);
}else{
	$precioProductoBase=round($precioProductoBase,2);
}

echo "<input type='number' id='precio_unitario$indice' min='0.01' name='precio_unitario$indice' value='$precioProductoBase' class='inputnumber' step='0.01' $txtValidacionPrecioCero>";

echo "<input type='hidden' id='costoUnit$indice' value='0' name='costoUnit$indice'>#####".$descuentoBs."#####".$descuentoPorcentaje."#####".$nombrePrecioAplicar;


?>