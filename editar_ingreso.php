<?php
	require("conexionmysqli.inc");	
	$codIngresoEditar=$_GET["codIngreso"];
	$sql=" select count(*) from ingreso_detalle_almacenes where cod_ingreso_almacen=".$codIngresoEditar;	
	$num_materiales=0;
	$resp= mysqli_query($enlaceCon, $sql);				
	while($dat=mysqli_fetch_array($resp)){	
		$num_materiales=$dat[0];
	}
?>
<script>
num=<?php echo $num_materiales;?>;

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
function calculaMargen(preciocliente, index){
	preciocliente=parseFloat(preciocliente.value);
	var preciocompra=document.getElementById('precio'+index).value;
	var costo=parseFloat(preciocompra);
	var cantidad=document.getElementById('cantidad_unitaria'+index).value;
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

	var margenNuevo=(preciocliente-costounitario)/costounitario;
	var margenNuevoF="M ["+ number_format((margenNuevo*100),0) + "%]";
	document.getElementById('divmargen'+index).innerHTML=margenNuevoF;

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

require("estilos_almacenes.inc");

if($fecha=="")
{   $fecha=date("d/m/Y");
}

?>
<form action='guarda_editaringresomateriales.php' method='post' name='form1'>
<input type="hidden" name="codIngreso" value="<?php echo $codIngresoEditar;?>" id="codIngreso">
<table border='0' class='textotit' align='center'><tr><th>Editar Ingreso de Materiales</th></tr></table><br>

<?php

$sqlIngreso="select i.`nro_correlativo`, i.`fecha`, i.`cod_tipoingreso`, i.`nota_entrega`, i.`nro_factura_proveedor`, 
		i.`observaciones` from `ingreso_almacenes` i where i.`cod_ingreso_almacen` = $codIngresoEditar" ;
$respIngreso=mysqli_query($enlaceCon, $sqlIngreso);
while($datIngreso=mysqli_fetch_array($respIngreso)){
	$nroCorrelativo=$datIngreso[0];
	$fechaIngreso=$datIngreso[1];
	$codTipoIngreso=$datIngreso[2];
	$notaEntrega=$datIngreso[3];
	$nroFacturaProv=$datIngreso[4];
	$obsIngreso=$datIngreso[5];
}

?>
<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
<tr><th>Numero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Factura</th></tr>
<tr>
	<td align='center'><?php echo $nroCorrelativo?></td>
	<td align='center'>
	<input type="text" disabled="true" class="texto" value="<?php echo $fechaIngreso;?>" id="fecha" size="10" name="fecha">
	<img id='imagenFecha' src='imagenes/fecha.bmp'>
	</td>
	
<?php
$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
$resp1=mysqli_query($enlaceCon, $sql1);
?>

<td align='center'><select name='tipo_ingreso' id='tipo_ingreso' class='texto'>

<?php

while($dat1=mysqli_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
?>
    <option value="<?php echo $cod_tipoingreso; ?>" <?php if($cod_tipoingreso==$codTipoIngreso){echo "selected";}?>"><?php echo $nombre_tipoingreso;?></option>
<?php
}
?>
</select></td>
<td align="center"><input type="text" class="texto" name="nro_factura" value="<?php echo $nroFacturaProv; ?>" id="nro_factura"></td></tr>
<tr><th colspan="4">Observaciones</th></tr>
<tr>
<td colspan="4" align="center"><input type="text" class="texto" name="observaciones" value="<?php echo $obsIngreso; ?>" size="100"></td></tr>
</table><br>

		<fieldset id="fiel" style="width:98%;border:0;" >
			<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
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
					<td width="10%" align="center">&nbsp;</td>
					<td width="40%" align="center">Producto</td>
					<td width="10%" align="center">Cantidad</td>
					<!--td width="10%" align="center">Lote</td-->
					<td width="10%" align="center">Vencimiento</td>
					<td width="10%" align="center">Precio Distribuidor<br>(Total_item)</td>
					<td width="10%" align="center">Precio Cliente Final</td>
					<td width="10%" align="center">&nbsp;</td>
				</tr>
			</table>
			
			<?php
			$sqlDetalle="select id.`cod_material`, m.`descripcion_material`, id.`cantidad_unitaria`, id.`precio_bruto`, id.`precio_neto`, 
				lote, fecha_vencimiento
				from `ingreso_detalle_almacenes` id, `material_apoyo` m where
				id.`cod_material`=m.`codigo_material` and id.`cod_ingreso_almacen`='$codIngresoEditar' order by 2";
			$respDetalle=mysqli_query($enlaceCon, $sqlDetalle);
			$indiceMaterial=1;
			while($datDetalle=mysqli_fetch_array($respDetalle)){
				$codMaterial=$datDetalle[0];
				$nombreMaterial=$datDetalle[1];
				$cantidadMaterial=$datDetalle[2];
				$precioBruto=$datDetalle[3];
				$precioNeto=$datDetalle[4];
				$loteMaterial=$datDetalle[5];
				$fechaVencimiento=$datDetalle[6];
				$num=$indiceMaterial;
			?>

<div id="div<?php echo $num?>">

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="10%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="40%" align="center">
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="<?php echo $codMaterial;?>">
<div id="cod_material<?php echo $num;?>" class='textomedianorojo'><?php echo $nombreMaterial;?></div>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" min="1" max="1000000" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5" value="<?php echo $cantidadMaterial;?>" required>
</td>

<!--td align="center" width="10%">
<input type="text" class="textoform" id="lote<?php echo $num;?>" name="lote<?php echo $num;?>" size="10" value="<?php echo $loteMaterial;?>" required>
</td-->

<td align="center" width="10%">
<input type="date" class="textoform" min="<?php echo $fechaActual; ?>" id="fechaVenc<?php echo $num;?>" name="fechaVenc<?php echo $num;?>" size="5" value="<?php echo $fechaVencimiento;?>" required>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" value="<?php echo $precioBruto;?>" id="precio<?php echo $num;?>" name="precio<?php echo $num;?>" size="5" min="0" required>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="preciocliente<?php echo $num;?>" name="preciocliente<?php echo $num;?>" size="4" min="0" step="0.01" onKeyUp='calculaMargen(this,<?php echo $num;?>);' onChange='calculaMargen(this,<?php echo $num;?>);' required>
</br>
<div id="divpreciocliente<?php echo $num;?>" class="textopequenorojo">-</div>
<div id="divmargen<?php echo $num;?>" class="textopequenorojo2">-</div>
<input type="hidden" name="margenlinea<?php echo $num;?>" id="margenlinea<?php echo $num;?>" value="0">
</td>

<td align="center"  width="10%" ><input class="boton1" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>

</tr>
</table>

</div>
			
			<?php
				$indiceMaterial++;
			}
			?>
			
		</fieldset>


<?php

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'></center>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresomateriales.php\"'></center>
</div>";
?>


<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center; height:445px; overflow-y: scroll;">
		<table align='center' class="texto">
			<tr><th>Linea</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select name='itemTipoMaterial' id="itemTipoMaterial" class="textogranderojo" style="width:300px">
			<?php
			$sqlTipo="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
			where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
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
			<td>
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial" class="textogranderojo">
			</td>
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
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