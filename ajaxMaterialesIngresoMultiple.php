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
<table border="1" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<!-- row -->
<input type="hidden" class="row-item" value="<?=$num?>">

<!-- Buscar -->
<td width="3%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<!-- Producto -->
<td width="26%" align="left">
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="<?=$codigoProductoX;?>">
<input type="hidden" name="cantidadpresentacion<?php echo $num;?>" id="cantidadpresentacion<?php echo $num;?>" value="<?=$cantPresentacionX?>">
	<div id="cod_material<?php echo $num;?>" class='textomedianorojo'><?=$nombreProductoX;?>
		<span class="textomedianonegro"> CP:<?=$cantPresentacionX;?></span>
	</div>
</td>

<!-- CANTIDAD -->
<td align="center" width="6%">
	<input type="number" class="inputnumber" min="1" max="1000000" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="" onKeyUp='calculaPrecioCliente(0, <?php echo $num;?>);' onKeyUp='calculaPrecioCliente(0, <?php echo $num;?>);' required>
	<!-- Botón para mostrar/ocultar el input de bonificación -->
	<span class="badge badge-secondary" id="badgeSpan<?php echo $num;?>" style="cursor: pointer; background-color: #d3d3d3; padding: 5px;" onclick="toggleBonificacion(<?php echo $num;?>)" title="Bonificación">
		B
	</span>
	<!-- Input de bonificación oculto por defecto -->
	<input type="number" class="inputnumber" min="0" id="bonificacion<?php echo $num;?>" name="bonificacion<?php echo $num;?>" placeholder="BonificadoUnit" value="0" style="display: none; width: 90px; background-color: #ffffcc; color: blue; font-size: 10px; font-weight: bold;" size="5" onChange='calculaPrecioCliente(0, <?php echo $num;?>);' onKeyUp='calculaPrecioCliente(0, <?php echo $num;?>);'>
</td>

<!-- PRECIO UNITARIO -->
<td align="center" width="6%">
<input type="number" class="inputnumber" min="0.01" max="1000000" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" size="5"  value="0" onKeyUp='calculaPrecioCliente(0, <?php echo $num;?>);' onChange='calculaPrecioCliente(0, <?php echo $num;?>);' step="0.01" required>
</td>

<!-- Fecha de vencimiento -->
<td align="center" width="10%">
<input type="date" class="textoform" min="<?php echo $fechaActual; ?>" id="fechaVenc<?php echo $num;?>" name="fechaVenc<?php echo $num;?>" size="5" required>
</td>

<!-- Subtotal -->
<td align="center" width="6%">
<input type="number" class="inputnumber" value="0" id="precio_old<?php echo $num;?>" name="precio_old<?php echo $num;?>" size="5" min="0" step="0.01" onKeyUp='calculaPrecioCliente(this,<?php echo $num;?>);' onChange='calculaPrecioCliente(this,<?php echo $num;?>);' required>
</td>

<!-- DESCUENTO UNITARIO -->
<td align="center" width="6%">
%<input type="number" class="inputnumber" min="0" max="1000000" id="descuento_porcentaje<?php echo $num;?>" name="descuento_porcentaje<?php echo $num;?>" size="5"  value="0" onKeyUp='calcularDescuentoUnitario(1, <?php echo $num;?>);' onChange='calcularDescuentoUnitario(1, <?php echo $num;?>);' step="0.01" required data-tipo="1"><br>
Bs.<input type="number" class="inputnumber" min="0" max="1000000" id="descuento_numero<?php echo $num;?>" name="descuento_numero<?php echo $num;?>" size="5"  value="0" onKeyUp='calcularDescuentoUnitario(0, <?php echo $num;?>);' onChange='calcularDescuentoUnitario(0, <?php echo $num;?>);' step="0.01" required data-tipo="0">
</td>

<!-- Decuento Adicional -->
<td align="center" width="6%">
<input type="number" class="inputnumber" value="0" id="descuento_adicional<?php echo $num;?>" name="descuento_adicional<?php echo $num;?>" size="5" min="0" step="0.01" disabled>
</td>

<!-- Monto TOTAL -->
<td align="center" width="8%">
<input type="number" class="inputnumber" value="0" id="precio<?php echo $num;?>" name="precio<?php echo $num;?>" size="5" min="0" step="0.01" readonly>
</td>

<td align="center" width="10%">
	<!-- Precio Venta Calculado -->
	<input type="number" class="inputnumber" value="0" id="preciocliente<?php echo $num;?>" name="preciocliente<?php echo $num;?>" size="4" min="0" step="0.01" style="height:20px;font-size:15px;width:80px;color:black;" disabled>
	<div id="divmargenOf<?php echo $num;?>" class="textopequenorojo2">-</div>
</td>

<td align="center" width="10%">
	<!-- PrecioCliente a Guardar -->	
	<input type="number" class="inputnumber" value="<?=$precioProductoX;?>" id="precioclienteguardar<?php echo $num;?>" name="precioclienteguardar<?php echo $num;?>" size="4" min="0" step="0.01" onKeyUp='calculaMargen(this,<?php echo $num;?>);' onChange='calculaMargen(this,<?php echo $num;?>);' style="height:20px;font-size:19px;width:80px;color:blue;" required>
	<!-- PrecioCliente sin modificacion -->
	<input type="hidden" class="inputnumber" value="0" id="precioclienteOf<?php echo $num;?>" name="precioclienteOf<?php echo $num;?>">
	</br>
	<div id="divpreciocliente<?php echo $num;?>" class="textopequenorojo">PrecioActual:<?=$precioProductoX;?></div>
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
<td align="center"  width="3%" ><input class="boton2peque" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>
</tr>
</table>

</div>

<?php
}
?>

</head>
</html>