
<?php

$codMaterial = $_GET["codmat"];
$codAlmacen = $_GET["codalm"];
$indice = $_GET["indice"];

//
require("../../conexion.inc");
$cadRespuesta="";
$consulta="
    SELECT SUM(id.cantidad_restante) as total
    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
    WHERE id.cod_material='$codMaterial' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$codAlmacen'";
//echo "$consulta<br>";
$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}
echo "<input type='text' id='stock$indice' name='stock$indice' value='$cadRespuesta' readonly >";
//echo "$cadRespuesta -> ".rand(0, 10);
//

?>
