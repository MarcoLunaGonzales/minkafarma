<?php
ini_set('post_max_size','100M');
?>

<script language='Javascript'>
function number_format(amount, decimals) {
    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.-]/g, '')); // elimino cualquier cosa que no sea numero o punto
    decimals = decimals || 0; // por si la variable no fue fue pasada
    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) 
        return parseFloat(0).toFixed(decimals);
    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);
    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;
    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');
    return amount_parts.join('.');
}

function redondear(value, precision) {
    var multiplier = Math.pow(10, precision || 0);
    return Math.round(value * multiplier) / multiplier;
}

function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function calcularPrecios(indice,cantidad_presentacion,porcentaje_margen){
	var main=document.getElementById("main");

	var precio1=parseFloat(document.getElementById('precio1_'+indice).value);
	
	var precioPresentacionMargen=precio1+(precio1*(parseFloat(porcentaje_margen)/100));
	var precioUnitarioMargen=precioPresentacionMargen/parseFloat(cantidad_presentacion);
	
	precioPresentacionMargen=redondear(precioPresentacionMargen,1);
	precioPresentacionMargen=number_format(precioPresentacionMargen,2);
	
	precioUnitarioMargen=redondear(precioUnitarioMargen,1);
	precioUnitarioMargen=number_format(precioUnitarioMargen,2);

	document.getElementById('precio2_'+indice).value=precioPresentacionMargen;
	document.getElementById('precio3_'+indice).value=precioUnitarioMargen;
	
	//alert(precio1);

}

function modifPreciosAjax(indice){
	var item=document.getElementById('item_'+indice).value;

	var precio3=document.getElementById('precio3_'+indice).value;

	contenedor = document.getElementById('contenedor_'+indice);
	
	if(isNaN(precio3) || precio3=="" || precio3==0){
		alert('El valor del precio no es valido!');
	}else{
		ajax=nuevoAjax();
		ajax.open("GET", "ajaxGuardarPrecios2.php?item="+item+"&precio3="+precio3,true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				contenedor.innerHTML = ajax.responseText
			}else{
				contenedor.innerHTML="Guardando...";
			}
		}
		ajax.send(null)	
	}	
}



function enviar(f){
	f.submit();
}
</script>

<?php

	require("conexionmysqli.php");
	require("estilos.inc");
	require("funciones.php");
	require("funcion_nombres.php");

	$globalAlmacen=$_COOKIE['global_almacen'];
	$ordenLista=$_GET['orden'];
	
	$codLineaProveedorX=$_GET['linea'];
	$nombreLineaProveedorX="";
	
	$sqlMargen="select p.margen_precio from proveedores_lineas p
		where p.cod_linea_proveedor='$codLineaProveedorX' ";
	$respMargen=mysqli_query($enlaceCon,$sqlMargen);
	$numFilasMargen=mysqli_num_rows($respMargen);
	$porcentajeMargen=0;
	if($numFilasMargen>0){
		$datMargen=mysqli_fetch_array($respMargen);
		$porcentajeMargen=$datMargen[0];
		//$porcentajeMargen=mysql_result($respMargen,0,0);			
	}
	
	if(isset($codLineaProveedorX)){
		$nombreLineaProveedorX="Linea: ".nombreLineaProveedor($enlaceCon,$codLineaProveedorX)." Margen: $porcentajeMargen";
	}
	
	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	
		$sql="select codigo_material, descripcion_material, p.nombre_linea_proveedor , ma.cantidad_presentacion
		from material_apoyo ma, proveedores_lineas p 
		where ma.cod_linea_proveedor=p.cod_linea_proveedor and p.cod_linea_proveedor='$codLineaProveedorX'
		order by 2";
	


	//echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Registro y Edición de Precios  $nombreLineaProveedorX</h1>";
	
	echo "<center><table class='texto' id='main'>";
	echo "<tr>
	<th>Material</th>
	<th>Cantidad Presentacion</th>
	<th>Precio Actual(Bs.)</th>
	<th>Stock</th>
	<th>Precio Compra(Bs.)</th>
	<th>Precio + Margen(Bs.)</th>
	<th>Precio Unitario Venta(Bs.)</th>
	<th>&nbsp;</th>";
	
	echo "</tr>";
	$indice=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreTipo=$dat[2];
		$cantidadPresentacion=$dat[3];


		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo";
		$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		$numFilas=mysqli_num_rows($respPrecio);
		if($numFilas==1){
			$datPrecio=mysqli_fetch_array($respPrecio);
			$precio1=$datPrecio[0];
			//$precio1=mysql_result($respPrecio,0,0);
			$precio1=redondear2($precio1);
		}else{
			$precio1=0;
			$precio1=redondear2($precio1);
		}

		$stockProducto=stockProducto($enlaceCon,$globalAlmacen, $codigo);
		if($stockProducto>0){
			$stockProducto="<b class='textograndenegro' style='color:#C70039'>".$stockProducto."</b>";
		}

		//sql ultimo precio compra
		$sqlUltimaCompra="select id.precio_neto from ingreso_almacenes i, ingreso_detalle_almacenes id
			where id.cod_ingreso_almacen=i.cod_ingreso_almacen and i.ingreso_anulado=0 and 
		i.cod_almacen='$globalAlmacen' and id.cod_material='$codigo' order by i.fecha desc limit 0,1";
		$respUltimaCompra=mysqli_query($enlaceCon,$sqlUltimaCompra);
		$numFilasUltimaCompra=mysqli_num_rows($respUltimaCompra);
		$precioBase=0;
		if($numFilasUltimaCompra>0){
			$datUltimaCompra=mysqli_fetch_array($respUltimaCompra);
			$precioBase=$datUltimaCompra[0];
			//$precioBase=mysql_result($respUltimaCompra,0,0);
		}
		$precioBase=redondear2($precioBase);
		
		
		$precioConMargen=$precioBase+($precioBase*($porcentajeMargen/100));
	
		//(Ultima compra: $precioBase  --  Precio+Margen: $precioConMargen)
		echo "<tr><td>$nombreMaterial ($nombreTipo) 
		</td>";
		echo "<td>$cantidadPresentacion</td>";
		echo "<td>$precio1</td>";
		echo "<td>$stockProducto</td>";
		echo "<input type='hidden' name='item_$indice' id='item_$indice' value='$codigo'>";
		echo "<input type='hidden' name='cantpres_$indice' id='cantpres_$indice' value='$cantidadPresentacion'>";
		echo "<td align='center'><input type='text' size='5' value='' id='precio1_$indice' name='$codigo|1' onKeyUp='javascript:calcularPrecios($indice,$cantidadPresentacion,$porcentajeMargen);'></td>";
		echo "<td align='center'><input type='text' size='5' value='' id='precio2_$indice' name='$codigo|2' readonly></td>";
		echo "<td align='center'><input type='text' size='5' value='' id='precio3_$indice' name='$codigo|3' readonly></td>";
		echo "<td><a href='javascript:modifPreciosAjax($indice)'>
		<img src='imagenes/save3.jpg' title='Guardar este item.' width='20'></a>
		<div id='contenedor_$indice'></div></td>";
		echo "</tr>";
		
		$indice++;

	}
	echo "</table></center>";

	/*echo "<div class='divBotones'>
	<input type='button' value='Guardar Todo' name='adicionar' class='boton' onclick='enviar(form1)'>
	</div>";*/
	echo "</form>";
?>

