<?php
require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
?>

<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
        <script type='text/javascript' language='javascript'>

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
	var nombreItem=f.itemNombreMaterial.value;
	contenedor = document.getElementById('divListaMateriales');
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMaterialesIngreso.php?codTipo="+codTipo+"&nombreItem="+nombreItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}

function buscarMaterialLinea(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('itemNombreMaterial').focus();	
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
function marcarDesmarcar(f,elem){
	 var i;
      var j=0;
	 if(elem.checked==true){      	       
      for(i=0;i<=f.length-1;i++){
       if(f.elements[i].type=='checkbox'){       
		f.elements[i].checked=true;
        }
      }	
    }else{
		for(i=0;i<=f.length-1;i++){
       if(f.elements[i].type=='checkbox'){       
		f.elements[i].checked=false;
        }
      }	
	}
}
function buscarMaterialSelec(f, numMaterial){
	f.materialActivo.value=numMaterial;

	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('itemNombreMaterial').focus();	
}

function ver(elem){
	alert(elem.value);
	}
function setMateriales(f, cod, nombreMat, cantidadpresentacion, precio, margenlinea){
	var numRegistro=f.materialActivo.value;
		
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	document.getElementById('divpreciocliente'+numRegistro).innerHTML=number_format(precio,2);
	document.getElementById('margenlinea'+numRegistro).value=margenlinea;
	
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

	document.getElementById("cantidad_unitaria"+numRegistro).focus();	
}
function setMaterialesSelec(f, cod, nombreMat, cantidadpresentacion, precio, margenlinea){
	var numRegistro=f.materialActivo.value;
//alert(numRegistro);
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	document.getElementById('divpreciocliente'+numRegistro).innerHTML=number_format(precio,2);
	document.getElementById('margenlinea'+numRegistro).value=margenlinea;
}
function masSelec() {	
 	console.log("entrando masSelec num="+num);
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
			//buscarMaterial(form1, num);
			return (true);
		}
	}		
	ajax.send(null);
}	
function setSeleccionados(f){
	
	var i;
   
	var cadena="";
	var aux="";
	var corrnum="";
	var prodArray;
	 var sw=0;
	 var x=0;
	 var cont=1;
	 var sw=0;
	 var numRegistro;
	 numRegistro=f.materialActivo.value;
	//alert("numRegistro="+numRegistro);
	for(i=0;i<=f.length-1;i++){
    	if(f.elements[i].type=='checkbox'){  	   
			if(f.elements[i].checked==true){ 
				numRegistro=num;
				cadena=f.elements[i].value;
				console.log("i: "+i+" cadena: "+cadena+" name: "+f.elements[i].name);
				
				
				prodArray=new Array();
				prodArray =cadena.split("|");
				aux=prodArray[0]+prodArray[1]+prodArray[2]+prodArray[3]+prodArray[4];
			    //console.log("datoSelec"+prodArray[0]);
				if(sw==0){
					sw=1;
				}else{
					masSelec();
				}
				
				console.log("num: "+num);
				console.log("CodMaterialF: "+prodArray[0]);
				console.log("MaterialF: "+prodArray[1]);
				//console.log("material"+num+" = "+document.getElementById('material'+num).value);
				 
				// document.getElementById('material'+num).value=prodArray[0];
				// document.getElementById('cod_material'+num).innerHTML=prodArray[1];

				
				//document.getElementById('material2').value=10101010;
				//document.getElementById('cod_material2').innerHTML="vamos carajo";

				//setMateriales(f,prodArray[0], prodArray[1], prodArray[2], prodArray[3], prodArray[4]);
				//document.getElementById('material'+numRegistro).value=prodArray[1];
				//document.getElementById('material'+numRegistro).value=prodArray[1];
				
				//document.getElementById('cod_material'+numRegistro).innerHTML=prodArray[0];
				//document.getElementById('divpreciocliente'+num).innerHTML=number_format(precio,2);
				//document.getElementById('margenlinea'+num).value=margenlinea;
				//numRegistro;
				// setMaterialesSelec(f,prodArray[0], prodArray[1], prodArray[2], prodArray[3], prodArray[4]);
				 ////////////
				 //alert("hola"+num);
				// numRegistro=num*1;
				 /////////////
			}
        }
      }	
	//alert("numRegistro=="+numRegistro);
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}
	

