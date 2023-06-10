<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexionmysqli2.inc");
$num=$_GET['codigo'];

$fechaActual=date("Y-m-d");
?>

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="5%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="20%" align="left"><!--?php echo $num;?-->
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="0">
<div id="cod_material<?php echo $num;?>" class='textomedianorojo'>-</div>
</td>
<!-- CANTIDAD -->
<td align="center" width="10%">
<input type="number" class="inputnumber" min="1" max="1000000" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="0" onKeyUp='calculaPrecioCliente(0, <?php echo $num;?>);' onChange='calculaPrecioCliente(0, <?php echo $num;?>);' required>
</td>

<!-- PRECIO UNITARIO -->
<td align="center" width="10%">
<input type="number" class="inputnumber" min="0.01" max="1000000" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" size="5"  value="0" onKeyUp='calculaPrecioCliente(0, <?php echo $num;?>);' onChange='calculaPrecioCliente(0, <?php echo $num;?>);' step="0.01" required>
</td>

<!--td align="center" width="10%">
<input type="text" class="textoform" id="lote<?php echo $num;?>" name="lote<?php echo $num;?>" size="10" value="0" required>
</td-->

<td align="center" width="10%">
<input type="date" class="textoform" min="<?php echo $fechaActual; ?>" id="fechaVenc<?php echo $num;?>" name="fechaVenc<?php echo $num;?>" size="5" required>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="precio_old<?php echo $num;?>" name="precio_old<?php echo $num;?>" size="5" min="0" step="0.01" onKeyUp='calculaPrecioCliente(this,<?php echo $num;?>);' onChange='calculaPrecioCliente(this,<?php echo $num;?>);' required>
</td>

<!-- DESCUENTO UNITARIO -->
<td align="center" width="5%">
%<input type="number" class="inputnumber" min="1" max="1000000" id="descuento_porcentaje<?php echo $num;?>" name="descuento_porcentaje<?php echo $num;?>" size="5"  value="0" onKeyUp='calcularDescuentoUnitario(1, <?php echo $num;?>);' onChange='calcularDescuentoUnitario(1, <?php echo $num;?>);' step="0.01" required data-tipo="1">
Bs.<input type="number" class="inputnumber" min="0" max="1000000" id="descuento_numero<?php echo $num;?>" name="descuento_numero<?php echo $num;?>" size="5"  value="0" onKeyUp='calcularDescuentoUnitario(0, <?php echo $num;?>);' onChange='calcularDescuentoUnitario(0, <?php echo $num;?>);' step="0.01" required data-tipo="0">
</td>

<!-- Decuento Adicional -->
<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="descuento_adicional<?php echo $num;?>" name="descuento_adicional<?php echo $num;?>" size="5" min="0" step="0.01" disabled>
</td>

<!-- Monto TOTAL -->
<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="precio<?php echo $num;?>" name="precio<?php echo $num;?>" size="5" min="0" step="0.01" readonly>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="preciocliente<?php echo $num;?>" name="preciocliente<?php echo $num;?>" size="4" min="0" step="0.01" onKeyUp='calculaMargen(this,<?php echo $num;?>);' onChange='calculaMargen(this,<?php echo $num;?>);' style="height:20px;font-size:18px;width:80px;color:red;" required>
</br>
<div id="divpreciocliente<?php echo $num;?>" class="textopequenorojo">-</div>
<div id="divmargen<?php echo $num;?>" class="textopequenorojo2">-</div>
<input type="hidden" name="margenlinea<?php echo $num;?>" id="margenlinea<?php echo $num;?>" value="0">
</td>


<!--td align="center" width="20%">
<select name="ubicacion_estante<?php echo $num;?>">
<?php
	$sql="select codigo, nombre from ubicaciones_estantes where cod_estado=1";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
?>
	<option value="<?=$dat[0];?>"><?=$dat[1];?></option>
<?php
	}
?>
</select>
<select name="ubicacion_fila<?php echo $num;?>">
<?php
	$sql="select codigo, nombre from ubicaciones_filas where cod_estado=1";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
?>
	<option value="<?=$dat[0];?>"><?=$dat[1];?></option>
<?php
	}
?>
</select>
</td-->
<td align="center"  width="15%" >
	<input class="boton2peque" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>
</tr>
</table>

</head>
</html>