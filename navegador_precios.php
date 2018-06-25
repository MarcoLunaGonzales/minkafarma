<?php
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
		var dato=parseFloat(main.rows[i].cells[2].firstChild.value);
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
		var dato=parseFloat(main.rows[i].cells[3].firstChild.value);
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

function modifPreciosAjax(indice){
	var item=document.getElementById('item_'+indice).value;
	var precio1=document.getElementById('precio1_'+indice).value;
	var precio2=document.getElementById('precio2_'+indice).value;
	var precio3=document.getElementById('precio3_'+indice).value;
	var precio4=document.getElementById('precio4_'+indice).value;
	contenedor = document.getElementById('contenedor_'+indice);
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxGuardarPrecios.php?item="+item+"&precio1="+precio1+"&precio2="+precio2+"&precio3="+precio3+"&precio4="+precio4,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}else{
			contenedor.innerHTML="Guardando...";
		}
	}
	ajax.send(null)
	
}
function enviar(f){
	f.submit();
}
</script>

<?php

	require("conexion.inc");
	require("estilos.inc");
	require("funciones.php");

	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	$sql="select codigo_material, descripcion_material, t.`nombre_tipomaterial` from material_apoyo ma, `tipos_material` t
			where ma.`cod_tipo_material`=t.`cod_tipomaterial` order by 3,2";

	$resp=mysql_query($sql);
	echo "<h1>Registro de Precios</h1>";
	
	echo "<center><table class='texto' id='main'>";

	echo "<tr><th>Material</th>
	<th>Precio A</th>
	<th>Precio B<input type='text' size='2' name='valorPrecioB' id='valorPrecioB' value='0'>
	<a href='javascript:modifPrecioB()'><img src='imagenes/edit.png' width='30' alt='Editar'></a></th>
	<th>Precio C<input type='text' size='2' name='valorPrecioC' id='valorPrecioC' value='0'>
	<a href='javascript:modifPrecioC()'><img src='imagenes/edit.png' width='30' alt='Editar'></a></th>
	<th>Precio Factura<input type='text' size='2' name='valorPrecioF' id='valorPrecioF' value='0'>
	<a href='javascript:modifPrecioF()'><img src='imagenes/edit.png' width='30' alt='Editar'></th>
	</tr>";
	$indice=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreTipo=$dat[2];


		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio1=mysql_result($respPrecio,0,0);
			$precio1=redondear2($precio1);
		}else{
			$precio1=0;
			$precio1=redondear2($precio1);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=2 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio2=mysql_result($respPrecio,0,0);
			$precio2=redondear2($precio2);
		}else{
			$precio2=0;
			$precio2=redondear2($precio2);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=3 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio3=mysql_result($respPrecio,0,0);
			$precio3=redondear2($precio3);
		}else{
			$precio3=0;
			$precio3=redondear2($precio3);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=4 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio4=mysql_result($respPrecio,0,0);
			$precio4=redondear2($precio4);
		}else{
			$precio4=0;
			$precio4=redondear2($precio4);
		}
		
		echo "<tr><td>$nombreMaterial <a href='javascript:modifPreciosAjax($indice)'>
		<img src='imagenes/save3.png' alt='Guardar' width='30'></a></td>";
		echo "<input type='hidden' name='item_$indice' id='item_$indice' value='$codigo'>";
		echo "<td align='center'><input type='text' size='5' value='$precio1' id='precio1_$indice' name='$codigo|1'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio2' id='precio2_$indice' name='$codigo|2'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio3' id='precio3_$indice' name='$codigo|3'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio4' id='precio4_$indice' name='$codigo|4'></td>";
		echo "<td><div id='contenedor_$indice'></div></td>";
		echo "</tr>";
		
		$indice++;

	}
	echo "</table></center>";

	echo "<div class='divBotones'>
	<input type='button' value='Guardar Todo' name='adicionar' class='boton' onclick='enviar(form1)'>
	</div>";
	echo "</form>";
?>