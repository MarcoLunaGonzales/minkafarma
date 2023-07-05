<?php

require_once 'conexionmysqli2.inc';
require_once 'funcionRecalculoCostos.php';

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

set_time_limit(0);

$codAlmacen=$_POST["cod_almacen"];

$sqlProductos="SELECT distinct(id.cod_material) from ingreso_almacenes i, ingreso_detalle_almacenes id
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$codAlmacen' and
i.ingreso_anulado=0";
$respProductos=mysqli_query($enlaceCon,$sqlProductos);

while($datProductos=mysqli_fetch_array($respProductos)){
  $codigoProducto=$datProductos[0];
  echo "Codigo Producto Procesado: ".$codigoProducto."<br>";

  $banderaRecalculo=recalculaCostos($enlaceCon, $codigoProducto, $codAlmacen);
}

echo "Proceso terminado satisfactoriamente!";

?>

