<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
		
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
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ajaxTipoDoc(f){
	var contenedor;
	contenedor=document.getElementById("divTipoDoc");
	ajax=nuevoAjax();
	var codTipoSalida=(f.tipoSalida.value);
	ajax.open("GET", "ajaxTipoDoc.php?codTipoSalida="+codTipoSalida,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function ajaxPesoMaximo(codVehiculo){
	var contenedor;
	contenedor=document.getElementById("divPesoMax");
	ajax=nuevoAjax();
	var codVehiculo=codVehiculo;
	ajax.open("GET", "ajaxPesoMaximo.php?codVehiculo="+codVehiculo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function ajaxNroDoc(f){
	var contenedor;
	contenedor=document.getElementById("divNroDoc");
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function actStock(indice){
	var contenedor;
	contenedor=document.getElementById("idstock"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
    var codalm=document.getElementById("global_almacen").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			ajaxPrecioItem(indice);
		}
	}
	ajax.send(null);
}

function mostrarMetraje(indice){
	var contenedor;
	contenedor=document.getElementById("idnro_metros"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
    var codalm=document.getElementById("global_almacen").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxMostrarMetraje.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);	
}

function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPrecioItem.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			ajaxPesoItem(indice);
		}
	}
	ajax.send(null);
}

function calcularCantidadMetros(indice){
	var  nroMetrosItem=document.getElementById("cantidadMetrosItem"+indice).value;
	var  nroMetrosSal=document.getElementById("nro_metros"+indice).value;
	var cantidad=document.getElementById("cantidadMM"+indice).value;
	cantidad=parseFloat(cantidad);
	if(nroMetrosItem==0){
		var totalCantidad= cantidad;
	}else{
		var totalCantidad= cantidad +(nroMetrosSal/nroMetrosItem);
	}
	//alert(nroMetrosSal+' '+nroMetrosItem);
	document.getElementById("cantidad_unitaria"+indice).value=totalCantidad;
	calculaMontoMaterial(indice);
}

function ajaxPesoItem(indice){
	var contenedor;
	contenedor=document.getElementById("idpeso"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPesoItem.php?codmat="+codmat+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			mostrarMetraje(indice);	
		}
	}
	ajax.send(null);
}


function ajaxTipoPrecio(f){
	var contenedor;
	contenedor=document.getElementById("divTipoPrecio");
	var codCliente=document.getElementById("cliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxTipoPrecio.php?codCliente="+codCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			ajaxRazonSocial(codCliente);
		}
	}
	ajax.send(null);
}

function ajaxRazonSocial(f){
	var contenedor;
	contenedor=document.getElementById("divRazonSocial");
	var codCliente=document.getElementById("cliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxRazonSocial.php?codCliente="+codCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			ajaxNIT(f);
		}
	}
	ajax.send(null);
}

function ajaxNIT(f){
	var contenedor;
	contenedor=document.getElementById("divNIT");
	var codCliente=document.getElementById("cliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxNIT.php?codCliente="+codCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function calculaMontoMaterial(indice){

	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	var precioUnitario=document.getElementById("precio_unitario"+indice).value;
	var descuentoUnitario=document.getElementById("descuentoProducto"+indice).value;
	var peso=document.getElementById("pesoItem"+indice).value;
	
	var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario)) * (1-(descuentoUnitario/100));
	montoUnitario=Math.round(montoUnitario*100)/100
	
	var pesoTotalItem=(parseFloat(cantidadUnitaria)*parseFloat(peso));
	
	document.getElementById("montoMaterial"+indice).value=montoUnitario;
	document.getElementById("pesoItemTotal"+indice).value=pesoTotalItem;
	
	totales();
}

function totales(){
	var subtotal=0;
	var pesoTotal=0;
    for(var ii=1;ii<=num;ii++){
	 	var monto=document.getElementById("montoMaterial"+ii).value;
		var peso=document.getElementById("pesoItemTotal"+ii).value;
		subtotal=subtotal+parseFloat(monto);
		pesoTotal=pesoTotal+parseFloat(peso);
    }
	subtotal=Math.round(subtotal*100)/100;
	pesoTotal=Math.round(pesoTotal*100)/100;
	
    document.getElementById("totalVenta").value=subtotal;
	document.getElementById("totalFinal").value=subtotal;
	document.getElementById("totalPesoVenta").value=pesoTotal;
}

