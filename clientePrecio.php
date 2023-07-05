<?php

$indexGerencia=1;
require "conexionmysqli.php";
require("funciones.php");
require("funcion_nombres.php");
require("estilos_almacenes.inc");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$cod_cliente = $_GET['cod_cliente'];

$rpt_territorio=$_COOKIE['global_agencia'];
$rpt_almacen=$_COOKIE['global_almacen'];
 
$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];
?>

<html>
    <head>
		<title>Cliente Precio</title>
        <link  rel="icon"   href="imagenes/card.png" type="image/png" />
        <link href="assets/style.css" rel="stylesheet" />
		    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<script type="text/javascript" src="functionsGeneral.js"></script>

        <style type="text/css">
        	body{
              zoom: 86%;
              line-height: 0;
            }
            img.bw {
	            filter: grayscale(0);
            }

            img.bw.grey {
            	filter: brightness(0.8) invert(0.4);
            	transition-property: filter;
            	transition-duration: 1s;	
            } 
            .btn-info{
            	background:#006db3 !important;
            }
            .btn-info:hover{
            	background:#e6992b !important;
            }
            .btn-warning{
            	background:#e6992b !important;
            }
            .btn-warning:hover{
            	background:#1d2a76 !important;
            }


            .check_box:not(:checked),
			.check_box:checked {
			position : absolute;
			left     : -9999px;
			}

			.check_box:not(:checked) + label,
			.check_box:checked + label {
			position     : relative;
			padding-left : 30px;
			cursor       : pointer;
			}

			.check_box:not(:checked) + label:before,
			.check_box:checked + label:before {
			content    : '';
			position   : absolute;
			left       : 0px;
			top        : 0px;
			width      : 20px;
			height     : 20px;
			border     : 1px solid #aaa;
			background : #f8f8f8;
			}

			.check_box:not(:checked) + label:after,
			.check_box:checked + label:after {
			font-family             : 'Material Icons';
			content                 : 'check';
			text-rendering          : optimizeLegibility;
			font-feature-settings   : "liga" 1;
			font-style              : normal;
			text-transform          : none;
			line-height             : 22px;
			font-size               : 21px;
			width                   : 22px;
			height                  : 22px;
			text-align              : center;
			position                : absolute;
			top                     : 0px;
			left                    : 0px;
			display                 : inline-block;
			overflow                : hidden;
			-webkit-font-smoothing  : antialiased;
			-moz-osx-font-smoothing : grayscale;
			color                   : #09ad7e;
			transition              : all .2s;
			}

			.check_box:not(:checked) + label:after {
			opacity   : 0;
			transform : scale(0);
			}

			.check_box:checked + label:after {
			opacity   : 1;
			transform : scale(1);
			}

			.check_box:disabled:not(:checked) + label:before,
			.check_box:disabled:checked + label:before {
			&, &:hover {
				border-color     : #bbb !important;
				background-color : #ddd;
			}
			}

			.check_box:disabled:checked + label:after {
			color : #999;
			}

			.check_box:disabled + label {
			color : #aaa;
			}

			.check_box:checked:focus + label:before,
			.check_box:not(:checked):focus + label:before {
			border : 1px dotted #09ad7e;
			}

			label:hover:before {
			border : 1px solid #09ad7e !important;
			}
				td a:focus {
					color: #febd00 !important;
					/*font-size: 20px !important;*/
					background:#1d2a76 !important;
					}
					td a:hover {
					color: #febd00 !important;
					/*font-size: 20px !important;*/
					background:#1d2a76 !important;
					}       



			.sidenav {
			height: 100%;
			width: 0;
			position: fixed;
			z-index: 1;
			top: 0;
			left: 0;
			background-color: #006db3;
			overflow-x: hidden;
			transition: 0.1s;
			padding-top: 60px;
			color: #fff;
			}

			.sidenav a {
			padding: 8px 8px 8px 32px;
			text-decoration: none;
			font-size: 25px;
			color: #818181;
			display: block;
			transition: 0.3s;
			}

			.sidenav a:hover {
			color: #f1f1f1;
			}

			.sidenav .closebtn {
			position: absolute;
			top: 0;
			right: 25px;
			font-size: 36px;
			margin-left: 50px;
			}

			@media screen and (max-height: 450px) {
			.sidenav {padding-top: 15px;}
			.sidenav a {font-size: 18px;}
			}
        </style>
