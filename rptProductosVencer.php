<script language='JavaScript'>

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

function cambiaPrecio(f, id, codigo, precio, tipoPrecio){
	var contenedor;
	contenedor = document.getElementById(id);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxCambiaPrecio.php?codigo='+codigo+'&precio='+precio+'&id='+id+'&tipoPrecio='+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function guardaAjaxPrecio(combo, codigo, id, tipoPrecio){
	var contenedor;
	var precio=combo.value;
	contenedor = document.getElementById(id);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxGuardaPrecio.php?codigo='+codigo+'&precio='+precio+'&tipoPrecio='+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ShowBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}		

function ajaxBuscarItems(f){
	var nombreItem, tipoItem;
	nombreItem=document.getElementById("nombreItem").value;
	tipoItem=document.getElementById("tipo_material").value;

	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxBuscarItems.php?nombreItem="+nombreItem+"&tipoItem="+tipoItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}

</script>
<?php

	require("conexion.inc");
	require("estilos_almacenes.inc");
	require("funciones.php");

	$fechaActual=date("Y-m-d");
	
	$nroMeses=3;
	$fechaIni=date("Y-m-d");
	$fechaFin=date('Y-m-d',strtotime($fechaIni.'+'.$nroMeses.' month'));
	
	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	
	echo "<h1>Reporte de Productos Proximos a Vencer</h1>";
	
	$sql="select m.descripcion_material, DATE_FORMAT(id.fecha_vencimiento, '%d/%m/%Y'), id.cantidad_restante, id.fecha_vencimiento, 
	(select pl.nombre_linea_proveedor from proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor) as linea, 
	(select p.nombre_proveedor from proveedores_lineas pl, proveedores p where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor) as proveedor 
	from material_apoyo m, ingreso_detalle_almacenes id, ingreso_almacenes i
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and id.cod_material=m.codigo_material and 
		i.ingreso_anulado=0 and id.fecha_vencimiento<='$fechaFin' and id.cantidad_restante>0 
		order by id.fecha_vencimiento";
		
	$resp=mysql_query($sql);
	
	echo "<center><table class='texto'>";
	echo "<tr>
	<th>#</th>
	<th>Distribuidor</th>
	<th>Linea</th>
	<th>Material</th>
	<th>Fecha</th>
	<th>Cantidad</th>
	</tr>";
	$indice=1;
	while($dat=mysql_fetch_array($resp))
	{
		$nombreMaterial=$dat[0];
		$fechaVencimiento=$dat[1];
		$cantidadUnitaria=$dat[2];
		$fechaVencimientoSF=$dat[3];
		$lineaProveedor=$dat[4];
		$distribuidor=$dat[5];
		
		
		//echo $fechaVencimientoSF." ".$fechaActual;
		
		if($fechaVencimientoSF<=$fechaActual){
			echo "<td align='center'><div class='textogranderojo'>$indice</div></td>";
			echo "<td align='left'><div class='textogranderojo'>$distribuidor</div></td>";
			echo "<td align='left'><div class='textogranderojo'>$lineaProveedor</div></td>";
			echo "<td align='left'><div class='textogranderojo'>$nombreMaterial</div></td>";
			echo "<td align='center'><div class='textogranderojo'>$fechaVencimiento</div></td>";
			echo "<td align='center'><div class='textogranderojo'>$cantidadUnitaria</div></td>";
		}else{
			echo "<td align='center'>$indice</td>";
			echo "<td align='left'>$distribuidor</td>";
			echo "<td align='left'>$lineaProveedor</td>";
			echo "<td align='left'>$nombreMaterial</td>";
			echo "<td align='center'>$fechaVencimiento</td>";
			echo "<td align='center'>$cantidadUnitaria</td>";
		}
		
		echo "</tr>";
		
		$indice++;
	}
	echo "</table></center><br>";	
?>
