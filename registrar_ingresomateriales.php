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
		
	//var importeNeto=parseFloat(precioCompra)- (parseFloat(precioCompra)*0.13);
	var importeNeto=parseFloat(precioCompra);
	
	if(importeNeto=="NaN"){
		importeNeto.value=0;
	}
	document.getElementById('neto'+fila).value=importeNeto;
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
		 //num=parseInt(num)-1;
		 fi = document.getElementById('fiel');
  		 fi.removeChild(document.getElementById('div'+numero));
			
 		 calcularTotal();
		 
	}

function validar(f)
{   f.cantidad_material.value=num;
	var cantidadItems=num;
	
	if(cantidadItems>0){
		var notaEntrega=document.getElementById("nota_entrega").value;
		var nroFactura=document.getElementById("nro_factura").value;
		var tipoIngreso=document.getElementById("tipo_ingreso").value;
		var nroSalida=document.getElementById("nro_salida").value;
		var item="";
		var cantidad="";
		var precioBruto="";
		var precioNeto="";
		
		if(notaEntrega==""){
			alert("La Nota de Entrega no puede ir vacia."); return(false);
		}
		if(nroFactura==""){
			alert("La Factura no puede ir vacia."); return(false);
		}
		if(tipoIngreso=="1001"){
			if(nroSalida=="" || nroSalida=="0"){
				alert("El Numero de Salida no puede estar vacio o ser 0.");
				return(false);
			}
		}
		
		for(var i=1; i<=cantidadItems; i++){
			item=parseFloat(document.getElementById("material"+i).value);
			cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
			precioBruto=parseFloat(document.getElementById("precio"+i).value);
			precioNeto=parseFloat(document.getElementById("neto"+i).value);
			
			if(item==0){
				alert("Debe escoger un item en la fila "+i);
				return(false);
			}
			if(cantidad==0){
				alert("La cantidad no puede ser 0 ni vacia. Fila "+i);
				return(false);
			}
			if(precioBruto==0){
				alert("El precio no puede ser 0 ni vacio. Fila "+i);
				return(false);
			}
			f.submit();
		}
		
	}else{
		alert("El ingreso debe tener al menos 1 item.");
		return(false);
	}
	
}


	</script>
<?php

require("conexion.inc");

require("estilos_almacenes.inc");

if($fecha=="")
{   $fecha=date("d/m/Y");
}

echo "<form action='guarda_ingresomateriales.php' method='post' name='form1'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Ingreso de Materiales</th></tr></table><br>";
echo "<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>";
echo "<tr><th>Numero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Nota de Entrega</th><th>Factura</th></tr>";
echo "<tr>";
$sql="select nro_correlativo from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
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
echo "<td align='center'>";

echo "<input type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
echo "<img id='imagenFecha' src='imagenes/fecha.bmp'>";
echo "</td>";

$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_ingreso' id='tipo_ingreso' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
    echo "<option value='$cod_tipoingreso'>$nombre_tipoingreso</option>";
}
echo "</select></td>";
echo "<td align='center'><input type='text' class='texto' name='nota_entrega' value='$nota_entrega' id='nota_entrega'></td>";
echo "<td align='center'><input type='text' class='texto' name='nro_factura' value='' id='nro_factura'></td></tr>";

echo "<tr><th>Proveedor</th>";
echo "<th>Nro. Salida Origen</th><th colspan='3'>Observaciones</th></tr>";
$sql1="select cod_proveedor, nombre_proveedor from proveedores order by 2";
$resp1=mysql_query($sql1);
echo "<tr><td align='center'><select name='proveedor' id='proveedor' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
    echo "<option value='$codigo'>$nombre</option>";
}
echo "</select></td>";
echo "<td><input type='text' name='nro_salida' id='nro_salida' class='texto' onKeyDown='ajaxNroSalida();' value='0'><div id='divNroSalida'></div></td><td colspan='4' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";
?>
		<fieldset id="fiel" style="width:98%;border:0;" >
			<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
				<tr>
					<td align="center" colspan="6">
						<input class="boton" type="button" value="Nuevo Item (+)" onclick="mas(this)" accesskey="N"/>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="6">
					<div style="width:100%;" align="center"><b>DETALLE</b></div>
					</td>				
				</tr>				
				<tr class="titulo_tabla" align="center">
					<td width="60%">Material</td>
					<td width="8%">Cantidad</td>
					<td width="8%">Precio </td>
					<td width="8%">Precio Neto</td>
					<td width="8%">&nbsp;</td>
				</tr>
			</table>
		</fieldset>


<?php

echo "<table align='center'><tr><td><a href='navegador_ingresomateriales.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>



<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Tipo Material</th><th>Cod. Int.</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select name='itemTipoMaterial' id="itemTipoMaterial">
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
				<input type='text' name='itemCodMaterial' id="itemCodMaterial">
			</td>
			<td>
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial">
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