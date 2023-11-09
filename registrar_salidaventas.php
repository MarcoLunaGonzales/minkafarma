<?php

$indexGerencia=1;
require "conexionmysqli.inc";
require("funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');
?>

<html>
    <head>
		<title>VENTA</title>
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





/*.circle::before, .circle::after {
  content:"";
  position:absolute;
  top: 50%;
  left: 50%;
  transform:translate(-50%, -50%);
  border: 10px solid gray;
  border-radius:100%;
  animation: latido linear 3s infinite;
}

.circle::after {
  animation-delay: -1.5s;
}

@keyframes latido {
  0% { width:60px; height:40px; border:7px solid gray; }
  100% { width:120px; height:120px; border:10px solid transparent; }
}

*/

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
	ajax.open("GET", "ajaxListaMateriales.php?codigoMat="+codigoMat+"&tipoSalida="+tipoSalida+"&codTipo="+codTipo+"&nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados+"&codProv="+codTipo+"&stock="+stock+"&nomAccion="+nomAccion+"&nomPrincipio="+nomPrincipio,true);
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


function ajaxNroDoc(f){
	var contenedor;
	contenedor=document.getElementById("divNroDoc");
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	
	if(codTipoDoc==2){
		document.getElementById("nitCliente").value="123";
		document.getElementById("razonSocial").value="SN";
	}

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
	totales();
	ajax.send(null);
}

function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio"+indice).value;
	var cod_cliente  = document.getElementById('cliente').value;


	console.log("AjaxPrecioItemStart->descuento: "+tipoPrecio);
	//var tipoPrecio=1;
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	if(cantidadUnitaria>0){
	}else{
		cantidadUnitaria=0;
	}

	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPrecioItem.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio+"&cod_cliente="+cod_cliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
				var respuesta=ajax.responseText.split("#####");
				contenedor.innerHTML = respuesta[0];				
				if(parseFloat(cantidadUnitaria>0)){
					document.getElementById("descuentoProducto"+indice).value=(respuesta[1]*parseFloat(cantidadUnitaria));
				}else{
					document.getElementById("descuentoProducto"+indice).value=(respuesta[1]);
				}
				if(respuesta[2]==0){
					console.log("No aplica porcentaje para el producto");
				}else{
					if(parseFloat(cantidadUnitaria>0)){
						document.getElementById("tipoPrecio"+indice).value=(respuesta[2]*parseFloat(cantidadUnitaria));						
					}else{
						document.getElementById("tipoPrecio"+indice).value=(respuesta[2]);
					}
				}
				if(respuesta[3]!=""){
					document.getElementById("divMensajeOferta"+indice).innerHTML=respuesta[3];
				}
	    	calculaMontoMaterial(indice);
		}else{
			contenedor.innerHTML = "Error en Conexión!!";				
		}
	}
	ajax.send(null);
}

function ajaxRazonSocial(f){
	var contenedor;
	contenedor=document.getElementById("divRazonSocial");
	var nitCliente=document.getElementById("nitCliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxRazonSocial.php?nitCliente="+nitCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			document.getElementById('razonSocial').focus();
			ajaxClienteBuscar();
			ajaxVerificarNitCliente();			
		}
	}
	ajax.send(null);
}
function ajaxRazonSocialCliente(f){
	var contenedor;
	contenedor=document.getElementById("divRazonSocial");
	var cliente=document.getElementById("cliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxRazonSocialCliente.php?cliente="+cliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			if(cliente!=146){
				contenedor.innerHTML = ajax.responseText;
			}			
			document.getElementById('razonSocial').focus();
			if($("#cliente").val()==146){
				$("#razonSocial").attr("readonly",false);								
			}else{
				$("#razonSocial").attr("readonly",true);	
			}
			
		}
	}
	ajax.send(null);
}

function ajaxNitCliente(f){
	var contenedor;
	var nitCliente=document.getElementById("nitCliente").value;
	
}
function ajaxVerificarNitCliente(){
	$("#siat_error").attr("style","");
	$("#siat_error_valor").val(0);
	//$("#siat_error").html("Verificando existencia del NIT...");
	var parametros={"nit":$("#nitCliente").val()};
	/*$.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxVerificarNitSiatCliente.php",
        data: parametros,
        success:  function (resp) {          
           var r=resp.split("#####");
           $("#siat_error").html(r[1]);  

           if(r[2]=="1"){
           		$("#siat_error").attr("style","background:white;color:green;padding:10px;border-radius:10px;font-weight:bold;margin-left:220px;height:40px;font-size:30px;");
           		$("#tipo_documento").val(5);
           		$("#siat_error_valor").val(1);
           }else{
           	
           		$("#siat_error").attr("style","background:white;color:#5E5E5E;padding:10px;border-radius:10px;font-weight:bold;margin-left:220px;height:40px;font-size:30px;");
           		// if ($("#tipo_documento").val()!=2){
           		// 	$("#tipo_documento").val(1);           		           		
           		// }
           		
           }
           mostrarComplemento();
           $("#tipo_documento").selectpicker("refresh");                        	   
        }
    });	
	*/
}
function ajaxClienteBuscar(f){
	var contenedor;
	contenedor=document.getElementById("divCliente");
	var nitCliente=document.getElementById("nitCliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxClienteLista.php?nitCliente="+nitCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var datos_resp=ajax.responseText.split("####");
			//alert(datos_resp[1])
			//$("#cliente").val(datos_resp[1]);			
			$("#cliente").html(datos_resp[1]);				
			ajaxRazonSocialCliente(document.getElementById('form1'));
			$("#cliente").selectpicker('refresh');
		}
	}
	ajax.send(null);
}


function refrescarComboCliente(cliente){
	var parametros={"cliente":cliente,"nit":$("#nitCliente").val()};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "listaClientesActual.php",
        data: parametros,
        success:  function (resp) {
        	Swal.fire("Correcto!", "Se guardó el cliente con éxito", "success");   
           $("#cliente").html(resp);  
           ajaxRazonSocialCliente(document.getElementById('form1'));
           $("#cliente").selectpicker("refresh");          
           $("#modalNuevoCliente").modal("hide");                  	   
        }
    });	
}

function mostrarClientesActualesCombo(){
	var parametros={"0":0};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "listaClientesActual.php",
        data: parametros,
        success:  function (resp) {
           $("#cliente_campana").html(resp);  
           $("#cliente_campana").val($("#cliente").val());
           $("#cliente_campana").selectpicker("refresh");                         	   
        }
    });	
}


function mostrarRegistroConTarjeta(){
	$("#titulo_tarjeta").html("");
	if($("#nro_tarjeta").val()!=""){
      $("#titulo_tarjeta").html("(REGISTRADO)");
	}
	if($("#monto_tarjeta").val()==""){	  
      $("#monto_tarjeta").val($("#totalFinal").val());
      $("#efectivoRecibidoUnido").val($("#totalFinal").val());
      $("#tipoVenta").val(2);
      $(".selectpicker").selectpicker("refresh");
      aplicarMontoCombinadoEfectivo(form1);
      document.getElementById("nro_tarjeta").focus();
	}
	$("#modalPagoTarjeta").modal("show");	
	//$("#nro_tarjeta").focus();	
}
function verificarPagoTargeta(){	
  var nro_tarjeta=$("#nro_tarjeta").val();
  if(nro_tarjeta!=""){
  	$("#boton_tarjeta").attr("style","background:green");
  }else{
  	$("#boton_tarjeta").attr("style","background:#96079D");
  }
}


function calculaMontoMaterial(indice){
	console.log("function calculaMontoMaterial");
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	if(cantidadUnitaria==""){
		cantidadUnitaria=0;
	}
	var precioUnitario=document.getElementById("precio_unitario"+indice).value;
	var porcentajeDescuentoUnitario=document.getElementById("tipoPrecio"+indice).value;
	
	console.log("Variables entrada porcentaje: "+porcentajeDescuentoUnitario);

	descuentoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario)) * (parseFloat(porcentajeDescuentoUnitario)/100);
	descuentoUnitario=Math.round(descuentoUnitario*100)/100;
	console.log("calculo: CU: "+cantidadUnitaria+" PU: "+precioUnitario+" DUPorc: "+porcentajeDescuentoUnitario+" DU:"+descuentoUnitario);
	var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario)) - (parseFloat(descuentoUnitario));
	montoUnitario=Math.round(montoUnitario*100)/100;
	document.getElementById("descuentoProducto"+indice).value=descuentoUnitario;
	document.getElementById("montoMaterial"+indice).value=montoUnitario;
	
	totales();
}

