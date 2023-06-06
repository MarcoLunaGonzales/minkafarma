
<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$indice = $_GET["indice"];
$codTipoPrecio=$_GET["tipoPrecio"];

$codigoCiudadGlobal=$_COOKIE["global_agencia"];
$globalAdmin=$_COOKIE["global_admin_cargo"];


//
require("conexionmysqli2.inc");

$cadRespuesta="";
$consulta="select p.`precio`,  p.descuento_unitario from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`=1 and 
    p.cod_ciudad='$codigoCiudadGlobal' ";
$rs=mysqli_query($enlaceCon,$consulta);
$registro=mysqli_fetch_array($rs);
$cadRespuesta=$registro[0];
$descuentoUnitarioProducto=$registro[1];

if($cadRespuesta==""){   
	$cadRespuesta=0;
}
$cadRespuesta=redondear2($cadRespuesta);
if($descuentoUnitarioProducto==""){
	$descuentoUnitarioProducto=0;
}

$descuentoAplicarCasoX=0;
/*Esto se aplica en los casos tipo no Farmacoss*/
$descuentoPrecio=0;
if($globalAdmin!=1){
	$sqlTipoPrecio="select abreviatura from tipos_precio where codigo='$codTipoPrecio'";
	$rsTipoPrecio=mysqli_query($enlaceCon,$sqlTipoPrecio);
	$datTipoPrecio=mysqli_fetch_array($rsTipoPrecio);
	$descuentoPrecio=$datTipoPrecio[0];	
}elseif($globalAdmin==1) {
	$descuentoPrecio=$codTipoPrecio;
}
/*FIN CASO 1 NO TIPO FARMACOSS*/

/*Aqui sacamos la configuracion si estan habilitados los descuentos en precio y de Mayoristas caso tipo Farmacoss*/
//Si la Bandera es 1 procedemos
$banderaPreciosDescuento=obtenerValorConfiguracion($enlaceCon,52);
$maximoDescuentoPrecio=0;
$descuentoMayorista=0;
if($banderaPreciosDescuento==1){
	$maximoDescuentoPrecio=obtenerValorConfiguracion($enlaceCon,53);
	if($maximoDescuentoPrecio < 0 || $maximoDescuentoPrecio==""){
		$maximoDescuentoPrecio=0;
	}
	$descuentoProductoGeneral=$descuentoUnitarioProducto*($maximoDescuentoPrecio/100);

	$descuentoMayorista=precioMayoristaSucursal($enlaceCon, $codigoCiudadGlobal);
	$descuentoAplicarCasoX=$descuentoProductoGeneral+$descuentoMayorista;
	$descuentoAplicarCasoX=redondear2($descuentoAplicarCasoX);

	$descuentoPrecio=$descuentoAplicarCasoX;
}
/*Fin caso tipo Farmacoss*/


$indiceConversion=0;
$descuentoPrecioMonto=0;
if($descuentoPrecio>0){
	$indiceConversion=($descuentoPrecio/100);
	$descuentoPrecioMonto=round($cadRespuesta*($indiceConversion),2);
	//$cadRespuesta=$cadRespuesta-($cadRespuesta*($indiceConversion));
}


echo "<input type='number' id='precio_unitario$indice' min='0.1' name='precio_unitario$indice' value='$cadRespuesta' class='inputnumber' onKeyUp='calculaMontoMaterial($indice);' step='0.01' readonly>";
//echo " CP[$costoMaterialii]";
echo "<input type='hidden' id='costoUnit$indice' value='$costoMaterialii' name='costoUnit$indice'>#####".$descuentoPrecioMonto."#####".$descuentoAplicarCasoX;
?>