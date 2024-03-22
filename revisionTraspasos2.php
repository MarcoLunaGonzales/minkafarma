<?php
require_once 'conexionmysqli2.inc';
require_once 'funciones.php';

 error_reporting(E_ALL);
 ini_set('display_errors', '1');
 
 //echo "entrando";


$sqlIngresos="SELECT i.cod_almacen, i.cod_ingreso_almacen, count(distinct(id.cod_material)), i.nro_correlativo, i.cod_salida_almacen 
from ingreso_almacenes i, ingreso_detalle_almacenes id 
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_tipoingreso=1003 and i.cod_almacen=1002 
group by i.cod_almacen, i.cod_ingreso_almacen, i.nro_correlativo";
//echo $sqlIngresos;
$respIngreso=mysqli_query($enlaceCon, $sqlIngresos);

while($datIngreso=mysqli_fetch_array($respIngreso)){ 
  $codAlmacen=$datIngreso[0];
  $codIngreso=$datIngreso[1];
  $cantItemsIngreso=$datIngreso[2];
  $nroCorrelativoIng=$datIngreso[3];
  $codSalidaTraspaso=$datIngreso[4];

  //echo "ALMACEN: ".$codAlmacen." CODINGRESO: ".$codIngreso." codSalida:".$codSalidaTraspaso."<br>";

  $sqlDetalle="SELECT id.cod_material,sum(id.cantidad_unitaria) from ingreso_detalle_almacenes id where 
    id.cod_ingreso_almacen='$codIngreso' group by id.cod_material";
  $respDetalle=mysqli_query($enlaceCon, $sqlDetalle);
  $cadenaIngresos="";
  while($datDetalle=mysqli_fetch_array($respDetalle)){
    //$cadenaIngresos.=$datDetalle[0]."&".intval($datDetalle[1])."|";
    $codProductoIngreso=$datDetalle[0];
    $cantidadIngreso=$datDetalle[1];

    $sqlSalidas="select s.cod_salida_almacenes, sd.cod_material, sd.cod_ingreso_almacen, id.fecha_vencimiento  from salida_almacenes s
      inner JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
      LEFT JOIN ingreso_almacenes i ON sd.cod_ingreso_almacen=i.cod_ingreso_almacen
      LEFT JOIN ingreso_detalle_almacenes id ON i.cod_ingreso_almacen=id.cod_ingreso_almacen and id.cod_material=sd.cod_material
      where 
      s.cod_salida_almacenes='$codSalidaTraspaso' and sd.cod_material='$codProductoIngreso'";
    $respSalidas=mysqli_query($enlaceCon,$sqlSalidas);
    while($datSalidas=mysqli_fetch_array($respSalidas)){
      $fechaVencimiento=$datSalidas[3];
      if($fechaVencimiento=="1969-12-30"){
        $fechaVencimiento=obtenerFechaVencimiento($enlaceCon, $codAlmacen, $codProductoIngreso);
        $fecha_array = explode('/', $fechaVencimiento);
        $month = $fecha_array[0];
        $year = $fecha_array[1];
        $ultimo_dia_mes = date("t", strtotime("$year-$month-01"));
        $fechaVencimiento = $year."-".$month."-".$ultimo_dia_mes;
      }
    }
    //echo $codProductoIngreso." ".$cantidadIngreso." FV: ".$fechaVencimiento."<br>";
    
    echo "update ingreso_detalle_almacenes set fecha_vencimiento='$fechaVencimiento' 
      where cod_ingreso_almacen='$codIngreso' and cod_material='$codProductoIngreso';";

  }

  //echo $cadenaIngresos."<br>";

  /*$sqlSalidas="SELECT s.cod_almacen, s.cod_salida_almacenes, count(distinct(sd.cod_material))as contador, s.nro_correlativo from salida_almacenes s, salida_detalle_almacenes sd 
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
    echo "ALMACEN: ".$codAlmacen." CODINGRESO: ".$codIngreso." CANT ITEMS: ".$cantItemsIngreso."<br>";
    echo $cadenaIngresos." ***** ".$cadenaSalidas."<br>";      
    if($cadenaIngresos==$cadenaSalidas){
      echo "ALMACEN: ".$codAlmacen." NRO INGRESO: ".$nroCorrelativoIng." CANT ITEMS: ".$cantItemsIngreso."Numero Salida: ".$nroCorrelativoSalida."almacen ".$codAlmacenSalida."<br>";
      echo $cadenaIngresos." ***** ".$cadenaSalidas."<br>";      
      //echo "update ingreso_almacenes set cod_salida_almacen='$codSalida' where cod_ingreso_almacen='$codIngreso' and cod_almacen='$codAlmacen';"."<br>";
    }

  }*/
}

?>