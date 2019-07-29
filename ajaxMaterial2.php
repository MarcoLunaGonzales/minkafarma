<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexion.inc");
$num=$_GET['codigo'];

$fechaActual=date("Y-m-d");
?>

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="5%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="30%" align="center">
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="0">
<input type="hidden" name="cantidad_presentacion<?php echo $num;?>" id="cantidad_presentacion<?php echo $num;?>" value="1">
<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td align="center" width="20%">
<input type="number" class="inputnumberpequeno" min="0" max="1000000" id="cajas<?php echo $num;?>" name="caja<?php echo $num;?>" size="3" onKeyUp="calculaCantidades('<?php echo $num;?>')" value="0" required>
<input type="number" class="inputnumberpequeno" min="0" max="1000000" id="unidades<?php echo $num;?>" name="unidades<?php echo $num;?>" size="4" onKeyUp="calculaCantidades('<?php echo $num;?>')" value="0" required>
</td>

<td align="center" width="10%">
	<input type="number" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" min="1" value="0" readonly="true" required="required">
</td>


<td align="center" width="8%">
<input type="text" id="lote<?php echo $num;?>" name="lote<?php echo $num;?>" class="inputnumberpequeno" value="0" required>
</td>

<td align="center" width="10%">
<input type="date" class="textoform" min="<?php echo $fechaActual; ?>" id="fechaVenc<?php echo $num;?>" name="fechaVenc<?php echo $num;?>" size="5" required>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="precio<?php echo $num;?>" name="precio<?php echo $num;?>" size="5" min="0" required>

<td align="center"  width="7%" ><input class="boton2" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>

</tr>
</table>

</head>
</html>