function calculaMontoMaterial_bs(indice){
	console.log("function calculaMontoMaterialBolivianos");
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	if(cantidadUnitaria==""){
		cantidadUnitaria=0;
	}
	var precioUnitario=document.getElementById("precio_unitario"+indice).value;
	
	var descuentoUnitarioBS=document.getElementById("descuentoProducto"+indice).value;
	
	console.log("Variables entrada porcentajeDescuentoUnitarioBS: "+descuentoUnitarioBS);

	porcentajeDescuentoUnitario = ((parseFloat(descuentoUnitarioBS)) / (parseFloat(cantidadUnitaria)*parseFloat(precioUnitario))*100);
	porcentajeDescuentoUnitario=Math.round(porcentajeDescuentoUnitario*100)/100;
	console.log("calculo: CU: "+cantidadUnitaria+" PU: "+precioUnitario+" DUPorc: "+porcentajeDescuentoUnitario+" DU:"+descuentoUnitario);
	var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario)) - (parseFloat(descuentoUnitarioBS));
	montoUnitario=Math.round(montoUnitario*100)/100;
	document.getElementById("tipoPrecio"+indice).value=porcentajeDescuentoUnitario;
	document.getElementById("montoMaterial"+indice).value=montoUnitario;
	
	totales();
}


