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

function calculaPrecioDol(tipoCambio){
	var montoUnitarioBs=parseFloat(document.getElementById("montoOC").value);
	var montoUnitarioDol=montoUnitarioBs/tipoCambio;
	document.getElementById("montoOCDol").value=montoUnitarioDol;
}

function calculaPrecioBs(tipoCambio){
	var montoUnitarioDol=parseFloat(document.getElementById("montoOCDol").value);
	var montoUnitarioBs=montoUnitarioDol*tipoCambio;
	document.getElementById("montoOC").value=montoUnitarioBs;
}
function ajaxNroSalida(){
	var contenedor;
	var nroSalida = parseInt(document.getElementById('nro_salida').value);
	if(isNaN(nroSalida)){
		nroSalida=0;
	}
	contenedor = document.getElementById('divNroSalida');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxNroSalida.php?nroSalida="+nroSalida,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}
function listaMateriales(f){
	var contenedor;
	var codTipo=f.itemTipoMaterial.value;
	var codItem=f.itemCodMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	contenedor = document.getElementById('divListaMateriales');
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMateriales.php?codTipo="+codTipo+"&codItem="+codItem+"&nombreItem="+nombreItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}

function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('itemNombreMaterial').focus();
}
function setMateriales(f, cod, nombreMat){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).value=nombreMat;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	
	document.getElementById("cantidad_unitaria"+numRegistro).focus();
	
}
		
function precioNeto(fila){

	var precioCompra=document.getElementById('precio'+fila).value;
	var cantidadUnit=document.getElementById('cantidad_unitaria'+fila).value;
		
	var precioTotal=parseFloat(precioCompra)*parseFloat(cantidadUnit);

	if(precioTotal=="NaN"){
		precioTotal.value=0;
	}
	document.getElementById('neto'+fila).value=precioTotal;
}

function enviar_form(f)
{   f.submit();
}
function fun13(cadIdOrg,cadIdDes)
{   var num=document.getElementById(cadIdOrg).value;
    num=(100-13)*num/100;
    document.getElementById(cadIdDes).value=num;
}

	num=0;

	function mas(obj) {

  		num++;
		fi = document.getElementById('fiel');
		contenedor = document.createElement('div');
		contenedor.id = 'div'+num;  
		fi.type="style";
		fi.appendChild(contenedor);
		var div_material;
		div_material=document.getElementById("div"+num);			
		ajax=nuevoAjax();
		ajax.open("GET","ajaxMaterial.php?codigo="+num,true);
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {
				div_material.innerHTML=ajax.responseText;
		    }
	    }		
		ajax.send(null);
	}	
		
	function menos(numero) {
		 num=parseInt(num)-1;
		 fi = document.getElementById('fiel');
  		 fi.removeChild(document.getElementById('div'+numero));
			
 		 calcularTotal();
		 
	}

function validar(f)
{   
	var nroFactura=document.getElementById("nro_factura").value;
	var montoOC=document.getElementById("montoOC").value;
	
	if(nroFactura==""){
		alert("La Factura no puede ir vacia."); return(false);
	}
	if(montoOC==0){
		alert("El monto de la OC no puede ser 0."); return(false);
	}

	f.submit();			
}
function totales(){
	var subtotal=0;
    for(var ii=1;ii<=num;ii++){
	 	var monto=document.getElementById("neto"+ii).value;
		subtotal=subtotal+parseFloat(monto);
    }
	subtotal=Math.round(subtotal*100)/100;
	
    document.getElementById("totalOC").value=subtotal;
	//alert(subtotal);
}

	</script>
<?php

require("conexion.inc");

require("estilos_almacenes.inc");

if($fecha=="")
{   $fecha=date("d/m/Y");
}

$sqlDolar="select valor from `cotizaciondolar`";
$respDolar=mysql_query($sqlDolar);
$tipoCambio=mysql_result($respDolar,0,0);

echo "<form action='guardaOCTerceros.php' method='post' name='form1'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Orden de Compra de Terceros</th></tr></table><br>";
echo "<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>";
echo "<tr><th>Numero de O.C.</th><th>Fecha O.C.</th><th>Proveedor</th><th>Nro. Factura</th><th>Tipo de Pago</th><th>Monto OC Bs.</th><th>Monto OC Dolares</th></tr>";
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
echo "<td align='center'><input type='text' class='texto' name='montoOC' id='montoOC' size='10' value='0' onChange='calculaPrecioDol($tipoCambio);'></td>";
echo "<td align='center'><input type='text' class='texto' name='montoOCDol' id='montoOCDol' size='10' value='0' onChange='calculaPrecioBs($tipoCambio);'></td></tr>";


echo "<tr><th>Tipo de Cambio</th><th colspan='4'>Observaciones</th></tr>";
echo "<tr><th>$tipoCambio Bs.<input type='hidden' name='tipoCambio' value='$tipoCambio' id='tipoCambio'></th>
<td colspan='5' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";

echo "<table align='center'><tr><td><a href='navegador_ordenCompra.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>

</form>
</body>