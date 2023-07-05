<?php
require_once 'conexionmysqli.inc';

require('estilos.inc');
require('funciones.php');
?>
<head>
</head>
<style>
	input[type=radio]
	{
	  /* Double-sized Checkboxes */
	  -ms-transform: scale(2); /* IE */
	  -moz-transform: scale(2); /* FF */
	  -webkit-transform: scale(2); /* Safari and Chrome */
	  -o-transform: scale(2); /* Opera */
	  transform: scale(2);
	  padding: 10px;
	}

	/* Might want to wrap a span around your checkbox text */
	.radiotext
	{
	  /* Checkbox text */
	  font-size: 110%;
	  display: inline;
	}

</style>
<script language='Javascript'>
	function validar(f){
		console.log("editando");
	}

</script>


<?php

$codProducto=$_GET['cod_material'];
$paginaRetorno=$_GET['pagina_retorno'];

$globalAdmin=$_COOKIE["global_admin_cargo"];


$sqlEdit="select m.codigo_material, m.descripcion_material, m.estado, m.cod_linea_proveedor, m.cod_forma_far, m.cod_empaque, 
	m.cantidad_presentacion, m.principio_activo, m.cod_tipoventa, m.producto_controlado, m.accion_terapeutica, m.codigo_barras, bandera_venta_unidades from material_apoyo m where m.codigo_material='$codProducto'";
$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){
	$nombreProductoX=$datEdit[1];
	$codLineaX=$datEdit[3];
	$codFormaX=$datEdit[4];
	$codEmpaqueX=$datEdit[5];
	$cantidadPresentacionX=$datEdit[6];
	$principioActivoX=$datEdit[7];
	$codTipoVentaX=$datEdit[8];
	$productoControlado=$datEdit[9];
	$accionTerapeutica=$datEdit[10];
	$codigoBarras=$datEdit[11];
	$ventaSoloCajas=$datEdit[12];
}

		$cadenaPrecios="";
		$sqlSucursales="select cod_ciudad, descripcion from ciudades order by 1";
		$respSucursales=mysqli_query($enlaceCon,$sqlSucursales);
		while($datSucursales=mysqli_fetch_array($respSucursales)){
			$codCiudadPrecio=$datSucursales[0];
			$nombreCiudadPrecio=$datSucursales[1];
			$sqlPrecios="select precio from precios where cod_precio=1 and cod_ciudad='$codCiudadPrecio' and codigo_material='$codProducto'";
			//echo $sqlPrecios;
			$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
			$precio1=mysqli_result($respPrecios,0,0);
			$precio1=redondear2($precio1);
			
			$cadenaPrecios.="$nombreCiudadPrecio<input type='text' size='5' value='$precio1' id='precio_producto|$codCiudadPrecio' name='precio_producto|$codCiudadPrecio'>";
		}

echo "<form action='guarda_editarproducto.php' method='post' name='form1'>";

echo "<h2>Editar Producto</h2>";


echo "<input type='hidden' name='pagina_retorno' id='pagina_retorno' value='$paginaRetorno'>";
echo "<input type='hidden' name='linea_anterior' id='linea_anterior' value='$codLineaX'>";
echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='textomedianorojo' name='material' size='60' style='text-transform:uppercase;' value='$nombreProductoX' required>
	</td>";
	
echo "<tr><th align='left'>Linea</th>";
$sql1="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='codLinea' id='codLinea' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true' required>
		<option value=''></option>";
		while($dat1=mysqli_fetch_array($resp1))
		{	$codLinea=$dat1[0];
			$nombreLinea=$dat1[1];
			if($codLinea==$codLineaX){
				echo "<option value='$codLinea' selected>$nombreLinea</option>";
			}else{
				echo "<option value='$codLinea'>$nombreLinea</option>";
			}
		}
		echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Forma Farmaceutica</th>";
$sql1="select f.cod_forma_far, f.nombre_forma_far from formas_farmaceuticas f 
where f.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='codForma' id='codForma' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true' required>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codForma=$dat1[0];
				$nombreForma=$dat1[1];
				if($codForma==$codFormaX){
					echo "<option value='$codForma' selected>$nombreForma</option>";
				}else{
					echo "<option value='$codForma'>$nombreForma</option>";
				}
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Cantidad Presentacion</th>
	<td><input type='number' name='cantidadPresentacion' id='cantidadPresentacion' min='1' max='1000' value='$cantidadPresentacionX' class='textomedianorojo' required></td>
	</tr>";
	
