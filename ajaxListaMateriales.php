<html>
<body>
<table align='center' class="texto">
<tr>
<th>Producto</th><th>Stock</th></tr>
<?php
require("conexion.inc");
$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$globalAlmacen=$_COOKIE['global_almacen'];

$sql="select m.codigo_material, m.descripcion_material, sum(id.cantidad_restante) from material_apoyo m, ingreso_almacenes i, ingreso_detalle_almacenes id
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and id.cod_material=m.codigo_material and 
i.ingreso_anulado=0 and i.cod_almacen='$globalAlmacen' and id.cantidad_restante>0";
if($nombreItem!=""){
	$sql=$sql. " and descripcion_material like '%$nombreItem%'";
}
$sql=$sql." group by m.codigo_material, m.descripcion_material order by 2";
//echo $sql;
$resp=mysql_query($sql);

$numFilas=mysql_num_rows($resp);
if($numFilas>0){	
	while($dat=mysql_fetch_array($resp)){
		$codigo=$dat[0];
		$nombre=$dat[1];
		$stockMaterial=$dat[2];
		
		echo "<tr><td><div class='textograndenegro'>
		<a href='javascript:setMateriales(form1, $codigo, \"$nombre\")'>$nombre</a>
		</div></td>
		<td>$stockMaterial</td></tr>";
	}
}else{
	$sql="select m.codigo_material, m.descripcion_material from material_apoyo m where estado=1";
	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}
	$sql=$sql." order by 1";
	$resp=mysql_query($sql);

	$numFilas=mysql_num_rows($resp);
	if($numFilas>0){
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			
			echo "<tr><td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre\")'>$nombre</a></div></td><td><div class='textograndenegro'>-</a></div></td><td>-</td></tr>";
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}
}
?>
</table>

</body>
</html>