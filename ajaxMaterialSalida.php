<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexion.inc");
	$num=$_GET['codigo'];
?>

<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">
<td>
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>
<td width="50%" align="center">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td width="20%" align="center">
	<div id='idstock<?php echo $num;?>'>
		<input type='hidden' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value=''>
	</div>
</td>

<td align="center" width="20%">
	<input class="inputnumber" type="number" value="" min="1" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" required> 
</td>

<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>