function totales(){
	var subtotal=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null){
			var monto=document.getElementById("montoMaterial"+ii).value;
			subtotal=subtotal+parseFloat(monto);
		}
    }
    var subtotalPrecio=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null){
			var precio=document.getElementById("precio_unitario"+ii).value;
			var cantidad=document.getElementById("cantidad_unitaria"+ii).value;
			subtotalPrecio=subtotalPrecio+parseFloat(precio*cantidad);
		}
    }
    //document.getElementById("total_precio_sin_descuento").innerHTML=subtotalPrecio;

    subtotalPrecio=Math.round(subtotalPrecio*100)/100;

	subtotal=Math.round(subtotal*100)/100;
	
	var tipo_cambio=$("#tipo_cambio_dolar").val();

    document.getElementById("totalVenta").value=subtotal;
	document.getElementById("totalFinal").value=subtotal;

	document.getElementById("totalVentaUSD").value=Math.round((subtotal/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((subtotal/tipo_cambio)*100)/100;

    //setear descuento o aplicar la suma total final con el descuento
	document.getElementById("descuentoVenta").value=0;
	document.getElementById("descuentoVentaUSD").value=0;

	aplicarDescuentoPorcentaje(form1);

	aplicarCambioEfectivo();
	minimoEfectivo();
}

function aplicarDescuento(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVenta").value;
	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(descuento*100)/100;
	
	document.getElementById("totalFinal").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoUSD=(parseFloat(total)-parseFloat(descuento))/tipo_cambio;
	document.getElementById("descuentoVentaUSD").value=Math.round((descuento/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((descuentoUSD)*100)/100;

	document.getElementById("descuentoVentaPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	document.getElementById("descuentoVentaUSDPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	aplicarCambioEfectivo();
	minimoEfectivo();
	//totales();
	
}
function aplicarDescuentoUSD(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVentaUSD").value;
	var descuento=document.getElementById("descuentoVentaUSD").value;
	
	descuento=Math.round(descuento*100)/100;
	
	document.getElementById("totalFinalUSD").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoBOB=(parseFloat(total)-parseFloat(descuento))*tipo_cambio;
	document.getElementById("descuentoVenta").value=Math.round((descuento*tipo_cambio)*100)/100;
	document.getElementById("totalFinal").value=Math.round((descuentoBOB)*100)/100;
	document.getElementById("descuentoVentaPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	document.getElementById("descuentoVentaUSDPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	aplicarCambioEfectivoUSD();
	minimoEfectivo();
	//totales();
}

function aplicarDescuentoPorcentaje(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVenta").value;
    
    var descuentoPorcentaje=document.getElementById("descuentoVentaPorcentaje").value;
    document.getElementById("descuentoVentaUSDPorcentaje").value=descuentoPorcentaje;

	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round((parseFloat(descuentoPorcentaje)*parseFloat(total)/100)*100)/100;
	console.log("descuento: "+descuento);
	
	document.getElementById("totalFinal").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoUSD=(parseFloat(total)-parseFloat(descuento))/tipo_cambio;
	document.getElementById("descuentoVenta").value=Math.round((descuento)*100)/100;
	document.getElementById("descuentoVentaUSD").value=Math.round((descuento/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((descuentoUSD)*100)/100;
	
	aplicarCambioEfectivo();
	minimoEfectivo();
	//totales();
}
function minimoEfectivo(){
  //obtener el minimo a pagar
	var minimoEfectivo=$("#totalFinal").val();
	var minimoEfectivoUSD=$("#totalFinalUSD").val();
	//asignar el minimo al atributo min
	//$("#efectivoRecibidoUnido").attr("min",minimoEfectivo);
	//$("#efectivoRecibidoUnidoUSD").attr("min",minimoEfectivoUSD);		
}
function aplicarCambioEfectivo(f){
	var recibido=document.getElementById("efectivoRecibido").value;
	var total=document.getElementById("totalFinal").value;
	var cambio=0;

	if(recibido=="" || recibido==0){
		recibido=0;
	}else{
		cambio=Math.round((parseFloat(recibido)-parseFloat(total))*100)/100;
	}
	document.getElementById("cambioEfectivo").value=parseFloat(cambio);
	minimoEfectivo();
}
function aplicarMontoCombinadoEfectivo(f){
  var efectivo=$("#efectivoRecibidoUnido").val();	
  if(efectivo==""){
   efectivo=0;
  }
  var monto_total_bolivianos=parseFloat(efectivo);
  document.getElementById("efectivoRecibido").value=Math.round((monto_total_bolivianos)*100)/100;
  aplicarCambioEfectivo(f);
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
function ver(elem){
	//alert(elem.value);
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

function check(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8||tecla==13) {
        return true;
    }
    // Patron de entrada, en este caso solo acepta numeros y letras
    if($("#tipo_documento").val()!=1){
    	patron = /[A-Za-z0-9-]/;
    }else{
    	patron = /[0-9]/;
    }    
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

$(document).ready(function (){
		mostrarComplemento();
});

function isNumeric(char) {
  return !isNaN(char - parseInt(char));
}

function maskIt(pattern, value) {
  let position = 0;
  let currentChar = 0;
  let masked = '';
  while(position < pattern.length && currentChar < value.length) {
    if(pattern[position] === '0') {
      masked += value[currentChar];
      currentChar++;
    } else {
      masked += pattern[position];
    }
    position++;
  }
  return masked;
}
function numberCharactersPattern(pattern) {
  let numberChars = 0;
  for(let i = 0; i < pattern.length; i++) {
    if(pattern[i] === '0') {
      numberChars ++;
    }
  }
  return numberChars;
}
function applyInputMask(elementId, mask) {
  let inputElement = document.getElementById(elementId);
  let content = '';
  let maxChars = numberCharactersPattern(mask);
  
  inputElement.addEventListener('keydown', function(e) {
    e.preventDefault();
    if (isNumeric(e.key) && content.length < maxChars) {
      content += e.key;
    }
    if(e.keyCode == 8) {
      if(content.length > 0) {
        content = content.substr(0, content.length - 1);
      }
    }
    inputElement.value = maskIt('0000********0000', content);
  })
}

$( document ).ready(function() {
  applyInputMask('nro_tarjeta', '0000********0000');
});

</script>


<?php 
require("estilos_almacenes.inc");

$rpt_territorio=$_COOKIE['global_agencia'];
$rpt_almacen=$_COOKIE['global_almacen'];

$fecha_inicio="01/".date("m/Y");

$fecha_actual=date("Y-m-d");

$fecha_inicio_kardex=obtenerValorConfiguracion($enlaceCon,50);
if($fecha_inicio_kardex==0 || $fecha_inicio_kardex==""){
	$fecha_inicio_kardex=$fecha_inicio;	
}


?>
<script>

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
	document.getElementById('fecha_vencimiento'+numRegistro).innerHTML=fecha_venc_x;

	document.getElementById('stock'+numRegistro).value=parseInt(stockProducto);

	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	if(venta_solo_cajax==1){
		document.getElementById("cantidad_unitaria"+numRegistro).step=cantidad_presentacionx;
		document.getElementById("cantidad_unitaria"+numRegistro).min=cantidad_presentacionx;
		document.getElementById("div_venta_caja"+numRegistro).innerHTML="Venta x Caja";
		console.log('cambiando a venta por caja.'+cantidad_presentacionx);
	}
	document.getElementById("cantidad_unitaria"+numRegistro).focus();

	//Validacion para poner en amarillo cuando el stock es menor a la cantidad de presentacion.
	var tableProductoX=document.getElementById("data"+numRegistro);
	if(stockProducto<=cantidad_presentacionx){
		tableProductoX.style.backgroundColor = "yellow";
	}
	//Validacion en caso de la fecha de Vencimiento poner en rojo cuando son menos de 3 meses
	var numeroMesesControlVencimiento = document.getElementById('nro_meses_control_vencimiento').value;
	console.log("control Vencimiento en meses: "+numeroMesesControlVencimiento);
	var fechaActual = new Date();
	var tempFechaDiv = document.createElement("div");
	tempFechaDiv.innerHTML = fecha_venc_x;
	var fechaObjetivo = tempFechaDiv.textContent || tempFechaDiv.innerText;
	console.log("Fecha Vencimiento X: "+fechaObjetivo);
	var partesFecha = fechaObjetivo.split("/");
	var mesObjetivo = parseInt(partesFecha[0]);
	var anioObjetivo = parseInt(partesFecha[1]);
	var mesesFaltantes = (anioObjetivo - fechaActual.getFullYear()) * 12 + mesObjetivo - (fechaActual.getMonth() + 1);
	console.log("Meses faltantes: " + mesesFaltantes);
	if(numeroMesesControlVencimiento>0 && mesesFaltantes<=numeroMesesControlVencimiento){
		tableProductoX.style.backgroundColor = "red";
	}


	//actStock(numRegistro);
	ajaxPrecioItem(numRegistro);
	totales();

	/*El precio del cliente debe integrarse al ajaxPrecioItem*/
	//precioCliente(numRegistro, cod);
}
/**
 * Verificación de Precio Cliente
 */
function precioCliente(numRegistro, item){
	let cod_cliente  = $('#cliente').val();
	let cod_producto = item;
	let index 		 = numRegistro;
	$('#cantidad_unitaria'+index).val(1);
	if(cod_cliente != null || cod_cliente != '') {
		$.ajax({
			url: "clientePrecioSearch.php",
			type: "POST",
			dataType: 'json',
			data: {
				cod_cliente: cod_cliente,
				cod_producto: cod_producto,
			},
			success:  function (resp) {
				// Detalle de Respuesta de Precio Cliente
				if(resp.status){
					$('#tipoPrecio'+index).val(parseFloat(resp.data.porcentaje_aplicado));
					$('#descuentoProducto'+index).val(resp.data.precio_aplicado);
				}
			}
		});
	}
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
			ajax.open("GET","ajaxMaterialVentas.php?codigo="+num+"&cod_precio="+cod_precio,true);
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

		var cod_cliente  = document.getElementById('cliente').value;


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
			  var index = name.charAt(name.length - 1);
			  console.log("index: "+index);
			  arrayCantidades.push([name,value,index]);
			});
			/*fin recuperar*/
			/*recuperamos los stocks de los otros productos*/
			var inputs = $('form input[name^="stock"]');
			var arrayStocks=[];
			inputs.each(function() {
			  var name = $(this).attr('name');
			  var value = $(this).val();
			  var index = name.charAt(name.length - 1);
			  console.log("index: "+index);
			  arrayStocks.push([name,value,index]);
			});
			/*fin recuperar*/

			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialVentasMultiple.php?codigo="+numFilas+"&productos_multiple="+productosMultiples+"&cod_cliente="+cod_cliente,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material_linea.innerHTML=div_material_linea.innerHTML+ajax.responseText;
				}
				for (x=0;x<arrayCantidades.length;x++) {
					console.log("Iniciando recorrido Matriz");
					var name_set_stock=arrayStocks[x][0];
					var value_set_stock=arrayStocks[x][1];
					var index_set_stock=arrayStocks[x][2];
					document.getElementById(name_set_stock).value=value_set_stock;

					var name_set=arrayCantidades[x][0];
					var value_set=arrayCantidades[x][1];
					var index_set=arrayCantidades[x][2];
					document.getElementById(name_set).value=value_set;
					calculaMontoMaterial(index_set);
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
	totales();
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


</script>
<?php
echo "</head><body onLoad='funcionInicio();'>";
?>
<script>

function leerCookie(nombre) {
	var lista = document.cookie.split(";");
	for (i in lista) {
	   var busca = lista[i].search(nombre);
	   if (busca > -1) {micookie=lista[i]}
	   }
	var igual = micookie.indexOf("=");
	var valor = micookie.substring(igual+1);
	return valor;
}

function validar(f){	
	
	var mensajeGuardar="";
	var cookieSucursal=leerCookie("global_agencia");	
	var formularioSucursal=$("#sucursal_origen").val();
	console.log("cookieSucursal: "+cookieSucursal+" formularioSucursal: "+formularioSucursal);

	document.getElementById('cantidad_material').value=num;

	if(parseInt(cookieSucursal)!=parseInt(formularioSucursal)){
		Swal.fire("NO PUEDE GENERAR LA VENTA!!!.", "Error en sesion de Sucursal!!!!. Cierre la Ventana de Facturacion y vuelva a abrir la misma.", "error");		
		return (false);
	}

	if($("#nitCliente").val()=="0"){
		Swal.fire("Nit!", "Se requiere un número de NIT / CI / CEX válido", "warning");
		return(false);
	}
	if($("#nro_tarjeta").val().length!=16&&$("#nro_tarjeta").val()!=""){
		Swal.fire("Tarjeta!", "El número de Tarjeta debe tener 16 digitos<br><br><b>Ej: 1234********1234</b>", "info");
		return(false);
	}

	/******** Validacion productos vacios ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="materiales"]');
	inputs.each(function() {
  	var value = $(this).val();
  	if(value==0 || value==""){
			banderaValidacionDetalle=1;
  	}else{
  	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Productos!", "Debe seleccionar un producto para cada fila.", "info");
		return(false);
	}
	/******** Fin validacion productos vacios ********/
	
	/******** Validacion Cantidades ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="cantidad_unitaria"]');
	inputs.each(function() {
  	var value = $(this).val();
  	if(value<=0 || value==""){
			banderaValidacionDetalle=1;
  	}else{
  	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Cantidades!", "Hay algún campo con la cantidad vacia o en cero.", "info");
		return(false);
	}
	/******** Fin validacion Cantidades ********/

	/******** Validacion Precios ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="precio_unitario"]');
	inputs.each(function() {
  	var value = $(this).val();
  	if(value<=0 || value==""){
			banderaValidacionDetalle=1;
  	}else{
  	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Precios!", "No pueden existir precios menores o iguales a 0.", "info");
		return(false);
	}
	/******** Fin Precios ********/

	/******** Validacion Montos Productos ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="montoMaterial"]');
	inputs.each(function() {
  	var value = $(this).val();
  	if(value==0 || value==""){
			banderaValidacionDetalle=1;
  	}else{
  	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Montos por Producto!", "No puede existir la venta de un producto en 0.", "info");
		return(false);
	}
	/******** Fin Montos Productos ********/


	/**************************************************/
	/************ Validacion de Stocks ****************/
	/**************************************************/
	var banderaValidacionStock=document.getElementById("bandera_validacion_stock").value;
	console.log("bandera stocks valid: "+banderaValidacionStock);
	banderaValidacionDetalle=0;
	var inputs_stocks = $('form input[name^="stock"]');
	var inputs_cantidades = $('form input[name^="cantidad_unitaria"]');
	for (var i = 0; i < inputs_stocks.length; i++) {
  	var cantidadFila = parseFloat(inputs_cantidades[i].value);
  	var stockFila = parseFloat(inputs_stocks[i].value);
  	if(banderaValidacionStock==1){
  		if(cantidadFila>stockFila){
  			banderaValidacionDetalle=1;
  		}
  	}else{ 				//para todos los otros casos de stocks
  		if(cantidadFila>stockFila){
  			banderaValidacionDetalle=2;
  		}
  	}
  	console.log("cantidadStock: "+stockFila);
  	console.log( "cantidadFila: "+cantidadFila);
	}
	console.log("validacionDetalleStocks:"+banderaValidacionDetalle);
	if(banderaValidacionDetalle==1){
		Swal.fire("Stocks!", "NO puede sacar cantidades mayores al stock.", "error");
		return(false);
	}
	if(banderaValidacionDetalle==2){
		mensajeGuardar="La venta provocara NEGATIVO en el STOCK!";				
	}
	/**************************************************/
	/************ Fin Validacion de Stocks ************/
	/**************************************************/

  /**************** Validar Efectivo y Total Final ****************/
  var banderaValidacionEfectivoRecibido=document.getElementById("bandera_efectivo_recibido").value;
  var efectivoRecibido = parseFloat($("#efectivoRecibido").val());
  var totalFinalVenta = parseFloat($("#totalFinal").val());
  if (isNaN(efectivoRecibido)) { efectivoRecibido = 0; }
  if (isNaN(totalFinalVenta)) { totalFinalVenta = 0; }
  console.log("efectivo: "+efectivoRecibido);
  if(banderaValidacionEfectivoRecibido==1){
	  if(efectivoRecibido < totalFinalVenta){
	    Swal.fire("Error en Monto Recibido en Efectivo!", "<b>El monto en efectivo NO puede ser menor al monto total.</b>", "error");
			return (false);
	  }  	
  }


	if(totalFinalVenta<=0){
		Swal.fire("Monto Final!", "El Monto Final del documento no puede ser 0", "info");
		return(false);
	}
  /**************** Fin Validar Efectivo y Total Final ****************/

	var banderaMsgGuardar=document.getElementById("bandera_msg_guardar").value;
	if(banderaMsgGuardar==1){
			Swal.fire({
				title: '¿Esta seguro de Realizar la Venta?',
				text: mensajeGuardar,
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Si, Vender/Facturar!'
				}).then((result) => {
						console.log(result);
						console.log("resultado: "+result.value);
				    if (result.value) {
				      console.log("Enviando");
				      document.getElementById("btsubmit").value = "Enviando...";
							document.getElementById("btsubmit").disabled = true;   
							document.forms[0].submit();
				    }if(result.dismiss){
								console.log("Cancelando....");
								return false;
				    }
			});		
	}else{
			document.getElementById("btsubmit").value = "Enviando...";
			document.getElementById("btsubmit").disabled = true;   
			document.forms[0].submit();				
	}


  return false;
}

function validarCotizacion(f){		
	var cookieSucursal=leerCookie("global_agencia");	
	var formularioSucursal=$("#sucursal_origen").val();
	console.log("cookieSucursal: "+cookieSucursal+" formularioSucursal: "+formularioSucursal);

	if(parseInt(cookieSucursal)!=parseInt(formularioSucursal)){
		Swal.fire("NO PUEDE GUARDAR LA COTIZACION!!!.", "Error en sesion de Sucursal!!!!. Cierre la Ventana de Facturacion y vuelva a abrir la misma.", "error");		
		return (false);
	}

	if($("#nitCliente").val()=="0"){
		// errores++;
		Swal.fire("Nit!", "Se requiere un número de NIT / CI / CEX válido", "warning");
		// $("#pedido_realizado").val(0);		
		return(false);
	}

	if($("#totalFinal").val()<=0){
		Swal.fire("Monto Final!", "El Monto Final del documento no puede ser 0", "info");
		return(false);
	}
	f.cantidad_material.value=num;
	var cantidadItems=num;
	console.log("numero de items: "+cantidadItems);
	console.log("MontoFinal: "+totalFinal);

	if(cantidadItems>0){
		var item="";
		var cantidad="";
		var stock="";
		var descuento="";
		var monto_item="";
		for(var i=1; i<=cantidadItems; i++){
			console.log("valor i: "+i);
			console.log("objeto materiales: "+document.getElementById("materiales"+i));
			if(document.getElementById("materiales"+i)!=null){
				item=parseFloat(document.getElementById("materiales"+i).value);
				cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
				monto_item=parseFloat(document.getElementById("montoMaterial"+i).value);
				
				descuento=parseFloat(document.getElementById("descuentoProducto"+i).value);
				precioUnit=parseFloat(document.getElementById("precio_unitario"+i).value);				
				var costoUnit=parseFloat(document.getElementById("costoUnit"+i).value);
		
				console.log("materiales"+i+" valor: "+item);
				console.log("stock: "+stock+" cantidad: "+cantidad+ "precio: "+precioUnit);

				if(item==0){
					alert("Debe escoger un item en la fila "+i);
					return(false);
				}

				if( (cantidad<=0 || precioUnit<=0) || (Number.isNaN(cantidad)) || Number.isNaN(precioUnit) || (Number.isNaN(monto_item)) || monto_item<=0 ){
					alert("La cantidad, Precio y Monto por Item no pueden estar vacios o ser <= 0.");
					return(false);
				}
			}
		}
	}else{
		alert("La cotización debe tener al menos 1 item.");
		return(false);
	}
	f.action="guardarCotizacion.php";
	f.submit();
}

function mostrarComplemento(){
	var tipo=$("#tipo_documento").val();
	//$("#nitCliente").attr("type","number");
	if(tipo==1){
		//if($("#nitCliente").val()!=""){
			//$("#nitCliente").val($("#nitCliente").val().replace(/ [A-Za-z-] + / g, ''));
		//}		
		$("#complemento").attr("type","text");
		$("#nitCliente").attr("placeholder","INGRESE EL CARNET");
	}else{
		$("#complemento").attr("type","hidden");
		if(tipo==5){
			$("#nitCliente").attr("placeholder","INGRESE EL NIT");	
		}else{
			$("#nitCliente").attr("placeholder","INGRESE EL DOCUMENTO");
		}
		
	}
}
$(document).ready(function (){
		mostrarComplemento();
});

function mostrarListadoNits(){
	var rs=$("#razonSocial").val();
	//$("#nitCliente").val("");
	var parametros={"rs":rs};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "listaRazonActual.php",
        data: parametros,
        success:  function (resp) { 
        	var re=resp.split("#####");
           $("#lista_nits").html(re[0]);  
           if(parseInt(re[1])==1){
              asignarNit(re[2]);
           }else{
             $("#modalAsignarNit").modal("show");                  	   	
           }                 
        }
    });	
}
function asignarNit(nit){
   $("#nitCliente").val(nit); 
   $("#modalAsignarNit").modal("hide");      
}

function adicionarCliente() {	
    var nomcli = $("#nomcli").val();
    var apcli = $("#apcli").val();
    var ci = $("#ci").val();
    var nit = $("#nit").val();
    var dir = $("#dir").val();
    var tel1 = $("#tel1").val();
    var mail = $("#mail").val();
    var area = $("#area").val();
    var fact = $("#fact").val();
    var edad = $("#edad").val();
    var genero = $("#genero").val();
	var tipoPrecio = $("#tipoPrecio").val();	

  if(nomcli==""||nit==""|| (mail==""&&tel1=="")){
    Swal.fire("Informativo!", "Debe llenar los campos obligatorios", "warning");
  }else{
	  
  	if(validarCorreoUnicoCliente(0,nit,mail)==0){
  			Swal.fire("Error!", "El cliente con correo: "+mail+", ya se encuentra registrado!", "error");
  	}else{
				
		    var parametros={"nomcli":nomcli,"nit":nit,"ci":ci,"dir":dir,"tel1":tel1,"mail":mail,"area":area,"fact":fact,"edad":edad,"apcli":apcli,"tipoPrecio":tipoPrecio,"genero":genero,"dv":1};
		    $.ajax({
		        type: "GET",
		        dataType: 'html',
		        url: "programas/clientes/prgClienteAdicionar.php",
		        data: parametros,
		        success:  function (resp) {      						
		           var r=resp.split("#####");	

		           console.log("response:"+r);
		           console.log("response[]:"+r[1]);

		           if(parseInt(r[1])>0){           	
		           	  
		           	  refrescarComboCliente(r[1]);   
		           	  
		           	  $("#nomcli").val("");
							    $("#apcli").val("");
							    $("#ci").val("");
							    $("#nit").val("");
							    $("#dir").val("");
							    $("#tel1").val("");
							    $("#mail").val("");
							    $("#area").val("");
							    $("#fact").val("");
							    // $("#edad").val(0);
							    // $("#genero").val(0);

		           }else{		           	
		           	  $("#modalNuevoCliente").modal("hide"); 
		           	  Swal.fire("Error!", "Error al crear cliente", "error");
		           }            
		                            	   
		        }
		    });	
  	}
  }
}
function validarCorreoUnicoCliente(cliente,nit,correo){
	var dato=0;
	var parametros={"cliente":cliente,"nit":nit,"correo":correo};
    $.ajax({
        type: "GET",
        dataType: 'html',
        url: "validarCorreoUnicoCliente.php",
        data: parametros,
        async:false,
        success:  function (resp) {
          dato=resp;     
        }
    });
    return dato;
}
function registrarNuevoCliente(){
	
	$("#nomcli").val("");
  $("#apcli").val("");
  $("#ci").val("");
  $("#nit").val("");
  $("#dir").val("");
  $("#tel1").val("");
  $("#mail").val("");
  $("#fact").val("");
	if($("#nitCliente").val()!=""){
		$("#nit").val($("#nitCliente").val());
		//$("#nomcli").val($("#razonSocial").val());
		$("#fact").val($("#razonSocial").val());		
		$("#boton_guardado_cliente").attr("onclick","adicionarCliente()");		
		$("#titulo_cliente").html("NUEVO CLIENTE");
		$("#modalNuevoCliente").modal("show");
	}else{
		alert("Ingrese el NIT para registrar el cliente!");
	}	
}
/**
 * Se obtiene la lista de documentos del cliente
 */
function obtenerListaDocumentosCliente(){
	let cod_cliente = $('#cliente').val();
	if(cod_cliente > 0){
		let url = "clienteDocumento.php?cod_cliente=" + cod_cliente;
		window.open(url, "_blank");
	}else{
		alert('No se seleccionó cliente')
	}
}
function refrescarComboCliente(cliente){
	
	var parametros={"cliente":cliente,"nit":$("#nitCliente").val()};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "listaClientesActual.php",
        data: parametros,
        success:  function (resp) {
          Swal.fire("Correcto!", "Se guardó el cliente con éxito", "success");   
           $("#cliente").html(resp); 		   
           ajaxRazonSocialCliente(document.getElementById('form1'));
           $("#cliente").selectpicker("refresh");       
           $("#modalNuevoCliente").modal("hide");                  	   
        }
    });	
}
function editarDatosClienteRegistro(){
	if($("#cliente").val()!=146){
		var parametros={"cliente":$("#cliente").val()};
		$.ajax({
	        type: "GET",
	        dataType: 'html',
	        url: "ajaxClienteEncontrar.php",
	        data: parametros,
	        success:  function (resp) { 
	        	// alert(resp)
	        	var r=resp.split("#####");
            $("#nomcli").val(r[1]);
				    $("#apcli").val(r[2]);
				    $("#ci").val(r[3]);
				    $("#nit").val(r[4]);
				    $("#dir").val(r[5]);
				    $("#tel1").val(r[6]);
				    $("#mail").val(r[7]);
				    $("#area").val(r[8]);
				    $("#fact").val(r[9]);
				    $("#edad").val(r[10]);
				    $("#genero").val(r[11]);       
	        	$("#boton_guardado_cliente").attr("onclick","editarDatosCliente()");
						$("#titulo_cliente").html("EDITAR CLIENTE");
						$("#edad").selectpicker("refresh");
				    $("#genero").selectpicker("refresh");   
						$("#modalNuevoCliente").modal("show");   	   
	        }
	    });
		
	}else{
		alert("Seleccione un cliente para editar");
	}	
}
function editarDatosCliente() {
	var cod_cliente = $("#cliente").val();
    var nomcli = $("#nomcli").val();
    var apcli = $("#apcli").val();
    var ci = $("#ci").val();
    var nit = $("#nit").val();
    var dir = $("#dir").val();
    var tel1 = $("#tel1").val();
    var mail = $("#mail").val();
    var area = $("#area").val();
    var fact = $("#fact").val();
    var edad = $("#edad").val();
    var genero = $("#genero").val();

  if(nomcli==""||nit==""||mail==""||tel1==""){
    Swal.fire("Informativo!", "Debe llenar los campos obligatorios", "warning");
  }else{
  	if(validarCorreoUnicoCliente(cod_cliente,nit,mail)==0){
  			Swal.fire("Error!", "El cliente con correo: "+mail+" y nit: "+nit+" ya se encuentra registrado!", "error");
  	}else{
		    var parametros={"nomcli":nomcli,"nit":nit,"ci":ci,"dir":dir,"tel1":tel1,"mail":mail,"area":area,"fact":fact,"edad":edad,"apcli":apcli,"genero":genero,"dv":1,"cod_cliente":cod_cliente};
		    $.ajax({
		        type: "GET",
		        dataType: 'html',
		        url: "programas/clientes/prgClienteEditar.php",
		        data: parametros,
		        success:  function (resp) { 
		           var r=resp.split("#####");
		           if(parseInt(r[1])>0){
		           		//alert(r[1]);
		           	  refrescarComboCliente(r[1]);   
		           	  $("#nomcli").val("");
							    $("#apcli").val("");
							    $("#ci").val("");
							    $("#nit").val("");
							    $("#dir").val("");
							    $("#tel1").val("");
							    $("#mail").val("");
							    $("#area").val("");
							    $("#fact").val("");
							    // $("#edad").val("");
							    // $("#genero").val("");

		           }else{
		           	  $("#modalNuevoCliente").modal("hide"); 
		           	  Swal.fire("Error!", "Error al editar cliente", "error");
		           }            
		                            	   
		        }
		    });	
  	}
  }
}
function cambiarNotaRemision(){
	if($("#boton_nota_remision").length>0){
		var tipo=$("#tipoDoc").val();
		if(tipo==2){
			$("#tipoDoc").val(1);
			$("#boton_nota_remision").addClass("boton-plomo");
			if($("#boton_nota_remision").hasClass("boton-plomo-osc")){
              $("#boton_nota_remision").removeClass("boton-plomo-osc");  
			}
		}else{
			$("#tipoDoc").val(2);
			$("#boton_nota_remision").addClass("boton-plomo-osc");
			if($("#boton_nota_remision").hasClass("boton-plomo")){
              $("#boton_nota_remision").removeClass("boton-plomo");  
			}
		}
		$("#nitCliente").val(123);
		$("#razonSocial").val("Sin Nombre");
		ajaxNroDoc(form1);
	}
}

function buscarKardexProducto(f, numMaterial){
	window.open('rpt_inv_kardex.php?rpt_territorio=<?=$rpt_territorio?>&rpt_almacen=<?=$rpt_almacen?>&fecha_ini=<?=$fecha_inicio_kardex?>&fecha_fin=<?=$fecha_actual?>&tipo_item=2&rpt_item='+$("#materiales"+numMaterial).val()+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');		
}

var tablaBuscadorSucursales=null;
function encontrarMaterial(numMaterial){
	var cod_material = $("#materiales"+numMaterial).val();
	var parametros={"cod_material":cod_material};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajax_encontrar_productos.php",
        data: parametros,
        success:  function (resp) { 
           // alert(resp);           
        	$("#modalProductosCercanos").modal("show");
        	$("#tabla_datosE").html(resp);   
        }
    });	
}


</script>

		
</head><body onLoad="funcionInicio();">
<?php

$cadComboGenero="";
$consult="select t.`cod_genero`, t.`descripcion` from `generos` t where cod_estadoreferencial=1";

$rs1=mysqli_query($enlaceCon,$consult);
while($reg1=mysqli_fetch_array($rs1)){
		$codTipo = $reg1["cod_genero"];
    $nomTipo = $reg1["descripcion"];
    $cadComboGenero=$cadComboGenero."<option value='$codTipo'>$nomTipo</option>";
}
  
  $cadComboEdad = "";
$consultaEdad="SELECT c.codigo,c.nombre, c.abreviatura FROM tipos_edades AS c WHERE c.estado = 1 ORDER BY 1";
$rs=mysqli_query($enlaceCon,$consultaEdad);
while($reg=mysqli_fetch_array($rs))
   {$codigoEdad = $reg["codigo"];
    $nomEdad = $reg["abreviatura"];
    $desEdad = $reg["nombre"];
    $cadComboEdad=$cadComboEdad."<option value='$codigoEdad'>$nomEdad ($desEdad)</option>";
   }


if(!isset($fecha)||$fecha==""){   
	$fecha=date("Y-m-d");
}

	$sqlCambioUsd="select valor from cotizaciondolar order by 1 desc limit 1";
	$respUsd=mysqli_query($enlaceCon,$sqlCambioUsd);
	$tipoCambio=1;
	while($filaUSD=mysqli_fetch_array($respUsd)){
		$tipoCambio=$filaUSD[0];	
	}
?><input type="hidden" id="tipo_cambio_dolar" value="<?=$tipoCambio?>"><?php
$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];

//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$tipoDocDefault=$datConf[0];
//$tipoDocDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$clienteDefault=$datConf[0];
//$clienteDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$facturacionActivada=$datConf[0];
//$facturacionActivada=mysql_result($respConf,0,0);


//SACAMOS LA CONFIGURACION PARA SABER SI MOSTRAR MENSAJES DE BIENVENIDA PANTALLA PRINCIPAL
$banderaMensajesDoblePantalla=0;
$mensajeBienvenida="";
$mensajeVerifiquePrecios="";

$banderaMensajesDoblePantalla=obtenerValorConfiguracion($enlaceCon,14);
$mensajeBienvenida=obtenerValorConfiguracion($enlaceCon,15);
$mensajeVerifiquePrecios=obtenerValorConfiguracion($enlaceCon,16);

/*Visualizacion de precios descuento y mayorista*/
$banderaPreciosDescuento=obtenerValorConfiguracion($enlaceCon,52);
$porcentajePrecioMayorista=precioMayoristaSucursal($enlaceCon,$globalAgencia);
$txtPrecioMayorista="";
if($banderaPreciosDescuento==1){
	$txtPrecioMayorista="[Precio Mayorista:".$porcentajePrecioMayorista."%]";
}

require_once 'datosUsuario.php';

if(isset($_GET['file'])){
	unlink($_GET['file']);
}

?>
<nav class="mb-4 navbar navbar-expand-lg" style='background:#006db3 !important;color:white !important;'>
                <a class="navbar-brand font-bold" href="#">[<?php echo $fechaSistemaSesion?>][<b id="hora_sistema"><?php echo $horaSistemaSesion;?></b>] [<?php echo $nombreAlmacenSesion;?>]</a>
                <span style='background:#FFDA33 !important;color:yellow;font-size:20px; !important;'><?=$txtPrecioMayorista;?></span>
                
                <?php
								if($banderaMensajesDoblePantalla==1){
                ?>
                <div id="siat_error" style="color:#FFDA33;padding:0px;border-radius:5px;font-weight:bold;margin-left:100px;height:10px;font-size:50px;"><?=$mensajeBienvenida;?></div>
                <?php
              	}
                ?>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class="fa fa-user"></i> <?php echo $nombreUsuarioSesion?> <span class="sr-only">(current)</span></a>
                        </li>                        
                      
                        <li class="nav-item">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                    </ul>
                </div>
            </nav>

<form action='guardarSalidaMaterial.php' method='POST' name='form1' id="guardarSalidaVenta" onsubmit="return validar(this)">

	<input type="hidden" id="siat_error_valor" name="siat_error_valor">
	<input type="hidden" id="confirmacion_guardado" value="0">
	<input type="hidden" id="tipo_cambio_dolar" name="tipo_cambio_dolar"value="<?=$tipoCambio?>">
	<input type="hidden" id="global_almacen" value="<?=$globalAlmacen?>">

	<input type="hidden" id="almacen_origen" name="almacen_origen" value="<?=$globalAlmacen?>">
	<input type="hidden" id="sucursal_origen" name="sucursal_origen" value="<?=$globalAgencia?>">

	<input type="hidden" id="validacion_clientes" name="validacion_clientes" value="<?=obtenerValorConfiguracion($enlaceCon,11)?>">
	<input type="hidden" id="bandera_validacion_stock" name="bandera_validacion_stock" value="<?=obtenerValorConfiguracion($enlaceCon,4)?>">
	<input type="hidden" id="bandera_efectivo_recibido" name="bandera_efectivo_recibido" value="<?=obtenerValorConfiguracion($enlaceCon,21)?>">
	<input type="hidden" id="bandera_msg_guardar" name="bandera_msg_guardar" value="<?=obtenerValorConfiguracion($enlaceCon,23)?>">
	<input type="hidden" id="nro_meses_control_vencimiento" name="nro_meses_control_vencimiento" value="<?=obtenerValorConfiguracion($enlaceCon,28)?>">


<table class='' width='100%' style='width:100%;margin-top:-24px !important;'>
<tr class="bg-info text-white" align='center' id='venta_detalle' style="color:#fff;background:#006db3 !important; font-size: 13px;">
<?php

if($tipoDocDefault==2){
	$razonSocialDefault="SN";
	$nitDefault="123";
}else{
	$razonSocialDefault="";
	$nitDefault="";
}


?>
<th>Tipo de Doc</th>
<th>Nro.Doc</th>
<th>Fecha</th>
<th class='d-none'>Precio</th>
<th>Tipo Pago</th>
<th width="20%">NIT/CI/CEX</th>
<th colspan="2" width="25%">Nombre/RazonSocial</th>
<th colspan='2' width="15%">Cliente</th>
</tr>
<tr>
<input type="hidden" name="tipoSalida" id="tipoSalida" value="1001">
<td>
	<?php
		
		if($facturacionActivada==1){
			$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (1,2) order by 2 desc";
		}else{
			$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (2) order by 2 desc";
		}
		$resp=mysqli_query($enlaceCon,$sql);

		echo "<select class='selectpicker form-control' data-style='btn-info' name='tipoDoc' id='tipoDoc' onChange='ajaxNroDoc(form1)' required>";
		echo "<option value=''>-</option>";
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			if($codigo==$tipoDocDefault){
				echo "<option value='$codigo' selected>$nombre</option>";
			}else{
				echo "<option value='$codigo'>$nombre</option>";
			}
		}
		echo "</select>";
		?>
