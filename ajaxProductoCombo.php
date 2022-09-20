<?php
$estilosVenta=1;
require('conexionmysqli2.inc');
$codigo=$_GET['codigo'];
$descripcion=$_GET['descripcion'];

$sql_item="select m.codigo_material, m.descripcion_material,(SELECT nombre_proveedor from proveedores where cod_proveedor=(SELECT cod_proveedor from proveedores_lineas where cod_linea_proveedor=m.cod_linea_proveedor))proveedor from material_apoyo m where m.codigo_material<>0 ";

if($codigo!=""){
   $sql_item.=" and m.codigo_material in ($codigo) ";
}else{
	if($descripcion!=""){
	  $sql_item.=" and m.descripcion_material like '%$descripcion%' ";		
	}else{
 	  $sql_item.=" and m.codigo_material<-45465 ";			//para que no liste nada
	}   
}

$sqlTipoSum=" and m.codigo_material>0 ";
if(isset($_COOKIE['global_tipo_almacen'])&&$_COOKIE['global_tipo_almacen']!=1){
  $sqlTipoSum=" and m.codigo_material<0 ";
}

$sql_item.=" $sqlTipoSum order by m.descripcion_material limit 0,100 ";
//echo $sql_item;
	$resp=mysqli_query($enlaceCon,$sql_item);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		$nombre_item=$dat[1];
		$proveedor=$dat[2];
		echo "<option value='$codigo_item' selected>$codigo_item: $nombre_item ($proveedor)</option>";
	}