<script type='text/javascript' language='javascript'>
function funcionInicio(){
	//document.getElementById('nitCliente').focus();
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

function listaMateriales(f){
		var stock=0;
	if($("#solo_stock").is(":checked")){
		stock=1;
	}
	/*TIPO DE SALIDA VENTA*/
	var tipoSalida=1001;
	var contenedor;
	var codTipo=f.itemTipoMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	var codigoMat=(f.itemCodigoMaterial.value);

	var nomAccion=f.itemAccionMaterialNom.value;
	var nomPrincipio=f.itemPrincipioMaterialNom.value;

   if(codigoMat==""&&nomAccion==""&&nomPrincipio==""&&nombreItem==""){
     alert("Debe ingresar un criterio de busqueda"+nombreItem);
   }else{
	contenedor = document.getElementById('divListaMateriales');
    contenedor.innerHTML="<br><br><br><br><br><br><p class='text-muted'style='font-size:50px'>Buscando Producto(s)...</p>";
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
	ajax.open("GET", "clienteAjaxListaMateriales.php?codigoMat="+codigoMat+"&tipoSalida="+tipoSalida+"&codTipo="+codTipo+"&nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados+"&codProv="+codTipo+"&stock="+stock+"&nomAccion="+nomAccion+"&nomPrincipio="+nomPrincipio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {			
			contenedor.innerHTML = ajax.responseText;
			/*var oRows = document.getElementById('listaMaterialesTabla').getElementsByTagName('tr');
            var nFilas = oRows.length;					
			if(parseInt(nFilas)==2){
				if(ajax.responseText!=""){
				  document.getElementsByClassName('enlace_ref')[0].click();	
				}				
				//$(".enlace_ref").click();
			}
			//
			document.getElementById('itemCodigoMaterial').focus();				*/
		}		
	}
	ajax.send(null)
   }
}

function actStock(indice){
	var codmat=document.getElementById("materiales"+indice).value;
    var codalm=document.getElementById("global_almacen").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			ajaxPrecioItem(indice);
		}
	}
	ajax.send(null);
}

function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio"+indice).value;
	console.log("descuento: "+tipoPrecio);
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	if(cantidadUnitaria>0){
	}else{
		cantidadUnitaria=0;
	}

	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPrecioItem.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var respuesta=ajax.responseText.split("#####");
				contenedor.innerHTML = respuesta[0]; 
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
	document.getElementById('itemCodigoMaterial').value='';	
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('itemAccionMaterialNom').value='';	
	document.getElementById('itemPrincipioMaterialNom').value='';	
	document.getElementById('itemNombreMaterial').focus();	
	
}