</td>
<td align='center'>
	<div id='divNroDoc'>
		<?php

		if($tipoDocDefault==1){
			$vectorNroCorrelativo=numeroCorrelativoCUFD($enlaceCon,$tipoDocDefault);
		}else{
			$vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$tipoDocDefault);
		}
		$nroCorrelativo=$vectorNroCorrelativo[0];
		$banderaErrorFacturacion=$vectorNroCorrelativo[1];

		echo "<span class='textogranderojo'>$nroCorrelativo</span>";
	  if($nroCorrelativo=="CUFD INCORRECTO / VENCIDO" && $tipoDocDefault==1){
			?><script>$(document).ready(function (){
				$("#dosificar_factura_sucursal").removeClass("d-none");
			})</script><?php
	  }
		?>	
	</div>
</td>

<td align='center'>
	<input type='date' class='texto' value='<?php echo $fecha;?>' id='fecha' size='10' name='fecha' readonly>
</td>

<td class='d-none' align='center'>
	&nbsp;
</td>

<td>
	<div id='divTipoVenta'>
		<?php
			$sql1="select cod_tipopago, nombre_tipopago from tipos_pago order by 1";
			$resp1=mysqli_query($enlaceCon,$sql1);
			echo "<select name='tipoVenta' class='selectpicker form-control' id='tipoVenta' data-style='btn-info'>";
			while($dat=mysqli_fetch_array($resp1)){
				$codigo=$dat[0];
				$nombre=$dat[1];
				echo "<option value='$codigo'>$nombre</option>";
			}
			echo "</select>";
			?>

	</div>
