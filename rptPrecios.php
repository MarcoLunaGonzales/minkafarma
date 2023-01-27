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

	require("conexionmysqli.inc");
	require("estilos_almacenes.inc");
	require("funciones.php");

	error_reporting(E_ALL);
 ini_set('display_errors', '1');



	$almacenReporte=$_COOKIE["global_almacen"];
	$codigoCiudadGlobal=$_COOKIE["global_agencia"];


	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	
	echo "<h1>Reporte de Precios</h1>";
	
	
	//echo "<div class='divBotones'><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
	
	echo "<div id='divCuerpo'>";
	$sql="select codigo_material, descripcion_material, (select p.nombre_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=ma.cod_linea_proveedor) as proveedor  from material_apoyo ma where estado=1 order by 3,2";
	$resp=mysqli_query($enlaceCon, $sql);
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Proveedor</th><th>Material</th>
	<th>Existencias</th>
	<th>Precio</th>
	</tr>";
	$indice=1;
	$precio1=0;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreProveedor=$dat[2];

		$stockProducto=stockProducto($enlaceCon, $almacenReporte, $codigo);
		$precioProducto=precioProductoSucursal($enlaceCon,$codigo,$codigoCiudadGlobal);

		$precioProductoF=formatonumeroDec($precioProducto);
		$indice++;

		if($stockProducto>0){
			echo "<tr><td>$nombreProveedor</td>";
			echo "<td>$nombreMaterial</td>";
			echo "<td align='right'>$stockProducto</td>";
			echo "<td align='right'><div id='1$codigo'>$precioProductoF</div></td>";
			echo "</tr>";			
		}

	}
	echo "</table></center><br>";
	echo "</div>";

	//echo "<div class='divBotones'><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
	
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Nombre Item</td>
				<td>
				<input type='text' name='nombreItem' id="nombreItem" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Tipo Material</td>
				<td>
				<?php
					$sql1="select * from tipos_material order by nombre_tipomaterial";
					$resp1=mysqli_query($enlaceCon, $sql1);
				?>
					<select name='tipo_material' id='tipo_material' class='texto'>
					<option value="0">Seleccione una opcion.</option>
				<?php
					while($dat1=mysqli_fetch_array($resp1))
					{	$cod_tipomaterial=$dat1[0];
						$nombre_tipomaterial=$dat1[1];
				?>	
					<option value='<?php echo $cod_tipomaterial;?>'><?php echo $nombre_tipomaterial;?></option>
				<?php	
					}
				?>
					</select>
				</td>
			</tr>			
		</table>	
		<center>
			<input type='button' value='Buscar' onClick="ajaxBuscarItems(this.form)">
			<input type='button' value='Cancelar' onClick="HiddenBuscar()">
			
		</center>
	</div>
</div>


</form>

