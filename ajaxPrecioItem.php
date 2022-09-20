
<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$indice = $_GET["indice"];
$codTipoPrecio=$_GET["tipoPrecio"];

//
require("conexionmysqli2.inc");
$cadRespuesta="";
$consulta="
    select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`=1";
$rs=mysqli_query($enlaceCon,$consulta);
$registro=mysqli_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}
$cadRespuesta=redondear2($cadRespuesta);

$sqlTipoPrecio="select abreviatura from tipos_precio where codigo='$codTipoPrecio'";
$rsTipoPrecio=mysqli_query($enlaceCon,$sqlTipoPrecio);
$datTipoPrecio=mysqli_fetch_array($rsTipoPrecio);
$descuentoPrecio=$datTipoPrecio[0];
$indiceConversion=0;
$descuentoPrecioMonto=0;
if($descuentoPrecio>0){
	$indiceConversion=($descuentoPrecio/100);
	$descuentoPrecioMonto=round($cadRespuesta*($indiceConversion),2);
	//$cadRespuesta=$cadRespuesta-($cadRespuesta*($indiceConversion));
}


/*$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia'";
$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
$dat_almacen=mysqli_fetch_array($resp_almacen);
$global_almacen=$dat_almacen[0];

$sqlCosto="select id.costo_promedio from ingreso_almacenes i, ingreso_detalle_almacenes id
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
id.cod_material='$codMaterial' and i.cod_almacen='$global_almacen' ORDER BY i.cod_ingreso_almacen desc limit 0,1";
$respCosto=mysqli_query($enlaceCon,$sqlCosto);
$costoMaterialii=0;
while($datCosto=mysqli_fetch_array($respCosto)){
	$costoMaterialii=$datCosto[0];
	$costoMaterialii=redondear2($costoMaterialii);
}*/

echo "<input type='number' id='precio_unitario$indice' min='0.1' name='precio_unitario$indice' value='$cadRespuesta' class='inputnumber' onKeyUp='calculaMontoMaterial($indice);' step='0.01' readonly>";
//echo " CP[$costoMaterialii]";
echo "<input type='hidden' id='costoUnit$indice' value='$costoMaterialii' name='costoUnit$indice'>#####".$descuentoPrecioMonto;
?>