</td>
<td>
		<div class="row">
			<div class="col-sm-3" style="padding-right: 5px;">
		<select name='tipo_documento' class='selectpicker form-control' data-live-search="true" id='tipo_documento' onChange='mostrarComplemento(form1);' required data-style="btn btn-rose">
<?php
$sql2="SELECT codigoClasificador,descripcion FROM siat_sincronizarparametricatipodocumentoidentidad;";
$resp2=mysqli_query($enlaceCon,$sql2);

while($dat2=mysqli_fetch_array($resp2)){
   $codCliente=$dat2[0];
	$nombreCliente=$dat2[1]." ".$dat2[2];
?><option value='<?php echo $codCliente?>'><?php echo $nombreCliente?></option><?php
}
?>
	</select>
	</div>
		<div id='divNIT' class="col-sm-9" style="padding: 0;">
			<input type='text' value='<?php echo $nitDefault; ?>' name='nitCliente' id='nitCliente'  onChange='ajaxRazonSocial(this.form);' onkeypress="return check(event)" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" required placeholder="INGRESE EL NIT" autocomplete="off">
		</div>
		<!-- style="font-size: 20px;color:#9D09BB"-->		
	</div>
	<input type='hidden' name='complemento' id='complemento' value='' class="form-control" placeholder="COMPLEMENTO" style="text-transform:uppercase;position:absolute;width:160px !important;background:#D2FFE8;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
	</td>	
	<td colspan="2">
		<div id='divRazonSocial'>
          <input type='text' name='razonSocial' id='razonSocial' value='<?php echo $razonSocialDefault; ?>' class="form-control" required placeholder="Ingrese la razon social" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  onchange='ajaxNitCliente(this.form);' pattern='[A-Za-z0-9Ññ.& ]+'>          
        </div>
        <span class="input-group-btn" style="position:absolute;width:10px !important;">
            <a href="#" onclick="ajaxVerificarNitCliente(); return false;" class="btn btn-info btn-sm" style="position:absolute;right: 100%;"><i class="material-icons">refresh</i> Verificar Nit</a>
            <a href="#" class="btn btn-primary btn-sm" onclick="mostrarListadoNits();return false;"><span class="material-icons">person_search</span> Encontrar NIT
            </a>
        </span>
           <span class="input-group-btn" style="position:absolute;left:5px !important;">
            
          </span>
		  
	</td>
	<td align='center' id='divCliente' width="20%">	
		<!--span class="input-group-btn" style="position:absolute;width:100px !important;"-->		
			<select name='cliente' class='selectpicker form-control' data-live-search="true" id='cliente' onChange='ajaxRazonSocialCliente(this.form);' required data-style="btn btn-rose">
				<option value='146'>NO REGISTRADO</option>
			</select>
			<input type='text' name='observaciones' id='observaciones' value='' class="form-control" placeholder="Observaciones" style="text-transform:uppercase;position:absolute;width:300px !important;">
		<!--/span-->
	</td>
	<td>	
		<a href="#" title="Editar Cliente" data-toggle='tooltip' onclick="editarDatosClienteRegistro(); return false;" class="btn btn-primary btn-round btn-sm text-white btn-fab"><i class="material-icons">edit</i></a>
		<a href="#" title="Registrar Nuevo Cliente" data-toggle='tooltip' onclick="registrarNuevoCliente(); return false;" class="btn btn-success btn-round btn-sm text-white circle" id="button_nuevo_cliente">+</a>
		<!-- Lista de Documentos -->
		<a href="#" title="Documentos adjuntos del Cliente" data-toggle='tooltip' 
			onclick="obtenerListaDocumentosCliente();" 
			class="btn btn-info btn-round btn-sm text-white btn-fab">
			<i class="material-icons">insert_drive_file</i></a>
	</td>



