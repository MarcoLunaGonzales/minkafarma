<?php 
require_once('conexionmysqli.inc');
require_once('funciones.php');

 // error_reporting(E_ALL);
 // ini_set('display_errors', '1');


$num=$_GET['codigo'];

$globalAdmin=$_COOKIE["global_admin_cargo"];

/*Esta Bandera trabaja con el precio con descuento si es 1 los saca de la tabla si es 0 es descuento manual*/
$banderaPreciosDescuento=obtenerValorConfiguracion($enlaceCon,52);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="10%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
	<a href="javascript:buscarKardexProducto(form1, <?php echo $num;?>)" class="btn btn-dark btn-sm btn-fab" style='background:#1d2a76;color:#fff;'><i class='material-icons float-left' title="Ver Kardex">analytics</i></a>
	<a href="javascript:encontrarMaterial(<?php echo $num;?>)" class="btn btn-primary btn-sm btn-fab"><i class='material-icons float-left' title="Ver en otras Sucursales">place</i></a>
</td>

<td width="38%" align="center">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td width="8%" align="center">
	<div id='idstock<?php echo $num;?>'>
		<input type='hidden' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value='' style='background:#ADF8FA;'>
	</div>
</td>

<td align="center" width="8%">
	<input class="inputnumber" type="number" value="" min="1" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' required> 
</td>


<td align="center" width="8%">
	<div id='idprecio<?php echo $num;?>'>
		<input class="inputnumber" type="number" min="1" value="0" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" step="0.01" readonly>
	</div>
</td>

<td align="center" width="15%">
	<?php
		if($globalAdmin==0){
			$sql1="select codigo, nombre, abreviatura from tipos_precio where estado=1 order by 3";
			//echo $sql1."XXXXXXXXXXXXXXXXXX";
			$resp1=mysqli_query($enlaceCon,$sql1);
			echo "<select name='tipoPrecio' class='texto".$num."' id='tipoPrecio".$num."' style='width:55px !important;float:left;' onchange='ajaxPrecioItem(".$num.")'>";
			while($dat=mysqli_fetch_array($resp1)){
				$codigo=$dat[0];
				$nombre=$dat[1];
				$abreviatura=$dat[2];
				if($codigo==$cod_precio){
                 echo "<option value='$codigo' selected>$abreviatura %</option>";					 
				}else{
				echo "<option value='$codigo'>$abreviatura %</option>";					
				}
			}
			echo "</select>";			
		}elseif($globalAdmin==1 || $banderaPreciosDescuento==1){
			$txtDisabled="";
			if($banderaPreciosDescuento==1){
				$txtDisabled="readonly";
			}
			echo "<input class='inputnumber' type='number' min='0' max='90' step='0.01' value='0' id='tipoPrecio$num' name='tipoPrecio$num' onKeyUp='ajaxPrecioItem(".$num.")' style='background:#ADF8FA;' $txtDisabled >%";
		}


			?>
	<input class="inputnumber" type="number" value="0" id="descuentoProducto<?php echo $num;?>" name="descuentoProducto<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' onChange='calculaMontoMaterial(<?php echo $num;?>);' step="0.01" style='background:#ADF8FA;' readonly>
</td>

<td align="center" width="8%">
	<input class="inputnumber" type="number" value="0" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" value="0"  step="0.01" style="height:20px;font-size:19px;width:80px;color:red;" required readonly> 
</td>

<td align="center"  width="5%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>