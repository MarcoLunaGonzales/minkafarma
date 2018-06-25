<html>
    <head>
        
<script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="dlcalendar.js"></script>
<script type='text/javascript' language='javascript'>
<?php

	require("conexion.inc");
	
	$codIngresoEditar=$_GET["codIngreso"];
	$sql=" select count(*) from ingreso_detalle_almacenes where cod_ingreso_almacen=".$codIngresoEditar;	
	$num_materiales=0;
	$resp= mysql_query($sql);				
	while($dat=mysql_fetch_array($resp)){	
		$num_materiales=$dat[0];
	}
?>
num=<?php echo $num_materiales;?>;
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
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}
function setMateriales(f, cod, nombreMat){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).value=nombreMat;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	
}
		
function precioNeto(fila){

	var precioCompra=document.getElementById('precio'+fila).value;
		
	var importeNeto=parseFloat(precioCompra)- (parseFloat(precioCompra)*0.13);

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

	//num=0;

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

?>
<form action='guarda_editaringresomateriales.php' method='post' name='form1'>
<input type="hidden" name="codIngreso" value="<?php echo $codIngresoEditar;?>" id="codIngreso">
<table border='0' class='textotit' align='center'><tr><th>Editar Ingreso de Materiales</th></tr></table><br>

<?php

$sqlIngreso="select i.`nro_correlativo`, i.`fecha`, i.`cod_tipoingreso`, i.`nota_entrega`, i.`nro_factura_proveedor`, 
		i.`observaciones` from `ingreso_almacenes` i where i.`cod_ingreso_almacen` = $codIngresoEditar" ;
$respIngreso=mysql_query($sqlIngreso);
while($datIngreso=mysql_fetch_array($respIngreso)){
	$nroCorrelativo=$datIngreso[0];
	$fechaIngreso=$datIngreso[1];
	$codTipoIngreso=$datIngreso[2];
	$notaEntrega=$datIngreso[3];
	$nroFacturaProv=$datIngreso[4];
	$obsIngreso=$datIngreso[5];
}

?>
<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
<tr><th>Numero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Nota de Entrega</th><th>Factura</th></tr>
<tr>
	<td align='center'><?php echo $nroCorrelativo?></td>
	<td align='center'>
	<input type="text" disabled="true" class="texto" value="<?php echo $fechaIngreso;?>" id="fecha" size="10" name="fecha">
	<img id='imagenFecha' src='imagenes/fecha.bmp'>
	</td>
	
<?php
$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
$resp1=mysql_query($sql1);
?>

<td align='center'><select name='tipo_ingreso' id='tipo_ingreso' class='texto'>

<?php

while($dat1=mysql_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
?>
    <option value="<?php echo $cod_tipoingreso; ?>" <?php if($cod_tipoingreso==$codTipoIngreso){echo "selected";}?>"><?php echo $nombre_tipoingreso;?></option>
<?php
}
?>
</select></td>
<td align="center"><input type="text" class="texto" name="nota_entrega" value="<?php echo $notaEntrega; ?>" id="nota_entrega"></td>
<td align="center"><input type="text" class="texto" name="nro_factura" value="<?php echo $nroFacturaProv; ?>" id="nro_factura"></td></tr>
<tr><th>Nro. Salida Origen</th><th colspan="4">Observaciones</th></tr>
<tr>
<td><input type="text" name="nro_salida" id="nro_salida" class='texto' onKeyDown='ajaxNroSalida();' value='0'><div id='divNroSalida'></div></td>
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
					<td width="60%">Material</td>
					<td width="8%">Cantidad</td>
					<td width="8%">Precio </td>
					<td width="8%">Precio Neto</td>
					<td width="8%">&nbsp;</td>
				</tr>
			</table>
			
			<?php
			$sqlDetalle="select id.`cod_material`, m.`descripcion_material`, id.`cantidad_unitaria`, id.`precio_bruto`, id.`precio_neto` 
				from `ingreso_detalle_almacenes` id, `material_apoyo` m where
				id.`cod_material`=m.`codigo_material` and id.`cod_ingreso_almacen`=$codIngresoEditar";
			$respDetalle=mysql_query($sqlDetalle);
			$indiceMaterial=1;
			while($datDetalle=mysql_fetch_array($respDetalle)){
				$codMaterial=$datDetalle[0];
				$nombreMaterial=$datDetalle[1];
				$cantidadMaterial=$datDetalle[2];
				$precioBruto=$datDetalle[3];
				$precioNeto=$datDetalle[4];
			?>
			<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%"  style="border:#ccc 1px solid;" id="data<?php echo $indiceMaterial?>" >
			<tr bgcolor="#FFFFFF">
			<td width="60%" align="center">
			<a href="javascript:buscarMaterial(form1, <?php echo $indiceMaterial;?>)">Buscar</a>
			<input type="hidden" name="material<?php echo $indiceMaterial;?>" id="material<?php echo $indiceMaterial;?>" value="<?php echo $codMaterial;?>">
			<input type="text" class="textoform" id="cod_material<?php echo $indiceMaterial;?>" name="cod_material<?php echo $indiceMaterial;?>"  value="<?php echo $nombreMaterial;?>" onChange="" size="70" readonly>
			</td>
			<td align="center" width="8%">
			<input type="text" class="textoform" id="cantidad_unitaria<?php echo $indiceMaterial;?>" name="cantidad_unitaria<?php echo $indiceMaterial;?>"  value="<?php echo $cantidadMaterial;?>" size="5">
			</td>
			<td align="center" width="8%">
			<input type="text" class="textoform" id="precio<?php echo $indiceMaterial;?>" name="precio<?php echo $indiceMaterial;?>" onKeyUp="precioNeto('<?php echo $indiceMaterial;?>')" value="<?php echo $precioBruto;?>" size="5" >
			</td>
			<td align="center" width="8%">
			<input type="text" class="textoform" id="neto<?php echo $indiceMaterial;?>" name="neto<?php echo $indiceMaterial;?>" value="<?php echo $precioNeto;?>" size="5" readonly>
			</td>
			<td align="center"  width="8%" ><input class="boton1" type="button" value="(-)" onclick="menos(<?php echo $indiceMaterial;?>)" size="5"/></td>
			</tr>
			</table>
			
			<?php
				$indiceMaterial++;
			}
			?>
			
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