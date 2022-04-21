<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
        <script type='text/javascript' language='javascript'>
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
function ajaxCargarPagos(){
	var contenedor;
	contenedor = document.getElementById('divDetalle');

	var codProveedor = document.getElementById('proveedor').value;

	ajax=nuevoAjax();

	ajax.open("GET", "ajaxCargarPagos.php?codProveedor="+codProveedor,true);

	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}else{
			contenedor.innerHTML = "Cargando...";
		}
	}
	ajax.send(null)
}


function calculaSaldoDol(indice, tipoCambio){

	var montoBs=parseFloat(document.getElementById("montoPagoBs"+indice).value);
	var montoDol=montoBs/tipoCambio;
	document.getElementById("montoPagoDol"+indice).value=montoDol;
}

function calculaSaldoBs(indice, tipoCambio){

	var montoDol=parseFloat(document.getElementById("montoPagoDol"+indice).value);
	var montoBs=montoDol*tipoCambio;
	document.getElementById("montoPagoBs"+indice).value=montoBs;
}

function validar(f)
{   
	var codProveedor=document.getElementById("proveedor").value;
	var numRegistros=document.getElementById("nroFilas").value;
	var monto;
	var nroDoc;
	if(codProveedor==0){
		alert("Debe seleccionar un Proveedor");
	}else{
		if(numRegistros>0){
			for(var i=1; i<=numRegistros; i++){
				monto=parseFloat(document.getElementById("montoPago"+i).value);
				nroDoc=parseFloat(document.getElementById("nroDoc"+i).value);
				//if(monto==0 || nroDoc==0 || monto=="NaN" || nroDoc=="NaN"){
					//alert("Monto de Pago, Nro. Doc. no pueden estar vacios. Fila: "+i);
					//return(false);
				//}
				f.submit();
			}
		}
		
	}	
}

	</script>
<?php

require("conexionmysqli.php");

require("estilos_almacenes.inc");
?>
<body>
<form action='guardarPagos.php' method='post' name='form1'>
<h3 align="center">Registrar Pagos</h3>

<table border='0' class='texto' cellspacing='0' align='center' width='80%' style='border:#ccc 1px solid;'>
<tr><th>Proveedor</th><th>Fecha Pago</th><th>Observaciones</th></tr>
<?php
$sql1="select cod_proveedor, nombre_proveedor from proveedores order by 2";
$resp1=mysqli_query($enlaceCon,$sql1);
?>
<tr>
<td align='center'>
<select name='proveedor' id='proveedor' class='texto' onChange="ajaxCargarPagos();">
	<option value="0">Seleccione una opcion</option>
<?php
while($dat1=mysqli_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
?>
	<option value="<?php echo $codigo; ?>"><?php echo $nombre; ?></option>
<?php	
}
$fecha=date("d/m/Y");
?>
</select>
</td>
<td>
<input type='text' disabled='true' class='texto' value='<?php echo $fecha; ?>' id='fecha' size='10' name='fecha'>
<img id='imagenFecha' src='imagenes/fecha.bmp'>
</td>
<td>
<input type='text' class='texto' value="" id='observaciones' size='40' name='observaciones'>
</td>


</tr>
</table>

</br>

<div id="divDetalle">
	<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
		<tr><th>Nro. OC</th><th>Fecha OC</th><th>Monto OC</th><th>A Cuenta</th><th>Saldo OC</th><th>Monto a Pagar</th><th>Nro. Doc. Pago</th></tr>
	</table>
</div>


<div class="divBotones">
<input type='submit' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick="location.href='navegador_pagos.php'">
</div>

<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>

</form>
</body>