<?php
require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funciones.php");
?>

<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
        <script type="text/javascript" src="functionsGeneral.js"></script>
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
	//alert(elem.value);
	}
function setMateriales(f, cod, nombreMat, cantidadpresentacion, precio, margenlinea){
	var numRegistro=f.materialActivo.value;
		
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cantidadpresentacion'+numRegistro).value=cantidadpresentacion;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat+" - <span class='textomedianonegro'>CP:"+cantidadpresentacion+"</span>";
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
	document.getElementById('cantidadpresentacion'+numRegistro).value=cantidadpresentacion;
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

	function masMultiple(form) {
		var banderaItems0=0;
		console.log("bandera: "+banderaItems0);
		var numFilas=num;

		menos(numFilas);

		var productosMultiples=new Array();		
		for(i=0;i<=form.length-1;i++){
    		if(form.elements[i].type=='checkbox'){  	   
				if(form.elements[i].checked==true && form.elements[i].name.indexOf("idchk")!==-1 ){ 
					cadena=form.elements[i].value;
					console.log("i: "+i+" cadena: "+cadena+" name: "+form.elements[i].name);
					productosMultiples.push(cadena);
					banderaItems0=1;
					num++;
				}
			}
		}
		num--;
		console.log("bandera: "+banderaItems0);
		if(banderaItems0==1){
			num++;
			div_material_linea=document.getElementById("fiel");

			/*recuperamos las cantidades de los otros productos*/
			var inputs = $('form input[name^="cantidad_unitaria"]');
			var arrayCantidades=[];
			inputs.each(function() {
			  	var name = $(this).attr('name');
			  	var value = $(this).val();
			  	var partes = name.split("cantidad_unitaria");
				var name_form = partes[0]; // Contiene todo antes de "cantidad_unitaria"
				var index_form = partes[1]; // Contiene todo después de "cantidad_unitaria"
			  	arrayCantidades.push([name,value,index_form]);
			});
			var inputs = $('form input[name^="precio_unitario"]');
			var arrayPreciosCaja=[];
			inputs.each(function() {
			  	var name = $(this).attr('name');
			  	var value = $(this).val();
			  	var partes = name.split("precio_unitario");
				var name_form = partes[0]; 
				var index_form = partes[1];
			  	arrayPreciosCaja.push([name,value,index_form]);
			});
			var inputs = $('form input[name^="fechaVenc"]');
			var arrayFV=[];
			inputs.each(function() {
			  	var name = $(this).attr('name');
			  	var value = $(this).val();
			  	var partes = name.split("fechaVenc");
				var name_form = partes[0]; 
				var index_form = partes[1];
			  	arrayFV.push([name,value,index_form]);
			});
			var inputs = $('form input[name^="descuento_porcentaje"]');
			var arrayDescuentoPorcentaje=[];
			inputs.each(function() {
			  	var name = $(this).attr('name');
			  	var value = $(this).val();
			  	var partes = name.split("descuento_porcentaje");
				var name_form = partes[0]; 
				var index_form = partes[1];
			  	arrayDescuentoPorcentaje.push([name,value,index_form]);
			});
			var inputs = $('form input[name^="preciocliente"]');
			var arrayPrecioCliente=[];
			inputs.each(function() {
			  	var name = $(this).attr('name');
			  	var value = $(this).val();
			  	var partes = name.split("preciocliente");
				var name_form = partes[0]; 
				var index_form = partes[1];
			  	arrayPrecioCliente.push([name,value,index_form]);
			});
			console.log("Array Recuperado Cantidad: "+arrayCantidades);
			console.log("Array Recuperado Precios: "+arrayPreciosCaja);
			console.log("Array Recuperado FV: "+arrayFV);
			console.log("Array Recuperado Descuentos: "+arrayDescuentoPorcentaje);
			console.log("Array Recuperado PrecioCliente: "+arrayPrecioCliente);
			/*fin recuperar*/

			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialesIngresoMultiple.php?codigo="+numFilas+"&productos_multiple="+productosMultiples,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material_linea.innerHTML=div_material_linea.innerHTML+ajax.responseText;
				}
				for (x=0;x<arrayCantidades.length;x++) {
					console.log("Iniciando recorrido Matriz");
					/*reponiendo cantidades*/
					var name_set=arrayCantidades[x][0];
					var value_set=arrayCantidades[x][1];
					var index_set=arrayCantidades[x][2];
					document.getElementById(name_set).value=value_set;
					/*reponiendo preciosCaja*/
					name_set=arrayPreciosCaja[x][0];
					value_set=arrayPreciosCaja[x][1];
					index_set=arrayPreciosCaja[x][2];
					document.getElementById(name_set).value=value_set;
					calculaPrecioCliente(value_set, index_set);
					/*reponiendo FV*/
					name_set=arrayFV[x][0];
					value_set=arrayFV[x][1];
					index_set=arrayFV[x][2];
					document.getElementById(name_set).value=value_set;
					/*reponiendo Descuento*/
					name_set=arrayDescuentoPorcentaje[x][0];
					value_set=arrayDescuentoPorcentaje[x][1];
					index_set=arrayDescuentoPorcentaje[x][2];
					document.getElementById(name_set).value=value_set;
					calcularDescuentoUnitario(1,index_set);
					/*reponiendo PrecioCliente*/
					name_set=arrayPrecioCliente[x][0];
					value_set=arrayPrecioCliente[x][1];
					index_set=arrayPrecioCliente[x][2];
					document.getElementById(name_set).value=value_set;
					calculaMargen(document.getElementById(name_set),index_set);
					console.log("PRECIOCLIENTE: "+name_set+" "+index_set);
				}
			}		
			ajax.send(null);
		}
		console.log("CONTROL NUM: "+num);
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
		console.log("CONTROL NUM: "+num);
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
function calculaMargen(precioclienteForm, index){
	preciocliente=parseFloat(precioclienteForm.value);
	var preciocompra=document.getElementById("precio"+index).value;
	var costo=parseFloat(preciocompra);
	var cantidad=document.getElementById('cantidad_unitaria'+index).value;
	var cantidad_presentacion=document.getElementById('cantidadpresentacion'+index).value;

	cantidad=cantidad*cantidad_presentacion;
	
	var costounitario=parseFloat(costo)/parseFloat(cantidad);

	console.log("preciocompra: "+preciocompra);
	console.log("cantidad: "+cantidad);
	console.log("costoUnitario: "+costounitario);

	console.log("nuevo precio cliente: "+preciocliente);

	var margenNuevo=(preciocliente-costounitario)/costounitario;
	
	console.log("nuevo margen cliente: "+margenNuevo);

	var margenNuevoF="M ["+ number_format((margenNuevo*100),0) + "%]";
	document.getElementById('divmargen'+index).innerHTML=margenNuevoF;
}
// CALCULO DE DESCUENTOS EN PORCENTAJE
function calcularDescuentoUnitario(tipo, index){
	let precio_old = parseFloat(document.getElementById('precio_old'+index).value);
	if(tipo == 0){
		//  # Numerico
		let descuento_numero = parseFloat(document.getElementById('descuento_numero'+index).value);
		let total_descuento_numero = (descuento_numero/precio_old)*100;
		document.getElementById('descuento_porcentaje'+index).value = total_descuento_numero.toFixed(2);
	}else{
		//  % Porcentaje
		let descuento_porcentaje = parseFloat(document.getElementById('descuento_porcentaje'+index).value);
		let total_descuento_porcentaje = (descuento_porcentaje/100) * precio_old;
		document.getElementById('descuento_numero'+index).value = total_descuento_porcentaje.toFixed(2);
	}
	// Ajuste Descuento Adicional
	ajusteDescuento();
	// Ajuste Monto Total sin DESCUENTO ADICIONAL
	calculaPrecioCliente(0,index);
	totalesMonto();
}
// Calculo de montos TOTALES
function calculaPrecioCliente(preciocompra, index){
	/************ banderaCalculoPrecio = 0 precionFinal   1 = precioCompra *******************/
	var banderaCalculoPrecio = document.getElementById('bandera_calculo_precio').value;
	/****************************************************************************/
	// CALCULAR SUBTOTAL
	var cantidad 		= parseFloat(document.getElementById('cantidad_unitaria'+index).value);
	var precio_unitario = parseFloat(document.getElementById('precio_unitario'+index).value);
	var cantidad_presentacion = parseFloat(document.getElementById('cantidadpresentacion'+index).value);
	document.getElementById('precio_old'+index).value = (cantidad > 0 ? cantidad : 0) * (precio_unitario > 0 ? precio_unitario : 0);
	var margen		  = document.getElementById('margenlinea'+index).value;

	/*var total_subtotal  = (cantidad > 0 ? cantidad : 0) * (precio_unitario > 0 ? precio_unitario : 0);
	document.getElementById('precio_old'+index).value = total_subtotal.toFixed(2);*/
	/****************************************************************************/

	if(banderaCalculoPrecio==0){
		//var costo=preciocompra.value;
		var costo = parseFloat(document.getElementById("precio"+index).value);
		var costounitario=(costo/cantidad)/cantidad_presentacion;
		console.log("costoUnitario: "+costounitario); // s dejo esta parte de codigo
		var preciocliente=costounitario+(costounitario*(margen/100));
		preciocliente=redondear(preciocliente,2);
		preciocliente=number_format(preciocliente,2);
		document.getElementById('preciocliente'+index).value=preciocliente;		
	}else{
		var costounitario = precio_unitario / cantidad_presentacion;
		var preciocliente=(costounitario + (costounitario*(margen/100)));
		console.log('costounitario:'+costounitario)
		console.log('(costounitario*(margen/100)):'+(costounitario*(margen/100)))
		preciocliente=redondear(preciocliente,2);
		preciocliente=number_format(preciocliente,2);
		document.getElementById('preciocliente'+index).value=preciocliente;
	}

	var margenNuevo=(preciocliente-costounitario)/costounitario;
	var margenNuevoF="M ["+ number_format((margenNuevo*100),0) + "%]";
	document.getElementById('divmargen'+index).innerHTML=margenNuevoF;

	// Ajuste Descuento Adicional
	ajusteDescuento();
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

/*****************************************
 * Descuento Adicional a ITEMS
*****************************************/
function changeDescuentoAdicional(){
	// Ajuste Descuento Adicional
	ajusteDescuento();
	// Ajuste Monto Total sin DESCUENTO ADICIONAL
	totalesMonto();
}

/*****************************************
 * Ajuste de Descuento del Monto de Venta
*****************************************/
function ajusteDescuento(){
	
	var cantidadTotal=0;
	var precioTotal=0;
	var montoTotal=0;
	// Total Monto sin descuento adicional
	var monto_total_old = 0;
    for(var ii=1;ii<=num;ii++){
		if(document.getElementById('material'+ii)!=null){
			monto_total_old += parseFloat(document.getElementById("precio_old"+ii).value);
		}
	}
	// Detalle
    for(var ii=1;ii<=num;ii++){
		if(document.getElementById('material'+ii)!=null){
			var precio 	    = document.getElementById("precio_old"+ii).value;
			// Total Compra
			var total_compra= monto_total_old;
			// Descuento Adicional Superior
			var desc_ad_sup = document.getElementById("descuento_adicional").value;

			// Total Descuento ITEM
			document.getElementById("descuento_adicional"+ii).value = Math.round((parseFloat(precio)/parseFloat(total_compra))*desc_ad_sup);

			/*********************/
			/*		TOTAL		 */
			/*********************/
			var item_descuento_unitario = parseFloat(document.getElementById("descuento_numero"+ii).value);
			var item_descuento = parseFloat(document.getElementById("descuento_adicional"+ii).value);
			var item_precio    = parseFloat(document.getElementById("precio_old"+ii).value);
			var total_final = item_precio - (item_descuento+item_descuento_unitario);
			document.getElementById("precio"+ii).value = total_final.toFixed(2);
			
		}
	}
	
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

$banderaUpdPreciosSucursales=obtenerValorConfiguracion($enlaceCon,49);
$txtUpdPrecios="";
if($banderaUpdPreciosSucursales==0){
	$txtUpdPrecios="*** Los precios seran actualizados solo en ESTA SUCURSAL.";
}else{
	$txtUpdPrecios="*** Los precios seran actualizados en TODAS LAS SUCURSALES del sistema.";
}

$banderaCalculoPrecioFinal=obtenerValorConfiguracion($enlaceCon,52);
if($banderaCalculoPrecioFinal!=1){$banderaCalculoPrecioFinal=0;}
$txtCalculoPrecioFinal="";
if($banderaCalculoPrecioFinal==1){
	$txtCalculoPrecioFinal="*** El precio Cliente se calculará por Precio Compra ANTES de descuentos.";
}else{
	$txtCalculoPrecioFinal="*** El precio Cliente se calculará por Precio Compra DESPUÉS de descuentos.";
}

$global_almacen=$_COOKIE["global_almacen"];

$sql="select IFNULL(max(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen'";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==1){   
	$nro_correlativo=$dat[0];
}
echo "<form action='guarda_ingresomateriales.php' method='post' name='form1' onsubmit='return checkSubmit();'>";

echo "<input type='hidden' name='bandera_calculo_precio' id='bandera_calculo_precio' value='$banderaCalculoPrecioFinal''>";

echo "<table border='0' class='textotit' align='center'>
		<tr><th></th><th>Registrar Ingreso de Productos</th><th></th></tr>
		<tr><th align='left'><span class='textopequenorojo' style='background-color:yellow;'><b>$txtUpdPrecios</b></span></th>
			<th></th>
			<th align='left'><span class='textopequenorojo' style='background-color:aqua;'><b>$txtCalculoPrecioFinal</b></span></th>
		</tr>
		</table><br>";
echo "<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>";
echo "<tr>
	<th>Nro. Ingreso: <b>$nro_correlativo<b></th>";
echo"<th>Fecha: <input type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'></th>
	<th>Tipo de Ingreso: ";
$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso where cod_tipoingreso<>'999' order by nombre_tipoingreso";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<select name='tipo_ingreso' id='tipo_ingreso' class='texto'>";
while($dat1=mysqli_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
    echo "<option value='$cod_tipoingreso'>$nombre_tipoingreso</option>";
}
echo "</select></td>";
echo "<th>Factura:  <input type='number' class='texto' name='nro_factura' value='' id='nro_factura' required>
		</th></tr>";


echo "<tr><th>Proveedor</th>";
$sql1="select p.cod_proveedor, concat(p.nombre_proveedor) from proveedores p 
			order by 2";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<th align='center'><select name='proveedor' id='proveedor' class='texto' style='width:200px' required>";
echo "<option value=''>-</option>";
while($dat1=mysqli_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
	$margenPrecio=$dat1[2];
	
    echo "<option value='$codigo'>$nombre</option>";
}
echo "</select></th>";

echo "<th colspan='1'>Observaciones</th>";
echo "<th colspan='1' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='40'></th></tr>";
echo "</table><br>";
?>
        <div class="contenedor">
        	<div class="codigo-barras div-center">
				<input type="text" class="form-codigo-barras" id="input_codigo_barras" placeholder="Ingrese el código de barras." autofocus autocomplete="off">
			</div>

        </div>
		
		<fieldset id="fiel" style="width:98%;border:0;" >
			<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
				<tr>
					<th colspan="4"></th>
					<th>Descuento Final 1</th>
					<th colspan="2"><input type="text" id="descuento_adicional" value="0" onkeyup="changeDescuentoAdicional()"></th>
				</tr>
				<tr>
					<td align="center" colspan="7">
						<input class="boton" type="button" value="Nuevo Item (+)" onclick="mas(this)" accesskey="A"/>
						<!--input class="boton" type="button" value="Agregar por Linea (+)" onclick="modalMasLinea(this)" accesskey="B"/-->
					</td>
				</tr>
				<tr>
					<td align="center" colspan="7">
					<div style="width:100%;" align="center"><b>DETALLE</b></div>
					</td>				
				</tr>				
				<tr class="titulo_tabla" align="center">
					<td width="5%" align="center">&nbsp;</td>
					<td width="20%" align="center">Producto</td>
					<td width="10%" align="center">Cantidad</td>
					<td width="10%" align="center">Precio<br>Presentación</td>
					<!--td width="10%" align="center">Lote</td-->
					<td width="10%" align="center">Vencimiento</td>
					<!-- <td width="10%" align="center">Precio Distribuidor<br>(Total_item)</td> -->
					<td width="10%" align="center">Subtotal</td>

					<!-- Descuento Unitario -->
					<td width="5%" align="center">Desc.<br>Prod</td>
					<!-- Descuento Adicional -->
					<td width="10%" align="center">Desc.<br>Adicional</td>
					<!-- Monto Total -->
					<td width="10%" align="center">Total</td>

					<td width="10%" align="center">Precio<br>Cliente<br>Unitario</td>
					<td width="10%" align="center">-</td>
				</tr>
			</table>

			<div id="divMaterialLinea"></div>

		</fieldset>

		
		<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
			<tr>
				<td align='right' width="90%">Total Compra</td>
				<td align='right' width="10%"><input type='number' name='totalCompra' id='totalCompra' value='0' size='10' step="0.01" readonly></td>
			</tr>
			<tr>
				<td align='right' width="90%">Descuento Final 2</td>
				<td align='right' width="10%"><input type='number' name='descuentoTotal' id='descuentoTotal' value='0' size='10' onKeyUp='totalesMonto();' step="0.01" required></td>
			</tr>
			<tr>
				<td align='right' width="90%">Total</td>
				<td align='right' width="10%"><input type='number' name='totalCompraSD' id='totalCompraSD' value='0' size='10' step="0.01" readonly></td>
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
			$sqlTipo="select p.cod_proveedor, p.nombre_proveedor from proveedores p order by 2;";
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
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial" class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			<tr>
			<td colspan="3"><input type="button" class="boton2peque" onclick="javascript:masMultiple(this.form);" value="Incluir Productos Seleccionados">
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