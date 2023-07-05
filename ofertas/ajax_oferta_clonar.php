<?php
require("../conexionmysqli2.inc");

$cod_oferta=$_GET["codigo"];
$user=$_COOKIE["global_usuario"];

$sql="SELECT IFNULL(max(codigo)+1,1) FROM tipos_precio";
$resp=mysqli_query($enlaceCon,$sql);
$codigo=mysqli_result($resp,0,0);

$sql="INSERT INTO tipos_precio 
	 SELECT $codigo as codigo,nombre,abreviatura,desde,hasta,estado,1 as cod_estadodescuento,' CLONADO ' as observacion_descuento,por_linea,$user as cod_funcionario  FROM tipos_precio where codigo='$cod_oferta';";
$sql_inserta=mysqli_query($enlaceCon,$sql);
//echo $sql;  NOW() as 
if($sql_inserta==1){
	$sql="INSERT INTO tipos_precio_dias   
	SELECT $codigo as cod_tipoprecio,cod_dia FROM tipos_precio_dias where cod_tipoprecio='$cod_oferta';";
	$resp=mysqli_query($enlaceCon,$sql);	
	$sql="INSERT INTO tipos_precio_ciudad   
	SELECT $codigo as cod_tipoprecio,cod_ciudad FROM tipos_precio_ciudad where cod_tipoprecio='$cod_oferta';";
	$resp=mysqli_query($enlaceCon,$sql);	
	$sql="INSERT INTO tipos_precio_lineas   
	SELECT $codigo as cod_tipoprecio,cod_linea_proveedor FROM tipos_precio_lineas where cod_tipoprecio='$cod_oferta';";
	$resp=mysqli_query($enlaceCon,$sql);	
	$sql="INSERT INTO tipos_precio_productos   
	SELECT $codigo as cod_tipoprecio,cod_material,porcentaje_material FROM tipos_precio_productos where cod_tipoprecio='$cod_oferta';";
	$resp=mysqli_query($enlaceCon,$sql);	
	echo "1";
}else{
	echo "2";
}