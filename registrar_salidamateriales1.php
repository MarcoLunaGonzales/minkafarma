<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!--script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script-->
        <script type="text/javascript" src="lib/js/jquery-3.2.1.min.js"></script>

<?php

$indexGerencia=1;

require('conexionmysqli2.inc');
require('estilos_almacenes.inc');
require('funciones.php');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];

?>
		
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

	var stock=0;
	if($("#solo_stock").is(":checked")){
		stock=1;
	}

	var contenedor;
	var codTipo=f.itemTipoMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	var tipoSalida=(f.tipoSalida.value);
	contenedor = document.getElementById('divListaMateriales');
	
	var arrayItemsUtilizados=new Array();	
	var i=0;
	for(var j=1; j<=num; j++){
		if(document.getElementById('materiales'+j)!=null){
			console.log("codmaterial: "+document.getElementById('materiales'+j).value);
			arrayItemsUtilizados[i]=document.getElementById('materiales'+j).value;
			i++;
		}
	}
	
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMateriales.php?codTipo="+codTipo+"&nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados+"&tipoSalida="+tipoSalida+"&stock="+stock,true);
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
		}
	}
	ajax.send(null);
	
	
}

function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	
	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('itemNombreMaterial').focus();
}
function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}
function setMateriales(f, cod, nombreMat, stockProducto){
	var numRegistro=f.materialActivo.value;
	var nombre_material_x, fecha_venc_x, cantidad_presentacionx, venta_solo_cajax;
	var datos_material=nombreMat.split("####");
 	nombre_material_x=datos_material[0];  
 	fecha_venc_x=datos_material[1];  
 	cantidad_presentacionx=datos_material[2];
 	venta_solo_cajax=datos_material[3];
	document.getElementById('materiales'+numRegistro).value=cod;

	document.getElementById('cod_material'+numRegistro).innerHTML=nombre_material_x;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	document.getElementById("cantidad_unitaria"+numRegistro).focus();

	actStock(numRegistro);
}

num=0;
cantidad_items=0;

function mas(obj) {
	if(num>=15){
		alert("No puede registrar mas de 15 items en una nota.");
	}else{
		//aca validamos que el item este seleccionado antes de adicionar nueva fila de datos
		var banderaItems0=0;
		for(var j=1; j<=num; j++){
			if(document.getElementById('materiales'+j)!=null){
				if(document.getElementById('materiales'+j).value==0){
					banderaItems0=1;
				}
			}
		}
		//fin validacion
		console.log("bandera: "+banderaItems0);

		if(banderaItems0==0){
			num++;
			cantidad_items++;
			console.log("num: "+num);
			console.log("cantidadItems: "+cantidad_items);
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
					buscarMaterial(form1, num);
				}
			}		
			ajax.send(null);
		}

	}
	
}
		
function menos(numero) {
	cantidad_items--;
	console.log("TOTAL ITEMS: "+num);
	console.log("NUMERO A DISMINUIR: "+numero);
	if(numero==num){
		num=parseInt(num)-1;
 	}
	fi = document.getElementById('fiel');
	fi.removeChild(document.getElementById('div'+numero));
	totales();
}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}
	
function validar(f)
{   
	f.cantidad_material.value=num;
	var tipoSalida=document.getElementById("tipoSalida").value;
	var tipoDoc=document.getElementById("tipoDoc").value;
	var almacenDestino=document.getElementById("almacen").value;
		
	var cantidadItems=num;
	if(tipoSalida==0){
		alert("El tipo de Salida no puede estar vacio.");
		return(false);
	}
	if(tipoDoc==0){
		alert("El tipo de Documento no puede estar vacio.");
		return(false);
	}
	/* Validamos el almacen cuando es traspaso. */
	if(almacenDestino==0 && tipoSalida==1000){   
 		alert("El Almacen Destino no puede estar vacio.");
		return(false);
	}
	if(cantidadItems>0){		
		var item="";
		var cantidad="";
		var stock="";
		
		for(var i=1; i<=cantidadItems; i++){
			item=parseFloat(document.getElementById("materiales"+i).value);
			cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
			stock=parseFloat(document.getElementById("stock"+i).value);
	
			if(item==0){
				alert("Debe escoger un item en la fila "+i);
				return(false);
			}
			if(stock<cantidad){
				alert("No puede sacar cantidades mayores a las existencias. Fila "+i);
				return(false);
			}
		}
		console.log("Enviando");
      	document.getElementById("btsubmit").value = "Enviando...";
		document.getElementById("btsubmit").disabled = true;   
		document.forms[0].submit();
		//return(true);
	}else{
		alert("La transaccion debe tener al menos 1 item.");
		return(false);
	}
}
	
	
</script>	
<?php
echo "<body>";

