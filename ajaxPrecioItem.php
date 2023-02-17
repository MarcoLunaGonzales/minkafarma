
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
$consulta="select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`=1 and 
    p.cod_ciudad='$codigoCiudadGlobal' ";
$rs=mysqli_query($enlaceCon,$consulta);
$registro=mysqli_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}
$cadRespuesta=redondear2($cadRespuesta);

$descuentoPrecio=0;
if($globalAdmin!=1){
	$sqlTipoPrecio="select abreviatura from tipos_precio where codigo='$codTipoPrecio'";
	$rsTipoPrecio=mysqli_query($enlaceCon,$sqlTipoPrecio);
	$datTipoPrecio=mysqli_fetch_array($rsTipoPrecio);
	$descuentoPrecio=$datTipoPrecio[0];	
}elseif($globalAdmin==1) {
	$descuentoPrecio=$codTipoPrecio;
}



$indiceConversion=0;
$descuentoPrecioMonto=0;
if($descuentoPrecio>0){
	$indiceConversion=($descuentoPrecio/100);
	$descuentoPrecioMonto=round($cadRespuesta*($indiceConversion),2);
	//$cadRespuesta=$cadRespuesta-($cadRespuesta*($indiceConversion));
}

echo "<input type='number' id='precio_unitario$indice' min='0.1' name='precio_unitario$indice' value='$cadRespuesta' class='inputnumber' onKeyUp='calculaMontoMaterial($indice);' step='0.01' readonly>";
//echo " CP[$costoMaterialii]";
echo "<input type='hidden' id='costoUnit$indice' value='$costoMaterialii' name='costoUnit$indice'>#####".$descuentoPrecioMonto;
?>