function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}
function setMateriales(f, cod, nombreMat, precio){

	var numRegistro=f.materialActivo.value;
	
	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	document.getElementById('precio_unitario'+numRegistro).value=precio;
	document.getElementById('montoMaterial'+numRegistro).value=precio;
	
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

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
cantidad_items=0;

function mas(obj) {
	if(num>=1000){
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
			var cod_precio=0;
			ajax.open("GET","clienteAjaxMaterialVentas.php?codigo="+num+"&cod_precio="+cod_precio,true);
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

function masMultiple(form) {
		var banderaItems0=0;
		console.log("bandera: "+banderaItems0);
		var numFilas=num;
		console.log("numFilas: "+numFilas);

		menos(numFilas);
		console.log("numFilasActualizado: "+numFilas);

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

			/*recuperamos las cantidades de los otors productos*/
			var inputs = $('form input[name^="cantidad_unitaria"]');
			var arrayCantidades=[];
			inputs.each(function() {
			  var name = $(this).attr('name');
			  var value = $(this).val();
			  var index = name.charAt(name.length - 1);
			  arrayCantidades.push([name,value,index]);
			});
			/*fin recuperar*/

			ajax=nuevoAjax();
			ajax.open("GET","clienteAjaxMaterialVentasMultiple.php?codigo="+numFilas+"&productos_multiple="+productosMultiples,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material_linea.innerHTML=div_material_linea.innerHTML+ajax.responseText;
				}
				for (x=0;x<arrayCantidades.length;x++) {
					console.log("Iniciando recorrido Matriz");
					var name_set=arrayCantidades[x][0];
					var value_set=arrayCantidades[x][1];
					var index_set=arrayCantidades[x][2];
					document.getElementById(name_set).value=value_set;
				}
			}		
			ajax.send(null);
		}
		console.log("CONTROL NUM: "+num);
		Hidden();
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
}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
	    listaMateriales(f);	
	    //$("#enviar_busqueda").click();
	    //$("#enviar_busqueda").click();//Para mejorar la funcion	
	    return false;    	   	    	
		//listaMateriales(f);			
	}
}
// Función para calcular el descuento en Porcentaje
function calcularDescuento(num) {
  var precioUnitario = parseFloat(document.getElementById('precio_unitario' + num).value);
  var descuentoPorcentaje = parseFloat(document.getElementById('descuentoProdPorcen' + num).value);
  var descuentoNumero = (precioUnitario * descuentoPorcentaje) / 100;
  var montoMaterial = precioUnitario - descuentoNumero;

  document.getElementById('descuentoProdNumber' + num).value = descuentoNumero.toFixed(2);
  document.getElementById('montoMaterial' + num).value = montoMaterial.toFixed(2);
}
// Función para calcular el descuento en Precio Numero
function calcularDescuentoInverso(num) {
  var precioUnitario = parseFloat(document.getElementById('precio_unitario' + num).value);
  var descuentoNumero = parseFloat(document.getElementById('descuentoProdNumber' + num).value);
  var descuentoPorcentaje = (descuentoNumero / precioUnitario) * 100;
  var montoMaterial = precioUnitario - descuentoNumero;

  document.getElementById('descuentoProdPorcen' + num).value = descuentoPorcentaje.toFixed(2);
  document.getElementById('montoMaterial' + num).value = montoMaterial.toFixed(2);
}
// Guardar Registro
function guardarClientePrecio() {
	// Obtener todos los elementos <tr> con la clase 'lista_registro'
	var registros = document.querySelectorAll("tr.lista_registro");

	// Crear un array para almacenar los objetos de datos
	var datos = [];

	// Iterar sobre los elementos y extraer los valores
	registros.forEach(function(registro) {
		var precioUnitario = registro.querySelector(".precio_unitario").value;
		var descuentoPorcentaje = registro.querySelector(".descuentoProdPorcen").value;
		var descuentoMonto = registro.querySelector(".descuentoProdNumber").value;
		var montoMaterial = registro.querySelector(".montoMaterial").value;
		var materiales = registro.querySelector(".materiales").value;

		var obj = {
			precioUnitario: precioUnitario,
			descuentoPorcentaje: descuentoPorcentaje,
			descuentoMonto: descuentoMonto,
			montoMaterial: montoMaterial,
			materiales: materiales
		};

		datos.push(obj);
	});
	var observacion = document.getElementById('observacion').value;
	var cod_cliente = document.getElementById('cod_cliente').value;
	$.ajax({
        type: "POST",
        dataType: 'html',
        url: "clientePrecioSave.php",
        data: {
			items: datos,
			observacion: observacion,
			cod_cliente: cod_cliente
		},
        success:  function (resp) {
			// console.log(resp)
			alert('Registro Correcto!');
        }
    });
}
</script>

<form action='guardarSalidaMaterial.php' method='POST' name='form1' id="guardarSalidaVenta" ><!--onsubmit='return checkSubmit();'-->
	<input type="hidden" value="<?=$cod_cliente?>" id="cod_cliente">
	<input type="hidden" id="siat_error_valor" name="siat_error_valor">
	<input type="hidden" id="confirmacion_guardado" value="0">
	<input type="hidden" id="tipo_cambio_dolar" name="tipo_cambio_dolar"value="<?=$tipoCambio?>">
	<input type="hidden" id="global_almacen" value="<?=$globalAlmacen?>">

	<input type="hidden" id="almacen_origen" name="almacen_origen" value="<?=$globalAlmacen?>">
	<input type="hidden" id="sucursal_origen" name="sucursal_origen" value="<?=$globalAgencia?>">

	<input type="hidden" id="validacion_clientes" name="validacion_clientes" value="<?=obtenerValorConfiguracion($enlaceCon,11)?>">

<br>
<input type="hidden" id="ventas_codigo"><!--para validar la funcion mas desde ventas-->

<!-- <div class="codigo-barras div-center">
	<input class="boton" type="button" value="Add Item(+)" onclick="mas(this)" accesskey="a"/>&nbsp;&nbsp;&nbsp;
	<input type="text" class="form-codigo-barras" id="input_codigo_barras" placeholder="Ingrese el código de barras." autofocus autocomplete="off" hidden>
