<script language="JavaScript">
function calculaPrecioBs(indice, tipoCambio){

	var cantidadUnitaria=document.getElementById("cantidad"+indice).value;
	var montoUnitarioBs=parseFloat(document.getElementById("montoBs"+indice).value);
	var montoUnitarioDol=montoUnitarioBs/tipoCambio;
	document.getElementById("montoDol"+indice).value=montoUnitarioDol;
	var precioBs=parseFloat(montoUnitarioBs/cantidadUnitaria);
	var precioDol=parseFloat(montoUnitarioDol/cantidadUnitaria);
	
	document.getElementById("precioBs"+indice).value=precioBs;
	document.getElementById("precioDol"+indice).value=precioDol;
	
	totales();
}

function calculaPrecioDol(indice, tipoCambio){

	var cantidadUnitaria=document.getElementById("cantidad"+indice).value;
	var montoUnitarioDol=parseFloat(document.getElementById("montoDol"+indice).value);
	var montoUnitarioBs=montoUnitarioDol*tipoCambio;
	document.getElementById("montoBs"+indice).value=montoUnitarioBs;

	var precioBs=parseFloat(montoUnitarioBs/cantidadUnitaria);
	var precioDol=parseFloat(montoUnitarioDol/cantidadUnitaria);
	
	document.getElementById("precioBs"+indice).value=precioBs;
	document.getElementById("precioDol"+indice).value=precioDol;
	
	totales();
}

function calculaDescBs(tipoCambio){

	var montoUnitarioBs=parseFloat(document.getElementById("descuentoOCBs").value);
	var montoUnitarioDol=montoUnitarioBs/tipoCambio;
	document.getElementById("descuentoOCDol").value=montoUnitarioDol;
	
	totales();
}

function calculaDescDol(tipoCambio){
	var montoUnitarioDol=parseFloat(document.getElementById("descuentoOCDol").value);
	var montoUnitarioBs=montoUnitarioDol*tipoCambio;
	document.getElementById("descuentoOCBs").value=montoUnitarioBs;
	totales();
}

function totales(){
   var main=document.getElementById('detalle');   
   var numFilas=main.rows.length;

	var subtotalBs=0;
	var subtotalDol=0;
   for(var ii=1;ii<=numFilas-4;ii++){
	 	var montoBs=document.getElementById("montoBs"+ii).value;
		var montoDol=document.getElementById("montoDol"+ii).value;
		subtotalBs=subtotalBs+parseFloat(montoBs);
		subtotalDol=subtotalDol+parseFloat(montoDol);
    }
	subtotalBs=Math.round(subtotalBs*100)/100;
	subtotalDol=Math.round(subtotalDol*100)/100;
	
	document.getElementById("totalOCBs").value=subtotalBs;
	document.getElementById("totalOCDol").value=subtotalDol;
	
	var descuentoBs=document.getElementById("descuentoOCBs").value;
	var descuentoDol=document.getElementById("descuentoOCDol").value;
	
	var totalTotalBs=Math.round((subtotalBs-descuentoBs)*100/100);
	var totalTotalDol=Math.round((subtotalDol-descuentoDol)*100/100);
	
	document.getElementById("totalTotalOCBs").value=totalTotalBs;
	document.getElementById("totalTotalOCDol").value=totalTotalDol;
	
	
}

function validar(f){
	var fecha=document.getElementById("fecha").value;
	var proveedor=document.getElementById("proveedor").value;
	var nroFactura=document.getElementById("nro_factura").value;
	if(fecha==""){
		alert("La fecha Vencimiento esta vacia.");
		return(false);
	}
	if(proveedor==0){
		alert("Debe seleccionar un proveedor.");
		return(false);
	}
	if(nroFactura==""){
		alert("El Nro. de factura se encuentra vacio.");
		return(false);
	}
   var main=document.getElementById('detalle');   
   var numFilas=main.rows.length;
	//alert(numFilas);
	for(var i=1; i<=numFilas-4; i++){

		var precioBs=parseFloat(document.getElementById("precioBs"+i).value);
		var precioDol=parseFloat(document.getElementById("precioDol"+i).value);
		var montoBs=parseFloat(document.getElementById("montoBs"+i).value);
		var montoDol=parseFloat(document.getElementById("montoDol"+i).value);
		if(precioBs==0){
			alert("El precio en Bs. no puede ser 0. Fila: "+i);
			return(false);
		}
		if(precioDol==0){
			alert("El precio en $us no puede ser 0. Fila: "+i);
			return(false);
		}		
		if(montoBs==0){
			alert("El monto en Bs. no puede ser 0. Fila: "+i);
			return(false);
		}
		if(montoDol==0){
			alert("El monto en $us no puede ser 0. Fila: "+i);
			return(false);
		}
	}
	
	f.submit();
}

