<?php
ini_set('post_max_size','100M');
require('conexionmysqli.php');
require("estilos.inc");
require("funciones.php");
require("funcion_nombres.php");

$codigoProveedor=$_POST["cod_proveedor"];
$nombreProducto=$_POST["nombre_producto"];
$codigoBarras=$_POST["codigo_barras"];

?>


<script language='Javascript'>
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

function modifPreciosAjax(indice){
	var item=document.getElementById('item_'+indice).value;
	var precio1=document.getElementById('precio1_'+indice).value;
	contenedor = document.getElementById('contenedor_'+indice);
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxGuardarPrecios.php?item="+item+"&precio1="+precio1,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}else{
			contenedor.innerHTML="Guardando...";
		}
	}
	ajax.send(null)
	
}
</script>


<?php
	

	$globalAlmacen=$_COOKIE['global_almacen'];
	$globalAdmin=$_COOKIE['global_admin_cargo'];
	
	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	
	$sql="select ma.codigo_material, ma.descripcion_material, 
	(select pl.nombre_linea_proveedor from proveedores_lineas pl where pl.cod_linea_proveedor=ma.cod_linea_proveedor)as linea,
	(select p.nombre_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=ma.cod_linea_proveedor)as proveedor 
	from material_apoyo ma
	where ma.estado=1";

	if($codigoProveedor>0){
		$sql.=" and ma.cod_linea_proveedor in (select cod_linea_proveedor from proveedores_lineas p where p.cod_proveedor='$codigoProveedor')";
	}
	if($nombreProducto!=""){
		$sql.=" and ma.descripcion_material like '%$nombreProducto%' ";		
	}
	if($codigoBarras!=""){
		$sql.=" and ma.codigo_barras like '%$codigoBarras%' ";			
	}
	
	$sql.=" order by 2";

	//echo $sql;

	$resp=mysqli_query($enlaceCon,$sql);


	echo "<h1 align='center'>Ajuste de Precios</h1>";	
	echo "<center><table class='texto' id='main'>";
	echo "<tr><th>Proveedor / Linea</th>
	<th>Material</th>
	<th>Stock</th>
	<th>Precio Normal</th>
	<th>Guardar</th>
	<th>-</th>
	</tr>";
	$indice=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreLinea=$dat[2];
		$nombreProveedor=$dat[3];


		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo";
		$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		$numFilas=mysqli_num_rows($respPrecio);
		if($numFilas==1){
			
			$datPrecio=mysqli_fetch_array($respPrecio);
			$precio1=$datPrecio[0];
			//$precio1=mysql_result($respPrecio,0,0);
			$precio1=redondear2($precio1);
		}else{
			$precio1=0;
			$precio1=redondear2($precio1);
		}

		$precioConMargen=$precioBase+($precioBase*($porcentajeMargen/100));
		$stockProducto=stockProducto($enlaceCon, $globalAlmacen, $codigo);
		
		if($stockProducto==0){
			$stockProducto="-";
		}
		//(Ultima compra: $precioBase  --  Precio+Margen: $precioConMargen)
		echo "<tr>
		<td>$nombreProveedor - $nombreLinea</td>
		<td><a href='editar_material_apoyo.php?cod_material=$codigo&pagina_retorno=2'><div class='textomedianorojo'>$nombreMaterial</div></a></td>
		<td align='center'><div class='textomedianorojo'>$stockProducto</div></td>";
		echo "<input type='hidden' name='item_$indice' id='item_$indice' value='$codigo'>";
		echo "<td align='center'><input type='text' size='5' value='$precio1' id='precio1_$indice' name='$codigo|1'></td>";
		if($globalAdmin==1){
			echo "<td><a href='javascript:modifPreciosAjax($indice)'>
			<img src='imagenes/guardar.png' title='Guardar este item.' width='30'></a></td>";
		}else{
			echo "<td>&nbsp;</td>";

		}
		echo "<td align='center'><div id='contenedor_$indice'></div></td>";
		echo "</tr>";
		
		$indice++;

	}
	echo "</table></center>";

	/*echo "<div class='divBotones'>
	<input type='button' value='Guardar Todo' name='adicionar' class='boton' onclick='enviar(form1)'>
	</div>";*/
	echo "</form>";
?>