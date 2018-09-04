
<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$indice = $_GET["indice"];
$codTipoPrecio=$_GET["tipoPrecio"];

//
require("conexion.inc");
$cadRespuesta="";
$consulta="
    select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`='$codTipoPrecio'";
$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}

$cadRespuesta=redondear2($cadRespuesta);

$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia'";
$resp_almacen=mysql_query($sql_almacen);
$dat_almacen=mysql_fetch_array($resp_almacen);
$global_almacen=$dat_almacen[0];

$sqlCosto="select id.costo_promedio from ingreso_almacenes i, ingreso_detalle_almacenes id
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
id.cod_material='$codMaterial' and i.cod_almacen='$global_almacen' ORDER BY i.cod_ingreso_almacen desc limit 0,1";
$respCosto=mysql_query($sqlCosto);
$costoMaterialii=0;
while($datCosto=mysql_fetch_array($respCosto)){
	$costoMaterialii=$datCosto[0];
	$costoMaterialii=redondear2($costoMaterialii);
}

echo "<input type='number' id='precio_unitario$indice' name='precio_unitario$indice' value='$cadRespuesta' class='inputnumber' onKeyUp='calculaMontoMaterial($indice);' step='0.01'>";
//echo " CP[$costoMaterialii]";
echo "<input type='hidden' id='costoUnit$indice' value='$costoMaterialii' name='costoUnit$indice'>";

?>
