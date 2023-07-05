<?php

	require("conexionmysqli.inc");
	require("estilos_almacenes.inc");
	require("funciones.php");
	require("funcion_nombres.php");

	error_reporting(E_ALL);
 	ini_set('display_errors', '1');


	$almacenReporte=$_COOKIE["global_almacen"];
	$codigoCiudadGlobal=$_COOKIE["global_agencia"];



	$nombreCiudad=nombreTerritorio($enlaceCon,$codigoCiudadGlobal);

 	//PORCENTAJE DE DESCUENTO APLICADO AL PRECIO
 	$porcentajeVentaProd=obtenerValorConfiguracion($enlaceCon, 53);
 	$porcentajePrecioMayorista=precioMayoristaSucursal($enlaceCon,$codigoCiudadGlobal);

	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	
	echo "<h1>Reporte de Precios</h1>";
	echo "<h1>$nombreCiudad</h1>";
	
	
	//echo "<div class='divBotones'><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
	
	echo "<div id='divCuerpo'>";
	$sql="select codigo_material, descripcion_material, (select p.nombre_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=ma.cod_linea_proveedor) as proveedor  from material_apoyo ma where estado=1 order by 3,2";
	$resp=mysqli_query($enlaceCon, $sql);
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Proveedor</th><th>Material</th>";
	//<th>Existencias</th>
	echo "<th>Precio</th>
	</tr>";
	$indice=1;
	$precio1=0;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreProveedor=$dat[2];

		//$stockProducto=stockProducto($enlaceCon, $almacenReporte, $codigo);
		$precioProducto=precioProductoSucursalCalculado($enlaceCon,$codigo,$codigoCiudadGlobal);

		$precioProductoF=formatonumeroDec($precioProducto);
		$indice++;

		if($precioProducto>0){
			echo "<tr><td>$nombreProveedor</td>";
			echo "<td>$nombreMaterial</td>";
			//echo "<td align='right'>$stockProducto</td>";
			echo "<td align='right'><div id='1$codigo'>$precioProductoF</div></td>";
			echo "</tr>";			
		}

	}
	echo "</table></center><br>";
	echo "</div>";

	//echo "<div class='divBotones'><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
	
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
					$resp1=mysqli_query($enlaceCon, $sql1);
				?>
					<select name='tipo_material' id='tipo_material' class='texto'>
					<option value="0">Seleccione una opcion.</option>
				<?php
					while($dat1=mysqli_fetch_array($resp1))
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

