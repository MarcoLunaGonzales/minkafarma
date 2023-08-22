<?php
require_once 'conexionmysqli2.inc';

 error_reporting(E_ALL);
 ini_set('display_errors', '1');
 
 //echo "entrando";


$sqlIngresos="SELECT i.cod_almacen, i.cod_ingreso_almacen, count(distinct(id.cod_material)), i.nro_correlativo from ingreso_almacenes i, ingreso_detalle_almacenes id 
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_tipoingreso=1003 
and (i.cod_salida_almacen=0 or i.cod_salida_almacen is null) group by i.cod_almacen, i.cod_ingreso_almacen, i.nro_correlativo";
$respIngreso=mysqli_query($enlaceCon, $sqlIngresos);
while($datIngreso=mysqli_fetch_array($respIngreso)){ 
  $codAlmacen=$datIngreso[0];
  $codIngreso=$datIngreso[1];
  $cantItemsIngreso=$datIngreso[2];
  $nroCorrelativoIng=$datIngreso[3];

  $sqlDetalle="SELECT id.cod_material,sum(id.cantidad_unitaria) from ingreso_detalle_almacenes id where 
    id.cod_ingreso_almacen='$codIngreso' group by id.cod_material";
  $respDetalle=mysqli_query($enlaceCon, $sqlDetalle);
  $cadenaIngresos="";
  while($datDetalle=mysqli_fetch_array($respDetalle)){

    $cadenaIngresos.=$datDetalle[0]."&".intval($datDetalle[1])."|";
  }

  //echo "ALMACEN: ".$codAlmacen." CODINGRESO: ".$codIngreso." CANT ITEMS: ".$cantItemsIngreso."<br>";
  //echo $cadenaIngresos."<br>";

  $sqlSalidas="SELECT s.cod_almacen, s.cod_salida_almacenes, count(distinct(sd.cod_material))as contador, s.nro_correlativo from salida_almacenes s, salida_detalle_almacenes sd 
    where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_tiposalida=1000 and s.cod_salida_almacenes not in (
    select i.cod_salida_almacen from ingreso_almacenes i where i.cod_tipoingreso=1003 and i.ingreso_anulado=0
    ) and s.salida_anulada=0 GROUP BY s.cod_almacen, s.cod_salida_almacenes, s.nro_correlativo HAVING contador='$cantItemsIngreso'";
  $respSalidas=mysqli_query($enlaceCon,$sqlSalidas);
  while($datSalidas=mysqli_fetch_array($respSalidas)){
    $codAlmacenSalida=$datSalidas[0];
    $codSalida=$datSalidas[1];
    $cantItemsSali=$datSalidas[2];
    $nroCorrelativoSalida=$datSalidas[3];

    $sqlDetalleSalida="SELECT sd.cod_material, sum(sd.cantidad_unitaria) from salida_detalle_almacenes sd where 
    sd.cod_salida_almacen='$codSalida' group by sd.cod_material";
    $respDetalleSalida=mysqli_query($enlaceCon, $sqlDetalleSalida);
    $cadenaSalidas="";
    while($datDetalleSalidas=mysqli_fetch_array($respDetalleSalida)){
      $cadenaSalidas.=$datDetalleSalidas[0]."&".intval($datDetalleSalidas[1])."|";
    }
    //echo "ALMACEN: ".$codAlmacen." CODINGRESO: ".$codIngreso." CANT ITEMS: ".$cantItemsIngreso."<br>";
    //echo $cadenaIngresos." ***** ".$cadenaSalidas."<br>";      
    if($cadenaIngresos==$cadenaSalidas){
      //echo "ALMACEN: ".$codAlmacen." NRO INGRESO: ".$nroCorrelativoIng." CANT ITEMS: ".$cantItemsIngreso."Numero Salida: ".$nroCorrelativoSalida."almacen ".$codAlmacenSalida."<br>";
      //echo $cadenaIngresos." ***** ".$cadenaSalidas."<br>";      
      echo "update ingreso_almacenes set cod_salida_almacen='$codSalida' where cod_ingreso_almacen='$codIngreso' and cod_almacen='$codAlmacen';"."<br>";
    }

  }



}
?>