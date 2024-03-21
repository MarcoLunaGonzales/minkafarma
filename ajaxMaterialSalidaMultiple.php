<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 
require_once("conexionmysqli2.inc");
require_once("funciones.php");
require_once("funcionesVentas.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$globalAdmin=$_COOKIE["global_admin_cargo"];
$globalAlmacen=$_COOKIE["global_almacen"];
$globalAgencia=$_COOKIE["global_agencia"];

$numJS=$_GET['codigo'];
$arrayProductos=$_GET['productos_multiple'];
$fechaActual=date("Y-m-d");

/*Esta Bandera es para la validacion de stocks*/
$banderaValidacionStock=obtenerValorConfiguracion($enlaceCon,4);

$arrayProductosX=explode(",",$arrayProductos);

$codigoProductoX=0;
$nombreProductoX="";
$lineaProductoX="";

$stockProductoX=0;
$precioProductoX=0;

for( $j=0;$j<=sizeof($arrayProductosX)-1;$j++ ){
	$num=$numJS+$j;
	//echo "num".$num."<br>";
	$arrayProductosDetalle=$arrayProductosX[$j];
	list($codigoProductoX,$nombreProductoX,$lineaProductoX,$stockProductoX)=explode("|",$arrayProductosDetalle);

	$arrayPreciosAplicar=precioCalculadoParaFacturacion($enlaceCon,$codigoProductoX,$globalAgencia,0);
	$precioProductoBase=$arrayPreciosAplicar[0];
	$txtValidacionPrecioCero=$arrayPreciosAplicar[1];
	$descuentoBs=$arrayPreciosAplicar[2];
	$descuentoPorcentaje=$arrayPreciosAplicar[3];
	$nombrePrecioAplicar=$arrayPreciosAplicar[4];

	$precioProductoX=round($precioProductoBase,2);

?>

<div id="div<?php echo $num?>">
<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">
<td>
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>
<td width="50%" align="left">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="<?=$codigoProductoX;?>">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'><?=$nombreProductoX;?>-<?=$lineaProductoX;?></div>
</td>

<td width="20%" align="center">
	<div id='idstock<?php echo $num;?>'>
		<input type="text" id="stock<?=$num;?>" name="stock<?=$num;?>" value="<?=$stockProductoX;?>" readonly size="4" 
	style="height:20px;font-size:19px;width:80px;color:red;"> 
	</div>
</td>

<td width="10%" align="center">
	<div id='div_idprecio<?php echo $num;?>' class='textomedianonegro'>
		<?=$precioProductoX;?>
	</div>
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="1" min="1" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" required> 
</td>

<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>
</div>
<?php
}
?>
</head>
</html>