function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
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

	function modalMasLinea(form){
		buscarMaterialLinea(form1,0);
	}
	function masLinea(obj) {
		var banderaItems0=0;
		console.log("bandera: "+banderaItems0);
		var codLineaProveedor=form1.itemTipoMaterial.value;
		//alert(codLineaProveedor);
		if(banderaItems0==0){
			num++;
			div_material_linea=document.getElementById("divMaterialLinea");			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialLineaIngreso.php?codigo="+num+"&cod_linea_proveedor="+codLineaProveedor,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material_linea.innerHTML=ajax.responseText;
				}
			}		
			ajax.send(null);
		}
		Hidden();
	}	
	function mas(obj) {
		var banderaItems0=0;
		for(var j=1; j<=num; j++){
			if(document.getElementById('material'+j)!=null){
				if(document.getElementById('material'+j).value==0){
					banderaItems0=1;
				}
			}
		}
		//fin validacion
		console.log("bandera: "+banderaItems0);
		if(banderaItems0==0){
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
					buscarMaterial(form1, num);
				}
			}		
			ajax.send(null);
		}
	}	
	
	function menos(numero) {
		if(numero==num){
			num=parseInt(num)-1;
		}
		//num=parseInt(num)-1;
		fi = document.getElementById('fiel');
		fi.removeChild(document.getElementById('div'+numero));		
	}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}
function calculaPrecioCliente(preciocompra, index){
	//alert('calculaPrecioCliente');
	var costo=preciocompra.value;
	var margen=document.getElementById('margenlinea'+index).value;
	var cantidad=document.getElementById('cantidad_unitaria'+index).value;
	var costounitario=costo/cantidad;

	console.log("costoUnitario: "+costounitario); // s dejo esta parte de codigo

	var preciocliente=costounitario+(costounitario*(margen/100));
	preciocliente=redondear(preciocliente,1);
	preciocliente=number_format(preciocliente,2);
	document.getElementById('preciocliente'+index).value=preciocliente;
	totalesMonto();
}

function totalesMonto(){
	
	var cantidadTotal=0;
	var precioTotal=0;
	var montoTotal=0;
    for(var ii=1;ii<=num;ii++){
		if(document.getElementById('material'+ii)!=null){
			var precio=document.getElementById("precio"+ii).value;
			montoTotal=montoTotal+parseFloat(precio);
		}
	}
	montoTotal=Math.round(montoTotal*100)/100;
	
    document.getElementById("totalCompra").value=montoTotal;
	//alert(montoTotal);
	var descuentoTotal=document.getElementById("descuentoTotal").value;
	var totalSD=montoTotal-descuentoTotal;
	//alert(totalSD);
	document.getElementById("totalCompraSD").value=totalSD;
	
}

function validar(f){   
	f.cantidad_material.value=num;
	var cantidadItems=num;
	
	if(cantidadItems>0){
		var item="";
		var cantidad="";
		var precioBruto="";
		var precioNeto="";
		
		for(var i=1; i<=cantidadItems; i++){
			item=parseFloat(document.getElementById("material"+i).value);			
			if(item==0){
				alert("Debe escoger un item en la fila "+i);
				return(false);
			}
			return(true);
		}
		
	}else{
		alert("El ingreso debe tener al menos 1 item.");
		return(false);
	}
}


function checkSubmit() {
    document.getElementById("btsubmit").value = "Enviando...";
    document.getElementById("btsubmit").disabled = true;
    return true;
}

function redondear(value, precision) {
    var multiplier = Math.pow(10, precision || 0);
    return Math.round(value * multiplier) / multiplier;
}
	</script>
<?php


if($fecha=="")
{   $fecha=date("d/m/Y");
}

echo "<form action='guarda_ingresomateriales.php' method='post' name='form1' onsubmit='return checkSubmit();'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Ingreso de Materiales</th></tr></table><br>";
echo "<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>";
echo "<tr><th>Numero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Factura</th></tr>";
echo "<tr>";
$sql="select nro_correlativo from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $nro_correlativo=1;
}
else
{   $nro_correlativo=$dat[0];
    $nro_correlativo++;
}
echo "<td align='center'>$nro_correlativo</td>";
echo "<td align='center'>";