</script>
<?php
	require("conexion.inc");
	require('estilos.inc');
	$codIngresos=$_GET['datos'];

	echo "<form method='post' action='guardaGenerarOC.php'>";
	
	$sqlDolar="select valor from `cotizaciondolar`";
	$respDolar=mysql_query($sqlDolar);
	$tipoCambio=mysql_result($respDolar,0,0);
	
	echo "<center><table border='0' class='textotit'><tr><th>Generar OC</th></tr></table></center><br>";
	echo "<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>";
	echo "<tr><th>Numero de O.C.</th><th>Fecha OC</th><th>Proveedor</th><th>Nro. Factura</th><th>Tipo de Pago</th><th>Orden Propia</th></tr>";
	echo "<tr>";
	$sql="select nro_orden from orden_compra order by cod_orden desc";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$num_filas=mysql_num_rows($resp);
	if($num_filas==0)
	{   $nro_correlativo=1;
	}
	else
	{   $nro_correlativo=$dat[0];
		$nro_correlativo++;
	}
	echo "<td align='center'>$nro_correlativo</td>";
	$fecha=date("d/m/Y");
	echo" <TD bgcolor='#ffffff'><INPUT  type='text' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
	echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
	echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
	echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
	echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
	echo" input_element_id='fecha' ";
	echo" click_element_id='imagenFecha'></DLCALENDAR>";
	echo"  </TD>";

	$sql1="select cod_proveedor, nombre_proveedor from proveedores order by 2";
	$resp1=mysql_query($sql1);
	echo "<td align='center'><select name='proveedor' id='proveedor' class='texto'>";
	while($dat1=mysql_fetch_array($resp1))
	{   $codigo=$dat1[0];
		$nombre=$dat1[1];
		echo "<option value='$codigo'>$nombre</option>";
	}
	echo "</select></td>";

	echo "<td align='center'><input type='text' class='texto' name='nro_factura' id='nro_factura' size='10' value='0'></td>";

	$sql1="select cod_tipopago, nombre_tipopago from tipos_pago order by cod_tipopago";
	$resp1=mysql_query($sql1);
	echo "<td align='center'><select name='tipo_pago' id='tipo_pago' class='texto'>";
	while($dat1=mysql_fetch_array($resp1))
	{   $cod_tipopago=$dat1[0];
		$nombre_tipopago=$dat1[1];
		echo "<option value='$cod_tipopago'>$nombre_tipopago</option>";
	}
	echo "</select></td>";
	
	$sqlOP="select o.`cod_propiedad`, o.`nombre_propiedad` from `ordenes_propias` o";
	$resp1=mysql_query($sqlOP);
	echo "<td align='center'><select name='propiedadOC' id='propiedadOC' class='texto'>";
	while($dat1=mysql_fetch_array($resp1))
	{   $codigo=$dat1[0];
		$nombre=$dat1[1];
		echo "<option value='$codigo'>$nombre</option>";
	}
	echo "</select></td>";

	
	echo "<tr><th>Tipo de Cambio</th><th colspan='3'>Observaciones</th><th>Tipo de Factura</th></tr>";
	echo "<tr><th>$tipoCambio Bs.<input type='hidden' name='tipoCambio' value='$tipoCambio' id='tipoCambio'></th>
	<td colspan='3' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td>
	<td><select name='tipoFactura' id='tipoFactura'>
	<option value='1'>Propia</option>
	<option value='0'>Otro Nombre</option>
	</select></td>
	</tr>";
	echo "</table>";
	
	$sql_detalle="select i.`cod_material`, m.`descripcion_material`, sum(i.`cantidad_unitaria`) from `ingreso_detalle_almacenes` i, `material_apoyo` m
		where i.`cod_ingreso_almacen` in ($codIngresos) and i.`cod_material`=m.`codigo_material`
		group by i.`cod_material`, m.`descripcion_material` order by 2";
	$resp_detalle=mysql_query($sql_detalle);
	$filasDetalle=mysql_num_rows($resp_detalle);
	echo "<input type='hidden' value='$filasDetalle' name='numeroItems' id='numeroItems'>";
	echo "<br><table border=1 cellspacing='0' class='textomini' width='90%' align='center' id='detalle'>";
	
	echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad</th><th>Precio Bs.</th><th>Precio $us.</th><th>Monto Bs.</th><th>Monto Dol.</th></tr>";
	
	$indice=1;
	$montoTotal=0;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$codMaterial=$dat_detalle[0];
		$nombreMaterial=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$precio=$dat_detalle[3];
		
		echo "<tr><td align='center'>$indice</td>
		<td>$nombreMaterial<input type='hidden' name='codMaterial$indice' id='codMaterial$indice' value='$codMaterial'></td>
			<td align='center'><input type='text' name='cantidad$indice' value='$cantidad' id='cantidad$indice' readonly size='3'></td>
			<td align='center'><input type='text' name='precioBs$indice' id='precioBs$indice' value='0' readonly  size='3'></td>
			<td align='center'><input type='text' name='precioDol$indice' id='precioDol$indice' value='0' readonly  size='3'></td>
			<td align='center'><input type='text' name='montoBs$indice' id='montoBs$indice' onKeyUp='calculaPrecioBs($indice, $tipoCambio);' value='0'  size='3'></td>
			<td align='center'><input type='text' name='montoDol$indice' id='montoDol$indice' onKeyUp='calculaPrecioDol($indice, $tipoCambio);' value='0'  size='3'></td>
		</tr>";
		$indice++;
	}
	echo "<tr>
	<th colspan='5'>Totales</th>
	<th><input type='text' name='totalOCBs' id='totalOCBs' value='0' size='3'></th>
	<th><input type='text' name='totalOCDol' id='totalOCDol' value='0' size='3'></th>
	</tr>";
	echo "<tr>
	<th colspan='5'>Descuento</th>
	<th><input type='text' name='descuentoOCBs' id='descuentoOCBs' value='0' size='3' onKeyUp='calculaDescBs($tipoCambio);'></th>
	<th><input type='text' name='descuentoOCDol' id='descuentoOCDol' value='0' size='3' onKeyUp='calculaDescDol($tipoCambio);'></th>
	</tr>";
	echo "<tr>
	<th colspan='5'>Total - Descuento</th>
	<th><input type='text' name='totalTotalOCBs' id='totalTotalOCBs' value='0' size='3' readonly></th>
	<th><input type='text' name='totalTotalOCDol' id='totalTotalOCDol' value='0' size='3' readonly></th>
	</tr>";

	echo "</table>";

	echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
	
	echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
	echo "</form>";
?>