</tr>


<tr class="bg-info text-white" align='center' id='venta_detalle' style="color:#fff;background:#aa00ff !important; font-size: 16px;">


	<th>Vendedor</th>
	<!--th>Tipo Precio</th-->

</tr>
<tr>
	<td>
		<select name='cod_vendedor' class='selectpicker form-control' id='cod_vendedor' data-style="btn btn-rose" required>
			<option value=''>----</option>
			<?php
			$sql2="select f.`codigo_funcionario`,
				concat(f.`paterno`,' ', f.`nombres`) as nombre from `funcionarios` f, funcionarios_agencias fa 
				where fa.codigo_funcionario=f.codigo_funcionario and fa.`cod_ciudad`='$globalAgencia' and estado=1 order by 2";
			$resp2=mysqli_query($enlaceCon,$sql2);

			while($dat2=mysqli_fetch_array($resp2)){
				$codVendedor=$dat2[0];
				$nombreVendedor=$dat2[1];
			?>		
			<option value='<?php echo $codVendedor?>' <?=($codVendedor==$global_usuario)?"selected":"";?> ><?php echo $nombreVendedor?></option>
			<?php    
			}
			?>
		</select>
	</td>
<!--td>
	<div id='divTipoPrecio'>
		<?php
			//$sql1="select codigo, nombre, abreviatura from tipos_precio where estado=1 order by 3";
			//$resp1=mysqli_query($enlaceCon,$sql1);
			//echo "<select name='tipoPrecio' class='texto' id='tipoPrecio'>";
			//while($dat=mysqli_fetch_array($resp1)){
				//$codigo=$dat[0];
				//$nombre=$dat[1];
				//$abreviatura=$dat[2];
				//echo "<option value='$codigo'>$nombre ($abreviatura %)</option>";
			//}
			//echo "</select>";
			
		?>

	</div>
