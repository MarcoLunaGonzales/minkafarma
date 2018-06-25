<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexion.inc");
	$num=$_GET['codigo'];
?>

<table border="1" align="center" cellSpacing="1" cellPadding="1" width="100%"  style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="40%" align="center">
<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)">Buscar</a>

<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0">
<input type="text" id="cod_material<?php echo $num;?>" name="cod_material<?php echo $num;?>" onChange="" size="30" readonly>
</td>

<td width="7.5%">
<div id='idstock<?php echo $num;?>'>
<input type='text' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value='' readonly size="4">
</div>
</td>

<td align="center" width="7.5%">
<div id='idcantidad_unitaria<?php echo $num;?>'>
<input type="text" value="0"  id="cantidadMM<?php echo $num;?>" name="cantidadMM<?php echo $num;?>" size="4" onChange='calcularCantidadMetros(<?php echo $num;?>);'>
</div>
<div id='idnro_metros<?php echo $num;?>'>
<input type="text" value="0"  id="nro_metros<?php echo $num;?>" name="nro_metros<?php echo $num;?>" size="4" onChange='calcularCantidadMetros(<?php echo $num;?>);'>
<input type="hidden" value="0"  id="cantidadMetrosItem<?php echo $num;?>" name="cantidadMetrosItem<?php echo $num;?>">
</div>
<td align="center" width="7.5%">
<input type="text" value="0"  id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="4" readonly> 
</td>


<td align="center" width="7.5%">
<div id='idprecio<?php echo $num;?>'>
<input type="text" class="textoform" value="0" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" size="4" onChange='calculaMontoMaterial(<?php echo $num;?>);'>
</div>
</td>

<td align="center" width="7.5%">
<input class="textoform" value="0" id="descuentoProducto<?php echo $num;?>" name="descuentoProducto<?php echo $num;?>" size="3" onChange='calculaMontoMaterial(<?php echo $num;?>);' value="0">
</td>

<td align="center" width="7.5%">
<input type="text"  value="0" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" size="4" value="0">
</td>

<td align="center" width="7.5%">
<div id='idpeso<?php echo $num;?>'>
<input type="hidden" value="0" id="pesoItem<?php echo $num;?>" name="pesoItem<?php echo $num;?>">
<input type="text"  value="0" id="pesoItemTotal<?php echo $num;?>" name="pesoMaterial<?php echo $num;?>" size="4" value="0">
</div>
</td>

<td align="center"  width="7.5%" ><input class="boton1" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>