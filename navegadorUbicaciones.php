<?php
require('conexionmysqli.php');
ini_set('post_max_size','100M');
?>

<script language='Javascript'>
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

function modifPrecioB(){
   var main=document.getElementById('main');
   var numFilas=main.rows.length;
   var subtotal=0;
   var datoModif=parseFloat(document.getElementById('valorPrecioB').value);
   datoModif=datoModif/100;
	for(var i=1; i<=numFilas-1; i++){
		var dato=parseFloat(main.rows[i].cells[1].firstChild.value);
		var datoNuevo=dato+(datoModif*dato);
		main.rows[i].cells[2].firstChild.value=datoNuevo;
	}

}

function modifPrecioC(){
   var main=document.getElementById('main');
   var numFilas=main.rows.length;
   var subtotal=0;
   var datoModif=parseFloat(document.getElementById('valorPrecioC').value);
   datoModif=datoModif/100;
	for(var i=1; i<=numFilas-1; i++){
		var dato=parseFloat(main.rows[i].cells[1].firstChild.value);
		var datoNuevo=dato+(datoModif*dato);
		main.rows[i].cells[3].firstChild.value=datoNuevo;
	}

}

function modifPrecioF(){
   var main=document.getElementById('main');
   var numFilas=main.rows.length;
   var subtotal=0;
   var datoModif=parseFloat(document.getElementById('valorPrecioF').value);
   datoModif=datoModif/100;
	for(var i=1; i<=numFilas-1; i++){
		var dato=parseFloat(main.rows[i].cells[1].firstChild.value);
		var datoNuevo=dato+(datoModif*dato);
		main.rows[i].cells[4].firstChild.value=datoNuevo;
	}

}

function modifPrecios(indice){
	var main=document.getElementById("main");

	var datoModif=parseFloat(document.getElementById('valorPrecioB').value);
	datoModif=datoModif/100;
	var dato=parseFloat(main.rows[indice].cells[2].firstChild.value);
	var datoNuevo=dato+(datoModif*dato);
	main.rows[indice].cells[2].firstChild.value=datoNuevo;

	datoModif=parseFloat(document.getElementById('valorPrecioC').value);
	datoModif=datoModif/100;
	dato=parseFloat(main.rows[indice].cells[3].firstChild.value);
	datoNuevo=dato+(datoModif*dato);
	main.rows[indice].cells[3].firstChild.value=datoNuevo;

	datoModif=parseFloat(document.getElementById('valorPrecioF').value);
	datoModif=datoModif/100;
	dato=parseFloat(main.rows[indice].cells[4].firstChild.value);
	datoNuevo=dato+(datoModif*dato);
	main.rows[indice].cells[4].firstChild.value=datoNuevo;



}

function modifUbicacionesAjax(indice){
	var item=document.getElementById('item_'+indice).value;
	var cod_ingreso=document.getElementById('ingreso_'+indice).value;
	var estante=document.getElementById('estante_'+indice).value;
	var fila=document.getElementById('fila_'+indice).value;
	contenedor = document.getElementById('contenedor_'+indice);
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxGuardarUbicaciones.php?item="+item+"&cod_ingreso="+cod_ingreso+"&estante="+estante+"&fila="+fila,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}else{
			contenedor.innerHTML="Guardando...";
		}
	}
	ajax.send(null)
}

function cambiarPrecioIndividual(indice){
	var item=document.getElementById('item_'+indice).value;
	var precio1=document.getElementById('precio1_'+indice).value;
	precio1=parseFloat(precio1);
	
	var porcentajeCambiar=parseFloat(document.getElementById('valorPrecioB').value);
	porcentajeCambiar=porcentajeCambiar/100;
	var datoNuevo=precio1+(porcentajeCambiar*precio1);
	main.rows[indice].cells[2].firstChild.value=datoNuevo;
	
	var porcentajeCambiar=parseFloat(document.getElementById('valorPrecioC').value);
	porcentajeCambiar=porcentajeCambiar/100;
	var datoNuevo=precio1+(porcentajeCambiar*precio1);
	main.rows[indice].cells[3].firstChild.value=datoNuevo;
	
	var porcentajeCambiar=parseFloat(document.getElementById('valorPrecioF').value);
	porcentajeCambiar=porcentajeCambiar/100;
	var datoNuevo=precio1+(porcentajeCambiar*precio1);
	main.rows[indice].cells[4].firstChild.value=datoNuevo;
	
		
}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}
function listaMateriales(f){
	var contenedor;
	var codTipo=f.itemTipoMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	
	contenedor = document.getElementById('divPrecios');	
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaUbicaciones.php?codTipo="+codTipo+"&nombreItem="+nombreItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function enviar(f){
	f.submit();
}
</script>

<?php

	require("estilos.inc");
	require("funciones.php");

	$globalAlmacen=$_COOKIE['global_almacen'];
	$ordenLista=$_GET['orden'];
	
	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	

	echo "<h1>Registro y Edición de Ubicaciones</h1>";

	echo "<table align='center' width='60%'>
	<tr><th>Linea</th><th>Material</th><th>&nbsp;</th></tr>
	<tr>
	<td align='center'><select class='textomedianorojo' name='itemTipoMaterial' style='width:300px'>";
	
	$sqlTipo="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
	where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
	$respTipo=mysqli_query($enlaceCon,$sqlTipo);
	echo "<option value='0'>--</option>";
	while($datTipo=mysql_fetch_array($respTipo)){
		$codTipoMat=$datTipo[0];
		$nombreTipoMat=$datTipo[1];
		echo "<option value=$codTipoMat>$nombreTipoMat</option>";
	}
	echo "</select>
	</td>
	<td align='center'>
		<input type='text' name='itemNombreMaterial' id='itemNombreMaterial' class='textomedianorojo' onkeypress='return pressEnter(event, this.form);'>
	</td>
	<td>
		<input type='button' class='boton' value='Buscar' onClick='listaMateriales(this.form)'>
	</td>
	</tr>
	</table></br></br>";
	
	echo "<div id='divPrecios'><center><table class='texto' id='main'>";
	echo "<tr><th>Material</th>";

	$sql="select codigo, nombre from ubicaciones_estantes where cod_estado=1";
	echo "<th>Ubicacion Estante
	</th>";
	
	$sql="select codigo, nombre from ubicaciones_filas where cod_estado=1";
	echo "<th>Ubicacion Fila
	</th>";
	echo "</tr>";
	
	echo "<tr><td colspan='8' align='center'>Favor introducir datos para la busqueda.</td></tr>";
	echo "</table></center></div>";

	/*echo "<div class='divBotones'>
	<input type='button' value='Guardar Todo' name='adicionar' class='boton' onclick='enviar(form1)'>
	</div>";*/
	echo "</form>";
?>