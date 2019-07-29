<?php
	require("conexion.inc");
	require("estilos.inc");
	require("funciones.php");

	$codTipo=$_GET["codTipo"];
	$nombreItem=$_GET["nombreItem"];
	
	//echo $codTipo;
	
	$sql="select codigo_material, descripcion_material, p.nombre_linea_proveedor, id.cod_ubicacionestante,
	(select ue.nombre from ubicaciones_estantes ue where ue.codigo=id.cod_ubicacionestante)estante, 
	id.cod_ubicacionfila, (select uf.nombre from ubicaciones_filas uf where uf.codigo=id.cod_ubicacionestante)fila, id.cod_ingreso_almacen
	from material_apoyo ma, proveedores_lineas p, ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and id.cod_material=ma.codigo_material and 
	ma.cod_linea_proveedor=p.cod_linea_proveedor and id.cantidad_restante>0 ";
	if($codTipo>0){
		$sql.=" and p.cod_linea_proveedor='$codTipo' ";
	}
	if($nombreItem!=""){
		$sql.=" and ma.descripcion_material like '%$nombreItem%' ";
	}
	$sql.=" order by 3,2";	
	
	$resp1=mysql_query($sql);
	
	echo "<center><table class='texto' id='main'>";

	echo "<tr><th>Material</th>";
	echo "<th>Ubicacion Estante</th>";	
	echo "<th>Ubicacion Fila</th>";
	echo "<th>-</th>";
	echo "</tr>";
	
	$indice=1;
	while($dat=mysql_fetch_array($resp1))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreTipo=$dat[2];
		$codEstante=$dat[3];
		$nombreEstante=$dat[4];
		$codFila=$dat[5];
		$nombreFila=$dat[6];
		$codIngreso=$dat[7];

		echo "<tr><td>$nombreMaterial ($nombreTipo) <a href='javascript:modifUbicacionesAjax($indice)'>
		<img src='imagenes/save3.png' title='Guardar este item.' width='30'></a>
		</td>";
		echo "<input type='hidden' name='item_$indice' id='item_$indice' value='$codigo'>";
		echo "<input type='hidden' name='ingreso_$indice' id='ingreso_$indice' value='$codIngreso'>";
		$sqlE="select codigo, nombre from ubicaciones_estantes where cod_estado=1";
		$respE=mysql_query($sqlE);
		echo "<td align='center'>
		<select name='estante_$indice' id='estante_$indice'>";
		$respE=mysql_query($sqlE);
			while($datE=mysql_fetch_array($respE)){
				if($datE[0]==$codEstante){
					echo "<option value='$datE[0]' selected>$datE[1]</option>";					
				}else{
					echo "<option value='$datE[0]'>$datE[1]</option>";
				}
			}
		echo "</select></td>";
		
		$sqlU="select codigo, nombre from ubicaciones_filas where cod_estado=1";
		$respU=mysql_query($sqlU);
		echo "<td align='center'>		
		<select name='fila_$indice' id='fila_$indice'>";
		$respU=mysql_query($sqlU);
		while($datU=mysql_fetch_array($respU)){
			if($datU[0]==$codFila){
				echo "<option value='$datU[0]' selected>$datU[1]</option>";				
			}else{
				echo "<option value='$datU[0]'>$datU[1]</option>";
			}
		}
		echo "</select></td>";
		echo "<td><div id='contenedor_$indice'></div></td>";
		echo "</tr>";		
		$indice++;
	}
	echo "</table></center>";

?>