echo "<input type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
echo "<img id='imagenFecha' src='imagenes/fecha.bmp'>";
echo "</td>";

$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td align='center'><select name='tipo_ingreso' id='tipo_ingreso' class='texto'>";
while($dat1=mysqli_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
    echo "<option value='$cod_tipoingreso'>$nombre_tipoingreso</option>";
}
echo "</select></td>";
echo "<td align='center'><input type='number' class='texto' name='nro_factura' value='' id='nro_factura' required></td></tr>";

echo "<tr><th>Proveedor</th>";
echo "<th colspan='3'>Observaciones</th></tr>";

$sql1="select p.cod_proveedor, concat(p.nombre_proveedor,' ',pl.nombre_linea_proveedor), pl.margen_precio from proveedores p, proveedores_lineas pl 
			where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<tr><td align='center'><select name='proveedor' id='proveedor' class='texto' required>";
echo "<option value=''>-</option>";
while($dat1=mysqli_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
	$margenPrecio=$dat1[2];
	
    echo "<option value='$codigo'>$nombre  -  ($margenPrecio%)</option>";
}
echo "</select></td>";
echo "<td colspan='4' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";
?>
		<fieldset id="fiel" style="width:98%;border:0;" >
			<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
				<tr>
					<td align="center" colspan="6">
						<input class="boton" type="button" value="Nuevo Item (+)" onclick="mas(this)" accesskey="A"/>&nbsp;&nbsp;&nbsp;
						<input class="boton" type="button" value="Agregar por Linea (+)" onclick="modalMasLinea(this)" accesskey="B"/>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="6">
					<div style="width:100%;" align="center"><b>DETALLE</b></div>
					</td>				
				</tr>				
				<tr class="titulo_tabla" align="center">
					<td width="5%" align="center">&nbsp;</td>
					<td width="25%" align="center">Producto</td>
					<td width="10%" align="center">Cantidad</td>
					<!--td width="10%" align="center">Lote</td-->
					<td width="10%" align="center">Vencimiento</td>

					<td width="10%" align="center">PrecioDistribuidor(Total_item)</td>
					<td width="10%" align="center">PrecioClienteFinal</td>
					<td width="10%" align="center">&nbsp;</td>
				</tr>
			</table>
		</fieldset>

		<div id="divMaterialLinea"></div>
		
		<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
			<tr>
				<td align='right'>Total Compra</td><td align='right'><input type='number' name='totalCompra' id='totalCompra' value='0' size='3' readonly></td>
			</tr>
			<tr>
				<td align='right'>Descuento</td><td align='right'><input type='number' name='descuentoTotal' id='descuentoTotal' value='0' size='3' onKeyUp='totalesMonto();' required></td>
			</tr>
			<tr>
				<td align='right'>Total</td><td align='right'><input type='number' name='totalCompraSD' id='totalCompraSD' value='0' size='3' readonly></td>
			</tr>
		</table>

<?php


echo "<div class='divBotones'>
<input type='submit' class='boton' name='Guardar' value='Guardar'  id='btsubmit' onClick='return validar(this.form);'></center>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresomateriales.php\"'></center>
</div>";

echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>



<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:850px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:920px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:800px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center; height:445px; overflow-y: scroll;">
		<table align='center' class="texto">
			<tr><th>Linea</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select name='itemTipoMaterial' id="itemTipoMaterial" class="textomedianorojo" style="width:300px">
			
			<?php
			$sqlTipo="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor), pl.margen_precio from proveedores p, proveedores_lineas pl 
			where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				$margenPrecio=$datTipo[2];
				
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>
			</select>
			</td>
			<td>
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial" class="textogranderojo"  onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			<tr>
			<td><input type="button" class="boton2peque" onclick="javascript:masLinea(this.form);" value="BL">
			<input type="button" class="boton2peque" onclick="javascript:setSeleccionados(this.form);" value="Subir Productos Seleccionados"></td>
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