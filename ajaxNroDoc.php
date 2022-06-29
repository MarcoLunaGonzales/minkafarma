<?php
require("conexionmysqli.php");
require("funciones.php");

$codTipoDoc=$_GET['codTipoDoc'];

$vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$codTipoDoc);
$nroCorrelativo=$vectorNroCorrelativo[0];
$banderaErrorFacturacion=$vectorNroCorrelativo[1];
echo "<span class='textogranderojo'>$nroCorrelativo</span>";

?>
