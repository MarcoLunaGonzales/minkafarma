<?php 
require_once('conexionmysqli2.inc');
require_once('funciones.php');

 // error_reporting(E_ALL);
 // ini_set('display_errors', '1');


$num=$_GET['codigo'];

$globalAdmin=$_COOKIE["global_admin_cargo"];

/*Esta Bandera trabaja con el precio con descuento si es 1 los saca de la tabla si es 0 es descuento manual*/
$banderaPreciosDescuento=obtenerValorConfiguracion($enlaceCon,52);

/*Bandera de descuento abierto en Venta*/
$banderaDescuentoAbierto=obtenerValorConfiguracion($enlaceCon,54);


?>

<html>
<head>
<!--link rel="STYLESHEET" type="text/css" href="stilos.css" /-->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<table border="0" align="center" width="100%"  class="texto100" id="data<?php echo $num?>" style="background-color: white;">
<tr>

<td width="10%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
	<a href="javascript:buscarKardexProducto(form1, <?php echo $num;?>)" class="btn btn-dark btn-sm btn-fab" style='background:#1d2a76;color:#fff;'><i class='material-icons float-left' title="Ver Kardex">analytics</i></a>
	<a href="javascript:encontrarMaterial(<?php echo $num;?>)" class="btn btn-primary btn-sm btn-fab"><i class='material-icons float-left' title="Ver en otras Sucursales">place</i></a>
</td>

<td width="33%" align="center">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td width="5%" align="center" id="sec_fecha_vencimiento<?php echo $num;?>">
	<div id="fecha_vencimiento<?php echo $num;?>" class='textosmallazul'>-</div>
</td>

<td width="8%" align="center" id="sec_stock<?php echo $num;?>">
	<div id='idstock<?php echo $num;?>'>
		<input type="number" id="stock<?=$num;?>" name="stock<?=$num;?>" value="0" readonly size="5" style="height:20px;font-size:19px;width:80px;color:red;">
	</div>
</td>

<td align="center" width="8%">
	<input class="inputnumber" type="number" value="" min="1" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' required> 
	<div id="div_venta_caja<?=$num;?>" class="textosmallazul"></div>
</td>

<!--Cuando Carga el precio no es readonly para validar el precio 0 -->
<td align="center" width="8%">
	<div id='idprecio<?php echo $num;?>'>
		<input class="inputnumber" type="number" min="0.01" value="0" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" step="0.01">
	</div>
</td>

<td align="center" width="15%">
	<input class="inputnumber" type="number" min="0" max="90" step="0.01" value="0" id="tipoPrecio<?php echo $num;?>" name="tipoPrecio<?php echo $num;?>" style="background:#ADF8FA;" onkeyup='calculaMontoMaterial(<?php echo $num;?>);' onchange='calculaMontoMaterial(<?php echo $num;?>);' <?=($banderaDescuentoAbierto==0)?'readonly':'';?> >%

	<input class="inputnumber" type="number" value="0" id="descuentoProducto<?php echo $num;?>" name="descuentoProducto<?php echo $num;?>" step="0.01" style='background:#ADF8FA;' onkeyup='calculaMontoMaterial_bs(<?php echo $num;?>);' onchange='calculaMontoMaterial_bs(<?php echo $num;?>);' <?=($banderaDescuentoAbierto==0)?'readonly':'';?>>
	<div id="divMensajeOferta<?=$num;?>" class="textomedianosangre"></div>
</td>

<td align="center" width="5%">
	<input class="inputnumber" type="number" value="0" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" value="0"  step="0.01" style="height:20px;font-size:19px;width:80px;color:red;" required readonly> 
</td>

<td class="receta_detalle" width="3%">
	<input type="checkbox" checked="checked" class="check_box" name="receta_medica<?php echo $num;?>" id="receta_medica<?php echo $num;?>">
    <label for="receta_medica<?php echo $num;?>">PCR</label>
</td>

<td align="center"  width="5%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>