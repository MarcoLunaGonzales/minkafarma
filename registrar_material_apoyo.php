<?php
require_once 'conexionmysqli.inc';

require('estilos.inc');

?>
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
		console.log("Guardando Datos");
	}

</script>

<?php

echo "<form action='guarda_material_apoyo.php' method='post' name='form1'>";

echo "<h1>Adicionar Producto</h1>";


echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='textomedianorojo' name='material' size='60' style='text-transform:uppercase;'>
	</td>";
	
echo "<tr><th align='left'>Linea</th>";
$sql1="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='codLinea' id='codLinea' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true' required>
		<option value=''>Seleccionar Linea</option>";
		while($dat1=mysqli_fetch_array($resp1))
		{	$codLinea=$dat1[0];
		$nombreLinea=$dat1[1];
		echo "<option value='$codLinea'>$nombreLinea</option>";
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
				echo "<option value='$codForma'>$nombreForma</option>";
			}
			echo "</select>
			</td>";
echo "</tr>";

echo "<tr><th>Cantidad Presentacion</th>
	<td><input type='number' name='cantidadPresentacion' id='cantidadPresentacion' min='1' max='1000' value='1' class='textomedianorojo' required></td>
	</tr>";
	
echo "<tr><th>Principio Activo</th>
	<td><input type='text' name='principioActivo' id='principioActivo' size='60' class='textomedianonegro' style='text-transform:uppercase;'></td>
	</tr>";

echo "<tr><th>Tipo Venta</th>";
$sql1="select t.cod_tipoventa, t.nombre_tipoventa from tipos_venta t where t.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='codTipoVenta' id='codTipoVenta' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true' required>";

			while($dat1=mysqli_fetch_array($resp1))
			{	$codTipoVenta=$dat1[0];
				$nombreTipoVenta=$dat1[1];	
				echo "<option value='$codTipoVenta'>$nombreTipoVenta</option>";
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
				echo "<option value='$codigo'>$nombre</option>";
			}
echo "</select>
	</div>
	</div>
</td>";*/
echo "<td><input type='text' name='accion_terapeutica' id='accion_terapeutica'  size='60' style='text-transform:uppercase;' class='textomedianonegro'></td>";
echo "</tr>";


echo "<tr><th>Producto Controlado</th>";
echo "<td>
		<input type='radio' name='producto_controlado' value='0' checked><span class='radiotext'>&nbsp;&nbsp; NO &nbsp;&nbsp;</span>
        <input type='radio' name='producto_controlado' value='1'><span class='radiotext'>&nbsp; &nbsp;  SI &nbsp;&nbsp;</span>
</td>";
echo "</tr>";

echo "<tr><th>Restringir Venta a Caja (Cantidad de Presentación)</th>";
echo "<td>
		<input type='radio' name='venta_solo_caja' value='0' checked><span class='radiotext'>&nbsp;&nbsp; NO &nbsp;&nbsp;</span>
        <input type='radio' name='venta_solo_caja' value='1'><span class='radiotext'>&nbsp; &nbsp;  SI &nbsp;&nbsp;</span>
</td>";
echo "</tr>";

echo "<tr><th>Tipo Material</th>";
$sql1="SELECT tm.cod_tipomaterial, tm.nombre_tipomaterial, tm.obs_tipomaterial
		FROM tipos_material tm;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='cod_tipo_material' id='cod_tipo_material' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true' required>";

			while($dat1=mysqli_fetch_array($resp1))
			{	$codTipoMaterial   = $dat1[0];
				$nombreTipoMaterial = $dat1[1];	
				echo "<option value='$codTipoMaterial'>$nombreTipoMaterial</option>";
			}
		echo "</select>
</td>";
echo "</tr>";


echo "<tr><th align='left'>Precio de Venta</th>";
$sqlSucursales="select cod_ciudad, descripcion from ciudades order by 1";
$respSucursales=mysqli_query($enlaceCon,$sqlSucursales);
echo "<td align='left'>";
while($datSucursales=mysqli_fetch_array($respSucursales)){
	$codCiudadPrecio=$datSucursales[0];
	$nombreCiudadPrecio=$datSucursales[1];
	echo "<input type='number' class='texto' name='precio_producto|$codCiudadPrecio' id='precio_producto|$codCiudadPrecio' step='0.01' placeholder='$nombreCiudadPrecio' required>";
}
echo "</td></tr>";

echo "<tr><th>Codigo de Barras</th>";
echo "<td><input type='text' name='codigo_barras' id='codigo_barras' size='40'  style='text-transform:uppercase;'></td>";
echo "</tr>";

echo "</td></tr>";
echo "</table></center>";
echo "<input type='hidden' name='arrayAccionTerapeutica' id='arrayAccionTerapeutica'>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>

<script>
    $('.tokenize-sample-demo1').tokenize2();
</script>

