<?php
require("conexion.inc");
require("funciones.php");

$nombreItem=$_GET['nombreItem'];
$tipoItem=$_GET['tipoItem'];


$sql="select codigo_material, descripcion_material, t.`nombre_tipomaterial` from material_apoyo ma, `tipos_material` t
			where ma.`cod_tipo_material`=t.`cod_tipomaterial`";

if($nombreItem!=""){
	$sql=$sql." and ma.descripcion_material like '%$nombreItem%' ";
}
if($tipoItem!=0){
	$sql=$sql." and ma.cod_tipo_material='$tipoItem' ";
}
	$sql=$sql." order by 3,2";
	
	
$resp=mysql_query($sql);
	
echo "<center><table border='1' class='texto' cellspacing='0' width='80%' id='main'>";
echo "<tr><th>Material</th>
<th>Existencias</th>
<th>Precio A</th>
<th>Precio B</th>
<th>Precio C</th>
<th>Precio Factura</th>
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

	echo "<td align='center'>$precio1</td>";
	echo "<td align='center'>$precio2</td>";
	echo "<td align='center'>$precio3</td>";
	echo "<td align='center'>$precio4</td>";
	echo "</tr>";
}
echo "</table></center><br>";


?>
