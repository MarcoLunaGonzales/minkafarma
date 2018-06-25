<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexion.inc");
	$num=$_GET['codigo'];
?>

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%"  style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">
<td width="60%" align="center">
<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B">Buscar</a>
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="0">
<input type="text" class="textoform" id="cod_material<?php echo $num;?>" name="cod_material<?php echo $num;?>" onChange="" size="70" readonly>
</td>

<td align="center" width="8%">
<input type="text" class="textoform" value="0"  id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5" onKeyUp="precioNeto('<?php echo $num;?>')">
</td>
<td align="center" width="8%">
<input type="text" class="textoform" value="0" id="precio<?php echo $num;?>" name="precio<?php echo $num;?>" onKeyUp="precioNeto('<?php echo $num;?>')" size="5" >
</td>
<td align="center" width="8%">
<input type="text" class="textoform" value="0" id="neto<?php echo $num;?>" name="neto<?php echo $num;?>" size="5" readonly>
</td>

<td align="center"  width="8%" ><input class="boton1" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>

</tr>
</table>

</head>
</html>