echo "<tr><th>Principio Activo</th>
	<td><input type='text' class='textomedianonegro' size='60' name='principioActivo' id='principioActivo' style='text-transform:uppercase;' value='$principioActivoX'></td>
	</tr>";

echo "<tr><th>Tipo Venta</th>";
$sql1="select t.cod_tipoventa, t.nombre_tipoventa from tipos_venta t where t.estado=1;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='codTipoVenta' id='codTipoVenta' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true' required>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codTipoVenta=$dat1[0];
				$nombreTipoVenta=$dat1[1];
				if($codTipoVenta==$codTipoVentaX){
					echo "<option value='$codTipoVenta' selected>$nombreTipoVenta</option>";
				}else{
					echo "<option value='$codTipoVenta'>$nombreTipoVenta</option>";					
				}
			}
echo "</select>
</td>";
echo "</tr>";


echo "<tr><th>Accion Terapeutica</th>";
/*$sql1="select l.cod_accionterapeutica as value, l.nombre_accionterapeutica as texto from acciones_terapeuticas l;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
	<div class='container'>
		<div class='col-md-6'>
			<select name='codAccionTerapeutica' id='codAccionTerapeutica' class='tokenize-sample-demo1' multiple>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codigo=$dat1[0];
				$nombre=$dat1[1];
				$sqlRevisa="select count(*) from material_accionterapeutica m where m.cod_accionterapeutica='$codigo' and 
				m.codigo_material='$codProducto'";
				$respRevisa=mysqli_query($enlaceCon,$sqlRevisa);
				$datRevisa=mysqli_fetch_array($respRevisa);
				$numRevisa=$datRevisa[0];
				//$numRevisa=mysql_result($respRevisa,0,0);
				if($numRevisa>0){
					echo "<option value='$codigo' selected>$nombre</option>";
				}else{
					echo "<option value='$codigo'>$nombre</option>";
				}
			}
echo "</select>*/
/*	</div>
	</div>
</td>";*/
echo "<td><input type='text' name='accion_terapeutica' id='accion_terapeutica' value='$accionTerapeutica' size='60' style='text-transform:uppercase;' class='textomedianonegro'></td>";
echo "</tr>";

echo "<tr><th>Producto Controlado</th>";
if($productoControlado==0){
	echo "<td>
			<input type='radio' name='producto_controlado' value='0' checked><span class='radiotext'>&nbsp;&nbsp; NO &nbsp;&nbsp;</span>
			<input type='radio' name='producto_controlado' value='1'><span class='radiotext'>&nbsp;&nbsp; SI &nbsp;&nbsp;</span>
	</td>";	
}else{
	echo "<td>
			<input type='radio' name='producto_controlado' value='0' checked><span class='radiotext'>&nbsp;&nbsp; NO &nbsp;&nbsp;</span>
			<input type='radio' name='producto_controlado' value='1' checked><span class='radiotext'>&nbsp;&nbsp; SI &nbsp;&nbsp;</span>
	</td>";
}
echo "</tr>";

echo "<tr><th>Restringir Venta a Caja (Cantidad de Presentación)</th>";
if($ventaSoloCajas==0){
	echo "<td>
			<input type='radio' name='venta_solo_caja' value='0' checked><span class='radiotext'>&nbsp;&nbsp; NO &nbsp;&nbsp;</span>
			<input type='radio' name='venta_solo_caja' value='1'><span class='radiotext'>&nbsp;&nbsp; SI &nbsp;&nbsp;</span>
	</td>";	
}else{
	echo "<td>
			<input type='radio' name='venta_solo_caja' value='0' checked><span class='radiotext'>&nbsp;&nbsp; NO &nbsp;&nbsp;</span>
			<input type='radio' name='venta_solo_caja' value='1' checked><span class='radiotext'>&nbsp;&nbsp; SI &nbsp;&nbsp;</span>
	</td>";
}
echo "</tr>";

if($globalAdmin==1){
	echo "<tr><th align='left'>Precio de Venta</th>";
	echo "<td align='left'>
		$cadenaPrecios
	</td></tr>";	
}


echo "<tr><th>Codigo de Barras</th>";
echo "<td><input type='text' name='codigo_barras' id='codigo_barras' value='$codigoBarras' size='40'  style='text-transform:uppercase;'></td>";
echo "</tr>";

?>	
	
<?php
	echo "</td></tr>";
echo "</table></center>";
echo "<input type='hidden' name='arrayAccionTerapeutica' id='arrayAccionTerapeutica'>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>