</td>
	<!--th align='center' colspan="3">
		<input type='text' class='texto' name='observaciones' value='' size='40' rows="3">
	</th-->
</tr>

</table>

<?php
if($banderaMensajesDoblePantalla==1){
?>
<div id="siat_error2" style="color:orangered;padding:10px;border-radius:5px;font-weight:bold;margin-left:400px;height:30px;font-size:30px;width:1000px;"><?=$mensajeVerifiquePrecios;?></div>
<?php
}
?>

<br>
<input type="hidden" id="ventas_codigo"><!--para validar la funcion mas desde ventas-->

<div class="codigo-barras div-center">
	<input class="boton" type="button" value="Add Item(+)" onclick="mas(this)" accesskey="a"/>&nbsp;&nbsp;&nbsp;
  <input type="text" class="form-codigo-barras" id="input_codigo_barras" placeholder="Ingrese el código de barras." autofocus autocomplete="off">
</div>

<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="100%" id="data0" border="0">
	<!--tr>
		<td align="center" colspan="9">
			<b>Detalle de la Venta</b>
		</td>
	</tr-->

	<tr align="center">
		<td width="10%">&nbsp;</td>
		<td width="33%">Material</td>
		<td width="5%">FV</td>
		<td width="8%">Stock</td>
		<td width="8%">Cantidad</td>
		<td width="8%">Precio </td>
		<td width="15%">Desc.</td>
		<td width="8%">Monto</td>
		<td width="5%">&nbsp;</td>
	</tr>
	</table>
</fieldset>

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
			<tr>
				<td colspan="3"><input type="button" class="boton2peque" onclick="javascript:masMultiple(this.form);" value="Incluir Productos Seleccionados">
				</td>
			</tr>
		</table>		
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>
<div style="height:200px;"></div>


<?php

//SACAMOS EL PORCENTAJE PARA LOS DESCUENTOS TOTALES DIAS ESPECIALES
$confDescuentoHabilitado=0;
$confDescuentoHabilitado=obtenerValorConfiguracion($enlaceCon,51);
$descuentoTotalOferta=0;

if($confDescuentoHabilitado==1){
	$diaSemana=date("w"); //0 domingo 6 sabado
	if($globalAgencia==1 && $diaSemana==2){
		$descuentoTotalOferta=7;
	}
	if($globalAgencia==2 && $diaSemana==6){
		$descuentoTotalOferta=7;
	}	
}


?>

<div class="pie-div">
	<div class='float-right' style="padding-right:15px;"><a href='#' class='boton-plomo' style="width:10px !important;height:10px !important;font-size:10px !important;" id="boton_nota_remision" onclick="cambiarNotaRemision()">NR</a></div>
	<table class="pie-montos">
      <tr>
        <td>
	<table id='' width='100%' border="0">
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"></td><td align='center' colspan="2"><b style="font-size:20px;color:#0691CD;">Bs.</b></td>
		</tr>

		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Nota</td><td><input type='number' name='totalVenta' id='totalVenta' readonly style="background:#B0B4B3;width:120px;"></td>
			<td align='center' width='90%' style="color:#777B77;font-size:12px;"><b style="font-size:12px;color:#0691CD;">Efectivo Recibido</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento</td><td><input type='number' name='descuentoVenta' id='descuentoVenta' onChange='aplicarDescuento(form1);' style="height:20px;font-size:19px;width:120px;color:red;" onkeyup='aplicarDescuento(form1);' onkeydown='aplicarDescuento(form1);' value="0" step='0.01' required></td>
			<td><input type='number' style="background:#B0B4B3; width:120px;" name='efectivoRecibido' id='efectivoRecibido' readonly step="any" onChange='aplicarCambioEfectivo(form1);' onkeyup='aplicarCambioEfectivo(form1);' onkeydown='aplicarCambioEfectivo(form1);'></td>		
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento %</td><td><input type='number' name='descuentoVentaPorcentaje' id='descuentoVentaPorcentaje' style="height:20px;font-size:19px;width:120px;color:red;" onChange='aplicarDescuentoPorcentaje(form1);' onkeyup='aplicarDescuentoPorcentaje(form1);' onkeydown='aplicarDescuentoPorcentaje(form1);' value="<?=$descuentoTotalOferta;?>" step='0.01'></td>
			<td align='center' width='90%' style="color:#777B77;font-size:12px;"><b style="font-size:12px;color:#0691CD;">Cambio</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;font-size:12px;color:red;">Monto Final</td><td><input type='number' name='totalFinal' id='totalFinal' readonly style="background:#0691CD;height:20px;font-size:19px;width:120px;;color:#fff;"></td>
			<td><input type='number' name='cambioEfectivo' id='cambioEfectivo' readonly style="background:#7BCDF0;height:20px;font-size:18px;width:120px;"></td>
		</tr>
	</table>
      
        </td>
        <td>
	<table id='' width='100%' border="0" class="d-none">
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"></td><td align='center' colspan="2"><b style="font-size:20px;color:#189B22;"><p class="d-none">$ USD</p></b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Nota</td>
			<td><input type='number' name='totalVentaUSD' id='totalVentaUSD' readonly style="background:#B0B4B3; width:120px;"></td>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"><b style="font-size:12px;color:#189B22;">Efectivo Recibido</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento</td>
			<td><input type='number' name='descuentoVentaUSD' id='descuentoVentaUSD' style="height:20px;font-size:19px;width:120px;color:red;" onChange='aplicarDescuentoUSD(form1);' onkeyup='aplicarDescuentoUSD(form1);' onkeydown='aplicarDescuentoUSD(form1);' value="0" step='0.01' required></td>
			<td><input type='number' name='efectivoRecibidoUSD' id='efectivoRecibidoUSD' style="background:#B0B4B3; width:120px;" step="any" readonly onChange='aplicarCambioEfectivoUSD(form1);' onkeyup='aplicarCambioEfectivoUSD(form1);' onkeydown='aplicarCambioEfectivoUSD(form1);'></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento %</td>
			<td><input type='number' name='descuentoVentaUSDPorcentaje' id='descuentoVentaUSDPorcentaje' style="height:20px;font-size:19px;width:120px;color:red;" onChange='aplicarDescuentoUSDPorcentaje(form1);' onkeyup='aplicarDescuentoUSDPorcentaje(form1);' onkeydown='aplicarDescuentoUSDPorcentaje(form1);' value="0" step='0.01'></td>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"><b style="font-size:12px;color:#189B22;">Cambio</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Monto Final</td>
			<td><input type='number' name='totalFinalUSD' id='totalFinalUSD' readonly style="background:#189B22;height:30px;font-size:27px;width:120px;color:#fff;"> </td>
			<td><input type='number' name='cambioEfectivoUSD' id='cambioEfectivoUSD' readonly style="background:#4EC156;height:20px;font-size:19px;width:120px;"></td>
		</tr>
	</table>
        </td>
      </tr>
	</table>


<?php

if($banderaErrorFacturacion==0 || $tipoDocDefault!=1){
	echo "<div class='divBotones2'>
	        <input type='submit' class='boton-verde' value='Guardar Venta' id='btsubmit' name='btsubmit'>
					
					<!--input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresomateriales.php\"';-->
					
					<a href='#' class='btn btn-default btn-sm btn-fab' style='background:#96079D' onclick='mostrarRegistroConTarjeta(); return false;' id='boton_tarjeta' title='AGREGAR TARJETA DE CREDITO' data-toggle='tooltip'><i class='material-icons'>credit_card</i></a>

            <!--h2 style='font-size:11px;color:#9EA09E;'>TIPO DE CAMBIO $ : <b style='color:#189B22;'> ".$tipoCambio." Bs.</b></h2-->
            
            <table style='width:330px;padding:0 !important;margin:0 !important;bottom:25px;position:fixed;left:100px;'>
            <tr>
               <td style='font-size:12px;color:#0691CD; font-weight:bold;'>EFECTIVO Bs.</td>
               <td style='font-size:12px;color:#189B22; font-weight:bold;' class='d-none'>EFECTIVO $ USD</td
            </tr>
             <tr>
               <td><input type='number' name='efectivoRecibidoUnido' onChange='aplicarMontoCombinadoEfectivo(form1);' onkeyup='aplicarMontoCombinadoEfectivo(form1);' onkeydown='aplicarMontoCombinadoEfectivo(form1);' id='efectivoRecibidoUnido' style='height:25px;font-size:18px;width:100%;' value='0' step='any'></td>
               <td class='d-none'><input type='number' name='efectivoRecibidoUnidoUSD' onChange='aplicarMontoCombinadoEfectivo(form1);' onkeyup='aplicarMontoCombinadoEfectivo(form1);' onkeydown='aplicarMontoCombinadoEfectivo(form1);' id='efectivoRecibidoUnidoUSD' style='height:25px;font-size:18px;width:100%;' step='any'></td>
               <td><a href='#' class='btn btn-default btn-sm btn-fab' style='background:#FF0000' onclick='validarCotizacion(form1); return false;' id='boton_cotizacion' title='Guardar como Cotización' data-toggle='tooltip'><i class='material-icons'>file_download</i></a></td>
             </tr>
            </table>

			";
	echo "</div>";	
}else{
	echo "";
}


