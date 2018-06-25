<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$codAlmacen = $_GET["codalm"];
$indice = $_GET["indice"];

//
$nroMetros=0;
require("conexion.inc");
$cadRespuesta="";
$consulta="select item_metraje, nro_metros from material_apoyo WHERE codigo_material='$codMaterial'";
$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
$nroMetros=$registro[1];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}

if($cadRespuesta==1){
	echo "<input type='text' id='nro_metros$indice' name='nro_metros$indice' value='0' size='4' onKeyUp='calcularCantidadMetros($indice)'>";
	echo "<input type='hidden' value='$nroMetros'  id='cantidadMetrosItem$indice' name='cantidadMetrosItem$indice' >";

}else{
	echo "<input type='text' id='nro_metros$indice' name='nro_metros$indice' value='0' readonly size='4'>";
	echo "<input type='hidden' value='0'  id='cantidadMetrosItem$indice' name='cantidadMetrosItem$indice' >";
}
//echo "$cadRespuesta -> ".rand(0, 10);
//

?>