function aplicarDescuento(f){
	var total=document.getElementById("totalVenta").value;
	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(descuento*100)/100;
	
	document.getElementById("totalFinal").value=parseFloat(total)-parseFloat(descuento);
	
}
function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}
function setMateriales(f, cod, nombreMat){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).value=nombreMat;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	
	actStock(numRegistro);
}
		
function precioNeto(fila){

	var precioCompra=document.getElementById('precio'+fila).value;
		
	var importeNeto=parseFloat(precioCompra)- (parseFloat(precioCompra)*0.13);

	if(importeNeto=="NaN"){
		importeNeto.value=0;
	}
	document.getElementById('neto'+fila).value=importeNeto;
}
function fun13(cadIdOrg,cadIdDes)
{   var num=document.getElementById(cadIdOrg).value;
    num=(100-13)*num/100;
    document.getElementById(cadIdDes).value=num;
}

	num=0;

	function mas(obj) {

  		if(num>=15){
			alert("No puede registrar mas de 15 items en una nota.");
		}else{
			num++;
			fi = document.getElementById('fiel');
			contenedor = document.createElement('div');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialSalida.php?codigo="+num,true);
			ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {
				div_material.innerHTML=ajax.responseText;
				}
			}		
			ajax.send(null);
		}
		
	}	
		
	function menos(numero) {
		num=parseInt(num)-1;	
		fi = document.getElementById('fiel');
  		fi.removeChild(document.getElementById('div'+numero));
 		totales();
	}

function validar(f)
{   
	f.cantidad_material.value=num;
	var cantidadItems=num;
	var tipoSalida=document.getElementById("tipoSalida").value;
	var tipoDoc=document.getElementById("tipoDoc").value;
	var almacenDestino=document.getElementById("almacen").value;
	var cliente=document.getElementById("cliente").value;
	var tipoPrecio=document.getElementById("tipoPrecio").value;
	var razonSocial=document.getElementById("razonSocial").value;
	var nitCliente=document.getElementById("nitCliente").value;
	var descuentoTotal=document.getElementById("descuentoVenta").value;

	var globalAlmacen=document.getElementById("global_almacen").value;

	if(tipoDoc==0){
		alert("El tipo de documento no puede estar vacio.");
		return(false);
	}
	if(cliente==0){
		alert("El cliente no puede estar vacio.");
		return(false);
	}
	if(tipoPrecio==0){
		alert("El tipo de precio no puede estar vacio.");
		return(false);
	}
	if(razonSocial==""){
		alert("La Razon Social no puede estar vacia.");
		return(false);
	}
	if(nitCliente==""){
		alert("El NIT del Cliente no puede estar vacio.");
		return(false);
	}
	if(descuentoVenta==""){
		alert("El descuento Final no puede estar vacio.");
		return(false);
	}

	if(cantidadItems>0){
		
		var item="";
		var cantidad="";
		var stock="";
		var descuento="";
		
				
		for(var i=1; i<=cantidadItems; i++){
			item=parseFloat(document.getElementById("materiales"+i).value);
			cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
			stock=parseFloat(document.getElementById("stock"+i).value);
			descuento=parseFloat(document.getElementById("descuentoProducto"+i).value);
			precioUnit=parseFloat(document.getElementById("precio_unitario"+i).value);
			
			var costoUnit=parseFloat(document.getElementById("costoUnit"+i).value);
	
			if(item==0){
				alert("Debe escoger un item en la fila "+i);
				return(false);
			}
			if(cantidad==0){
				alert("La cantidad no puede ser 0 ni vacia. Fila "+i);
				return(false);
			}
			if(precioUnit<=0){
				alert("El precio Unitario no puede ser menor o igual a 0. Fila "+i);
				return(false);
			}
			
			if(costoUnit>precioUnit){
				if(confirm('El precio es menor al Costo Promedio. Desea Proseguir.')){
					
				}else{
					return(false);
				}
				
			}
			
			if(stock<cantidad && globalAlmacen!=1003){
				alert("No puede sacar cantidades mayores a las existencias. Fila "+i);
				return(false);
			}
			/*if(descuento==0){
				alert("El descuento no puede ser 0 ni vacio. Fila "+i);
				return(false);
			}*/
			var pesoTotalVenta=parseFloat(document.getElementById("totalPesoVenta").value);
			var pesoMaximoVehiculo=parseFloat(document.getElementById("pesoMaximoVehiculo").value);
			
			if(pesoTotalVenta>=pesoMaximoVehiculo && pesoMaximoVehiculo!=0){
				if(confirm("El peso Total excede a la capacidad del vehiculo. Desea guardar la nota de todas formas.")){
					f.submit();
				}else{
					return(false);
				}
			}else{
				f.submit();
			}
		}
		
	}else{
		alert("El ingreso debe tener al menos 1 item.");
		return(false);
	}
}
	
	
</script>

		
<?php
echo "<body>";
require("conexion.inc");
require("estilos_almacenes.inc");
if($fecha=="")
{   $fecha=date("d/m/Y");
}
$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' order by cod_salida_almacenes desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
    $codigo++;
}
?>
<form action='guardarSalidaMaterial.php' method='POST' name='form1'>
<table border='0' class='textotit' align='center'><tr><th>Registrar Venta</th></tr></table><br>
<table border='1' class='texto' cellspacing='0' align='center' width='100%'>
<tr><th>Tipo de Salida</th><th>Tipo de Documento</th><th>Numero de Salida/Venta</th><th>Fecha</th><th>Almacen Destino</th></tr>
<tr>
<td align='center'>
	<select name='tipoSalida' id='tipoSalida' onChange='ajaxTipoDoc(form1)'>
		<option value="0">--------</option>
