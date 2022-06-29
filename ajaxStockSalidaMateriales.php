<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$codAlmacen = $_GET["codalm"];
$indice = $_GET["indice"];

//
require("conexionmysqli.php");

$stockProducto=stockProducto($enlaceCon,$codAlmacen, $codMaterial);

echo "<input type='text' id='stock$indice' name='stock$indice' value='$stockProducto' readonly size='4'>";
//echo "$cadRespuesta -> ".rand(0, 10);
//

?>
