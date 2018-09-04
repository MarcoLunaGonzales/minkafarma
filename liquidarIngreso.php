<script language='JavaScript'>
function calcularPrecio(fila){

	var precioCompra=document.getElementById('precioBruto'+fila).value;
	var facturaSiNo=parseInt(document.getElementById('factura').value);
	
	if(facturaSiNo==1){
		var importeNeto=parseFloat(precioCompra)- (parseFloat(precioCompra)*0.13);
	}
	if(facturaSiNo==2){
		var importeNeto=parseFloat(precioCompra)+(parseFloat(precioCompra)*0.08);
	}
	
	if(importeNeto=="NaN"){
		importeNeto.value=0;
	}
	document.getElementById('precioNeto'+fila).value=importeNeto;
}

function validar(f, numItems)
{   var cantidadItems=numItems-1;
	
	var facturaSiNo=parseInt(document.getElementById("factura").value);
	var nroFactura=document.getElementById("nro_factura").value;
		
	if(facturaSiNo==1){
		if(nroFactura==""){
			alert("El Número de Factura no puede ir vacia."); return(false);
		}
	}
	
	for(var i=1; i<=cantidadItems; i++){
	
		item=parseFloat(document.getElementById("material"+i).value);
		precioBruto=parseFloat(document.getElementById("precioBruto"+i).value);
		precioNeto=parseFloat(document.getElementById("precioNeto"+i).value);
		
		if(isNaN(precioNeto) || isNaN(precioBruto)){
			alert("Los precios no pueden ir vacios.");
			return(false);
		}
		f.submit();
	}	
}
</script>

<?php
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	$codigo_ingreso=$_GET['codigo_registro'];
	
	echo "<form method='post' action='guardaLiquidarIngreso.php' name='form1'>";
	
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.cod_ingreso_almacen='$codigo_ingreso'";
	$resp=mysql_query($sql);
	
	echo "<input type='hidden' name='codigoIngreso' value='$codigo_ingreso'>";
	
	echo "<center><h1>Liquidar Ingreso</h1>";
	
	echo "<table class='texto' align='center'>";
	echo "<tr><th>Nro. Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
	echo "<tr>
		<td align='center'>$nro_correlativo</td>
		<td align='center'>$fecha_ingreso_mostrar</td>
		<td>$nombre_tipoingreso</td>
		<td>&nbsp;$obs_ingreso</td>
		</tr>";
	echo "<tr><th>Factura</th><th>Numero de Factura</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
	echo "<tr>
			<td>
				<select name='factura' id='factura' class='texto'>
					<option value='1'>Si</option>
					<option value='0'>No</option>
				</select>
			</td>
			<td>
				<input type='text' class='texto' id='nro_factura' name='nro_factura'>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>";
	echo "</table>";
	
	$sql_detalle="select i.cod_material, i.cantidad_unitaria from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='$codigo' and m.codigo_material=i.cod_material";
	$resp_detalle=mysql_query($sql_detalle);
	echo "<br><table class='texto' align='center'>";
	echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad</th><th>Precio Bruto</th><th>Precio Neto</th></tr>";
	$indice=1;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
		echo "<tr>
		<td align='center'>$indice</td>
		<td>$nombre_material</td>
		<td align='center'>$cantidad_unitaria</td>
		<td align='center'><input type='text' class='texto' value='0' id='precioBruto$indice' name='precioBruto$indice' onKeyDown='calcularPrecio($indice);'></td>
		<td align='center'><input type='text' class='texto' value='0' id='precioNeto$indice' name='precioNeto$indice' readonly></td>
		<input type='hidden' name='material$indice' id='material$indice' value='$cod_material'>
		</tr>";
		$indice++;
	}
	echo "</table>";
	echo "<input type='hidden' value='$indice' name='numeroItems'>";
	
	echo "<br><center>
		<input type='button' value='Guardar' name='adicionar' class='boton' onclick='validar(this.form, $indice)'>
		</center>";
	echo "</form>";
?>