<?php

require("conexionmysqli2.inc");
require("funciones.php");

$codTipoDoc=$_GET['codTipoDoc'];

if($codTipoDoc==1){
	$vectorNroCorrelativo=numeroCorrelativoCUFD($enlaceCon,$codTipoDoc);	
}else{
	$vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$codTipoDoc);	
}
$nroCorrelativo=$vectorNroCorrelativo[0];
$banderaErrorFacturacion=$vectorNroCorrelativo[1];
echo "<span class='textogranderojo'>$nroCorrelativo</span>";

?>
