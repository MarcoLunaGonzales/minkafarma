<?php
require('conexion.inc');
$codigo=$_GET['codigo'];
$precio=$_GET['precio'];
$tipoPrecio=$_GET['tipoPrecio'];

$sql="update precios set precio='$precio' where codigo_material='$codigo' and cod_precio='$tipoPrecio'";
$resp=mysql_query($sql);

echo $precio;
?>