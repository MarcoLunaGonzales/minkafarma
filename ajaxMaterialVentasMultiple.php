<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<?php 
require_once("conexionmysqli2.inc");
require_once("funciones.php");
require_once("funcionesVentas.php");


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

	$arrayPreciosAplicar=precioCalculadoParaFacturacion($enlaceCon,$codigoProductoX,$globalAgencia);
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

<td width="10%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
	<a href="javascript:buscarKardexProducto(form1, <?php echo $num;?>)" class="btn btn-dark btn-sm btn-fab" style='background:#1d2a76;color:#fff;'><i class='material-icons float-left' title="Ver Kardex">analytics</i></a>
	<a href="javascript:encontrarMaterial(<?php echo $num;?>)" class="btn btn-primary btn-sm btn-fab"><i class='material-icons float-left' title="Ver en otras Sucursales">place</i></a>   	    
</td>

<td width="33%" align="center">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="<?=$codigoProductoX;?>">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'><?=$nombreProductoX;?> - <?=$lineaProductoX;?></div>
</td>

<td width="5%" align="center">
	<div id="fecha_vencimiento<?php echo $num;?>" class='textosmallazul'>-</div>
</td>

<?php
echo "<td><div id='idstock<?php echo $num;?>'>";
echo "<input type='number' id='stock$num' name='stock$num' value='$stockProductoX' readonly size='5' style='height:20px;font-size:19px;width:80px;color:red;'>";
echo "</div></td>";
?>

<td align="center" width="8%">
	<input class="inputnumber" type="number" value="" min="1" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' required>
	<div id="div_venta_caja<?=$num;?>" class="textosmallazul"></div>
</td>


<td align="center" width="8%">
	<div id='idprecio<?php echo $num;?>'>
		<input class="inputnumber" type="number" min="1" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" step="0.01" value="<?php echo $precioProductoX;?>" <?=$txtValidacionPrecioCero;?> >
	</div>
</td>

<td align="center" width="15%">
	<input class="inputnumber" type="number" min="0" max="90" step="0.01" value="<?=$descuentoBs;?>" id="tipoPrecio<?php echo $num;?>" 	name="tipoPrecio<?php echo $num;?>" style="background:#ADF8FA;" readonly>%
	<input class="inputnumber" type="number" value="<?=$descuentoPorcentaje;?>" id="descuentoProducto<?php echo $num;?>" name="descuentoProducto<?php echo $num;?>" step="0.01" style='background:#ADF8FA;' readonly>
	<div id="divMensajeOferta<?=$num;?>" class="textosmallazul"><?=$nombrePrecioAplicar;?></div>
</td>

<td align="center" width="8%">
	<input class="inputnumber" type="number" value="0" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" value="0"  step="0.01" readonly> 
</td>

<td align="center"  width="5%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>
</div>
<?php
}
?>
</head>
</html>