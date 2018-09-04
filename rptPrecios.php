<script language='JavaScript'>

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

function cambiaPrecio(f, id, codigo, precio, tipoPrecio){
	var contenedor;
	contenedor = document.getElementById(id);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxCambiaPrecio.php?codigo='+codigo+'&precio='+precio+'&id='+id+'&tipoPrecio='+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function guardaAjaxPrecio(combo, codigo, id, tipoPrecio){
	var contenedor;
	var precio=combo.value;
	contenedor = document.getElementById(id);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxGuardaPrecio.php?codigo='+codigo+'&precio='+precio+'&tipoPrecio='+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ShowBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}		

function ajaxBuscarItems(f){
	var nombreItem, tipoItem;
	nombreItem=document.getElementById("nombreItem").value;
	tipoItem=document.getElementById("tipo_material").value;

	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxBuscarItems.php?nombreItem="+nombreItem+"&tipoItem="+tipoItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}

</script>
<?php

	require("conexion.inc");
	require("estilos_almacenes.inc");
	require("funciones.php");

	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	
	echo "<h1>Reporte de Precios</h1>";
	
	
	echo "<div class='divBotones'><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
	
	echo "<div id='divCuerpo'>";
	$sql="select codigo_material, descripcion_material from material_apoyo ma
			where estado=1 order by 2";
	$resp=mysql_query($sql);
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Material</th>
	<th>Existencias</th>
	<th>Precio A</th>
	<th>Precio B</th>
	<th>Precio C</th>
	<th>Precio Factura</th>
	<th>Costo</th>
	</tr>";
	$indice=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreTipo=$dat[2];
		
		//sacamos existencias
		$rpt_fecha=date("Y-m-d");
		$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$global_almacen'
		and id.cod_material='$codigo' and i.ingreso_anulado=0";
		$resp_ingresos=mysql_query($sql_ingresos);
		$dat_ingresos=mysql_fetch_array($resp_ingresos);
		$cant_ingresos=$dat_ingresos[0];
		$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
		where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$global_almacen'
		and sd.cod_material='$codigo' and s.salida_anulada=0";
		$resp_salidas=mysql_query($sql_salidas);
		$dat_salidas=mysql_fetch_array($resp_salidas);
		$cant_salidas=$dat_salidas[0];
		$stock2=$cant_ingresos-$cant_salidas;

		$sqlOC="select id.costo_promedio from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen=1000 and id.cod_material=$codigo and i.ingreso_anulado=0 order By i.cod_ingreso_almacen desc limit 0,1;";
		$respOC=mysql_query($sqlOC);
		$filasOC=mysql_num_rows($respOC);
		$precioOC=0;
		if($filasOC>0){
			$precioOC=mysql_result($respOC,0,0);
		}
		$precioOC=redondear2($precioOC);
		
		echo "<tr><td>$nombreMaterial </td>";
		echo "<td align='center'>$stock2</td>";

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio1=mysql_result($respPrecio,0,0);
			$precio1=redondear2($precio1);
		}else{
			$precio1=0;
			$precio1=redondear2($precio1);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=2 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio2=mysql_result($respPrecio,0,0);
			$precio2=redondear2($precio2);
		}else{
			$precio2=0;
			$precio2=redondear2($precio2);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=3 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio3=mysql_result($respPrecio,0,0);
			$precio3=redondear2($precio3);
		}else{
			$precio3=0;
			$precio3=redondear2($precio3);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=4 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio4=mysql_result($respPrecio,0,0);
			$precio4=redondear2($precio4);
		}else{
			$precio4=0;
			$precio4=redondear2($precio4);
		}

		$indice++;

		echo "<td align='center'><div id='1$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio1, 1)';>$precio1</div></td>";
		echo "<td align='center'><div id='2$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio2, 2)';>$precio2</div></td>";
		echo "<td align='center'><div id='3$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio3, 3)';>$precio3</div></td>";
		echo "<td align='center'><div id='4$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio4, 4)';>$precio4</div></td>";
		echo "<td align='center'>$precioOC</td>";
		echo "</tr>";
	}
	echo "</table></center><br>";
	echo "</div>";

	echo "<div class='divBotones'><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
	
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Nombre Item</td>
				<td>
				<input type='text' name='nombreItem' id="nombreItem" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Tipo Material</td>
				<td>
				<?php
					$sql1="select * from tipos_material order by nombre_tipomaterial";
					$resp1=mysql_query($sql1);
				?>
					<select name='tipo_material' id='tipo_material' class='texto'>
					<option value="0">Seleccione una opcion.</option>
				<?php
					while($dat1=mysql_fetch_array($resp1))
					{	$cod_tipomaterial=$dat1[0];
						$nombre_tipomaterial=$dat1[1];
				?>	
					<option value='<?php echo $cod_tipomaterial;?>'><?php echo $nombre_tipomaterial;?></option>
				<?php	
					}
				?>
					</select>
				</td>
			</tr>			
		</table>	
		<center>
			<input type='button' value='Buscar' onClick="ajaxBuscarItems(this.form)">
			<input type='button' value='Cancelar' onClick="HiddenBuscar()">
			
		</center>
	</div>
</div>


</form>

