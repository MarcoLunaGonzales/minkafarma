<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$codAlmacen = $_GET["codalm"];
$indice = $_GET["indice"];


require("conexionmysqli2.inc");

$stockProducto=0;

$stockProducto=stockProducto($enlaceCon,$codAlmacen, $codMaterial);

echo "<input type='text' id='stock$indice' name='stock$indice' value='$stockProducto' readonly size='4' style='height:20px;font-size:19px;width:80px;color:red;'>";

?>
