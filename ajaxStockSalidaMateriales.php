<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$codAlmacen = $_GET["codalm"];
$indice = $_GET["indice"];


require("conexionmysqli.php");
//SACAMOS LA CONFIGURACION PARA LA  VALIDACION DE STOCKS
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$banderaValidacionStock=$datConf[0];

$stockProducto=0;


$stockProducto=stockProducto($enlaceCon,$codAlmacen, $codMaterial);

if( $banderaValidacionStock==0 || ($banderaValidacionStock==2 && $stockProducto<=0) ){
	echo "<input type='text' id='stock$indice' name='stock$indice' value='-' readonly size='4' style='background:red;'>
	<span style='color:red;font-size:20px;'>S:$stockProducto</span>";
}elseif( $banderaValidacionStock==1 || ($banderaValidacionStock==2 && $stockProducto>0) ){
	echo "<input type='text' id='stock$indice' name='stock$indice' value='$stockProducto' readonly size='4'>";
}

?>
