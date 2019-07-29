<html>
<body>
<table align='center' class="texto">
<tr>
<th>Producto</th><th>Linea</th><th>Ubicacion</th><th>Stock</th><th>Precio</th></tr>
<?php
require("conexion.inc");
require("funciones.php");

$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$tipoSalida=$_GET['tipoSalida'];

$fechaActual=date("Y-m-d");

//SACAMOS LA CONFIGURACION PARA LA SALIDA POR VENCIMIENTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=5";
$respConf=mysql_query($sqlConf);
$tipoSalidaVencimiento=mysql_result($respConf,0,0);

	$sql="select m.codigo_material, m.descripcion_material,
	(select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor)
	from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor) from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";
	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}
	if($tipoSalidaVencimiento==$tipoSalida){
		$sql=$sql. " and m.codigo_material in (select id.cod_material from ingreso_almacenes i, ingreso_detalle_almacenes id 
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$globalAlmacen' and i.ingreso_anulado=0 
		and id.fecha_vencimiento<'$fechaActual') ";
	}
	$sql=$sql." order by 2";
	
	//echo $sql;
	
	$resp=mysql_query($sql);

	$numFilas=mysql_num_rows($resp);
	if($numFilas>0){
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$linea=$dat[2];
			
			$nombre=addslashes($nombre);
			
			if($tipoSalida==$tipoSalidaVencimiento){
				$stockProducto=stockProductoVencido($globalAlmacen, $codigo);
			}else{
				$stockProducto=stockProducto($globalAlmacen, $codigo);
			}
			
			$ubicacionProducto=ubicacionProducto($globalAlmacen, $codigo);
			
			$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1'";
			$rs=mysql_query($consulta);
			$registro=mysql_fetch_array($rs);
			$precioProducto=$registro[0];
			if($precioProducto=="")
			{   $precioProducto=0;
			}
			$precioProducto=redondear2($precioProducto);
			
			echo "<tr><td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre\")'>$nombre</a></div></td>
			<td>$linea</td>
			<td>$ubicacionProducto</td>
			<td>$stockProducto</td>
			<td>$precioProducto</td>
			</tr>";
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}
	
?>
</table>

</body>
</html>