?>

</div>

<input type='hidden' name='materialActivo' id='materialActivo' value="0">
<input type='hidden' name='cantidad_material' id='cantidad_material' value="0">
<!-- small modal -->
<div class="modal fade modal-primary" id="modalAsignarNit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content card">
               <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">person_search</i>
                  </div>
                  <h4 class="card-title text-rose font-weight-bold">Nit Encontrados</h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                	<table class="table table-sm table-condensed table-bordered"><thead><tr><th class="bg-info">Razón Social</th><th class="bg-info">NIT</th><th class="bg-info">-</th></tr></thead>
                	<tbody id="lista_nits"></tbody></table>   
                	<p>Seleccione el NIT correspondiente</p>                  
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->



<!-- small modal -->
<div class="modal fade modal-primary" id="modalPagoTarjeta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content card">
               <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon" style="background: #96079D;color:#fff;">
                    <i class="material-icons">credit_card</i>
                  </div>
                  <h4 class="card-title text-dark font-weight-bold">Pago con Tarjeta <small id="titulo_tarjeta"></small></h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
<div class="row">
	<div class="col-sm-12">
		         <div class="row d-none">
                  <label class="col-sm-3 col-form-label">Banco</label>
                  <div class="col-sm-9">
                    <div class="form-group">
                      <select class="selectpicker form-control" name="banco_tarjeta" id="banco_tarjeta" data-style="btn btn-success" data-live-search="true">                      	
                          <?php echo "$cadComboBancos"; ?>
                          <option value="0" selected>Otro</option>
                       </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label">Numero <br>Tarjeta</label>
                  <div class="col-sm-9">
                    <div class="form-group">
                      <input class="form-control" type="text" style='height:40px;font-size:25px;width:80%;background:#D7B3D8 !important; float:left; margin-top:4px; color:#4C079A;' id="nro_tarjeta" name="nro_tarjeta" value="" onkeydown="verificarPagoTargeta()" onkeyup="verificarPagoTargeta()" onkeypress="verificarPagoTargeta()" autocomplete="off" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label">Monto <br>Tarjeta</label>
                  <div class="col-sm-9">
                    <div class="form-group">
                      <input class="form-control" type="number" id="monto_tarjeta" name="monto_tarjeta" style='height:40px;font-size:35px;width:80%;background:#A5F9EA !important; float:left; margin-top:4px; color:#057793;' step="any" value=""/>
                    </div>
                  </div>
                </div> 
                <br>
                <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-info btn-sm">GUARDAR</a>               
                <br><br>
       </div>
</div>                  

                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->
</form>

<!-- small modal -->
<div class="modal fade modal-primary" id="modalNuevoCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content card" style="background:#1F2E84 !important;color:#fff;">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">add</i>
                  </div>
                  <h4 class="card-title text-white font-weight-bold" id="titulo_cliente">Nuevo Cliente</h4>
                   <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">                	
                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">Nombre (*)</label>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;text-transform:uppercase;" type="text" id="nomcli" required value="<?php echo "$nomCliente"; ?>" placeholder="Nombre del Cliente" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                  <label class="col-sm-1 col-form-label text-white">Apellidos</label>
                  <div class="col-sm-5">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;text-transform:uppercase;" type="text" id="apcli" value="<?php echo "$apCliente"; ?>" required placeholder="Apellido(s) del Cliente" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                </div>
                <div class="row">                  
                  <label class="col-sm-2 col-form-label text-white">Teléfono (*)</label>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="tel1" value="<?php echo "$telefono1"; ?>" required placeholder="Telefono/Celular"/>
                    </div>
                  </div>
                  <label class="col-sm-1 col-form-label text-white">Email (*)</label>
                  <div class="col-sm-5">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="email" id="mail" value="<?php echo "$email"; ?>" required placeholder="cliente@correo.com"/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">CI</label>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="ci" value="<?php echo "$ciCliente"; ?>"required/>
                    </div>
                  </div>
                  <label class="col-sm-1 col-form-label text-white">NIT(*)</label>
                  <div class="col-sm-5">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="nit" value="<?php echo "$nitCliente"; ?>" readonly/>
                    </div>
                  </div>                  
                </div>
                <div class="row">
                	<label class="col-sm-2 col-form-label text-white">Razon Social ó Nombre Factura</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="fact" value="<?php echo "$nomFactura"; ?>" required/>
                    </div>
                  </div>
                </div>
                <hr style="background: #FFD116;color:#FFD116;">
                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">Dirección</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="dir" value="<?php echo "$dirCliente"; ?>" required placeholder="Zona / Avenida-Calle / Puerta"/>
                    </div>
                  </div>
                </div>
                

                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">Género</label>
                  <div class="col-sm-10">
                    <div class="form-group">                    	
                      <select class="selectpicker form-control" name="genero"id="genero" data-style="btn btn-primary" data-live-search="true" required>
                      	<option value="0" selected>--SELECCIONE--</option>
                           <?php echo "$cadComboGenero"; ?>
                       </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">Edad</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <select class="selectpicker form-control" name="edad"id="edad" data-style="btn btn-primary" data-live-search="true" required>
                      	<option value="0" selected>--SELECCIONE--</option>
                          <?php echo "$cadComboEdad"; ?>
                       </select>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="area" id="area" value="1">           

                </div>
                <div  class="card-footer">
                   <div class="">
                      <input class="btn btn-warning" id="boton_guardado_cliente" type="button" value="Guardar" onclick="javascript:adicionarCliente();" />
                   </div>
                 </div> 
    </div>
  </div>
</div>  
<!--    end small modal -->

<!-- small modal -->
<div class="modal fade modal-primary" id="modalProductosCercanos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">place</i>
                  </div>
                  <h4 class="card-title text-primary font-weight-bold">Stock de Productos en Sucursales</h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                	<div class="form-group">
												 <input type="text" class="form-control pull-right" style="width:20%" id="busqueda_sucursal" placeholder="Buscar Sucursal">
									</div>
									<br>
                  <table class="table table-sm table-bordered" id='tabla_sucursal'>
                    <thead>
                      <tr style='background: #ADADAD;color:#000;'>
                      	<th width='10%'>-</th>
                    	</tr>
                    </thead>
                    <tbody id="tabla_datosE">
                    </tbody>
                  </table>
                  <br><br>
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->








<!--<script src="dist/selectpicker/dist/js/bootstrap-select.js"></script>-->
 <script type="text/javascript" src="dist/js/functionsGeneral.js"></script>


 <div id="dosificar_factura_sucursal" style="position: fixed;width:100%;height:100%;background: rgba(0, 0, 0,0.7);top:0;z-index: 9999999;color:#FFC300;" class="d-none"> 	
 	<h1 style="color:#FFC300;font-size:25px;">CUFD VENCIDO!</h1>
 	<center><img src="imagenes/facturacion.jpg" width="500"></center>
 	<br>
 	<?php 
 	
	 $codSucursal=$_COOKIE['global_agencia'];
	 $anio=date("Y");
	 $sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$codSucursal' and estado=1 and cod_gestion='$anio'";
  $respCuis=mysqli_query($enlaceCon,$sqlCuis);
  $datCuis=mysqli_fetch_array($respCuis);
  $cuis=$datCuis[0];//  $cuis=mysqli_result($respCuis,0,0);
  if($cuis!=""){

  }else{
  	$cuis="NO GENERADO PARA LA GESTIÓN ".$anio;
  }
 ?><center><p>Obtenga el CUFD para las ventas del día de HOY en la sucursal.</p>
 		<table class="table table-bordered table-success" style="width: 60%;">
 			<tr><td><b>SUCURSAL</b></td><td align="left"><?=$nombreAlmacenSesion?></td></tr>
 			<tr><td><b>CUIS</b></td><td align="left"><?=$cuis?></td></tr>
 			<tr><td><b>FECHA CUFD IMPUESTOS</b></td><td align="left"><?=date("d/m/Y")?></td></tr>
 		</table>
 	</center><?php 			
					?><center><a href="siat_folder/siat_cuis_cufd/generar_cufd.php?cod_ciudad=<?=$_COOKIE['global_agencia']?>&l=1" class="btn btn-warning" style="height: 60px;font-size: 20px;">Obtener CUFD <br> <img src="imagenes/actua.gif" width="80" height="80" style="position: absolute;top:0;right:0;"></a></center><?php			

 	?>  
 </div>

</body>
</html>
