<?php 
require_once('conexionmysqli2.inc');

$num=$_GET['codigo'];

$globalAdmin=$_COOKIE["global_admin_cargo"];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF" class="lista_registro">

<td width="38%" align="center">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0" class="materiales">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td align="center" width="8%">
	<div id='idprecio<?php echo $num;?>'>
		<input class="inputnumber precio_unitario" type="number" min="1" value="0" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" step="0.01" readonly>
	</div>
</td>

<td align="center" width="15%">
	<input class='inputnumber descuentoProdPorcen' type='number' min='0' max='90' step='0.5' value='0' id='descuentoProdPorcen<?php echo $num;?>' name='descuentoProdPorcen<?php echo $num;?>' style='background:#ADF8FA;' onkeyup='calcularDescuento(<?php echo $num;?>)'>
</td>
<td align="center" width="15%">
	<input class="inputnumber descuentoProdNumber" type="number" value="0" id="descuentoProdNumber<?php echo $num;?>" name="descuentoProdNumber<?php echo $num;?>" step="0.01" style='background:#ADF8FA;' onkeyup='calcularDescuentoInverso(<?php echo $num;?>)'>
</td>

<td align="center" width="8%">
	<input class="inputnumber montoMaterial" type="number" value="0" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" value="0"  step="0.01" style="height:20px;font-size:19px;width:80px;color:red;" required readonly> 
</td>

<td align="center"  width="5%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>