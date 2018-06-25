
<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
//
require("conexion.inc");
$cadRespuesta="";
$consulta="
    select peso from material_apoyo where codigo_material='$codMaterial'";
$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}

$cadRespuesta=redondear2($cadRespuesta);
echo "<input type='hidden' id='pesoItem$indice' name='pesoItem$indice' value='$cadRespuesta'>";
echo "<input type='text' id='pesoItemTotal$indice' name='pesoItem$indice' value='$cadRespuesta' size='4'>";
//echo "$cadRespuesta -> ".rand(0, 10);
//

?>
