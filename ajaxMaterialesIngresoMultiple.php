<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexionmysqli2.inc");
$numJS=$_GET['codigo'];
$arrayProductos=$_GET['productos_multiple'];
$fechaActual=date("Y-m-d");


$arrayProductosX=explode(",",$arrayProductos);
//echo "numero: ".$num." <br> ".var_dump($arrayProductos);
//reducimos el num en 1
//$numJS=$numJS-1;

//echo "inicio numjs:".$numJS."<br>";

//echo sizeof($arrayProductosX)."<br>";

for( $j=0;$j<=sizeof($arrayProductosX)-1;$j++ ){
	$num=$numJS+$j;
	//echo "num".$num."<br>";
	$arrayProductosDetalle=$arrayProductosX[$j];
	list($codigoProductoX,$nombreProductoX,$cantPresentacionX,$precioProductoX,$margenLineaX)=explode("|",$arrayProductosDetalle);
	$precioProductoX=round($precioProductoX,2);
?>
<div id="div<?php echo $num?>">
<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="10%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="40%" align="left"><?php echo $num;?>
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="<?=$codigoProductoX;?>">
<div id="cod_material<?php echo $num;?>" class='textomedianorojo'><?=$nombreProductoX;?></div>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" min="1" max="1000000" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="" onKeyUp='totalesMonto(<?php echo $num;?>);' onChange='totalesMonto(<?php echo $num;?>);' required>
</td>

<!--td align="center" width="10%">
<input type="text" class="textoform" id="lote<?php echo $num;?>" name="lote<?php echo $num;?>" size="10" value="0" required>
</td-->

<td align="center" width="10%">
<input type="date" class="textoform" min="<?php echo $fechaActual; ?>" id="fechaVenc<?php echo $num;?>" name="fechaVenc<?php echo $num;?>" size="5" required>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="precio<?php echo $num;?>" name="precio<?php echo $num;?>" size="5" min="0" step="0.01" onKeyUp='calculaPrecioCliente(this,<?php echo $num;?>);' onChange='calculaPrecioCliente(this,<?php echo $num;?>);' required>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="preciocliente<?php echo $num;?>" name="preciocliente<?php echo $num;?>" size="4" min="0" step="0.01" onKeyUp='calculaMargen(this,<?php echo $num;?>);' onChange='calculaMargen(this,<?php echo $num;?>);' required>
</br>
<div id="divpreciocliente<?php echo $num;?>" class="textopequenorojo"><?=$precioProductoX;?></div>
<div id="divmargen<?php echo $num;?>" class="textopequenorojo2">-</div>
<input type="hidden" name="margenlinea<?php echo $num;?>" id="margenlinea<?php echo $num;?>" value="<?=$margenLineaX;?>">
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
<td align="center"  width="10%" ><input class="boton2peque" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>
</tr>
</table>

</div>

<?php
}
?>

</head>
</html>