<?php
	$sqlTipo="select cod_tiposalida, nombre_tiposalida from tipos_salida where cod_tiposalida=1001 order by 2";
	$respTipo=mysql_query($sqlTipo);
	while($datTipo=mysql_fetch_array($respTipo)){
		$codigo=$datTipo[0];
		$nombre=$datTipo[1];
?>
		<option value='<?php echo $codigo?>'><?php echo $nombre?></option>
<?php		
	}
?>
	</select>
</td>
<td align='center'>
	<div id='divTipoDoc'>
		<select name='tipoDoc' id='tipoDoc'><option value="0"></select>
	</div>
</td>
<td align='center'>
	<div id='divNroDoc'>
	</div>
</td>

<td align='center'>
	<input type='text' class='texto' value='<?php echo $fecha?>' id='fecha' size='10' name='fecha'>
	<img id='imagenFecha' src='imagenes/fecha.bmp'>
</td>

<td align='center'>
	<select name='almacen' id='almacen' class='texto'>
		<option value='0'>-----</option>
<?php
	$sql3="select cod_almacen, nombre_almacen from almacenes order by nombre_almacen";
	$resp3=mysql_query($sql3);
	while($dat3=mysql_fetch_array($resp3)){
		$cod_almacen=$dat3[0];
		$nombre_almacen="$dat3[1] $dat3[2] $dat3[3]";
?>
		<option value="<?php echo $cod_almacen?>"><?php echo $nombre_almacen?></option>
<?php		
	}
?>
	</select>
</td>
</tr>

<tr>
	<th>Cliente</th>
	<th>Precio</th>
	<th>Razon Social</th>
	<th>NIT</th>
	<th>Vendedor - Vehiculo</th>
</tr>
<tr>
	<td align='center'>
		<select name='cliente' class='texto' id='cliente' onChange='ajaxTipoPrecio(form1);'>
			<option value=''>----</option>
<?php
    $sql2="select c.`cod_cliente`, c.`nombre_cliente` from clientes c order by 2";
    //$sql2="select c.`cod_cliente`, c.`nombre_cliente` from clientes c where c.`cod_area_empresa`=$global_agencia";
    $resp2=mysql_query($sql2);

	while($dat2=mysql_fetch_array($resp2)){
	   $codCliente=$dat2[0];
		$nombreCliente=$dat2[1];
?>		
		<option value='<?php echo $codCliente?>'><?php echo $nombreCliente?></option>
<?php    
	}
