<?php
	require("conexion.inc");
	require("estilos.inc");
	require("funciones.php");

	$codTipo=$_GET["codTipo"];
	$nombreItem=$_GET["nombreItem"];
	
	//echo $codTipo;
	
	$sql="select codigo_material, descripcion_material, p.nombre_linea_proveedor 
	from material_apoyo ma, proveedores_lineas p 
	where ma.cod_linea_proveedor=p.cod_linea_proveedor ";
	if($codTipo>0){
		$sql.=" and p.cod_linea_proveedor='$codTipo' ";
	}
	if($nombreItem!=""){
		$sql.=" and ma.descripcion_material like '%$nombreItem%' ";
	}
	$sql.=" order by 3,2";

	$resp=mysql_query($sql);
	
	echo "<center><table class='texto' id='main'>";
	echo "<tr><th>Material</th>
	<th>Precio Normal</th>
	<th>Precio Super Oferta<br><input type='text' size='2' name='valorPrecioB' id='valorPrecioB' value='0'>
	<a href='javascript:modifPrecioB()'><img src='imagenes/edit.png' width='30' alt='Editar'></a></th>
	<th>Precio Oferta<br><input type='text' size='2' name='valorPrecioC' id='valorPrecioC' value='0'>
	<a href='javascript:modifPrecioC()'><img src='imagenes/edit.png' width='30' alt='Editar'></a></th>
	<th>Precio Excepcional<br><input type='text' size='2' name='valorPrecioF' id='valorPrecioF' value='0'>
	<a href='javascript:modifPrecioF()'><img src='imagenes/edit.png' width='30' alt='Editar'></th>
	<th>-</th>
	</tr>";
	$indice=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreTipo=$dat[2];


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
		//sql ultimo precio compra
		$sqlUltimaCompra="select id.precio_neto from ingreso_almacenes i, ingreso_detalle_almacenes id
			where id.cod_ingreso_almacen=i.cod_ingreso_almacen and i.ingreso_anulado=0 and 
		i.cod_almacen='$globalAlmacen' and id.cod_material='$codigo' order by i.fecha desc limit 0,1";
		$respUltimaCompra=mysql_query($sqlUltimaCompra);
		$numFilasUltimaCompra=mysql_num_rows($respUltimaCompra);
		$precioBase=0;
		if($numFilasUltimaCompra>0){
			$precioBase=mysql_result($respUltimaCompra,0,0);
		}
		$precioBase=redondear2($precioBase);
		
		$sqlMargen="select p.margen_precio from material_apoyo m, proveedores_lineas p
			where m.cod_linea_proveedor=p.cod_linea_proveedor and m.codigo_material='$codigo'";
		$respMargen=mysql_query($sqlMargen);
		$numFilasMargen=mysql_num_rows($respMargen);
		$porcentajeMargen=0;

		if($numFilasMargen>0){
			$porcentajeMargen=mysql_result($respMargen,0,0);			
		}
		
		$precioConMargen=$precioBase+($precioBase*($porcentajeMargen/100));
		
		//(Ultima compra: $precioBase  --  Precio+Margen: $precioConMargen)
		echo "<tr><td>$nombreMaterial ($nombreTipo) <a href='javascript:modifPreciosAjax($indice)'>
		<img src='imagenes/save3.png' title='Guardar este item.' width='30'></a>
		<a href='javascript:cambiarPrecioIndividual($indice)'>
		<img src='imagenes/flecha.png' title='Aplicar Porcentaje a este item.' width='30'></a>
		</td>";
		echo "<input type='hidden' name='item_$indice' id='item_$indice' value='$codigo'>";
		echo "<td align='center'><input type='text' size='5' value='$precio1' id='precio1_$indice' name='$codigo|1'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio2' id='precio2_$indice' name='$codigo|2'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio3' id='precio3_$indice' name='$codigo|3'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio4' id='precio4_$indice' name='$codigo|4'></td>";
		echo "<td><div id='contenedor_$indice'></div></td>";
		echo "</tr>";
		
		$indice++;

	}
	echo "</table></center>";

?>