</div> -->

	<?php
		$nombreClienteX=nombreCliente($enlaceCon,$cod_cliente);
		// Obtener el CODIGO del registro antes de eliminarlo

		$query = "SELECT cp.observaciones, CONCAT(c.nombre_cliente, ' ', c.paterno) as cliente
				FROM clientes_precios cp
				LEFT JOIN clientes c ON c.cod_cliente = cp.cod_cliente
				WHERE cp.cod_cliente = '".$cod_cliente."'";

		//echo $query;
		$result = mysqli_query($enlaceCon, $query);
		if (!$result) {
			echo "Error al obtener el Observación del registro: " . mysqli_error($enlaceCon);
			exit;
		}
		$row = mysqli_fetch_assoc($result);
		$observacion = empty($row['observaciones'])?'':$row['observaciones'];
	?>
<center>
	<h4>Registro de Precios por Cliente <br> Cliente: <?= $nombreClienteX; ?></h4>
</center>
<center>
	<table border="0" class="texto">
		<tbody>
			<tr>
				<td>
					<input type="button" value="Add Item(+)" name="Guardar" class="boton" onclick="mas(this)">
				</td>
			</tr>
		</tbody>
	</table>
</center>
<table class="texto" align="center" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td>
				<th align="left">Observación:</th> 
				<td bgcolor="#ffffff">
				<textarea name="observacion" id="observacion" cols="60"><?=$observacion;?></textarea>
			</td>
		</tr>
	</tbody>
</table>

<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="100%" id="data0" border="0">
		<tr align="center">
			<td width="38%">Material</td>
			<td width="8%">Precio </td>
			<td width="15%">Desc. %</td>
			<td width="15%">Desc. Monto</td>
			<td width="8%">Precio Cliente</td>
			<td width="5%">Acciones</td>
		</tr>
	</table>
	<?php
	$cantidad_total = 0;
	// Obtener el detalle de un registro específico
	$codigo_registro = 1; // Código del registro que deseas obtener el detalle

	$query = "SELECT cpd.* 
				FROM clientes_precios cp
				LEFT JOIN clientes_preciosdetalle cpd ON cpd.cod_clienteprecio = cp.codigo 
				WHERE cp.cod_cliente = '".$cod_cliente."'
				ORDER BY cpd.codigo ASC";
	$result = mysqli_query($enlaceCon, $query);

	if (!$result) {
		echo "Error al ejecutar la consulta: " . mysqli_error($enlaceCon);
		exit;
	}

	// Verificar si se encontraron registros
	if (mysqli_num_rows($result) > 0) {
		// Recorrer los registros
		while ($row = mysqli_fetch_assoc($result)) {
			$cantidad_total++;
			if(!empty($row['cod_producto'])){
	?>

	<div id="div<?=$cantidad_total;?>"><link href="stilos.css" rel="stylesheet" type="text/css"><input type="hidden" value="1000" name="global_almacen" id="global_almacen">
		<link rel="STYLESHEET" type="text/css" href="stilos.css">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<table border="0" align="center" width="100%" class="texto" id="data1">
			<tbody><tr bgcolor="#FFFFFF" class="lista_registro">

			<td width="38%" align="center">
				<input type="hidden" name="materiales<?=$cantidad_total;?>" id="materiales<?=$cantidad_total;?>" value="<?=$row['cod_producto'];?>" class="materiales">
				<div id="cod_material1" class="textomedianonegro">KETOFIN-LC KETOROLACO 20 MG. X 30 TAB - LC FARMA-GLOBELA PHARMA (9062)</div>
			</td>

			<td align="center" width="8%">
				<div id="idprecio1">
					<input class="inputnumber precio_unitario" type="number" min="1" value="<?=$row['precio_base'];?>" id="precio_unitario<?=$cantidad_total;?>" name="precio_unitario<?=$cantidad_total;?>" step="0.01" readonly="">
				</div>
			</td>

			<td align="center" width="15%">
				<input class="inputnumber descuentoProdPorcen" type="number" min="0" max="90" step="0.5" value="<?=$row['porcentaje_aplicado'];?>" id="descuentoProdPorcen<?=$cantidad_total;?>" name="descuentoProdPorcen<?=$cantidad_total;?>" style="background:#ADF8FA;" onkeyup="calcularDescuento(<?=$cantidad_total;?>)">
			</td>
			<td align="center" width="15%">
				<input class="inputnumber descuentoProdNumber" type="number" value="<?=$row['precio_aplicado'];?>" id="descuentoProdNumber<?=$cantidad_total;?>" name="descuentoProdNumber<?=$cantidad_total;?>" step="0.01" style="background:#ADF8FA;" onkeyup="calcularDescuentoInverso(<?=$cantidad_total;?>)">
			</td>

			<td align="center" width="8%">
				<input class="inputnumber montoMaterial" type="number" value="<?=$row['precio_producto'];?>" id="montoMaterial<?=$cantidad_total;?>" name="montoMaterial<?=$cantidad_total;?>" step="0.01" style="height:20px;font-size:19px;width:80px;color:red;" required="" readonly=""> 
			</td>

			<td align="center" width="5%"><input class="boton2peque" type="button" value="-" onclick="menos(<?=$cantidad_total;?>)"></td>

			</tr>
			</tbody>
		</table>
	</div>
	<?php
			}
		}
	}
	?>
