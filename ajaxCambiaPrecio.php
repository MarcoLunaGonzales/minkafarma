<?php
require('conexion.inc');
$codigo=$_GET['codigo'];
$id=$_GET['id'];
$precio=$_GET['precio'];
$tipoPrecio=$_GET['tipoPrecio'];

echo "<input type='text' size='4' class='texto' onBlur='guardaAjaxPrecio(this, $codigo, $id, $tipoPrecio);' value='$precio'>";
?>