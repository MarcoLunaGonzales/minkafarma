<?php
require_once("funciones.php");
require_once("conexionmysqli2.inc");

$arrayPrecios = array(1 => 70, 2 => 71, 3 => 72);

var_dump($arrayPrecios);

$codProducto=1151;

$resp=actualizarPrecios($enlaceCon,$codProducto,$arrayPrecios);

echo "vamos";


?>