</fieldset>

<center>
	<table border="0" class="texto">
		<tbody>
			<tr>
				<td>
					<input type="button" value="Registrar Precios" name="Guardar" class="boton" onclick="guardarClientePrecio()">
					<input class="boton2" type="button" value="Volver" onclick="javascript:listaClientes();">
					<!-- <a href="programas/clientes/inicioClientes.php" target="contenedorPrincipal" class="mm-listitem__text">Clientes</a> -->
				</td>
			</tr>
		</tbody>
	</table>
</center>

<!-- Volver a lista de clientes -->
<script>
	function listaClientes(){
		location.href="programas/clientes/inicioClientes.php";
	}
</script>
<!--AQUI ESTA EL MODAL PARA LA BUSQUEDA DE PRODUCTOS-->
<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:1150px; height: 550px; top:30px; left:50px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:1160px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:1100px; height:500px; position:absolute; top:50px; left:70px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Proveedor</th><th>Acci&oacute;n Terap&eacute;utica</th><th>Principio Activo</th></tr>
			<tr>
			<td width="30%">
			<select class="selectpicker col-sm-12" name='itemTipoMaterial' id='itemTipoMaterial' data-live-search='true' data-size='6' data-style='btn btn-default btn-lg ' style="width:300px"> <!-- data-live-search='true' data-size='6' data-style='btn btn-default btn-lg '-->
			<?php
			$sqlTipo="select p.cod_proveedor,p.nombre_proveedor from proveedores p
			where p.cod_proveedor>0 order by 2;";
			$respTipo=mysqli_query($enlaceCon, $sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>

			</select>
			</td>
			<td width="40%">
			<input type='text' placeholder='Accion Terapeutica' name='itemAccionMaterialNom' id='itemAccionMaterialNom' class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<td width="30%">
			<input type='text' placeholder='Principio Activo' name='itemPrincipioMaterialNom' id='itemPrincipioMaterialNom' class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<tr><th>&nbsp;</th><th>Codigo / Producto</th><th>&nbsp;</th></tr>
	     <tr>
	     	<td>
				<div class="custom-control custom-checkbox small float-left">
                    <input type="checkbox" class="" id="solo_stock" checked="">
                    <label class="text-dark font-weight-bold" for="solo_stock">&nbsp;&nbsp;&nbsp;Solo Productos con Stock</label>
         </div>
			</td>
			<td>
				<div class="row">
					<div class="col-sm-3"><input type='number' placeholder='--' name='itemCodigoMaterial' id='itemCodigoMaterial' class="textogranderojo" onkeypress="return pressEnter(event, this.form);" onkeyup="return pressEnter(event, this.form);"></div>
					<div class="col-sm-7"><input type='text' placeholder='Descripción' name='itemNombreMaterial' id='itemNombreMaterial' class="textogranderojo" onkeypress="return pressEnter(event, this.form);"></div>				   
				</div>
				
			</td>	
					
			<td align="center">				
				<input type='button' id="enviar_busqueda" class='boton' value='Buscar' onClick="listaMateriales(this.form)">	
				<input type='button' id="enviar_busqueda" class='boton2' value='Limpiar' onClick="limpiarFormularioBusqueda();return false;">	
				<!--a href="#" class="btn btn-warning btn-fab float-right" title="Limpiar Formulario de Busqueda" data-toggle='tooltip' onclick="limpiarFormularioBusqueda();return false;"><i class="material-icons">cleaning_services</i></a-->
			</td>
 			</tr>
		</table>		
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>
<div style="height:200px;"></div>

<input type='hidden' name='materialActivo' value="<?=$cantidad_total;?>">
<input type='hidden' name='cantidad_material' value="<?=$cantidad_total;?>">

</form>

<script>
	num				= <?=$cantidad_total;?>;
	cantidad_items	= <?=$cantidad_total;?>;
</script>
<!--<script src="dist/selectpicker/dist/js/bootstrap-select.js"></script>-->
 <script type="text/javascript" src="dist/js/functionsGeneral.js"></script>

</body>
</html>