?>
		</select>
	</td>

	<td>
		<div id='divTipoPrecio'>
			<select name='tipoPrecio' class='texto' id='tipoPrecio'><option value="0"></select>
		</div>
	</td>

	<td>
		<div id='divRazonSocial'>
			<input type='text' name='razonSocial' id='razonSocial' value='0'>
		</div>
	</td>

	<td>
		<div id='divNIT'>
			<input type='text' value='0' name='nitCliente' id='nitCliente'>
		</div>
	</td>
	<td>
		<select name='chofer' class='texto' id='chofer'>
			<option value=''>----</option>
			<?php
			$sql2="select f.`codigo_funcionario`,
				concat(f.`paterno`,' ', f.`nombres`) as nombre from `funcionarios` f where f.`cod_cargo`=1002 
				and f.`cod_ciudad`=$global_agencia order by 2";
			$resp2=mysql_query($sql2);

			while($dat2=mysql_fetch_array($resp2)){
				$codChofer=$dat2[0];
				$nombreChofer=$dat2[1];
			?>		
			<option value='<?php echo $codChofer?>'><?php echo $nombreChofer?></option>
			<?php    
			}
			?>
		</select>

		<select name='vehiculo' class='texto' id='chofer' onChange='ajaxPesoMaximo(this.value);'>
			<option value=''>----</option>
			<?php
			$sql2="select codigo, nombre, placa, peso_maximo from vehiculos order by 2";
			$resp2=mysql_query($sql2);

			while($dat2=mysql_fetch_array($resp2)){
				$codVehi=$dat2[0];
				$nombreVehi="$dat2[1] $dat2[2]";
				$pesoMaximoVehiculo=$dat2[3];
			?>		
			<option value='<?php echo $codVehi?>'><?php echo $nombreVehi?></option>
			<?php    
			}
			?>
		</select>
		<div id='divPesoMax'>
			<input type='hidden' name='pesoMaximoVehiculo' id='pesoMaximoVehiculo' value='0'>
			Peso Maximo: 0
		</div>
		
	</td>

</tr>

<tr>
	<th colspan="5">Observaciones</th>
</tr>


<tr>	
	<td align='center' colspan="5">
		<input type='text' class='texto' name='observaciones' value='' size='100' rows="2">
	</td>
</tr>
</table>


<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" cellSpacing="0" cellPadding="0" width="100%" border="1" id="data0" style="border:#ccc 1px solid;">
	<tr>
		<td align="center" colspan="6">
			<input class="boton" type="button" value="Nuevo Item (+)" onclick="mas(this)" />
		</td>
	</tr>
	<tr>
		<td align="center" colspan="6">
			<div style="width:100%;" align="center"><b>DETALLE</b></div>
		</td>				
	</tr>				
	<tr class="titulo_tabla" align="center">
		<td width="40%">Material</td>
		<td width="7.5%">Stock</td>
		<td width="7.5%">Cant/Metro</td>
		<td width="7.5%">Cant. Total</td>
		<td width="7.5%">Precio </td>
		<td width="7.5%">Desc.(%)</td>
		<td width="7.5%">Monto</td>
		<td width="7.5%">Peso Total</td>
		<td width="7.5%">&nbsp;</td>
	</tr>
	</table>
</fieldset>
	<table id='pieNota' width='100%' border="0">
		<tr>
			<td align='right' width='90%'>Peso Total</td><td><input type='text' name='totalPesoVenta' id='totalPesoVenta'></td>
		</tr>
		<tr>
			<td align='right' width='90%'>Monto Nota</td><td><input type='text' name='totalVenta' id='totalVenta'></td>
		</tr>
		<tr>
			<td align='right' width='90%'>Descuento Bs.</td><td><input type='text' name='descuentoVenta' id='descuentoVenta' onChange='aplicarDescuento(form1);' value="0"></td>
		</tr>
		<tr>
			<td align='right' width='90%'>Monto Final</td><td><input type='text' name='totalFinal' id='totalFinal' ></td>
		</tr>

	</table>


<?php

echo "<table align='center'><tr><td><a href='navegador_ingresomateriales.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>



<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Tipo Material</th><th>Cod. Int.</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select name='itemTipoMaterial'>
			<?php
			$sqlTipo="select t.`cod_tipomaterial`, t.`nombre_tipomaterial` from `tipos_material` t order by t.`nombre_tipomaterial`";
			$respTipo=mysql_query($sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysql_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>
			</select>
			</td>
			<td>
				<input type='text' name='itemCodMaterial'>
			</td>
			<td>
				<input type='text' name='itemNombreMaterial'>
			</td>
			<td>
				<input type='button' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			
		</table>
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>
<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">

</form>
</body>