
<?php

$codMaterial = $_GET["codmat"];
$indice = $_GET["indice"];
$codTipoPrecio=$_GET["codTipoPrecio"];

//
require("../../conexion.inc");
$cadRespuesta="";
$consulta="select precio from precios where codigo_material=$codMaterial and cod_precio=$codTipoPrecio";

$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}
echo "<input type='text' id='precio_unitario$indice' name='precio_unitario$indice' value='$cadRespuesta' readonly >";
//echo "$cadRespuesta -> ".rand(0, 10);
//

?>