$fecha=date("d/m/Y");

$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' order by cod_salida_almacenes desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
    $codigo++;
}
?>
<form action='guardarSalidaMaterial.php' method='POST' name='form1' onsubmit="return validar(this)">
<h1>Registrar Salida de Almacen</h1>

<input type="hidden" id="almacen_origen" name="almacen_origen" value="<?=$globalAlmacen?>">
<input type="hidden" id="sucursal_origen" name="sucursal_origen" value="<?=$globalAgencia?>">
<input type="hidden" id="no_venta" name="no_venta" value="100">


<table class='texto' align='center' width='90%'>
<tr><th>Tipo de Salida</th><th>Tipo de Documento</th><th>Nro. Salida</th><th>Fecha</th><th>Almacen Destino</th></tr>
<tr>
<td align='center'>
	<select name='tipoSalida' id='tipoSalida' onChange='ajaxTipoDoc(form1)' class="selectpicker" data-style="btn btn-success">
		<option value="0">--------</option>
<?php
	$sqlTipo="select cod_tiposalida, nombre_tiposalida from tipos_salida where cod_tiposalida<>1001 order by 2";
	$respTipo=mysqli_query($enlaceCon,$sqlTipo);
	while($datTipo=mysqli_fetch_array($respTipo)){
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
	<div id='divNroDoc' class='textogranderojo'>
	</div>
</td>

<td align='center'>
	<input type='text' class='texto' value='<?php echo $fecha?>' id='fecha' size='10' name='fecha'>
	<img id='imagenFecha' src='imagenes/fecha.bmp'>
</td>

<td align='center'>
	<select name='almacen' id='almacen' class='texto'>
<?php
	$sql3="select cod_almacen, nombre_almacen from almacenes where cod_almacen<>'$global_almacen' order by nombre_almacen";
	$resp3=mysqli_query($enlaceCon,$sql3);
?>
		<option value="0">--</option>
<?php			
	while($dat3=mysqli_fetch_array($resp3)){
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
	<th>Observaciones</th>
	<th align='center' colspan="4">
		<input type='text' class='texto' name='observaciones' value='' size='100' rows="2">
	</th>
</tr>
</table>

<br>

<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
	<tr>
		<td align="center" colspan="9">
			<b>Detalle de la Transaccion   </b><input class="boton" type="button" value="Agregar (+)" onclick="mas(this)" />
		</td>
	</tr>
	<tr align="center">
		<th width="50%">Material</th>
		<th width="20%">Stock</th>
		<th width="20%">Cantidad</th>
		<th width="10%">&nbsp;</th>
	</tr>
	</table>
</fieldset>

<?php

echo "<div class='divBotones'>
	<input type='submit' class='boton' value='Guardar' id='btsubmit' name='btsubmit'>
	<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_salidamateriales.php\"'>
</div>";

echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>



<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:900px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:980px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:850px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Linea</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select class="textogranderojo" name='itemTipoMaterial' style="width:300px">
			<?php
			$sqlTipo="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
			where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>

			</select>
			</td>
			<td>
				<input type='text' name='itemNombreMaterial' id='itemNombreMaterial' class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			<tr>
				<td>
					<div class="custom-control custom-checkbox small float-left">
                    	<input type="checkbox" class="" id="solo_stock" checked="">
                    	<label class="text-dark font-weight-bold" for="solo_stock">&nbsp;&nbsp;&nbsp;Solo Productos con Stock</label>
         			</div>
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